<?php
include '../db/db.php';

$query = "SELECT events.*, categories.name AS category_name FROM events JOIN categories ON events.category_id = categories.id";
$stmt = $pdo->query($query);
echo json_encode($stmt->fetchAll());
?>
