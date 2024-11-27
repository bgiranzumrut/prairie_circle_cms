<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_GET['userId']) || !filter_var($_GET['userId'], FILTER_VALIDATE_INT)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid or missing user ID.']);
        exit();
    }

    $userId = $_GET['userId'];

    try {
        // Fetch user details
        $userQuery = "SELECT name FROM users WHERE id = :userId";
        $userStmt = $pdo->prepare($userQuery);
        $userStmt->execute(['userId' => $userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(404);
            echo json_encode(['error' => 'User not found.']);
            exit();
        }

        // Fetch events the user is joining
        $eventQuery = "
            SELECT e.id, e.title, e.description, e.event_date, c.name AS category_name
            FROM registrations er
            JOIN events e ON er.event_id = e.id
            JOIN categories c ON e.category_id = c.id
            WHERE er.user_id = :userId
        ";
        $eventStmt = $pdo->prepare($eventQuery);
        $eventStmt->execute(['userId' => $userId]);
        $events = $eventStmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'userName' => $user['name'],
            'events' => $events
        ]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred while fetching user details.']);
    }
}
