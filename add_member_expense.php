<?php
require_once('config.php');
session_start(); // Start the session to access $_SESSION variables

// Check if the connection was successful
if (!$db) { 
    die(json_encode(["success" => false, "message" => "Connection failed: " . $db->connect_error])); 
}

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (empty($data['memberId']) || empty($data['name']) || empty($data['price']) || empty($data['theme'])) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

// Get the logged-in user's ID from the session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "User not logged in."]);
    exit;
}
$userId = $_SESSION['user_id']; // Logged-in user's ID

// Sanitize and prepare the data
$memberId = $db->real_escape_string($data['memberId']);
$name = $db->real_escape_string($data['name']);
$price = $db->real_escape_string($data['price']);
$theme = $db->real_escape_string($data['theme']);
$details = isset($data['details']) ? $db->real_escape_string($data['details']) : null;

// Validate if the member belongs to the user
$checkMemberQuery = "SELECT 1 FROM family_members WHERE id = ? AND user_id = ?";
$stmt = $db->prepare($checkMemberQuery);
$stmt->bind_param('ii', $memberId, $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows == 0) {
    echo json_encode(["success" => false, "message" => "Invalid member_id or member does not belong to the user."]);
    $stmt->close();
    exit;
}
$stmt->close();

// Prepare the SQL query for inserting the expense
$query = "INSERT INTO expenses (member_id, user_id, name, price, theme, details) 
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error preparing statement: " . $db->error]);
    exit;
}

// Bind parameters
$stmt->bind_param('iisdds', $memberId, $userId, $name, $price, $theme, $details);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Expense added successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

// Close the statement and the connection
$stmt->close();
$db->close();
?>
