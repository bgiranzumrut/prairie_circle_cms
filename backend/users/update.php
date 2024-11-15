<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['id'], $data['name'], $data['email'], $data['role']) ||
    empty($data['id']) || empty($data['name']) || empty($data['email'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid or missing data.']);
    exit();
}

try {
    $query = "UPDATE users SET name = :name, email = :email, role = :role WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $data['id'],
        'name' => $data['name'],
        'email' => $data['email'],
        'role' => $data['role'],
    ]);

    if ($stmt->rowCount() > 0) {
        http_response_code(200);
        echo json_encode(['message' => 'User updated successfully!']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found or no changes made.']);
    }
} catch (PDOException $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
