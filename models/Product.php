<?php
// models/Product.php
class Product {
    private $conn;
    private $table_name = "products";

    public $id;
    public $name;
    public $description;
    public $price;
    public $image_url;
    public $stock_quantity;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all products with optional search and pagination
    public function read($search = null, $from_record_num = 0, $records_per_page = 10) {
        $query = "SELECT id, name, description, price, image_url, stock_quantity, created_at
                  FROM " . $this->table_name;
        
        // Add search condition if provided
        if (!empty($search)) {
            $query .= " WHERE name LIKE :search OR description LIKE :search";
        }
        
        $query .= " ORDER BY created_at DESC LIMIT :from_record_num, :records_per_page";

        $stmt = $this->conn->prepare($query);

        // Bind parameters
        if (!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(":search", $search_term);
        }
        
        $stmt->bindParam(":from_record_num", $from_record_num, PDO::PARAM_INT);
        $stmt->bindParam(":records_per_page", $records_per_page, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt;
    }

    // Get total count of products (for pagination)
    public function count($search = null) {
        $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name;
        
        if (!empty($search)) {
            $query .= " WHERE name LIKE :search OR description LIKE :search";
        }

        $stmt = $this->conn->prepare($query);
        
        if (!empty($search)) {
            $search_term = "%{$search}%";
            $stmt->bindParam(":search", $search_term);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total_rows'];
    }

    // Get single product by ID
    public function readOne() {
        $query = "SELECT id, name, description, price, image_url, stock_quantity, created_at
                  FROM " . $this->table_name . "
                  WHERE id = ?
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->name = $row['name'];
            $this->description = $row['description'];
            $this->price = $row['price'];
            $this->image_url = $row['image_url'];
            $this->stock_quantity = $row['stock_quantity'];
            $this->created_at = $row['created_at'];
            return true;
        }
        return false;
    }

    // Create new product
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET name=:name, description=:description, price=:price, 
                      image_url=:image_url, stock_quantity=:stock_quantity";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->stock_quantity = htmlspecialchars(strip_tags($this->stock_quantity));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update a product
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET name=:name, description=:description, price=:price, 
                      image_url=:image_url, stock_quantity=:stock_quantity
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->price = htmlspecialchars(strip_tags($this->price));
        $this->image_url = htmlspecialchars(strip_tags($this->image_url));
        $this->stock_quantity = htmlspecialchars(strip_tags($this->stock_quantity));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind parameters
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":image_url", $this->image_url);
        $stmt->bindParam(":stock_quantity", $this->stock_quantity);
        $stmt->bindParam(":id", $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete a product
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(1, $this->id);
        
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
