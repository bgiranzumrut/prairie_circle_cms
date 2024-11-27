<?php
header("Content-Type: application/json"); // Ensure JSON response
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include '../db/db.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception("Invalid request method.");
    }

    $data = json_decode(file_get_contents("php://input"), true);
    if (!isset($data['userId'], $data['eventId'])) {
        throw new Exception("Missing required parameters.");
    }

    $userId = $data['userId'];
    $eventId = $data['eventId'];

    // Attempt to insert into the database
    $query = "INSERT INTO registrations (user_id, event_id) VALUES (:userId, :eventId)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['userId' => $userId, 'eventId' => $eventId]);

    echo json_encode(['message' => 'Registration successful!']);
    http_response_code(200);
} catch (PDOException $e) {
    // Check if the error is caused by a duplicate entry
    if ($e->getCode() === '23000') { // SQLSTATE 23000 is for integrity constraint violations
        echo json_encode(['error' => 'You are already registered for this event.']);
        http_response_code(400);
    } else {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(['error' => 'A database error occurred.']);
        http_response_code(500);
    }
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
    http_response_code(400);
}