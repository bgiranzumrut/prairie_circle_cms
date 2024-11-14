<?php
// Include database connection
include_once '../db/db.php';

header("Content-Type: application/json; charset=UTF-8");

// Fetch all users
try {
    $query = "SELECT id, name, email, role, created_at FROM users";
    $stmt = $pdo->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return the list of users as JSON
    echo json_encode($users);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error fetching users: " . $e->getMessage()]);
}
