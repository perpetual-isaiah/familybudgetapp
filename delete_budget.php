<?php
session_start();
require_once('config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

// Check if the budget ID is provided
if (isset($_POST['budget_id'])) {
    $budgetId = $_POST['budget_id'];
    $userId = $_SESSION['user_id']; // Retrieve the logged-in user's ID

    // Prepare the delete query
    $query = "DELETE FROM budget WHERE id = ? AND user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('ii', $budgetId, $userId);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Budget deleted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting the budget.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No budget ID provided.']);
}
?>
