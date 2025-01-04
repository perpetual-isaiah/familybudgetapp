<?php
session_start();
header('Content-Type: application/json');

// Include database configuration
include('config.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if the database connection is successful
if ($db->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed: ' . $db->connect_error]);
    exit;
}

// Query to calculate the total earnings for the logged-in user for the current month
$query = "
    SELECT SUM(amount) AS total_earning 
    FROM earnings 
    WHERE user_id = ? 
    AND MONTH(date_column) = MONTH(CURRENT_DATE()) 
    AND YEAR(date_column) = YEAR(CURRENT_DATE())
";
$stmt = $db->prepare($query);

// Check if the query preparation was successful
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the SQL query: ' . $db->error]);
    exit;
}

// Bind the user ID parameter
$stmt->bind_param("i", $user_id);

// Execute the query
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to execute the SQL query: ' . $stmt->error]);
    exit;
}

// Bind the result
$stmt->bind_result($total_earning);
$stmt->fetch();

// Close the statement
$stmt->close();

// Return the total balance as JSON (default to 0 if null)
echo json_encode(['status' => 'success', 'total_earning' => $total_earning ?: 0]);

?>
