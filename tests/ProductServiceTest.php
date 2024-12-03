<?php

use PHPUnit\Framework\TestCase;
use Mytheresa\Product;
use Mytheresa\ProductService;

class ProductServiceTest extends TestCase
{
    public function testApplyDiscounts()
    {
        $products = [
            new Product('000001', 'BV Lean leather ankle boots', 'boots', 89000),
            new Product('000003', 'Ashlington leather ankle boots', 'boots', 71000),
        ];

        $service = new ProductService($products);
        $service->applyDiscounts();

        $this->assertEquals(62300, $products[0]->price);
        $this->assertEquals(49700, $products[1]->price);
    }
}
