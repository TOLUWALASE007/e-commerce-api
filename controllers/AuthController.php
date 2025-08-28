<?php
// controllers/AuthController.php
require_once __DIR__ . '/../models/User.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController {
    private $userModel;
    private $secret_key = "your_secret_jwt_key"; // Change this in production!
    
    public function __construct($db) {
        $this->userModel = new User($db);
    }
    
    public function register() {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Validate input
        if (empty($data->name) || empty($data->email) || empty($data->password)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required fields."));
            return;
        }
        
        // Set user properties
        $this->userModel->name = $data->name;
        $this->userModel->email = $data->email;
        $this->userModel->password = $data->password;
        $this->userModel->is_admin = isset($data->is_admin) ? $data->is_admin : false;
        
        // Check if email already exists
        if ($this->userModel->emailExists()) {
            http_response_code(409);
            echo json_encode(array("message" => "User already exists."));
            return;
        }
        
        // Create user
        if ($this->userModel->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "User created successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to create user."));
        }
    }
    
    public function login() {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Validate input
        if (empty($data->email) || empty($data->password)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing email or password."));
            return;
        }
        
        // Set email property
        $this->userModel->email = $data->email;
        
        // Check if email exists and password is correct
        if (!$this->userModel->emailExists() || !password_verify($data->password, $this->userModel->password)) {
            http_response_code(401);
            echo json_encode(array("message" => "Invalid credentials."));
            return;
        }
        
        // Generate JWT token
        $issuer_claim = "localhost"; // This can be the server name
        $issuedat_claim = time(); // Issued at
        $notbefore_claim = $issuedat_claim; // Not before
        $expire_claim = $issuedat_claim + 3600; // Expire in 1 hour
        
        $token = array(
            "iss" => $issuer_claim,
            "iat" => $issuedat_claim,
            "nbf" => $notbefore_claim,
            "exp" => $expire_claim,
            "data" => array(
                "id" => $this->userModel->id,
                "name" => $this->userModel->name,
                "email" => $this->userModel->email,
                "is_admin" => $this->userModel->is_admin
            )
        );
        
        $jwt = JWT::encode($token, $this->secret_key, 'HS256');
        
        http_response_code(200);
        echo json_encode(
            array(
                "message" => "Login successful.",
                "token" => $jwt,
                "expires" => $expire_claim,
                "user" => array(
                    "id" => $this->userModel->id,
                    "name" => $this->userModel->name,
                    "email" => $this->userModel->email,
                    "is_admin" => $this->userModel->is_admin
                )
            )
        );
    }
    
    // Verify JWT token
    public function verifyToken($token) {
        try {
            $decoded = JWT::decode($token, new Key($this->secret_key, 'HS256'));
            return $decoded;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
