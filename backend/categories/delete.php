<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['id']) || empty($data['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid input.']);
        exit();
    }

    try {
        $query = "DELETE FROM categories WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['id' => $data['id']]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['message' => 'Category deleted successfully!']);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found.']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
}
?>
