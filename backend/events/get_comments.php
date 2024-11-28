<?php
header("Content-Type: application/json"); // Ensure JSON response
header("Access-Control-Allow-Origin: http://localhost:5173"); // Adjust to match your frontend
header("Access-Control-Allow-Methods: GET, OPTIONS"); // Allowed HTTP methods
header("Access-Control-Allow-Headers: Content-Type, Authorization"); // Allowed headers
header("Access-Control-Allow-Credentials: true"); // Allow cookies/sessions

include '../db/db.php'; // Include your database connection file

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); // OK for preflight
    exit();
}

try {
    // Ensure the request method is GET
    if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
        throw new Exception("Invalid request method. Use GET.");
    }

    // Validate the required parameter
    if (!isset($_GET['event_id']) || !filter_var($_GET['event_id'], FILTER_VALIDATE_INT)) {
        throw new Exception("Invalid or missing event_id parameter.");
    }

    $event_id = intval($_GET['event_id']); // Sanitize and retrieve event ID

    // SQL query to fetch comments for the specified event
    $query = "
        SELECT 
            ec.id, 
            ec.comment, 
            ec.created_at, 
            u.name AS user_name
        FROM 
            event_comments ec
        JOIN 
            users u 
        ON 
            ec.user_id = u.id
        WHERE 
            ec.event_id = :event_id
        ORDER BY 
            ec.created_at DESC
    ";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
    $stmt->execute();

    $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Send the JSON-encoded comments back to the client
    echo json_encode($comments);
    http_response_code(200); // OK
} catch (PDOException $e) {
    // Log and handle database errors
    error_log("Database error: " . $e->getMessage());
    echo json_encode(["error" => "A database error occurred."]);
    http_response_code(500); // Internal Server Error
} catch (Exception $e) {
    // Log and handle general errors
    error_log("Error: " . $e->getMessage());
    echo json_encode(["error" => $e->getMessage()]);
    http_response_code(400); // Bad Request
}
?>
