<?php
session_start();
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit;
}

include('config.php');
$user_id = $_SESSION['user_id'];

// Query to fetch family members with total spending
$query = "
    SELECT fm.id, fm.name, fm.relationship, 
           IFNULL(SUM(e.price), 0) AS total_spendings
    FROM family_members fm
    LEFT JOIN expenses e ON fm.id = e.member_id
    WHERE fm.user_id = $user_id
    GROUP BY fm.id
";

$result = $db->query($query);

$family_members = [];

// Fetch data from the result
while ($row = $result->fetch_assoc()) {
    $family_members[] = $row;
}

// Return family members with total spending as JSON
echo json_encode($family_members);
?>
