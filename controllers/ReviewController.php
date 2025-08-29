<?php
require_once __DIR__ . '/../models/Review.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class ReviewController {
    private $review;
    
    public function __construct() {
        $this->review = new Review();
    }
    
    public function getProductReviews($productId) {
        try {
            $reviews = $this->review->getByProductId($productId);
            $avgRating = $this->review->getAverageRating($productId);
            
            http_response_code(200);
            echo json_encode([
                'success' => true, 
                'reviews' => $reviews,
                'average_rating' => $avgRating
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
    
    public function createReview() {
        if (!AuthMiddleware::requireAuth()) {
            return;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['product_id']) || !isset($data['rating']) || !isset($data['comment'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Product ID, rating, and comment are required']);
            return;
        }
        
        if ($data['rating'] < 1 || $data['rating'] > 5) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Rating must be between 1 and 5']);
            return;
        }
        
        try {
            $userId = $_SESSION['user_id'];
            
            // Check if user already reviewed this product
            $existingReview = $this->review->getUserReview($userId, $data['product_id']);
            if ($existingReview) {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => 'You have already reviewed this product']);
                return;
            }
            
            $result = $this->review->create($userId, $data['product_id'], $data['rating'], $data['comment']);
            if ($result) {
                http_response_code(201);
                echo json_encode(['success' => true, 'message' => 'Review created successfully']);
            } else {
                http_response_code(500);
                echo json_encode(['success' => false, 'error' => 'Failed to create review']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }
}
?>
