<?php
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class CategoryController {
    private $category;
    
    public function __construct() {
        $this->category = new Category();
    }
    
    public function getAllCategories() {
        try {
            $categories = $this->category->getAll();
            http_response_code(200);
            echo json_encode(['success' => true, 'categories' => $categories]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function createCategory() {
        if (!AuthMiddleware::requireAdmin()) {
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Category name is required']);
            return;
        }
        
        try {
            $result = $this->category->create($data['name'], $data['description'] ?? '');
            if ($result) {
                http_response_code(201);
                echo json_encode(['success' => true, 'message' => 'Category created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to create category']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function updateCategory($id) {
        if (!AuthMiddleware::requireAdmin()) {
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Category name is required']);
            return;
        }
        
        try {
            $result = $this->category->update($id, $data['name'], $data['description'] ?? '');
            if ($result) {
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to update category']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function deleteCategory($id) {
        if (!AuthMiddleware::requireAdmin()) {
            return;
        }
        
        try {
            $result = $this->category->delete($id);
            if ($result) {
                http_response_code(200);
                echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to delete category']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
?>
