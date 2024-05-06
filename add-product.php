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
    
    // Initialize specific attribute value as empty
    $specificAttribute = '';
    
    // Determine specific attribute based on product type
    if ($productType === 'DVD' && isset($_POST['size'])) {
        $specificAttribute = $_POST['size'];
    } elseif ($productType === 'Book' && isset($_POST['weight'])) {
        $specificAttribute = $_POST['weight'];
    } elseif ($productType === 'Furniture' && isset($_POST['dimensions'])) {
        $specificAttribute = $_POST['dimensions'];
    }
    
    // Check if SKU already exists
    $sql_check = "SELECT sku FROM products WHERE sku = '$sku'";
    $result_check = $conn->query($sql_check);
    if ($result_check->num_rows > 0) {
        echo "<script>alert('SKU already exists'); window.location.href = 'add-product.php';</script>";
        exit; // Stop further execution
    }
    
    // Insert data into the database
    $sql = "INSERT INTO products (sku, name, price, type, attributes) 
            VALUES ('$sku', '$name', '$price', '$productType', '$specificAttribute')";
    
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
                <button type="submit" id="saveBtn">Save</button>
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
                    id: 'sizeInput'
                },
                'Book': {
                    description: 'Please, provide weight (in Kg)',
                    attributeName: 'weight',
                    placeholder: 'Weight (Kg)',
                    id: 'weightInput'
                },
                'Furniture': {
                    description: 'Please, provide dimensions (HxWxL in cm)',
                    attributeName: 'dimensions',
                    placeholder: 'Dimensions (HxWxL)',
                    id: 'dimensionsInput'
                }
            };

            $('#productType').change(function(){
                var productType = $(this).val();
                var attributes = productAttributes[productType];
                var description = attributes.description;
                var attributeName = attributes.attributeName;
                var placeholder = attributes.placeholder;
                var id = attributes.id;

                $('#specificAttribute').html('<label for="' + attributeName + '">' + description + '</label><br><input type="text" id="' + id + '" name="' + attributeName + '" placeholder="' + placeholder + '" required autocomplete="off">');
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
                    var size = $('#sizeInput').val();
                    if (!(/^\d+$/.test(size))) {
                        showNotification("Please, provide the data of indicated type");
                        return;
                    }
                } else if (productType === 'Book') {
                    var weight = $('#weightInput').val();
                    if (!(/^\d+$/.test(weight))) {
                        showNotification("Please, provide the data of indicated type");
                        return;
                    }
                } else if (productType === 'Furniture') {
                    var dimensions = $('#dimensionsInput').val();
                    if (!(/^\d+$/.test(dimensions))) {
                        showNotification("Please, provide the data of indicated type");
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
</body>
</html>
