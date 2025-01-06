<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Content-Type: application/json");

include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method. Use GET instead.']);
    exit();
}

if (!isset($_GET['userId']) || !filter_var($_GET['userId'], FILTER_VALIDATE_INT)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid or missing user ID.']);
    exit();
}

$userId = intval($_GET['userId']); // Sanitize the userId

try {
    $userQuery = "SELECT name, email FROM users WHERE id = :userId";
    $userStmt = $pdo->prepare($userQuery);
    $userStmt->execute(['userId' => $userId]);
    $user = $userStmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404); // Not Found
        echo json_encode(['error' => 'User not found.']);
        exit();
    }

    $eventQuery = "
        SELECT 
            e.id, 
            e.title, 
            e.description, 
            e.event_date, 
            c.name AS category_name 
        FROM registrations er
        JOIN events e ON er.event_id = e.id
        LEFT JOIN categories c ON e.category_id = c.id
        WHERE er.user_id = :userId
        ORDER BY e.event_date ASC
    ";
    $eventStmt = $pdo->prepare($eventQuery);
    $eventStmt->execute(['userId' => $userId]);
    $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200); // OK
    echo json_encode([
        'userName' => htmlspecialchars($user['name']),
        'userEmail' => htmlspecialchars($user['email']),
        'events' => $events
    ]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'An error occurred while fetching user details.']);
}
