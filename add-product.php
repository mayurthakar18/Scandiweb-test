<?php
// Check if form data is submitted
if(isset($_POST['sku'], $_POST['name'], $_POST['price'], $_POST['productType'])) {
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
        echo "<script>alert('SKU already exists');</script>";
        //exit; // Stop further execution
    }
    
    // Insert data into the database
    $sql = "INSERT INTO products (sku, name, price, product_type, description) 
            VALUES ('$sku', '$name', '$price', '$productType', '$specificAttribute')";
    
    if ($conn->query($sql) === TRUE) {
        // Redirect to Product List page
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Add Product</h2>
        <form id="product_form" action="#" method="POST">
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
            $('#productType').change(function(){
                var productType = $(this).val();
                var description = '';
                if (productType === 'DVD') {
                    description = 'Please, provide size (in MB)';
                    $('#specificAttribute').html('<label for="size">' + description + '</label><br><input type="text" id="size" name="size" placeholder="Size (MB)" required autocomplete="off">');
                } else if (productType === 'Book') {
                    description = 'Please, provide weight (in Kg)';
                    $('#specificAttribute').html('<label for="weight">' + description + '</label><br><input type="text" id="weight" name="weight" placeholder="Weight (Kg)" required autocomplete="off">');
                } else if (productType === 'Furniture') {
                    description = 'Please, provide dimensions (HxWxL in cm)';
                    $('#specificAttribute').html('<label for="dimensions">' + description + '</label><br><input type="text" id="dimensions" name="dimensions" placeholder="Dimensions (HxWxL)" required autocomplete="off">');
                }
            });




            $('#saveBtn').click(function(){
                // Check if all fields are filled
                if ($('#sku').val() === '' || $('#name').val() === '' || $('#price').val() === '' || $('#productType').val() === '') {
                    showNotification("Please, submit required data");
                } else {
                    // Submit the form
                    $('#product_form').submit();
                }
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


