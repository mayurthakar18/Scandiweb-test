<?php
include('includes/db.php');

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sku = $_POST['sku'];
    $name = $_POST['name'];
    $price = $_POST['price'];
    $productType = $_POST['productType'];

    // Handle different product types
    switch ($productType) {
        case 'DVD':
            $specificAttribute = $_POST['size'];
            break;
        case 'Book':
            $specificAttribute = $_POST['weight'];
            break;
        case 'Furniture':
            $specificAttribute = $_POST['height'] . 'x' . $_POST['width'] . 'x' . $_POST['length'];
            break;
        default:
            $specificAttribute = '';
    }

    // Insert data into database
   $sql = "INSERT INTO products (sku, name, price, description)
        VALUES ('$sku', '$name', '$price', '$specificAttribute')";


    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

