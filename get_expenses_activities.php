<?php
session_start();
header('Content-Type: application/json');

// Include database configuration
include('config.php');

// Query to fetch expenses activities along with spender's name
$query = "
    SELECT 
        expenses.name AS expense_name, 
        expenses.price, 
        expenses.theme AS category, 
        users.name AS spender
    FROM expenses
    JOIN users ON expenses.user_id = users.id
    ORDER BY expenses.date_column DESC
    LIMIT 5
";

$stmt = $db->prepare($query);

// Check if query preparation was successful
if ($stmt === false) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the SQL query: ' . $db->error]);
    exit;
}

// Execute the query
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to execute the SQL query: ' . $stmt->error]);
    exit;
}

// Fetch results
$result = $stmt->get_result();
$expenses = [];
while ($row = $result->fetch_assoc()) {
    $expenses[] = [
        'name' => $row['expense_name'],
        'price' => $row['price'],
        'category' => $row['category'],
        'spender' => $row['spender']
    ];
}

// Close the statement
$stmt->close();

// Return the expenses data as JSON
echo json_encode(['status' => 'success', 'expenses' => $expenses]);
?>
