<?php
session_start();
require_once('config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id']; // Retrieve the logged-in user's ID
$budgetId = $_GET['id']; // Get the budget ID

$query = "SELECT * FROM budget WHERE id = ? AND user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('ii', $budgetId, $userId);
$stmt->execute();
$result = $stmt->get_result();
$budget = $result->fetch_assoc();

echo json_encode(['success' => true, 'budget' => $budget]);
?>
