<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json"); // Ensure response is JSON
session_start();

include '../db/db.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content for OPTIONS
    exit();
}

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

try {
    // Decode the incoming JSON payload
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate and sanitize input
    if (!isset($data['id']) || empty(trim($data['id'])) || !is_numeric($data['id'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid category ID.']);
        exit();
    }

    $id = intval($data['id']); // Convert to integer for safety

    // Prepare the DELETE statement
    $query = "DELETE FROM categories WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    // Check if the row was actually deleted
    if ($stmt->rowCount() > 0) {
        http_response_code(200); // OK
        echo json_encode(['message' => 'Category deleted successfully!']);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Category not found.']);
    }
} catch (PDOException $e) {
    // Handle database-related errors
    http_response_code(500); // Internal Server Error
    error_log("Database error: " . $e->getMessage()); // Log for debugging
    echo json_encode(['error' => 'An error occurred while deleting the category.']);
} catch (Exception $e) {
    // Handle generic errors
    http_response_code(500); // Internal Server Error
    error_log("General error: " . $e->getMessage()); // Log for debugging
    echo json_encode(['error' => 'An unexpected error occurred.']);
}
?>
