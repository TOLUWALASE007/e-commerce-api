<?php
// test_setup.php - Comprehensive setup verification

echo "<h1>E-Commerce API Setup Verification</h1>";

// Test 1: PHP Version
echo "<h2>1. PHP Version Check</h2>";
echo "PHP Version: " . phpversion() . "<br>";
if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
    echo "✅ PHP version is compatible<br>";
} else {
    echo "❌ PHP version must be 7.4 or higher<br>";
}

// Test 2: Required Extensions
echo "<h2>2. Required Extensions</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'json', 'openssl'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✅ $ext extension loaded<br>";
    } else {
        echo "❌ $ext extension not loaded<br>";
    }
}

// Test 3: Database Connection
echo "<h2>3. Database Connection Test</h2>";
if (file_exists('config/database.php')) {
    require_once 'config/database.php';
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        if ($db) {
            echo "✅ Database connection successful<br>";
            
            // Test if tables exist
            $query = "SHOW TABLES";
            $stmt = $db->prepare($query);
            $stmt->execute();
            $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            echo "Tables found: " . count($tables) . "<br>";
            if (count($tables) > 0) {
                echo "Table list:<br><ul>";
                foreach ($tables as $table) {
                    echo "<li>$table</li>";
                }
                echo "</ul>";
            }
        } else {
            echo "❌ Database connection failed<br>";
        }
    } catch (Exception $e) {
        echo "❌ Database error: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Database configuration file not found<br>";
}

// Test 4: File Structure
echo "<h2>4. File Structure Check</h2>";
$required_files = [
    'index.php',
    '.htaccess',
    'composer.json',
    'config/database.php',
    'database_schema.sql'
];

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}

// Test 5: Directory Structure
echo "<h2>5. Directory Structure Check</h2>";
$required_dirs = ['config', 'controllers', 'models', 'middleware'];
foreach ($required_dirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ $dir/ directory exists<br>";
    } else {
        echo "❌ $dir/ directory missing<br>";
    }
}

// Test 6: Composer
echo "<h2>6. Composer Check</h2>";
if (file_exists('vendor/autoload.php')) {
    echo "✅ Composer dependencies installed<br>";
} else {
    echo "❌ Composer dependencies not installed<br>";
    echo "Run: composer install<br>";
}

echo "<h2>Setup Summary</h2>";
echo "If you see any ❌ marks above, please fix those issues before proceeding.<br>";
echo "Once all tests pass, you can visit: <a href='index.php'>http://localhost/ecommerce-api/</a><br>";
?>
