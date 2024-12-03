<?php

namespace Mytheresa;

class Product
{
    public $sku;
    public $name;
    public $category;
    public $price;
    public $discountPercentage = null;

    public function __construct($sku, $name, $category, $price)
    {
        $this->sku = $sku;
        $this->name = $name;
        $this->category = $category;
        $this->price = $price;
    }

    public function applyDiscount($percentage)
    {
        $this->discountPercentage = $percentage;
        $this->price = round($this->price * (1 - $percentage / 100));
    }
}
