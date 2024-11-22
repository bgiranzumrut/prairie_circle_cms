<?php
include '../db/db.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit();
}

// Get query parameters
$sortCriteria = $_GET['sort'] ?? 'event_date'; // Default sort by event_date
$sortOrder = strtoupper($_GET['order'] ?? 'ASC'); // Default to ascending order
$titleFilter = $_GET['title'] ?? ''; // Filter by title
$statusFilter = $_GET['status'] ?? ''; // Filter by status
$categoryFilter = $_GET['category'] ?? ''; // Filter by category

// Validate sort criteria
$allowedSorts = ['event_date', 'title', 'status', 'category_name'];
$allowedOrders = ['ASC', 'DESC'];
if (!in_array($sortCriteria, $allowedSorts)) {
    $sortCriteria = 'event_date';
}
if (!in_array($sortOrder, $allowedOrders)) {
    $sortOrder = 'ASC';
}

try {
    // Build the query with dynamic filters
    $query = "SELECT events.*, categories.name AS category_name 
              FROM events 
              LEFT JOIN categories ON events.category_id = categories.id 
              WHERE 1=1"; // Placeholder for dynamic conditions

    // Apply title filter
    if (!empty($titleFilter)) {
        $query .= " AND events.title LIKE :title";
    }

    // Apply status filter
    if (!empty($statusFilter)) {
        $query .= " AND events.status = :status";
    }

    // Apply category filter
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

    echo json_encode($events);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
