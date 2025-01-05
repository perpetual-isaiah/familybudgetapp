<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id'];

$query = "SELECT budget_name, monthly_limit, savings, reminder_date, reminder_threshold FROM budget WHERE user_id = ? ORDER BY date_created ASC LIMIT 5";
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
