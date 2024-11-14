<?php
include '../db/db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['name'], $data['description'])) {
    $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['name' => $data['name'], 'description' => $data['description']]);
    echo json_encode(['message' => 'Category created successfully!']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
