<?php
// Handle CORS
 $allowed_origins = ['http://localhost:5173'];
 $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

 if (in_array($origin, $allowed_origins)) {
     header("Access-Control-Allow-Origin: $origin");
} else {
    header("Access-Control-Allow-Origin: null");
}

header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Content-Type: application/json");


// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files and authenticate
include '../db/db.php';
// include '../authentication/middleware.php';
// authenticate(['admin', 'registered_user']);

// Fetch users
try {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;

    $query = "SELECT * FROM users LIMIT :limit OFFSET :offset";
    $stmt = $pdo->prepare($query);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode($stmt->fetchAll());
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error fetching users: " . $e->getMessage()]);
}
