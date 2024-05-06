<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Product List</title>
</head>
<body>
    <?php include('includes/db.php'); ?>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the delete action is triggered
        if (isset($_POST['delete_products'])) {
            $selectedProducts = $_POST['selectedProducts'];
            $selectedProductsList = implode(',', $selectedProducts);
            // Delete selected products from the database
            $sql = "DELETE FROM products WHERE id IN ($selectedProductsList)";
            if ($conn->query($sql) === TRUE) {
                // echo "Selected products deleted successfully";
            } else {
                echo "Error deleting products: " . $conn->error;
            }
        }
    }
    ?>
    <div class="container">
        <div class="header">
            <h1>Product List</h1>
            <div class="action-buttons" text="ADD">
                <a href="add-product.php" class="add-button" >ADD</a>
                <form id="mass-delete-form" action="#" method="POST">
                    <button type="submit" id="delete-product-btn" text="MASS DELETE">MASS DELETE</button>
                    <input type="hidden" name="delete_products">
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <?php
        // Fetch products from the database
        $sql = "SELECT * FROM products ORDER BY id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            echo '<div class="product-list">';
            while ($row = $result->fetch_assoc()) {
                echo '<div class="product-box">';
                echo '<input type="checkbox" class="delete-checkbox" name="selectedProducts[]" value="' . $row['id'] . '">';
                echo '<div class="product-info">';
                echo '<span>SKU: ' . $row['sku'] . '</span><br>';
                echo '<span>Name: ' . $row['name'] . '</span><br>';
                echo '<span> $' . $row['price'] . '</span><br>';
                echo '<span>';
                 // Parse and display attributes based on product type
            $attributes = explode('*', $row['attributes']); // Assuming the attributes are separated by '*'
            if ($row['type'] === 'DVD') {
                echo 'Size: ' . $attributes[0] . ' MB';
            } elseif ($row['type'] === 'Book') {
                echo 'Weight: ' . $attributes[0] . ' Kg';
            } elseif ($row['type'] === 'Furniture') {
                echo 'Dimensions: ' . $attributes[0] . 'x' . $attributes[1] . 'x' . $attributes[2];
            }
                echo '</span>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
        } else {
            echo 'No products found';
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
                    if (confirm("Are you sure you want to delete selected products?")) {
                        // Submit the form with selected product IDs
                        $.ajax({
                            type: "POST",
                            url: "index.php",
                            data: {
                                delete_products: true,
                                selectedProducts: selectedProducts
                            },
                            success: function(response) {
                                window.location.reload();
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                } else {
                    alert('Please select products to delete.');
                }
            });
        });
    </script>
</body>
</html>
