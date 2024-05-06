<?php
include('includes/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['skus']) && is_array($_POST['skus'])) {
        $skus = $_POST['skus'];
        $skuList = implode("','", $skus);

        $sql = "DELETE FROM products WHERE sku IN ('$skuList')";
        if ($conn->query($sql) === TRUE) {
            echo count($skus) . " product(s) deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "No products selected for deletion.";
    }
}
?>

