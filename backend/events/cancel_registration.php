<?php
// CORS and Headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Allow requests from your frontend
header("Access-Control-Allow-Credentials: true"); // Allow cookies/sessions
header("Access-Control-Allow-Methods: GET, POST, OPTIONS"); // Allowed HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allowed headers
header("Content-Type: application/json"); // Ensure JSON response

include '../db/db.php'; // Include database connection
session_start();

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No content for preflight request
    exit();
}

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'You must be logged in to cancel registrations.']);
    exit();
}

// Decode the incoming JSON request body
$data = json_decode(file_get_contents("php://input"), true);

// Validate input
if (!isset($data['eventId']) || !filter_var($data['eventId'], FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid or missing event ID.']);
    exit();
}

$userId = $_SESSION['user_id']; // Retrieve user ID from session
$eventId = $data['eventId']; // Retrieve event ID from input

try {
    // Delete the registration record
    $query = "DELETE FROM registrations WHERE user_id = :userId AND event_id = :eventId";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId, 'eventId' => $eventId]);

    // Check if any rows were affected
    if ($stmt->rowCount() > 0) {
        http_response_code(200); // Success
        echo json_encode(['message' => 'Successfully canceled registration.']);
    } else {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'No registration found to cancel.']);
    }
} catch (PDOException $e) {
    // Log and return a generic error message
    error_log("Database error: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'An error occurred while canceling the registration.']);
}
?>
