<?php
// test_db.php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

if ($db) {
    echo "Database connection successful!<br>";
    
    // List all tables to verify our structure
    $query = "SHOW TABLES";
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "Tables in database:";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . $table . "</li>";
    }
    echo "</ul>";
} else {
    echo "Database connection failed!";
}
?>
