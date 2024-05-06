<?php
include('includes/db.php');
include('classes/Product.php');
include('classes/DVD.php');
include('classes/Book.php');
include('classes/Furniture.php');

// Function to sanitize input data
function sanitizeData($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Fetch form data
$sku = sanitizeData($_POST['sku']);
$name = sanitizeData($_POST['name']);
$price = sanitizeData($_POST['price']);
$productType = sanitizeData($_POST['productType']);

// Validate and sanitize product type specific attributes
$size = ($productType === 'DVD') ? sanitizeData($_POST['size']) : null;
$weight = ($productType === 'Book') ? sanitizeData($_POST['weight']) : null;
$height = ($productType === 'Furniture') ? sanitizeData($_POST['height']) : null;
$width = ($productType === 'Furniture') ? sanitizeData($_POST['width']) : null;
$length = ($productType === 'Furniture') ? sanitizeData($_POST['length']) : null;

// Create product object based on product type
$product = null;
if ($productType === 'DVD') {
    $product = new DVD($sku, $name, $price, $size);
} elseif ($productType === 'Book') {
    $product = new Book($sku, $name, $price, $weight);
} elseif ($productType === 'Furniture') {
    $product = new Furniture($sku, $name, $price, $height, $width, $length);
}

// Save product to database
if ($product && $product->save()) {
    echo "Product added successfully.";
} else {
    echo "Failed to add product.";
}
?>

