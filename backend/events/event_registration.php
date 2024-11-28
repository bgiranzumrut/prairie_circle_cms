<?php
header("Content-Type: application/json"); // Ensure JSON response
header("Access-Control-Allow-Origin: http://localhost:5173"); // Allow requests from frontend
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Allow POST and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Specify allowed headers
header("Access-Control-Allow-Credentials: true"); // Allow cookies/sessions

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

include '../db/db.php';

try {
    // Ensure the request method is POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method. Use POST.");
    }

    // Decode JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!isset($data['userId'], $data['eventId']) || !filter_var($data['userId'], FILTER_VALIDATE_INT) || !filter_var($data['eventId'], FILTER_VALIDATE_INT)) {
        throw new Exception("Invalid or missing userId or eventId.");
    }

    $userId = $data['userId'];
    $eventId = $data['eventId'];

    // Insert registration into the database
    $query = "INSERT INTO registrations (user_id, event_id) VALUES (:userId, :eventId)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId, 'eventId' => $eventId]);

    echo json_encode(['message' => 'Registration successful!']);
    http_response_code(200); // OK
} catch (PDOException $e) {
    // Handle database errors
    if ($e->getCode() === '23000') { // SQLSTATE 23000: Integrity constraint violation (e.g., duplicate entry)
        echo json_encode(['error' => 'You are already registered for this event.']);
        http_response_code(400); // Bad Request
    } else {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['error' => 'A database error occurred.']);
        http_response_code(500); // Internal Server Error
    }
} catch (Exception $e) {
    // Handle general errors
    error_log("Error: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(400); // Bad Request
}
?>
