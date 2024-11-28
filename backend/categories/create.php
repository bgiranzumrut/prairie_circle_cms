<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json"); // Ensure response is JSON
session_start();

// Include database connection
include '../db/db.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No content for preflight request
    exit();
}

// Check if the request is a POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

try {
    // Decode incoming JSON payload
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate and sanitize input
    if (!isset($data['name']) || empty(trim($data['name']))) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Category name is required.']);
        exit();
    }

    $name = htmlspecialchars(trim($data['name']), ENT_QUOTES, 'UTF-8');
    $description = isset($data['description']) ? htmlspecialchars(trim($data['description']), ENT_QUOTES, 'UTF-8') : null;

    // Insert into the database using prepared statements
    $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'name' => $name,
        'description' => $description,
    ]);

    // Success response
    http_response_code(201); // Created
    echo json_encode(['message' => 'Category created successfully!']);
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500); // Internal Server Error
    error_log("Database error: " . $e->getMessage()); // Log error for debugging
    echo json_encode(['error' => 'An error occurred while creating the category.']);
} catch (Exception $e) {
    // Handle generic errors
    http_response_code(500); // Internal Server Error
    error_log("General error: " . $e->getMessage()); // Log error for debugging
    echo json_encode(['error' => 'An unexpected error occurred.']);
}

?>
