<?php

// Check if form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connect to the database
    include('includes/db.php');
    
    // Prepare variables
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $productType = $_POST['productType'];
    
    // Define an object to map product types to specific attributes
    $attributeMap = [
        'DVD' => ['size' => $_POST['size']],
        'Book' => ['weight' => $_POST['weight']],
        'Furniture' => [
            'height' => $_POST['height'],
            'width' => $_POST['width'],
            'length' => $_POST['length']
        ]
    ];

    // Get the specific attribute based on product type
    $specificAttribute = isset($attributeMap[$productType]) ? $attributeMap[$productType] : null;
    
    // Check if SKU already exists
    $sql_check = "SELECT sku FROM products WHERE sku = '$sku'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        echo "<script>alert('SKU already exists'); window.location.href = 'add-product.php';</script>";
        exit; // Stop further execution
    }
    
    // Insert data into the database
    $sql = "INSERT INTO products (sku, name, price, productType";
    $sql_values = "VALUES ('$sku', '$name', '$price', '$productType'";

    // Add specific attributes to the query
    foreach ($attributeMap[$productType] as $key => $value) {
        $sql .= ", $key";
        $sql_values .= ", '" . $value . "'";
    }

    $sql .= ") " . $sql_values . ")";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error; // Display SQL error
    }

    // Close database connection
    $conn->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form id="product_form" action="add-product.php" method="POST">
            <div class="form-group">
                <label for="sku">SKU:</label>
                <input type="text" id="sku" name="sku" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" id="price" name="price" step="0.01" min="0" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="productType">Product Type:</label>
                <select id="productType" name="productType" required autocomplete="off">
                    <option value="" disabled selected>Select Product Type</option>
                    <option value="DVD">DVD</option>
                    <option value="Book">Book</option>
                    <option value="Furniture">Furniture</option>
                </select>
            </div>
            <div id="specificAttribute" class="form-group">
                <!-- Product type specific attribute fields will be added here dynamically -->
            </div>
            <div class="form-group">
                <button type="button" id="saveBtn">Save</button>
                <button type="button" id="cancelBtn">Cancel</button>
            </div>
        </form>
        <div id="notification" class="notification"></div> <!-- Notification div -->
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            // Map product types to descriptions, attribute names, and IDs
            var productAttributes = {
                'DVD': {
                    description: 'Please, provide size (in MB)',
                    attributeName: 'size',
                    placeholder: 'Size (MB)',
                    id: 'size'
                },
                'Book': {
                    description: 'Please, provide weight (in Kg)',
                    attributeName: 'weight',
                    placeholder: 'Weight (Kg)',
                    id: 'weight'
                },
                'Furniture': {
                    description: 'Please, provide dimensions (HxWxL)',
                    attributes: [
                        { name: 'Height', attributeName: 'height', placeholder: 'Height in cm', id: 'height' },
                        { name: 'Width', attributeName: 'width', placeholder: 'Width in cm', id: 'width' },
                        { name: 'Length', attributeName: 'length', placeholder: 'Length in cm', id: 'length' }
                    ]
                }
            };

            $('#productType').change(function(){
                var productType = $(this).val();
                var attributes = productAttributes[productType];
                var html = '';

                if (productType === 'DVD' || productType === 'Book') {
                    html += '<label for="' + attributes.attributeName + '">' + attributes.description + '</label><br>';
                    html += '<input type="text" id="' + attributes.id + '" name="' + attributes.attributeName + '" placeholder="' + attributes.placeholder + '" required autocomplete="off">';
                } else if (productType === 'Furniture') {
                    html += '<label>' + attributes.description + '</label><br>';
                    $.each(attributes.attributes, function(index, attribute) {
                        html += '<label for="' + attribute.attributeName + '">' + attribute.name + '</label><br>';
                        html += '<input type="text" id="' + attribute.id + '" name="' + attribute.attributeName + '" placeholder="' + attribute.placeholder + '" required autocomplete="off"><br>';
                    });
                }

                $('#specificAttribute').html(html);
            });

            $('#saveBtn').click(function(){
                // Check if all fields are filled
                if ($('#sku').val() === '' || $('#name').val() === '' || $('#price').val() === '' || $('#productType').val() === '') {
                    showNotification("Please, submit required data");
                    return;
                }
                // Check if price is a valid number
                var price = $('#price').val();
                if (isNaN(price) || parseFloat(price) <= 0) {
                    showNotification("Please, provide a valid price");
                    return;
                }
                // Check if size, weight, and dimensions are valid
                var productType = $('#productType').val();
                if (productType === 'DVD') {
                    var size = $('#size').val();
                    if (!/^\d+$/.test(size)) {
                        showNotification("Please, provide valid size (digits only)");
                        return;
                    }
                } else if (productType === 'Book') {
                    var weight = $('#weight').val();
                    if (!/^\d+$/.test(weight)) {
                        showNotification("Please, provide valid weight (digits only)");
                        return;
                    }
                } else if (productType === 'Furniture') {
                    var height = $('#height').val();
                    var width = $('#width').val();
                    var length = $('#length').val();
                    if (!/^\d+$/.test(height) || !/^\d+$/.test(width) || !/^\d+$/.test(length)) {
                        showNotification("Please, provide valid dimensions (digits only)");
                        return;
                    }
                }
                
                // Submit the form
                $('#product_form').submit();
            });

            $('#cancelBtn').click(function(){
                window.location.href = 'index.php';
            });

            // Function to show notification
            function showNotification(message) {
                $('#notification').removeClass().addClass('notification');
                $('#notification').text(message);
            }
        });
    </script>
 <footer>
        <hr style="border-top: 1px solid black; margin: 10px 0;">
        <div>Scandiweb Test assignment</div>
    </footer>
</body>
</html>
