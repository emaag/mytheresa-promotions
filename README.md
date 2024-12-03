Mytheresa Promotions API
Description

This project implements a REST API endpoint that:

    Applies discounts to a given list of products.
    Allows filtering by category and price.

The solution is built using PHP with the Slim framework and Composer for dependency management. It is designed to be lightweight, scalable, and easy to run.
Features

    Discount Rules:
        Products in the "boots" category get a 30% discount.
        The product with sku = 000003 gets a 15% discount.
        When multiple discounts apply, the higher discount is used.
    Filtering:
        Filter products by category using the category query parameter.
        Filter products by price using the priceLessThan query parameter.
    Paginated Response:
        Returns a maximum of 5 products per request.

Installation
Prerequisites

    PHP 8.1 or later
    Composer
    A web server (PHP's built-in server or Apache/Nginx)
    (Optional) Docker for containerized deployment

Steps

    Clone the repository:

git clone https://github.com/your-username/mytheresa-promotions.git
cd mytheresa-promotions

Install dependencies using Composer:

composer install

Start the development server:

php -S localhost:8080 -t public

Access the API at:

    http://localhost:8080/products

Usage
Example API Endpoints

    Retrieve All Products:

GET /products

Filter by Category:

GET /products?category=boots

Filter by Price:

GET /products?priceLessThan=80000

Filter by Category and Price:

    GET /products?category=boots&priceLessThan=80000

Project Structure

mytheresa-promotions/
├── public/
│   └── index.php       # Entry point for the API
├── src/
│   ├── Product.php     # Product model
│   └── ProductService.php  # Business logic for discounts and filtering
├── tests/
│   └── ProductServiceTest.php # Unit tests for the ProductService
├── vendor/             # Composer dependencies
├── composer.json       # Composer configuration
├── composer.lock       # Lock file for dependencies
└── README.md           # Project documentation

Testing
Prerequisites

    PHPUnit installed via Composer.

Run Tests

To execute the tests:

./vendor/bin/phpunit tests

API Response Examples
Example Product with a Discount

{
    "sku": "000001",
    "name": "BV Lean leather ankle boots",
    "category": "boots",
    "price": {
        "original": 89000,
        "final": 62300,
        "discount_percentage": "30%",
        "currency": "EUR"
    }
}

Example Product without a Discount

{
    "sku": "000004",
    "name": "Naima embellished suede sandals",
    "category": "sandals",
    "price": {
        "original": 79500,
        "final": 79500,
        "discount_percentage": null,
        "currency": "EUR"
    }
}

Deployment with Docker

    Build the Docker image:

docker build -t mytheresa-promotions .

Run the container:

docker run -p 8080:80 mytheresa-promotions

Access the API at:

http://localhost:8080/products
