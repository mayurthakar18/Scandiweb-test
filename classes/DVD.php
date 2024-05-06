<?php
require_once('Product.php');

class DVD extends Product {
    protected $size;

    public function __construct($sku, $name, $price, $size) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->size = $size;
    }

    public function save() {
        // Save to database
        global $conn;

        $sql = "INSERT INTO products (sku, name, price, size) VALUES ('$this->sku', '$this->name', '$this->price', '$this->size')";
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
                    <span>Size: $this->size MB</span>
                </div>
              </div>";
    }
}
?>


