<?php
require_once __DIR__ . '/../config/database.php';

class Review {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getByProductId($productId) {
        $stmt = $this->db->prepare("
            SELECT r.*, u.username 
            FROM reviews r 
            JOIN users u ON r.user_id = u.id 
            WHERE r.product_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($userId, $productId, $rating, $comment) {
        $stmt = $this->db->prepare("
            INSERT INTO reviews (user_id, product_id, rating, comment) 
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $productId, $rating, $comment]);
    }
    
    public function getAverageRating($productId) {
        $stmt = $this->db->prepare("SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = ?");
        $stmt->execute([$productId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($result['avg_rating'], 1);
    }
    
    public function getUserReview($userId, $productId) {
        $stmt = $this->db->prepare("SELECT * FROM reviews WHERE user_id = ? AND product_id = ?");
        $stmt->execute([$userId, $productId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
