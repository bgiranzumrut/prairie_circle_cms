<?php
// Set CORS and response headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Update with your frontend origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json"); // Ensure JSON response
session_start();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No content
    exit();
}

// Include database connection
include '../db/db.php';

// Error handling and logging
ini_set('display_errors', 0); // Hide errors from users
ini_set('log_errors', 1); // Enable error logging
error_reporting(E_ALL);

try {
    // Validate the request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405); // Method Not Allowed
        throw new Exception("Invalid request method. Use POST instead.");
    }

    // Decode JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate required fields
    if (!isset($data['event_id'], $data['user_id'], $data['comment'], $data['captcha'])) {
        http_response_code(400); // Bad Request
        throw new Exception("Missing required parameters.");
    }

    // Extract and sanitize inputs
    $event_id = (int)$data['event_id'];
    $user_id = (int)$data['user_id'];
    $comment = htmlspecialchars(trim($data['comment']), ENT_QUOTES, 'UTF-8'); // Sanitize comment
    $captcha = trim($data['captcha']);

    // Validate CAPTCHA
    if (!isset($_SESSION['captcha']) || strtolower($captcha) !== strtolower($_SESSION['captcha'])) {
        http_response_code(400); // Bad Request
        throw new Exception("Invalid CAPTCHA.");
    }

    // Insert comment into the database
    $query = "INSERT INTO event_comments (event_id, user_id, comment) VALUES (:event_id, :user_id, :comment)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'event_id' => $event_id,
        'user_id' => $user_id,
        'comment' => $comment
    ]);

    // Clear the CAPTCHA session after successful submission
    unset($_SESSION['captcha']);

    echo json_encode(['message' => 'Comment added successfully!']);
    http_response_code(200); // Success
} catch (PDOException $e) {
    // Handle database errors
    if ($e->getCode() === '23000') { // Duplicate entry
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'You have already submitted a comment for this event.']);
    } else {
        http_response_code(500); // Internal Server Error
        error_log("Database error: " . $e->getMessage()); // Log the error
        echo json_encode(['error' => 'A database error occurred.']);
    }
} catch (Exception $e) {
    // Handle general exceptions
    http_response_code(400); // Bad Request
    error_log("Error: " . $e->getMessage()); // Log the error
    echo json_encode(['error' => $e->getMessage()]);
}
?>
