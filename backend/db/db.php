<?php
// CORS Headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Adjust as needed for your frontend
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

try {
    // Load credentials from environment variables (more secure in production)
    $dbHost = getenv('DB_HOST') ?: 'localhost';
    $dbName = getenv('DB_NAME') ?: 'serverside';
    $dbUser = getenv('DB_USER') ?: 'serveruser';
    $dbPass = getenv('DB_PASS') ?: 'gorgonzola7!';
    
    $dsn = "mysql:host=$dbHost;dbname=$dbName;charset=utf8";
    
    // Initialize PDO
    $pdo = new PDO($dsn, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    // Log error and return a user-friendly message
    error_log("Database connection failed: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'An error occurred while connecting to the database. Please try again later.']);
    exit();
}
?>
