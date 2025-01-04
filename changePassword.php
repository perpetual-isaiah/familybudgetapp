<?php
session_start();
header('Content-Type: application/json');

// Ensure the request method is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access.']);
    exit;
}

include('config.php');

$user_id = $_SESSION['user_id'];
$data = json_decode(file_get_contents('php://input'), true);
$current_password = $data['current-password'] ?? null;
$new_password = $data['new-password'] ?? null;
$confirm_password = $data['confirm-password'] ?? null;

// Check if all fields are provided
if (!$current_password || !$new_password || !$confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

// Check if passwords match
if ($new_password !== $confirm_password) {
    echo json_encode(['status' => 'error', 'message' => 'Passwords do not match.']);
    exit;
}

// Validate current password
$stmt = $db->prepare("SELECT password FROM users WHERE id = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Database query error.']);
    exit;
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($hashed_password);
$stmt->fetch();
$stmt->close();

if (!password_verify($current_password, $hashed_password)) {
    echo json_encode(['status' => 'error', 'message' => 'Incorrect current password.']);
    exit;
}

// Update password
$new_hashed_password = password_hash($new_password, PASSWORD_BCRYPT);
$stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
if (!$stmt) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to prepare the update statement.']);
    exit;
}

$stmt->bind_param("si", $new_hashed_password, $user_id);
if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Password changed successfully.']);
} else {
    echo json_encode([
        'status' => 'error', 
        'message' => 'Failed to execute the update query.',
        'debug' => $stmt->error // Include SQL error for debugging
    ]);
}
$stmt->close();
?>
