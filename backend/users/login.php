<?php
include '../db/db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['email'], $data['password'])) {
    $query = "SELECT * FROM users WHERE email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $data['email']]);
    $user = $stmt->fetch();

    if ($user && password_verify($data['password'], $user['password'])) {
        echo json_encode(['message' => 'Login successful!', 'user' => $user]);
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid credentials!']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
