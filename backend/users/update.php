<?php
include '../db/db.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'], $data['name'], $data['email'], $data['password'], $data['role'])) {
    $query = "UPDATE users 
              SET name = :name, email = :email, password = :password, role = :role 
              WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'id' => $data['id'],
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        'role' => $data['role'],
    ]);
    
    if ($stmt->rowCount() > 0) {
        echo json_encode(['message' => 'User updated successfully!']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'User not found or no changes made!']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
