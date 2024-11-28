<?php
// Set CORS headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend's origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json"); // Ensure JSON response

session_start();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

// Include database connection
include '../db/db.php';

// Validate the request method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

try {
    // Decode JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    if (!isset($data['id'], $data['name']) || empty($data['id']) || empty($data['name'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Both ID and name are required.']);
        exit();
    }

    // Prepare and execute the update query
    $query = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $data['id'], // Ensure ID is valid
        'name' => htmlspecialchars($data['name'], ENT_QUOTES, 'UTF-8'), // Sanitize name
        'description' => isset($data['description']) ? htmlspecialchars($data['description'], ENT_QUOTES, 'UTF-8') : null // Sanitize description
    ]);

    // Check if the update affected any rows
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'Category updated successfully!']);
        http_response_code(200); // OK
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'Category not found or no changes made.']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    error_log("Database error: " . $e->getMessage()); // Log the error for debugging
    echo json_encode(['error' => 'An error occurred while updating the category.']);
}
?>
