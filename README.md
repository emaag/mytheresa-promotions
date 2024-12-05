Mytheresa Promotions API
eric.maag@gmail.com 

Installation, testing, implementation and dockerization below TODO.

TODO: since I was sick for the last 2 I had to create this as an MVP, below are my design notes for DB integration. 
NOTE: this is quick pseudocode that's not tested yet, but I think it could be easily integrated, please let me know (since I know time is a factor) if you'd like me to implement this as well.
PERFOMANCE NOTES: 
    Create indexes on columns frequently used in filters (category, price).
    Implement Redis for caching and responses
    

DESIGN A SQL FORMAT

   CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sku VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    category VARCHAR(255) NOT NULL,
    price INT NOT NULL, -- Store price as an integer (e.g., 100.00€ = 10000)
    discount_percentage INT DEFAULT NULL
  );

ABSTRACTION LAYER 

  Create an abstraction layer to interact with the database. Use an ORM (Object-Relational Mapper) or raw queries:

      class ProductRepository
    {
        private $collection;

    public function __construct($mongoClient)
    {
        $this->collection = $mongoClient->mydb->products;
    }

    public function getProducts($category = null, $priceLessThan = null)
    {
        $filter = [];

        if ($category) {
            $filter['category'] = $category;
        }

        if ($priceLessThan) {
            $filter['price'] = ['$lte' => $priceLessThan];
        }

        return $this->collection->find($filter, ['limit' => 5])->toArray();
    }
}

APPLICATION LOGIC

integrate the database abstraction layer with your business logic (ProductService) to apply discounts and format JSON responses:

    class ProductService
    {
    private $repository;

    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function getProducts($category = null, $priceLessThan = null)
    {
        $products = $this->repository->getProducts($category, $priceLessThan);

        foreach ($products as &$product) {
            $originalPrice = $product['price'];
            $discount = 0;

            if ($product['category'] === 'boots') {
                $discount = max($discount, 30);
            }

            if ($product['sku'] === '000003') {
                $discount = max($discount, 15);
            }

            $product['price'] = [
                'original' => $originalPrice,
                'final' => $discount ? round($originalPrice * (1 - $discount / 100)) : $originalPrice,
                'discount_percentage' => $discount ? "{$discount}%" : null,
                'currency' => 'EUR'
            ];
        }

        return $products;
    }
}

JSON RESPONSE

    $app->get('/products', function ($request, $response, $args) use ($productService) {
    $queryParams = $request->getQueryParams();
    $category = $queryParams['category'] ?? null;
    $priceLessThan = $queryParams['priceLessThan'] ?? null;

    $products = $productService->getProducts($category, $priceLessThan);

    $response->getBody()->write(json_encode($products));
    return $response->withHeader('Content-Type', 'application/json');
    });


DOCUMENTATION 
         

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

    localhost:8080/products?category=boots

Filter by Price:

GET /products?priceLessThan=80000

    localhost:8080/products?priceLessThan=80000
    
Filter by Category and Price:

 GET /products?category=boots&priceLessThan=80000
 
    localhost:8080/products?category=boots&priceLessThan=80000

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

DEPLOYMENT WITH DOCKER

Build the Docker image:

    docker build -t mytheresa-promotions .

Run the container:

    docker run -p 8080:80 mytheresa-promotions

http://localhost:8080/products
