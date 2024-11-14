<?php
include '../db/db.php';

$query = "SELECT * FROM categories";
$stmt = $pdo->query($query);
echo json_encode($stmt->fetchAll());
?>
