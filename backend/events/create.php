<?php
include '../db/db.php';

header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS"); // Allow specific HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow specific headers
header("Access-Control-Max-Age: 86400"); // Cache preflight response for 1 day

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['title'], $data['description'], $data['category_id'], $data['event_date'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

try {
    $stmt = $pdo->prepare("INSERT INTO events (title, description, category_id, event_date, status, image_path) 
                           VALUES (:title, :description, :category_id, :event_date, :status, :image_path)");
    $stmt->execute([
        'title' => $data['title'],
        'description' => $data['description'],
        'category_id' => $data['category_id'],
        'event_date' => $data['event_date'],
        'status' => $data['status'],
        'image_path' => $data['image_path'] ?? null,
    ]);

    echo json_encode(['message' => 'Event created successfully!', 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
