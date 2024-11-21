<?php
include '../db/db.php';

header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Max-Age: 86400");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

// Validate required fields
if (!isset($_POST['title'], $_POST['description'], $_POST['category_id'], $_POST['event_date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input: Missing required fields']);
    exit();
}

$imagePath = null;

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadsDir = '../uploads/events/'; // Use events subdirectory
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true); // Create directory if it doesn't exist
    }

    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['image']['name'])); // Sanitize file name
    $destPath = $uploadsDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $imagePath = 'uploads/events/' . $fileName; // Store relative path for frontend access
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload image']);
        exit();
    }
}

try {
    $stmt = $pdo->prepare("INSERT INTO events (title, description, category_id, event_date, status, image_path) 
                           VALUES (:title, :description, :category_id, :event_date, :status, :image_path)");
    $stmt->execute([
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'category_id' => $_POST['category_id'],
        'event_date' => $_POST['event_date'],
        'status' => 'upcoming', // Default status
        'image_path' => $imagePath,
    ]);

    echo json_encode(['message' => 'Event created successfully!', 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
