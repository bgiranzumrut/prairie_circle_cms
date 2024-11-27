<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include '../db/db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401); // Unauthorized
        echo json_encode(['error' => 'You must be logged in to view your profile.']);
        exit();
    }

    $userId = $_SESSION['user_id']; // Fetch user ID from session

    try {
        // Fetch user details
        $userQuery = "SELECT id, name, email, created_at FROM users WHERE id = :userId";
        $userStmt = $pdo->prepare($userQuery);
        $userStmt->execute(['userId' => $userId]);
        $user = $userStmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            http_response_code(404); // User not found
            echo json_encode(['error' => 'User not found.']);
            exit();
        }

        // Fetch user's registered events
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

        // Return user details and events
        echo json_encode([
            'user' => $user,
            'events' => $events
        ]);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['error' => 'An error occurred while fetching your profile.']);
    }
}
?>
