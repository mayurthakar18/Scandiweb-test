<?php
require_once('Product.php');

class Book extends Product {
    protected $weight;

    public function __construct($sku, $name, $price, $weight) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->weight = $weight;
    }

    public function save() {
        // Save to database
        global $conn;

        $sql = "INSERT INTO products (sku, name, price, weight) VALUES ('$this->sku', '$this->name', '$this->price', '$this->weight')";
        if ($conn->query($sql) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    public function display() {
        // Display product
        echo "<div class='product-box'>
                <div class='product-info'>
                    <span>SKU: $this->sku</span><br>
                    <span>Name: $this->name</span><br>
                    <span>Price: $this->price</span><br>
                    <span>Weight: $this->weight Kg</span>
                </div>
              </div>";
    }
}
?>

