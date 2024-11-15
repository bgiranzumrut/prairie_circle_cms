<?php
session_start();
session_destroy(); // Destroy the session
echo json_encode(['message' => 'Logged out successfully']);
?>
