<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

include '../db/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight requests
    http_response_code(204); // No Content
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use GET instead.']);
    exit();
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'You must be logged in to view your profile.']);
    exit();
}

$userId = $_SESSION['user_id'];

try {
    // Fetch user details
    $userQuery = "SELECT id, name, email, created_at FROM users WHERE id = :userId";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $userStmt->execute();
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'User not found.']);
        exit();
    }

    // Fetch user's registered events
    $eventQuery = "
        SELECT e.id, e.title, e.description, e.event_date, c.name AS category_name
        FROM registrations er
        JOIN events e ON er.event_id = e.id
        LEFT JOIN categories c ON e.category_id = c.id
        WHERE er.user_id = :userId
        ORDER BY e.event_date ASC
    ";
    $eventStmt = $pdo->prepare($eventQuery);
    $eventStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $eventStmt->execute();
    $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

    // Return user profile and registered events
    echo json_encode([
        'user' => [
            'id' => htmlspecialchars($user['id']),
            'name' => htmlspecialchars($user['name']),
            'email' => htmlspecialchars($user['email']),
            'created_at' => $user['created_at'],
        ],
        'events' => $events,
    ]);
    http_response_code(200); // OK
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log error for debugging
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'An error occurred while fetching your profile.']);
}
?>
