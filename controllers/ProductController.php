<?php
// controllers/ProductController.php
require_once __DIR__ . '/../models/Product.php';

class ProductController {
    private $productModel;
    
    public function __construct($db) {
        $this->productModel = new Product($db);
    }
    
    // Get all products with optional search and pagination
    public function readAll() {
        // Get query parameters
        $search = isset($_GET['search']) ? $_GET['search'] : null;
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $per_page = isset($_GET['per_page']) ? $_GET['per_page'] : 10;
        
        // Calculate pagination
        $from_record_num = ($per_page * $page) - $per_page;
        
        // Get products
        $stmt = $this->productModel->read($search, $from_record_num, $per_page);
        $num = $stmt->rowCount();
        
        // Check if any products found
        if ($num > 0) {
            $products_arr = array();
            $products_arr["records"] = array();
            $products_arr["paging"] = array();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                $product_item = array(
                    "id" => $id,
                    "name" => $name,
                    "description" => $description,
                    "price" => (float) $price,
                    "image_url" => $image_url,
                    "stock_quantity" => (int) $stock_quantity,
                    "created_at" => $created_at
                );
                
                array_push($products_arr["records"], $product_item);
            }
            
            // Include paging information
            $total_rows = $this->productModel->count($search);
            $products_arr["paging"] = array(
                "current_page" => (int) $page,
                "per_page" => (int) $per_page,
                "total_rows" => (int) $total_rows,
                "total_pages" => ceil($total_rows / $per_page)
            );
            
            http_response_code(200);
            echo json_encode($products_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No products found."));
        }
    }
    
    // Get single product by ID
    public function read($id) {
        $this->productModel->id = $id;
        
        if ($this->productModel->readOne()) {
            $product_arr = array(
                "id" => $this->productModel->id,
                "name" => $this->productModel->name,
                "description" => $this->productModel->description,
                "price" => (float) $this->productModel->price,
                "image_url" => $this->productModel->image_url,
                "stock_quantity" => (int) $this->productModel->stock_quantity,
                "created_at" => $this->productModel->created_at
            );
            
            http_response_code(200);
            echo json_encode($product_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Product not found."));
        }
    }
    
    // Create new product (Admin only)
    public function create() {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Validate input
        if (empty($data->name) || empty($data->price)) {
            http_response_code(400);
            echo json_encode(array("message" => "Missing required fields."));
            return;
        }
        
        // Set product properties
        $this->productModel->name = $data->name;
        $this->productModel->description = $data->description ?? '';
        $this->productModel->price = $data->price;
        $this->productModel->image_url = $data->image_url ?? '';
        $this->productModel->stock_quantity = $data->stock_quantity ?? 0;
        
        // Create product
        if ($this->productModel->create()) {
            http_response_code(201);
            echo json_encode(array("message" => "Product created successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to create product."));
        }
    }
    
    // Update a product (Admin only)
    public function update($id) {
        // Get posted data
        $data = json_decode(file_get_contents("php://input"));
        
        // Set ID and check if product exists
        $this->productModel->id = $id;
        if (!$this->productModel->readOne()) {
            http_response_code(404);
            echo json_encode(array("message" => "Product not found."));
            return;
        }
        
        // Set product properties
        $this->productModel->name = $data->name ?? $this->productModel->name;
        $this->productModel->description = $data->description ?? $this->productModel->description;
        $this->productModel->price = $data->price ?? $this->productModel->price;
        $this->productModel->image_url = $data->image_url ?? $this->productModel->image_url;
        $this->productModel->stock_quantity = $data->stock_quantity ?? $this->productModel->stock_quantity;
        
        // Update product
        if ($this->productModel->update()) {
            http_response_code(200);
            echo json_encode(array("message" => "Product updated successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to update product."));
        }
    }
    
    // Delete a product (Admin only)
    public function delete($id) {
        // Set ID and check if product exists
        $this->productModel->id = $id;
        if (!$this->productModel->readOne()) {
            http_response_code(404);
            echo json_encode(array("message" => "Product not found."));
            return;
        }
        
        // Delete product
        if ($this->productModel->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Product deleted successfully."));
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Unable to delete product."));
        }
    }
}
?>
