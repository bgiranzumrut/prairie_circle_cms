<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(['error' => 'Failed to upload file.']);
        exit();
    }

    $file = $_FILES['image'];
    $allowedTypes = ['image/jpeg', 'image/png'];
    if (!in_array($file['type'], $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only JPG and PNG allowed.']);
        exit();
    }

    $filename = uniqid() . '-' . basename($file['name']);
    $targetFile = $uploadsDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        echo json_encode(['image_path' => $filename]);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to move uploaded file.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method.']);
}
?>
