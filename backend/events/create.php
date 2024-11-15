<?php
include '../db/db.php';
include '../authentication/middleware.php';

authenticate(['admin', 'event_coordinator']);

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['title'], $data['description'], $data['category_id'], $data['event_date'], $data['status'])) {
    $query = "INSERT INTO events (title, description, category_id, event_date, status) VALUES (:title, :description, :category_id, :event_date, :status)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'title' => $data['title'],
        'description' => $data['description'],
        'category_id' => $data['category_id'],
        'event_date' => $data['event_date'],
        'status' => $data['status'],
    ]);
    echo json_encode(['message' => 'Event created successfully!']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
