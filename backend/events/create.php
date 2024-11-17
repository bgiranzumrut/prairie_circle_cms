<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Start session
session_start();

include '../db/db.php';

// Check user role
if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'event_coordinator'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Access denied.']);
    exit();
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!isset($data['title'], $data['description'], $data['category_id'], $data['event_date'], $data['status']) ||
        empty($data['title']) || empty($data['description']) || empty($data['category_id']) || empty($data['event_date']) || empty($data['status'])
    ) {
        http_response_code(400);
        echo json_encode(['error' => 'All fields are required.']);
        exit();
    }

    try {
        // Insert event into database
        $query = "INSERT INTO events (title, description, category_id, event_date, status) VALUES (:title, :description, :category_id, :event_date, :status)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $data['category_id'],
            'event_date' => $data['event_date'],
            'status' => $data['status'],
        ]);

        http_response_code(201);
        echo json_encode(['message' => 'Event created successfully!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
