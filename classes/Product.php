<?php
abstract class Product {
    protected $sku;
    protected $name;
    protected $price;

    abstract public function save();
    abstract public function display();
}
?>

