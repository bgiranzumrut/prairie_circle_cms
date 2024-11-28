<?php
include '../db/db.php';

// CORS Headers
header("Access-Control-Allow-Origin: http://localhost:5173"); // Frontend origin
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true"); // Allow credentials (cookies/session sharing)
header('Content-Type: application/json'); // Ensure JSON response
// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Validate and sanitize inputs
$event_id = $_GET['event_id'] ?? null; // Fetch by specific event_id if provided
$sortCriteria = $_GET['sort'] ?? 'event_date'; // Default sort by event_date
$sortOrder = strtoupper($_GET['order'] ?? 'ASC'); // Default to ascending order
$titleFilter = $_GET['title'] ?? ''; // Filter by title
$statusFilter = $_GET['status'] ?? ''; // Filter by status
$categoryFilter = $_GET['category'] ?? ''; // Filter by category

// Validate sorting options
$allowedSorts = ['event_date', 'title', 'status', 'category_name'];
$allowedOrders = ['ASC', 'DESC'];
if (!in_array($sortCriteria, $allowedSorts)) {
    $sortCriteria = 'event_date';
}
if (!in_array($sortOrder, $allowedOrders)) {
    $sortOrder = 'ASC';
}

try {
    // Check if a specific event is being fetched
    if (!empty($event_id) && is_numeric($event_id)) {
        $query = "SELECT events.*, categories.name AS category_name 
                  FROM events 
                  LEFT JOIN categories ON events.category_id = categories.id 
                  WHERE events.id = :event_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
        $stmt->execute();
        $event = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($event) {
            echo json_encode($event); // Return the specific event
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Event not found.']);
        }
        exit();
    }

    // Build the query for fetching all events with dynamic filters
    $query = "SELECT events.*, categories.name AS category_name 
              FROM events 
              LEFT JOIN categories ON events.category_id = categories.id 
              WHERE 1=1"; // Placeholder for dynamic conditions

    // Apply filters
    if (!empty($titleFilter)) {
        $query .= " AND events.title LIKE :title";
    }
    if (!empty($statusFilter)) {
        $query .= " AND events.status = :status";
    }
    if (!empty($categoryFilter)) {
        $query .= " AND events.category_id = :category";
    }

    // Append sorting
    $query .= " ORDER BY $sortCriteria $sortOrder";

    $stmt = $pdo->prepare($query);

    // Bind parameters
    if (!empty($titleFilter)) {
        $titleParam = '%' . $titleFilter . '%';
        $stmt->bindParam(':title', $titleParam, PDO::PARAM_STR);
    }
    if (!empty($statusFilter)) {
        $stmt->bindParam(':status', $statusFilter, PDO::PARAM_STR);
    }
    if (!empty($categoryFilter)) {
        $stmt->bindParam(':category', $categoryFilter, PDO::PARAM_INT);
    }

    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($events); // Return all filtered and sorted events
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
