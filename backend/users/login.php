<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include '../db/db.php';
session_start(); // Start the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if email and password are provided
    if (!isset($data['email'], $data['password']) || empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required.']);
        exit();
    }

    try {
        // Query the database for the user by email
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();

        if ($user && password_verify($data['password'], $user['password'])) {
            // Set session variables for the logged-in user
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Return success response with user details
            http_response_code(200);
            echo json_encode([
                'message' => 'Login successful!',
                'name' => $user['name'],
                'role' => $user['role'],
                'id' => $user['id']
            ]);
        } else {
            // Invalid credentials
            http_response_code(401);
            echo json_encode(['error' => 'Invalid email or password.']);
        }
    } catch (PDOException $e) {
        // Handle any database errors
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
