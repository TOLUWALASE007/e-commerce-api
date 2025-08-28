<?php
// models/User.php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $name;
    public $email;
    public $password;
    public $is_admin;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if email exists
    public function emailExists() {
        $query = "SELECT id, name, password, is_admin
                  FROM " . $this->table_name . "
                  WHERE email = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->password = $row['password'];
            $this->is_admin = $row['is_admin'];
            return true;
        }
        return false;
    }

    // Create new user
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET name=:name, email=:email, password=:password, is_admin=:is_admin";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->is_admin = htmlspecialchars(strip_tags($this->is_admin));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        
        // Hash password before saving
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $password_hash);
        
        $stmt->bindParam(":is_admin", $this->is_admin);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get user by ID
    public function getById() {
        $query = "SELECT id, name, email, is_admin, created_at
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->is_admin = $row['is_admin'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }
}
?>
