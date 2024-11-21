<?php
session_start();

// Define allowed roles
$admin_control = ['admin'];
$event_control = ['admin', 'event_coordinator'];
$user_control = ['admin', 'event_coordinator', 'registered_user'];

// Function to check user roles
function checkUserRole($allowedRoles) {
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], $allowedRoles)) {
        http_response_code(403); // Forbidden
        echo json_encode(['error' => 'Access denied.']);
        exit();
    }
}
?>
