<?php
session_start();
require_once('config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id']; // Retrieve the logged-in user's ID

$query = "SELECT * FROM budget WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$budgets = [];

while ($row = $result->fetch_assoc()) {
    $budgets[] = $row;
}

echo json_encode(['success' => true, 'budgets' => $budgets]);
?>
