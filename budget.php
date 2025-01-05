<?php
session_start();
require_once('config.php');

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id']; // Retrieve the logged-in user's ID

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $budgetName = $_POST['budget_name'] ?? null;
    $monthlyLimit = $_POST['monthly_limit'] ?? null;
    $savings = $_POST['savings'] ?? null;
    $reminderDate = $_POST['reminder_date'] ?? null;
    $reminderThreshold = $_POST['reminder_threshold'] ?? null;

    // Debugging: Log raw POST values
    error_log("Raw Reminder Date (PHP): $reminderDate");

    // Validate and format the date
    if ($reminderDate) {
        $reminderDate = DateTime::createFromFormat('Y-m-d', $reminderDate);
        if (!$reminderDate) {
            echo json_encode(['success' => false, 'message' => 'Invalid date format.']);
            exit();
        }
        $reminderDate = $reminderDate->format('Y-m-d');
    }

    // Debugging: Log the formatted date
    error_log("Formatted Reminder Date (PHP): $reminderDate");

    // Insert data into the database
    $query = "INSERT INTO budget (user_id, budget_name, monthly_limit, savings, reminder_date, reminder_threshold) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);

    if (!$stmt) {
        echo json_encode(['success' => false, 'message' => 'Database preparation error.']);
        exit();
    }

    $stmt->bind_param('isddsd', $userId, $budgetName, $monthlyLimit, $savings, $reminderDate, $reminderThreshold);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Budget created successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insertion error.']);
    }

    $stmt->close();
    $db->close();
}
?>
