<?php
// require_once('config.php');

// // Check if the connection was successful
// if (!$conn) {
//     die("Connection failed: " . $conn->connect_error);
// }

// Get the data from the POST request
$data = json_decode(file_get_contents("php://input"), true);

// Prepare the SQL query
$query = "INSERT INTO expenses (member_id, name, price, theme, details) VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

if ($stmt === false) {
    die("Error preparing statement: " . $conn->error); // Print error if the prepare() fails
}

// Bind the parameters
$stmt->bind_param('isdss', $data['member_id'], $data['name'], $data['price'], $data['theme'], $data['details']);

// Execute the query
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

// Close the statement and the connection
$stmt->close();
$conn->close();
?>
