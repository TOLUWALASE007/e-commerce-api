<?php
// models/Cart.php
class Cart {
    private $conn;
    private $table_name = "cart_items";

    public $id;
    public $user_id;
    public $product_id;
    public $quantity;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get cart items for a user
    public function getCartItems($user_id) {
        $query = "SELECT ci.id, ci.quantity, p.id as product_id, p.name, p.price, p.image_url, p.stock_quantity
                  FROM " . $this->table_name . " ci
                  JOIN products p ON ci.product_id = p.id
                  WHERE ci.user_id = :user_id
                  ORDER BY ci.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    // Check if product already exists in user's cart
    public function itemExists($user_id, $product_id) {
        $query = "SELECT id, quantity 
                  FROM " . $this->table_name . " 
                  WHERE user_id = :user_id AND product_id = :product_id 
                  LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->quantity = $row['quantity'];
            return true;
        }
        return false;
    }

    // Add item to cart
    public function addItem($user_id, $product_id, $quantity = 1) {
        // Check if item already exists in cart
        if ($this->itemExists($user_id, $product_id)) {
            // Update quantity if item exists
            return $this->updateQuantity($this->id, $this->quantity + $quantity);
        }

        $query = "INSERT INTO " . $this->table_name . "
                  SET user_id=:user_id, product_id=:product_id, quantity=:quantity";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $user_id = htmlspecialchars(strip_tags($user_id));
        $product_id = htmlspecialchars(strip_tags($product_id));
        $quantity = htmlspecialchars(strip_tags($quantity));

        // Bind parameters
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":product_id", $product_id);
        $stmt->bindParam(":quantity", $quantity);

        // Execute query
        return $stmt->execute();
    }

    // Update item quantity
    public function updateQuantity($cart_item_id, $quantity) {
        $query = "UPDATE " . $this->table_name . "
                  SET quantity=:quantity
                  WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Sanitize inputs
        $quantity = htmlspecialchars(strip_tags($quantity));
        $cart_item_id = htmlspecialchars(strip_tags($cart_item_id));

        // Bind parameters
        $stmt->bindParam(":quantity", $quantity);
        $stmt->bindParam(":id", $cart_item_id);

        // Execute query
        return $stmt->execute();
    }

    // Remove item from cart
    public function removeItem($cart_item_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        
        $cart_item_id = htmlspecialchars(strip_tags($cart_item_id));
        $stmt->bindParam(":id", $cart_item_id);
        
        return $stmt->execute();
    }

    // Clear user's cart
    public function clearCart($user_id) {
        $query = "DELETE FROM " . $this->table_name . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        
        $user_id = htmlspecialchars(strip_tags($user_id));
        $stmt->bindParam(":user_id", $user_id);
        
        return $stmt->execute();
    }

    // Get cart total for user
    public function getCartTotal($user_id) {
        $query = "SELECT SUM(p.price * ci.quantity) as total
                  FROM " . $this->table_name . " ci
                  JOIN products p ON ci.product_id = p.id
                  WHERE ci.user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'] ?? 0;
    }

    // Check if all items in cart are available in stock
    public function checkStockAvailability($user_id) {
        $query = "SELECT ci.product_id, ci.quantity, p.name, p.stock_quantity
                  FROM " . $this->table_name . " ci
                  JOIN products p ON ci.product_id = p.id
                  WHERE ci.user_id = :user_id AND ci.quantity > p.stock_quantity";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $out_of_stock_items = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $out_of_stock_items[] = array(
                    "product_id" => $row['product_id'],
                    "product_name" => $row['name'],
                    "requested_quantity" => $row['quantity'],
                    "available_quantity" => $row['stock_quantity']
                );
            }
            return $out_of_stock_items;
        }
        return true; // All items are available
    }
}
?>
