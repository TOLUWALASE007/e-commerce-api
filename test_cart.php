<?php
// test_cart.php
require_once 'config/database.php';
require_once 'models/Product.php';
require_once 'models/User.php';
require_once 'models/Cart.php';

echo "<h2>🧪 Testing Shopping Cart Functionality</h2>";

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        echo "❌ Database connection failed<br>";
        exit;
    }
    
    echo "✅ Database connected successfully<br><br>";
    
    // Create a test user
    $user = new User($db);
    $user->name = "Cart Test User";
    $user->email = "cart@example.com";
    $user->password = password_hash("password123", PASSWORD_BCRYPT);
    $user->is_admin = false;
    
    if ($user->create()) {
        echo "✅ Test user created successfully<br>";
        
        // Get the user ID
        $user->email = "cart@example.com";
        $user->emailExists();
        $user_id = $user->id;
        
        echo "User ID: " . $user_id . "<br><br>";
        
        // Create sample products if they don't exist
        $product = new Product($db);
        
        $sample_products = [
            [
                'name' => 'Test Product 1',
                'description' => 'First test product for cart',
                'price' => 29.99,
                'image_url' => 'https://example.com/test1.jpg',
                'stock_quantity' => 10
            ],
            [
                'name' => 'Test Product 2',
                'description' => 'Second test product for cart',
                'price' => 49.99,
                'image_url' => 'https://example.com/test2.jpg',
                'stock_quantity' => 15
            ]
        ];
        
        $product_ids = [];
        foreach ($sample_products as $sample) {
            $product->name = $sample['name'];
            $product->description = $sample['description'];
            $product->price = $sample['price'];
            $product->image_url = $sample['image_url'];
            $product->stock_quantity = $sample['stock_quantity'];
            
            if ($product->create()) {
                echo "✅ Product '{$sample['name']}' created successfully<br>";
                $product_ids[] = $db->lastInsertId();
            } else {
                echo "❌ Failed to create product '{$sample['name']}'<br>";
            }
        }
        
        echo "<br>🛒 Testing Cart Operations:<br>";
        
        // Test cart functionality
        $cart = new Cart($db);
        
        // Add items to cart
        foreach ($product_ids as $product_id) {
            if ($cart->addItem($user_id, $product_id, 2)) {
                echo "✅ Product ID {$product_id} added to cart (quantity: 2)<br>";
            } else {
                echo "❌ Failed to add product ID {$product_id} to cart<br>";
            }
        }
        
        // Get cart items
        echo "<br>📋 Cart Contents:<br>";
        $stmt = $cart->getCartItems($user_id);
        $total_items = 0;
        $total_price = 0;
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $item_total = $row['price'] * $row['quantity'];
            $total_items += $row['quantity'];
            $total_price += $item_total;
            
            echo "- {$row['name']} (Quantity: {$row['quantity']}, Price: \${$row['price']}, Total: \${$item_total})<br>";
        }
        
        // Get cart total
        $cart_total = $cart->getCartTotal($user_id);
        echo "<br>💰 Cart Summary:<br>";
        echo "Total Items: {$total_items}<br>";
        echo "Total Price: $" . number_format($cart_total, 2) . "<br>";
        
        // Test stock availability
        $stock_check = $cart->checkStockAvailability($user_id);
        if ($stock_check === true) {
            echo "✅ All items in cart are available in stock<br>";
        } else {
            echo "⚠️ Some items have insufficient stock:<br>";
            foreach ($stock_check as $item) {
                echo "- {$item['product_name']}: Requested {$item['requested_quantity']}, Available {$item['available_quantity']}<br>";
            }
        }
        
        echo "<br>🎉 Cart testing completed successfully!<br>";
        echo "<br>📱 You can now test the cart interface at: <a href='test_cart.html'>test_cart.html</a><br>";
        echo "🔑 Use these credentials to login: cart@example.com / password123<br>";
        
    } else {
        echo "❌ Failed to create test user<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<br><h3>📚 Next Steps:</h3>";
echo "<ul>";
echo "<li>Visit the cart test interface</li>";
echo "<li>Login with the test user credentials</li>";
echo "<li>Test adding products to cart</li>";
echo "<li>Test viewing cart contents</li>";
echo "<li>Test updating quantities</li>";
echo "<li>Test removing items</li>";
echo "</ul>";
?>
