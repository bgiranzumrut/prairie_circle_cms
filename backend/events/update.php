<?php
include '../db/db.php';

// CORS Headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with frontend origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json");

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Enforce POST method
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method. Use POST instead.']);
    exit();
}

// Validate required fields
if (!isset($_POST['id'], $_POST['title'], $_POST['description'], $_POST['category_id'], $_POST['event_date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

// Validate and sanitize input
$id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
$title = filter_var($_POST['title'], FILTER_SANITIZE_STRING);
$description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
$category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
$event_date = filter_var($_POST['event_date'], FILTER_SANITIZE_STRING);
$status = filter_var($_POST['status'] ?? 'upcoming', FILTER_SANITIZE_STRING);

// Ensure valid input
if (!$id || !$category_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input data']);
    exit();
}

$imagePath = null;

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
    $maxFileSize = 2 * 1024 * 1024; // 2 MB

    if (!in_array($_FILES['image']['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only JPG, PNG, and GIF are allowed.']);
        exit();
    }

    if ($_FILES['image']['size'] > $maxFileSize) {
        http_response_code(400);
        echo json_encode(['error' => 'File size exceeds 2MB limit.']);
        exit();
    }

    $uploadsDir = "../uploads/";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true); // Create uploads directory if it doesn't exist
    }

    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = uniqid() . '-' . basename($_FILES['image']['name']);
    $destPath = $uploadsDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $imagePath = "uploads/" . $fileName; // Save relative path for database
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload image']);
        exit();
    }
}

try {
    // Start building the SQL query
    $query = "UPDATE events 
              SET title = :title, description = :description, category_id = :category_id, 
                  event_date = :event_date, status = :status";

    // Add image_path to the query only if a new image is uploaded
    if ($imagePath) {
        $query .= ", image_path = :image_path";
    }

    $query .= " WHERE id = :id";

    $stmt = $pdo->prepare($query);

    // Bind parameters
    $params = [
        ':id' => $id,
        ':title' => $title,
        ':description' => $description,
        ':category_id' => $category_id,
        ':event_date' => $event_date,
        ':status' => $status,
    ];

    if ($imagePath) {
        $params[':image_path'] = $imagePath;
    }

    // Execute the query
    $stmt->execute($params);

    echo json_encode(['message' => 'Event updated successfully!']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
