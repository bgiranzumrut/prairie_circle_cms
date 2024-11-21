<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id'], $data['name']) || empty($data['id']) || empty($data['name'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input.']);
        exit();
    }

    try {
        $query = "UPDATE categories SET name = :name, description = :description WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'id' => $data['id'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
        echo json_encode(['message' => 'Category updated successfully!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
