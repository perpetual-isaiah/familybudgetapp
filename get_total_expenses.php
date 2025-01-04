<?php
session_start();
header('Content-Type: application/json');

// Include database configuration
include('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Get the current user's ID from the session
$current_user_id = $_SESSION['user_id'];

// Query to calculate the total expenses for the current user for the current month
$stmt = $db->prepare("
    SELECT SUM(price) AS total_expenses 
    FROM expenses 
    WHERE user_id = ? 
      AND MONTH(date_column) = MONTH(CURRENT_DATE()) 
      AND YEAR(date_column) = YEAR(CURRENT_DATE())
");

if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the query: ' . $db->error]);
    exit;
}

// Bind the parameter and execute the query
$stmt->bind_param('i', $current_user_id);
$stmt->execute();
$stmt->bind_result($total_expenses);
$stmt->fetch();
$stmt->close();

// Return the total expenses as JSON
echo json_encode(['status' => 'success', 'total_expenses' => $total_expenses ?: 0]);

?>
