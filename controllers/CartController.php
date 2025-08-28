<?php
// controllers/CartController.php
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/Product.php';

class CartController {
    private $cartModel;
    private $productModel;
    private $db;
    
    public function __construct($db) {
        $this->cartModel = new Cart($db);
        $this->productModel = new Product($db);
        $this->db = $db;
    }
    
    // Get user's cart items
    public function getCart($user_id) {
        $stmt = $this->cartModel->getCartItems($user_id);
        $num = $stmt->rowCount();
        
        if ($num > 0) {
            $cart_arr = array();
            $cart_arr["items"] = array();
            $cart_arr["summary"] = array();
            
            $total = 0;
            $items_count = 0;
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                $item_total = $price * $quantity;
                $total += $item_total;
                $items_count += $quantity;
                
                $cart_item = array(
                    "id" => $id,
                    "product_id" => $product_id,
                    "name" => $name,
                    "price" => (float) $price,
                    "image_url" => $image_url,
                    "quantity" => (int) $quantity,
                    "item_total" => (float) $item_total,
                    "stock_quantity" => (int) $stock_quantity
                );
                
                array_push($cart_arr["items"], $cart_item);
            }
            
            // Add summary information
            $cart_arr["summary"] = array(
                "total_items" => $items_count,
                "total_price" => (float) $total
            );
            
            http_response_code(200);
            echo json_encode($cart_arr);
        } else {
            http_response_code(200);
            echo json_encode(array(
                "items" => array(),
                "summary" => array(
                    "total_items" => 0,
                    "total_price" => 0.0
                ),
                "message" => "Your cart is empty."
            ));
        }
    }
    
    // Add item to cart
    public function addToCart($user_id) {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Validate input
        if (empty($data->product_id)) {
            http_response_code(400);
            echo json_encode(array("message" => "Product ID is required."));
            return;
        }
        
        $quantity = $data->quantity ?? 1;
        
        // Check if product exists
        $this->productModel->id = $data->product_id;
        if (!$this->productModel->readOne()) {
            http_response_code(404);
            echo json_encode(array("message" => "Product not found."));
            return;
        }
        
        // Check stock availability
        if ($this->productModel->stock_quantity < $quantity) {
            http_response_code(400);
            echo json_encode(array(
                "message" => "Insufficient stock.",
                "available_quantity" => $this->productModel->stock_quantity
            ));
            return;
        }
        
        // Add item to cart
        if ($this->cartModel->addItem($user_id, $data->product_id, $quantity)) {
            http_response_code(200);
            echo json_encode(array("message" => "Product added to cart successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to add product to cart."));
        }
    }
    
    // Update cart item quantity
    public function updateCartItem($user_id, $cart_item_id) {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Validate input
        if (!isset($data->quantity) || $data->quantity < 1) {
            http_response_code(400);
            echo json_encode(array("message" => "Valid quantity is required."));
            return;
        }
        
        // First, get the cart item to verify ownership and get product info
        $query = "SELECT ci.product_id, p.stock_quantity 
                  FROM cart_items ci
                  JOIN products p ON ci.product_id = p.id
                  WHERE ci.id = :cart_item_id AND ci.user_id = :user_id";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":cart_item_id", $cart_item_id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(array("message" => "Cart item not found."));
            return;
        }
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Check stock availability
        if ($row['stock_quantity'] < $data->quantity) {
            http_response_code(400);
            echo json_encode(array(
                "message" => "Insufficient stock.",
                "available_quantity" => $row['stock_quantity']
            ));
            return;
        }
        
        // Update quantity
        if ($this->cartModel->updateQuantity($cart_item_id, $data->quantity)) {
            http_response_code(200);
            echo json_encode(array("message" => "Cart updated successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to update cart."));
        }
    }
    
    // Remove item from cart
    public function removeFromCart($user_id, $cart_item_id) {
        // Verify ownership before deletion
        $query = "SELECT id FROM cart_items WHERE id = :cart_item_id AND user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":cart_item_id", $cart_item_id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        if ($stmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(array("message" => "Cart item not found."));
            return;
        }
        
        // Remove item
        if ($this->cartModel->removeItem($cart_item_id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Item removed from cart successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to remove item from cart."));
        }
    }
    
    // Clear user's cart
    public function clearCart($user_id) {
        if ($this->cartModel->clearCart($user_id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Cart cleared successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to clear cart."));
        }
    }
}
?>
