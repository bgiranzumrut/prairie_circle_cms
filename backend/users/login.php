<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // Allow requests from your frontend
header("Access-Control-Allow-Methods: POST, OPTIONS"); // Allow POST and OPTIONS methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allow headers
header("Access-Control-Allow-Credentials: true"); // Allow cookies/sessions

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight request
    http_response_code(204); // No Content
    exit();
}

session_start();

// Clear any existing session data
session_unset();
session_destroy();

// Start a new session
session_start();

include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate input
    if (!isset($data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required.']);
        exit();
    }
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid email format.']);
        exit();
    }

    try {
        // Fetch user by email
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($data['password'], $user['password'])) {
            // Regenerate session ID for security
            session_regenerate_id(true);

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Respond with user details
            http_response_code(200);
            echo json_encode([
                'message' => 'Login successful!',
                'name' => $user['name'],
                'id' => $user['id'],
                'role' => $user['role'],
            ]);
        } else {
            // Log failed login attempt
            error_log("Failed login attempt for email: " . $data['email']);
            
            http_response_code(401); // Unauthorized
            echo json_encode(['error' => 'Invalid email or password.']);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage()); // Log database error
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'An error occurred while processing your request.']);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
