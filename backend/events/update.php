<?php
include '../db/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Validate input
if (!isset($_POST['id'], $_POST['title'], $_POST['description'], $_POST['category_id'], $_POST['event_date'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

$imagePath = null;

// Check if a new image is uploaded
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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
        'id' => $_POST['id'],
        'title' => $_POST['title'],
        'description' => $_POST['description'],
        'category_id' => $_POST['category_id'],
        'event_date' => $_POST['event_date'],
        'status' => $_POST['status'] ?? 'upcoming', // Default status
    ];

    if ($imagePath) {
        $params['image_path'] = $imagePath;
    }

    // Execute the query
    $stmt->execute($params);

    echo json_encode(['message' => 'Event updated successfully!']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
