<?php
session_start();

// Define allowed roles for different actions
$roles = [
    'admin_control' => ['admin'],
    'event_control' => ['admin', 'event_coordinator'],
    'user_control' => ['admin', 'event_coordinator', 'registered_user'],
];

/**
 * Check if the current user has one of the allowed roles.
 *
 * @param array $allowedRoles List of roles allowed for the action.
 */
function checkUserRole($allowedRoles) {
    if (!isset($_SESSION['user_role'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'Unauthorized access. Please log in.']);
        exit();
    }

    if (!in_array($_SESSION['user_role'], $allowedRoles)) {
        http_response_code(403); // Forbidden
        echo json_encode(['error' => 'Access denied. You do not have the required permissions.']);
        exit();
    }
}

// Example usage:
// checkUserRole($roles['admin_control']);
