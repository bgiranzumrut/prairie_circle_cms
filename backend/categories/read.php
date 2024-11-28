<?php
// Set CORS headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend's origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Content-Type: application/json"); // Ensure JSON response

session_start();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content for OPTIONS
    exit();
}

// Include database connection
include '../db/db.php';

try {
    // Fetch all categories
    $query = "SELECT * FROM categories ORDER BY id ASC"; // You can change the ordering as needed
    $stmt = $pdo->query($query);
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch as associative array

    // Return JSON response
    echo json_encode($categories, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); // JSON_UNESCAPED_UNICODE for special characters
    http_response_code(200); // OK
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500); // Internal Server Error
    error_log("Database error: " . $e->getMessage()); // Log the error for debugging
    echo json_encode(['error' => 'An error occurred while fetching categories.']);
}
?>
