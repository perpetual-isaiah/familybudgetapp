<?php
session_start();
require_once('config.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id']; // Get the logged-in user's ID

// Get the current date
$currentDate = date('Y-m-d'); // Get today's date in the format 'YYYY-MM-DD'

// Query to check for any reminders that match today's date
$query = "SELECT * FROM budget WHERE reminder_date = ? AND user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('si', $currentDate, $userId);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $budgetsDue = $result->fetch_all(MYSQLI_ASSOC);

    if (count($budgetsDue) > 0) {
        // If there are budgets due, return success and include the budget details
        $notifications = array_map(function($budget) {
            return [
                'bill_name' => $budget['budget_name'],  // Assuming 'budget_name' is the bill's name
                'due_date' => $budget['reminder_date']  // Assuming 'reminder_date' is the due date
            ];
        }, $budgetsDue);
        
        echo json_encode($notifications);  // Return the list of notifications
    } else {
        // If no budgets are due, return an empty array
        echo json_encode([]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Error checking reminder dates.']);
}
?>
