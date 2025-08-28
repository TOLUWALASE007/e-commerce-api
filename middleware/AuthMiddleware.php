<?php
// middleware/AuthMiddleware.php
require_once __DIR__ . '/../controllers/AuthController.php';

class AuthMiddleware {
    public static function authenticate() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied. No token provided."));
            exit();
        }
        
        $authHeader = $headers['Authorization'];
        $arr = explode(" ", $authHeader);
        $token = $arr[1] ?? null;
        
        if (!$token) {
            http_response_code(401);
            echo json_encode(array("message" => "Access denied. No token provided."));
            exit();
        }
        
        $database = new Database();
        $db = $database->getConnection();
        $authController = new AuthController($db);
        
        $decoded = $authController->verifyToken($token);
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(array("message" => "Invalid token."));
            exit();
        }
        
        return $decoded;
    }
    
    public static function requireAdmin() {
        $decoded = self::authenticate();
        
        if (!$decoded->data->is_admin) {
            http_response_code(403);
            echo json_encode(array("message" => "Admin access required."));
            exit();
        }
        
        return $decoded;
    }
}
?>
