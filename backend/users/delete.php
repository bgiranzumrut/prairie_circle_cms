<?php
// Enable CORS for frontend-backend communication
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend's domain
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true"); // Allow session cookies

session_start();

// Include database connection
include '../db/db.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No content
    exit();
}

// Check if the user is authenticated and authorized (e.g., admin)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    http_response_code(403); // Forbidden
    echo json_encode(['error' => 'Unauthorized access.']);
    exit();
}

// Check if the request is a POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

// Decode JSON request body
$data = json_decode(file_get_contents("php://input"), true);

// Validate the received data
if (!isset($data['id']) || empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid or missing user ID.']);
    exit();
}

try {
    // Prepare and execute the delete query
    $query = "DELETE FROM users WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $data['id']]);

    // Check if a row was affected (user was deleted)
    if ($stmt->rowCount() > 0) {
        http_response_code(200); // OK
        echo json_encode(['message' => 'User deleted successfully!']);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'User not found.']);
    }
} catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage()); // Log the error
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'A database error occurred. Please try again later.']);
}
?>
