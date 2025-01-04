<?php
session_start();
require_once('config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id']; // Retrieve the logged-in user's ID

// Check if it's a POST request (creating or editing a budget)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the form data
    $budgetName = $_POST['budget_name'];
    $monthlyLimit = $_POST['monthly_limit'];
    $savings = $_POST['savings'];
    $reminderDate = $_POST['reminder_date']; // Get the reminder_date
    $reminderThreshold = $_POST['reminder_threshold'];

    // Debugging: Check the value of reminder_date
    error_log("Reminder Date (raw): " . $reminderDate);

    // Ensure reminder date is in the correct format (Y-m-d)
    $reminderDate = date('Y-m-d', strtotime($reminderDate));

    // Debugging: Check the value after formatting
    error_log("Reminder Date (formatted): " . $reminderDate);

    // Check if reminder date is empty or invalid
    if (empty($reminderDate) || $reminderDate === '1970-01-01') {
        $reminderDate = null;  // Set to null if the date is invalid
    }

    // Check if we are editing an existing budget
    if (isset($_POST['budget_id'])) {
        $budgetId = $_POST['budget_id'];
        // Update query for editing the budget
        $query = "UPDATE budget SET budget_name = ?, monthly_limit = ?, savings = ?, reminder_date = ?, reminder_threshold = ? WHERE id = ? AND user_id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param('ssssiii', $budgetName, $monthlyLimit, $savings, $reminderDate, $reminderThreshold, $budgetId, $userId);
        $stmt->execute();

        // Return success response
        echo json_encode(['success' => true]);
    } else {
        // Insert query for creating a new budget
        $query = "INSERT INTO budget (user_id, budget_name, monthly_limit, savings, reminder_date, reminder_threshold) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param('isiiis', $userId, $budgetName, $monthlyLimit, $savings, $reminderDate, $reminderThreshold);
        $stmt->execute();

        // Return success response
        echo json_encode(['success' => true]);
    }
}
?>
