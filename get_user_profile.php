<?php
session_start();
header('Content-Type: application/json');

// Include database configuration
include('config.php');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch the user's profile data, including the profile picture
$query = "SELECT u.name, u.email, p.bio, p.profile_picture FROM users u LEFT JOIN profiles p ON u.id = p.user_id WHERE u.id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($name, $email, $bio, $profile_picture);
$stmt->fetch();
$stmt->close();

// Check if a profile picture exists, otherwise use the default picture
$profile_picture_path = $profile_picture ? '/uploads/' . $profile_picture : '/uploads/profile-3.png';

// Return the user profile data as a JSON response
if ($name) {
    echo json_encode([
        'status' => 'success',
        'name' => $name,
        'email' => $email,
        'bio' => $bio ?: '',
        'profile_picture' => $profile_picture_path // Include the correct profile picture path
    ]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'User profile not found']);
}
?>
