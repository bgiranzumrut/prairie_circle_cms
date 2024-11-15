<?php
session_start(); // Start session

function authenticate($roles = []) {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized. Please log in.']);
        exit();
    }

    // Check if user has the required role
    if (!empty($roles) && !in_array($_SESSION['role'], $roles)) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied.']);
        exit();
    }
}
?>
