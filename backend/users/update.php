<?php
// CORS Headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Adjust for your frontend origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");
session_start();

// Include database connection
include '../db/db.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

// Decode incoming JSON data
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['id'], $data['name'], $data['email']) ||
    empty(trim($data['id'])) || empty(trim($data['name'])) || empty(trim($data['email']))) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid or missing data.']);
    exit();
}

// Sanitize inputs
$id = filter_var($data['id'], FILTER_VALIDATE_INT);
$name = htmlspecialchars(strip_tags(trim($data['name'])));
$email = filter_var(trim($data['email']), FILTER_VALIDATE_EMAIL);

if (!$id || !$email) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid ID or email format.']);
    exit();
}

try {
    // Prepare update query
    $query = "UPDATE users SET name = :name, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $id,
        'name' => $name,
        'email' => $email,
    ]);

    // Check if any rows were affected
    if ($stmt->rowCount() > 0) {
        http_response_code(200); // Success
        echo json_encode(['message' => 'User updated successfully!']);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'User not found or no changes made.']);
    }
} catch (PDOException $e) {
    // Handle database errors
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
