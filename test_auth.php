<?php
// test_auth.php
require_once 'config/database.php';
require_once 'controllers/AuthController.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$database = new Database();
$db = $database->getConnection();

$authController = new AuthController($db);

// Test data
$test_user = array(
    "name" => "Test User",
    "email" => "test@example.com",
    "password" => "password123",
    "is_admin" => false
);

echo "<h2>Testing Authentication System</h2>";

// Test registration
echo "<h3>1. Testing Registration</h3>";
$authController->register($test_user);

// Test login with correct credentials
echo "<h3>2. Testing Login with Correct Credentials</h3>";
$authController->login(array(
    "email" => "test@example.com",
    "password" => "password123"
));

// Test login with incorrect credentials
echo "<h3>3. Testing Login with Incorrect Credentials</h3>";
$authController->login(array(
    "email" => "test@example.com",
    "password" => "wrongpassword"
));

// Test token verification
echo "<h3>4. Testing Token Verification</h3>";
// First get a valid token
$test_login = $authController->login(array(
    "email" => "test@example.com",
    "password" => "password123"
));

// Extract token from response (in a real scenario, this would come from the API response)
// For testing, we'll manually create a token
$token = JWT::encode(
    array(
        "iss" => "localhost",
        "iat" => time(),
        "nbf" => time(),
        "exp" => time() + 3600,
        "data" => array(
            "id" => 1,
            "name" => "Test User",
            "email" => "test@example.com",
            "is_admin" => false
        )
    ),
    "your_secret_jwt_key",
    'HS256'
);

echo "Token: " . $token . "<br><br>";

// Verify the token
$decoded = $authController->verifyToken($token);
if ($decoded) {
    echo "Token is valid!<br>";
    echo "User ID: " . $decoded->data->id . "<br>";
    echo "User Email: " . $decoded->data->email . "<br>";
} else {
    echo "Token is invalid!<br>";
}

// Test with invalid token
echo "<h3>5. Testing with Invalid Token</h3>";
$invalid_token = "invalid.token.here";
$decoded = $authController->verifyToken($invalid_token);
if ($decoded) {
    echo "Token is valid!<br>";
} else {
    echo "Token is invalid!<br>";
}
?>
