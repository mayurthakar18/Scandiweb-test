<?php
// Connect to the database
include('includes/db.php');

// Check if SKU exists
$sku = $_POST['sku'];
$sql_check = "SELECT COUNT(*) as count FROM products WHERE sku = '$sku'";
$result_check = $conn->query($sql_check);
$row = $result_check->fetch_assoc();

echo ($row['count'] > 0) ? 'exists' : 'unique';

// Close database connection
$conn->close();
?>

