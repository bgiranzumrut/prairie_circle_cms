<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
session_start();
include '../db/db.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No content
    exit();
}
// Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

// Parse incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['id'], $data['name'], $data['email']) ||
    empty($data['id']) || empty($data['name']) || empty($data['email'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid or missing data.']);
    exit();
}

try {
    // Prepare update query
    $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $data['id'],
        'name' => $data['name'],
        'email' => $data['email'],
    ]);

    // Check affected rows
    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['message' => 'User updated successfully!']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found or no changes made.']);
    }
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
