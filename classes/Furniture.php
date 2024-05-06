<?php
require_once('Product.php');

class Furniture extends Product {
    protected $height;
    protected $width;
    protected $length;

    public function __construct($sku, $name, $price, $height, $width, $length) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }

    public function save() {
        // Save to database
        global $conn;

        $sql = "INSERT INTO products (sku, name, price, height, width, length) VALUES ('$this->sku', '$this->name', '$this->price', '$this->height', '$this->width', '$this->length')";
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
                    <span>Dimensions: $this->height x $this->width x $this->length cm</span>
                </div>
              </div>";
    }
}
?>

