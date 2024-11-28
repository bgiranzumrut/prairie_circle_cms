<?php
header("Access-Control-Allow-Origin: http://localhost:5173"); // Replace with your frontend URL
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

$uploadsDir = "../uploads/";
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true); // Create uploads directory if it doesn't exist
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate uploaded file
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'No file uploaded or upload error.']);
        exit();
    }

    $file = $_FILES['image'];

    // Validate file type
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only JPG and PNG allowed.']);
        exit();
    }

    // Validate file size (limit to 2MB)
    $maxFileSize = 2 * 1024 * 1024; // 2 MB
    if ($file['size'] > $maxFileSize) {
        http_response_code(400);
        echo json_encode(['error' => 'File size exceeds 2MB limit.']);
        exit();
    }

    // Sanitize and generate a unique filename
    $originalName = preg_replace("/[^a-zA-Z0-9\._-]/", "", basename($file['name']));
    $filename = uniqid() . '-' . $originalName;
    $targetFile = $uploadsDir . $filename;

    // Move the uploaded file
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        echo json_encode(['image_path' => $filename]);
        http_response_code(200);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move uploaded file.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
