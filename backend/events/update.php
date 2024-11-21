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

if (!isset($data['id'], $data['title'], $data['description'], $data['category_id'], $data['event_date'], $data['status'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit();
}

try {
    $stmt = $pdo->prepare("UPDATE events 
                           SET title = :title, description = :description, category_id = :category_id, 
                               event_date = :event_date, status = :status, image_path = :image_path 
                           WHERE id = :id");
    $stmt->execute([
        'id' => $data['id'],
        'title' => $data['title'],
        'description' => $data['description'],
        'category_id' => $data['category_id'],
        'event_date' => $data['event_date'],
        'status' => $data['status'],
        'image_path' => $data['image_path'] ?? null,
    ]);

    echo json_encode(['message' => 'Event updated successfully!']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
