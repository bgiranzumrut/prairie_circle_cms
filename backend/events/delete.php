<?php
include '../db/db.php';




$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['id'])) {
    $query = "DELETE FROM events WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $data['id']]);
    echo json_encode(['message' => 'Event deleted successfully!']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
