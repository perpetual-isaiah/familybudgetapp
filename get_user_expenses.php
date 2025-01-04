<?php
session_start();
header('Content-Type: application/json');

// Include database configuration
include('config.php');

// Query to fetch each user's total expenses for the current month and their last_seen timestamp
$query = "
    SELECT 
        u.id, 
        u.name, 
        IFNULL(SUM(e.price), 0) AS total_expenses, 
        u.last_seen
    FROM users u
    LEFT JOIN expenses e 
        ON u.id = e.user_id 
        AND MONTH(e.date_column) = MONTH(CURRENT_DATE()) 
        AND YEAR(e.date_column) = YEAR(CURRENT_DATE())
    GROUP BY u.id, u.name, u.last_seen
    ORDER BY total_expenses DESC
    LIMIT 3";  // Fetch only the top 3 users

$result = $db->query($query);

if ($result) {
    $userExpenses = [];
    while ($row = $result->fetch_assoc()) {
        $userExpenses[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'total_expenses' => $row['total_expenses'],
            'last_seen' => $row['last_seen']
        ];
    }

    echo json_encode(['status' => 'success', 'data' => $userExpenses]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to fetch user expenses.']);
}
?>
