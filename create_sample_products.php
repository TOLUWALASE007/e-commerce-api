<?php
// create_sample_products.php
require_once 'config/database.php';
require_once 'models/Product.php';

$database = new Database();
$db = $database->getConnection();

$product = new Product($db);

$sample_products = [
    [
        'name' => 'iPhone 13',
        'description' => 'Latest iPhone with A15 Bionic chip',
        'price' => 999.99,
        'image_url' => 'https://example.com/iphone13.jpg',
        'stock_quantity' => 15
    ],
    [
        'name' => 'Samsung Galaxy S21',
        'description' => 'Powerful Android smartphone',
        'price' => 899.99,
        'image_url' => 'https://example.com/galaxys21.jpg',
        'stock_quantity' => 12
    ],
    [
        'name' => 'MacBook Air',
        'description' => 'Lightweight laptop with M1 chip',
        'price' => 1299.99,
        'image_url' => 'https://example.com/macbookair.jpg',
        'stock_quantity' => 8
    ],
    [
        'name' => 'Sony Headphones',
        'description' => 'Noise-cancelling wireless headphones',
        'price' => 299.99,
        'image_url' => 'https://example.com/sonyheadphones.jpg',
        'stock_quantity' => 20
    ],
    [
        'name' => 'iPad Pro',
        'description' => 'Professional tablet with M1 chip',
        'price' => 1099.99,
        'image_url' => 'https://example.com/ipadpro.jpg',
        'stock_quantity' => 10
    ],
    [
        'name' => 'Dell XPS 13',
        'description' => 'Premium Windows laptop',
        'price' => 1199.99,
        'image_url' => 'https://example.com/dellxps13.jpg',
        'stock_quantity' => 6
    ]
];

echo "<h2>Creating Sample Products</h2>";

foreach ($sample_products as $sample) {
    $product->name = $sample['name'];
    $product->description = $sample['description'];
    $product->price = $sample['price'];
    $product->image_url = $sample['image_url'];
    $product->stock_quantity = $sample['stock_quantity'];
    
    if ($product->create()) {
        echo "✅ Product '{$sample['name']}' created successfully.<br>";
    } else {
        echo "❌ Failed to create product '{$sample['name']}'.<br>";
    }
}

echo "<br><h3>Sample Products Creation Completed!</h3>";
echo "<p>You can now test the product endpoints:</p>";
echo "<ul>";
echo "<li><strong>GET /products</strong> - List all products</li>";
echo "<li><strong>GET /products/1</strong> - Get specific product</li>";
echo "<li><strong>POST /products</strong> - Create new product (Admin only)</li>";
echo "<li><strong>PUT /products/1</strong> - Update product (Admin only)</li>";
echo "<li><strong>DELETE /products/1</strong> - Delete product (Admin only)</li>";
echo "</ul>";
?>
