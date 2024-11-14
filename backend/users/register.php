<?php
include '../db/db.php';

// Set response header to JSON
header("Content-Type: application/json");

try {
    // Get input data
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate inputs
    if (empty($data['name']) || !is_string($data['name'])) {
        echo json_encode(["error" => "Invalid name"]);
        http_response_code(400);
        exit;
    }
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["error" => "Invalid email"]);
        http_response_code(400);
        exit;
    }
    if (empty($data['password']) || strlen($data['password']) < 6) {
        echo json_encode(["error" => "Password must be at least 6 characters"]);
        http_response_code(400);
        exit;
    }
    if (empty($data['role']) || !in_array($data['role'], ['admin', 'event_coordinator', 'registered_user'])) {
        echo json_encode(["error" => "Invalid role"]);
        http_response_code(400);
        exit;
    }

    // Check for duplicate email
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
    $stmt->execute(['email' => $data['email']]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(["error" => "Email already exists"]);
        http_response_code(409); // Conflict
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

    // Insert user into database
    $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)");
    $stmt->execute([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => $hashedPassword,
        'role' => $data['role']
    ]);

    // Success response
    echo json_encode(["message" => "User registered successfully!"]);
    http_response_code(201); // Created
} catch (PDOException $e) {
    // Handle database errors gracefully
    echo json_encode(["error" => "Internal Server Error", "details" => $e->getMessage()]);
    http_response_code(500);
}
