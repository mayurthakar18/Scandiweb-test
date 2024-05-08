<?php include('includes/db.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style1.css">
    <title>Product List</title>
    <style>
        .center-align {
            text-align: center;
            margin-top: 150px;
        }
        .center-align .action-buttons {
            display: inline-block;
        }
    </style>
</head>
<body>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the delete action is triggered
        if (isset($_POST['delete_products'])) {
            $selectedProducts = $_POST['selectedProducts'];
            // Prepare the array of selected products for SQL query
            $selectedProductsList = "'" . implode("','", $selectedProducts) . "'";
            // Delete selected products from the database
            $sql = "DELETE FROM products WHERE sku IN ($selectedProductsList)";
            if ($conn->query($sql) === TRUE) {
                // Reload the page after successful deletion
                echo "<script>window.location.reload();</script>";
            } else {
                echo "Error deleting products: " . $conn->error;
            }
        }
    }
    ?>
   <div class="container">
    <div class="header">
        <h1>Product List</h1>
        <div class="action-buttons">
            <a href="add-product.php" class="add-button">ADD</a>
            <form id="mass-delete-form" method="POST">
                <button type="submit" id="delete-product-btn">MASS DELETE</button>
                <input type="hidden" name="delete_products">
            </form>
        </div>
    </div>
</div>
    <div class="container">
        <?php
        // Fetch products from the database
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<div class="product-list">';
            // If there are products, start a product list div
            while ($row = $result->fetch_assoc()) {
                // Iterate over each product
                echo '<div class="product-box">';
                // Start a product box div
                echo '<input type="checkbox" class="delete-checkbox" name="selectedProducts[]" value="' . $row['sku'] . '">';
                // Add a checkbox for the product
                echo '<div class="product-info">';
                // Start a product info div
                echo '<span>SKU: ' . $row['sku'] . '</span><br>';
                // Display SKU
                echo '<span>Name: ' . $row['name'] . '</span><br>';
                // Display name
                echo '<span> $' . $row['price'] . '</span><br>';
                // Display price
                echo '<span>';
                // Start a span for additional product details
                if ($row['productType'] === 'DVD') {
                    echo 'Size: ' . $row['size'] . ' MB';
                } elseif ($row['productType'] === 'Book') {
                    echo 'Weight: ' . $row['weight'] . ' Kg';
                } elseif ($row['productType'] === 'Furniture') {
                    echo 'Dimensions: ' . $row['height'] . 'x' . $row['width'] . 'x' . $row['length'] . ' cm';
                }
                // Display additional details based on product type
                echo '</span>';
                // Close the span
                echo '</div>';
                // Close the product info div
                echo '</div>';
                // Close the product box div
            }
            echo '</div>';
            // Close the product list div
        } else {
            // If no products found, display a message and add a button to add a new product
            echo '<div class="center-align">';
            echo '<p>No products found!</p>';
            echo '<div class="action-buttons">';
            echo '<a href="add-product.php" class="add-button">ADD PRODUCT</a>';
            echo '</div>';
            echo '</div>';
        }
        
        ?>
    </div>
    <footer>
        <hr style="border-top: 1px solid black; margin: 10px 0;">
        <div>Scandiweb Test assignment</div>
    </footer>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mass-delete-form').submit(function(e) {
                e.preventDefault();
                var selectedProducts = [];
                $('.delete-checkbox:checked').each(function() {
                    selectedProducts.push($(this).val());
                });

                if (selectedProducts.length > 0) {
    // Submit the form with selected product SKUs
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {
            delete_products: true,
            selectedProducts: selectedProducts
        },
        // AJAX request to delete products
        success: function(response) {
            // Reload the page after successful deletion
            window.location.reload();
        },
        error: function(xhr, status, error) {
            // Log errors to the console
            console.error(xhr.responseText);
        }
    });
} 

            });
        });
    </script>
</body>
</html>
