<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // Frontend origin
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Start session
session_start();

include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validate email and password
    if (!isset($data['email'], $data['password']) || empty($data['email']) || empty($data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Email and password are required.']);
        exit();
    }

    try {
        // Fetch user by email
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($query);
        $stmt->execute(['email' => $data['email']]);
        $user = $stmt->fetch();

        // Debugging: Log fetched user data
        error_log("Fetched user data: " . print_r($user, true));

        if ($user && password_verify($data['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];
        
            // Debug: Print the user data being sent
            error_log("Login success: " . json_encode([
                'message' => 'Login successful!',
                'name' => $user['name'],
                'role' => $user['role'],
                'id' => $user['id'],
            ]));
        
            http_response_code(200);
            echo json_encode([
                'message' => 'Login successful!',
                'name' => $user['name'],
                'role' => $user['role'],
                'id' => $user['id'],
            ]);
        } else {
            error_log("Login failed for email: " . $data['email']);
            http_response_code(401);
            echo json_encode(['error' => 'Invalid email or password.']);
        }
        
    } catch (PDOException $e) {
        // Debugging: Log database error
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
    }
}
?>
