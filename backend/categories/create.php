<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
session_start();
// Include database connection
include '../db/db.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No content
    exit();
}

// Check if the request is a POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}



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
