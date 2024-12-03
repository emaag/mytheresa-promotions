<?php

namespace Mytheresa;

class ProductService
{
    private $products;

    public function __construct($products)
    {
        $this->products = $products;
    }

    public function applyDiscounts()
    {
        foreach ($this->products as $product) {
            $discounts = [];

            if ($product->category === 'boots') {
                $discounts[] = 30; // 30% discount for boots
            }

            if ($product->sku === '000003') {
                $discounts[] = 15; // 15% discount for this specific SKU
            }

            if (!empty($discounts)) {
                $product->applyDiscount(max($discounts));
            }
        }
    }

    public function getFilteredProducts($category = null, $priceLessThan = null)
    {
        $filtered = $this->products;

        if ($category) {
            $filtered = array_filter($filtered, fn($product) => $product->category === $category);
        }

        if ($priceLessThan) {
            $filtered = array_filter($filtered, fn($product) => $product->price <= $priceLessThan);
        }

        return array_slice($filtered, 0, 5);
    }
}
