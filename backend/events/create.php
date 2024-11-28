<?php
include '../db/db.php';

header("Access-Control-Allow-Origin: *"); // Allow requests from any origin
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Max-Age: 86400");
header("Content-Type: application/json"); // Ensure JSON response

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204); // No Content
    exit();
}

// Check if the request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Invalid request method']);
    exit();
}

// Validate required fields
if (!isset($_POST['title'], $_POST['description'], $_POST['category_id'], $_POST['event_date'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Missing required fields']);
    exit();
}

// Sanitize inputs
$title = htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8');
$category_id = filter_var($_POST['category_id'], FILTER_VALIDATE_INT);
$event_date = $_POST['event_date'];

// Validate category_id and event_date
if (!$category_id) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid category ID']);
    exit();
}

if (!DateTime::createFromFormat('Y-m-d', $event_date)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Invalid event date format. Use YYYY-MM-DD']);
    exit();
}

$imagePath = null;

// Handle image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadsDir = '../uploads/events/'; // Use events subdirectory
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true); // Secure permissions
    }

    $fileTmpPath = $_FILES['image']['tmp_name'];
    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', basename($_FILES['image']['name'])); // Sanitize file name
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Restrict file types
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($fileExtension, $allowedExtensions)) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'Invalid file type. Allowed types: jpg, jpeg, png, gif']);
        exit();
    }

    // Limit file size (e.g., max 5MB)
    if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
        http_response_code(400); // Bad Request
        echo json_encode(['error' => 'File size exceeds the 5MB limit']);
        exit();
    }

    $destPath = $uploadsDir . $fileName;

    if (move_uploaded_file($fileTmpPath, $destPath)) {
        $imagePath = 'uploads/events/' . $fileName; // Store relative path for frontend access
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(['error' => 'Failed to upload image']);
        exit();
    }
}

try {
    // Prepare and execute the query
    $stmt = $pdo->prepare("INSERT INTO events (title, description, category_id, event_date, status, image_path) 
                           VALUES (:title, :description, :category_id, :event_date, :status, :image_path)");
    $stmt->execute([
        'title' => $title,
        'description' => $description,
        'category_id' => $category_id,
        'event_date' => $event_date,
        'status' => 'upcoming', // Default status
        'image_path' => $imagePath,
    ]);

    http_response_code(201); // Created
    echo json_encode(['message' => 'Event created successfully!', 'id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage()); // Log the error
    http_response_code(500); // Internal Server Error
    echo json_encode(['error' => 'Database error. Please try again later.']);
}
?>
