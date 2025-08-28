<?php
// index.php

// Enable CORS for cross-origin requests
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'vendor/autoload.php';
require_once 'config/database.php';
require_once 'middleware/AuthMiddleware.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$request_uri = $_SERVER['REQUEST_URI'];
$path = parse_url($request_uri, PHP_URL_PATH);
$path = str_replace('/ecommerce-api', '', $path); // Adjust based on your folder structure
$path_segments = explode('/', trim($path, '/'));

// Connect to database
$database = new Database();
$db = $database->getConnection();

// Initialize Controllers
require_once 'controllers/AuthController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/CartController.php';

$authController = new AuthController($db);
$productController = new ProductController($db);
$cartController = new CartController($db);

// Route the request
$endpoint = isset($path_segments[0]) ? $path_segments[0] : '';
$param1 = isset($path_segments[1]) ? $path_segments[1] : null;
$param2 = isset($path_segments[2]) ? $path_segments[2] : null;

// Authenticate user for protected routes
$user_id = null;
if (in_array($endpoint, ['cart']) || 
    ($method == 'POST' && $endpoint == 'orders')) {
    $decoded = AuthMiddleware::authenticate();
    $user_id = $decoded->data->id;
}

switch ("$method $endpoint") {
    case 'POST register':
        $authController->register();
        break;
        
    case 'POST login':
        $authController->login();
        break;
        
    case 'GET products':
        if ($param1) {
            $productController->read($param1);
        } else {
            $productController->readAll();
        }
        break;
        
    case 'POST products':
        AuthMiddleware::requireAdmin();
        $productController->create();
        break;
        
    case 'PUT products':
        AuthMiddleware::requireAdmin();
        if ($param1) {
            $productController->update($param1);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Product ID required."));
        }
        break;
        
    case 'DELETE products':
        AuthMiddleware::requireAdmin();
        if ($param1) {
            $productController->delete($param1);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Product ID required."));
        }
        break;
        
    case 'GET cart':
        $cartController->getCart($user_id);
        break;
        
    case 'POST cart':
        $cartController->addToCart($user_id);
        break;
        
    case 'PUT cart':
        if ($param1) {
            $cartController->updateCartItem($user_id, $param1);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Cart item ID required."));
        }
        break;
        
    case 'DELETE cart':
        if ($param1) {
            $cartController->removeFromCart($user_id, $param1);
        } else {
            // If no ID provided, clear entire cart
            $cartController->clearCart($user_id);
        }
        break;
        
    default:
        // If no specific endpoint, show welcome message
        if (empty($endpoint)) {
            http_response_code(200);
            echo json_encode(array(
                "message" => "Welcome to E-Commerce API", 
                "version" => "1.0",
                "database" => $db ? "Connected successfully" : "Connection failed",
                "endpoints" => array(
                    "POST /register" => "User registration",
                    "POST /login" => "User login",
                    "GET /products" => "List all products",
                    "GET /products/{id}" => "Get single product",
                    "POST /products" => "Create product (Admin only)",
                    "PUT /products/{id}" => "Update product (Admin only)",
                    "DELETE /products/{id}" => "Delete product (Admin only)",
                    "GET /cart" => "View cart (Auth required)",
                    "POST /cart" => "Add item to cart (Auth required)",
                    "PUT /cart/{id}" => "Update cart item (Auth required)",
                    "DELETE /cart/{id}" => "Remove item from cart (Auth required)",
                    "DELETE /cart" => "Clear entire cart (Auth required)"
                )
            ));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Endpoint not found."));
        }
        break;
}
?>
