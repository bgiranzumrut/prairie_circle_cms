<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // Allow requests from the frontend
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Include database connection
include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    http_response_code(204);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!isset($data['name'], $data['email'], $data['password']) || 
        empty($data['name']) || 
        empty($data['email']) || 
        empty($data['password'])) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Name, email, and password are required.']);
        exit();
    }

    try {
        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

        // Default user role
        $role = 'registered_user';

        // Insert user into the database
        $query = "INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => $role,
        ]);

        // Return success message
        http_response_code(201); // Created
        echo json_encode(['message' => 'Welcome, ' . $data['name'] . '!']);
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate email
            http_response_code(409); // Conflict
            echo json_encode(['error' => 'Email already in use.']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'An error occurred. Please try again.']);
        }
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
