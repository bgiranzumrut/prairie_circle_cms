<?php
session_start();
include '../db/db.php';
include '../auth/middleware.php';

authenticate(['registered_user', 'admin', 'event_coordinator']); // Only logged-in users can register

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['event_id'])) {
    $query = "INSERT INTO registrations (user_id, event_id) VALUES (:user_id, :event_id)";
    $stmt = $pdo->prepare($query);
    $stmt->execute([
        'user_id' => $_SESSION['user_id'],
        'event_id' => $data['event_id']
    ]);
    echo json_encode(['message' => 'Registration successful!']);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input!']);
}
?>
