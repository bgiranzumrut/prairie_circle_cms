<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
session_start();
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['name'], $data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name, email, and password are required.']);
        exit();
    }

    try {
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
        $role = 'registered_user';

        $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => $role,
        ]);

        echo json_encode(['message' => 'Welcome, ' . $data['name'] . '!']);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate email
            http_response_code(409);
            echo json_encode(['error' => 'Email already in use.']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}
?>
