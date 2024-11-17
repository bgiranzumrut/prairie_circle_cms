<?php
session_start(); // Start session

function authenticate($roles = []) {
    session_start(); // Ensure the session is started

    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized. Please log in.']);
        exit();
    }

    // Debug: Check role data
    if (isset($_SESSION['role'])) {
        echo json_encode(['role' => $_SESSION['role'], 'roles_allowed' => $roles]);
    }

    // Check if user has the required role
    if (!empty($roles) && !in_array($_SESSION['role'], $roles)) {
        http_response_code(403);
        echo json_encode(['error' => 'Access denied. Only authorized users can create events.']);
        exit();
    }
}
