<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

if (isset($_POST['budget_id'])) {
    $budgetId = $_POST['budget_id'];
    $userId = $_SESSION['user_id'];

    $budgetName = $_POST['budget_name'];
    $monthlyLimit = $_POST['monthly_limit'];
    $savings = $_POST['savings'];
    $reminderDate = $_POST['reminder_date'];
    $reminderThreshold = $_POST['reminder_threshold'];

    $query = "UPDATE budget SET budget_name = ?, monthly_limit = ?, savings = ?, reminder_date = ?, reminder_threshold = ? WHERE id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ssssiis', $budgetName, $monthlyLimit, $savings, $reminderDate, $reminderThreshold, $budgetId, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Budget updated successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error updating the budget.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No budget ID provided.']);
}
?>
