<?php
// require_once('config.php');

// // Check if the connection was successful
// if (!$db) {
//     die("Connection failed: " . $db->connect_error);
// }

// // Get the data from the POST request
// $data = json_decode(file_get_contents("php://input"), true);

// // Prepare the SQL query
// $query = "INSERT INTO expenses (member_id, user_id, name, price, theme, details) VALUES (?, ?, ?, ?, ?, ?)";
// $stmt = $db->prepare($query);

// if ($stmt === false) {
//     die("Error preparing statement: " . $db->error); // Print error if the prepare() fails
// }

// // Bind the parameters
// $stmt->bind_param('isdss', $data['member_id'], $data['user_id'], $data['name'], $data['price'], $data['theme'], $data['details']);

// // Execute the query
// if ($stmt->execute()) {
//     echo json_encode(["success" => true]);
// } else {
//     echo json_encode(["success" => false, "message" => $stmt->error]);
// }

// // Close the statement and the connection
// $stmt->close();
// $db->close();

require_once('config.php');

// Check if the connection was successful
if (!$db) { 
    die(json_encode(["success" => false, "message" => "Connection failed: " . $db->connect_error])); 
}

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Validate required fields
if (empty($data['memberId']) || empty($data['userId']) || empty($data['name']) || empty($data['price']) || empty($data['theme'])) {
    echo json_encode(["success" => false, "message" => "Missing required fields."]);
    exit;
}

// Sanitize and prepare the data
$memberId = $db->real_escape_string($data['memberId']);
$userId = $db->real_escape_string($data['userId']);
$name = $db->real_escape_string($data['name']);
$price = $db->real_escape_string($data['price']);
$theme = $db->real_escape_string($data['theme']);
$details = isset($data['details']) ? $db->real_escape_string($data['details']) : null;

// Prepare the SQL query
$query = "INSERT INTO expenses (member_id, user_id, name, price, theme, details) 
          VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $db->prepare($query);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Error preparing statement: " . $db->error]);
    exit;
}

// Bind parameters
// 'iisdds' -> integer, string (userId), string (name), double (price), string (theme), string (details or NULL)
$stmt->bind_param('iisdds', $memberId, $userId, $name, $price, $theme, $details);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Error: " . $stmt->error]);
}

// Close the statement and the connection
$stmt->close();
$db->close();

?>
