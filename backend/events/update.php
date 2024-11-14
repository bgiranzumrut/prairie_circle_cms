<?php
include '../db/db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['id'], $data['title'], $data['description'], $data['category_id'], $data['event_date'], $data['status'])) {
    $query = "UPDATE events SET title = :title, description = :description, category_id = :category_id, event_date = :event_date, status = :status WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $data['id'],
        'title' => $data['title'],
        'description' => $data['description'],
        'category_id' => $data['category_id'],
        'event_date' => $data['event_date'],
        'status' => $data['status'],
    ]);
    echo json_encode(['message' => 'Event updated successfully!']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
