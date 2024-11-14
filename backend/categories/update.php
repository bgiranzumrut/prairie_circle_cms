<?php
include '../db/db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['id'], $data['name'], $data['description'])) {
    $query = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $data['id'], 'name' => $data['name'], 'description' => $data['description']]);
    echo json_encode(['message' => 'Category updated successfully!']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
