<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name']) || empty($data['name'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Category name is required']);
        exit();
    }

    try {
        $query = "INSERT INTO categories (name, description) VALUES (:name, :description)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);
        echo json_encode(['message' => 'Category created successfully!']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
