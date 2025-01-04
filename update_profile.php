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

// Get POST data
$userName = $_POST['userName'];
$userEmail = $_POST['userEmail'];
$userBio = $_POST['userBio'];
$profilePicture = $_FILES['profilePicture'] ?? null;

// Handle profile picture upload
$profilePicturePath = null;
if ($profilePicture && $profilePicture['error'] == UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/profile_pictures/';
    $profilePicturePath = $uploadDir . basename($profilePicture['name']);

    if (!move_uploaded_file($profilePicture['tmp_name'], $profilePicturePath)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to upload profile picture']);
        exit;
    }
}

// Check if the user already has a profile in the database
$query = "SELECT * FROM profiles WHERE user_id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

// If a profile exists, update it
if ($stmt->num_rows > 0) {
    $stmt->close();
    $query = "UPDATE profiles SET bio = ?, profile_picture = ?, updated_at = CURRENT_TIMESTAMP WHERE user_id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssi", $userBio, $profilePicturePath, $user_id);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile updated successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update profile']);
    }
} else {
    // If no profile exists, create a new profile
    $stmt->close();
    $query = "INSERT INTO profiles (user_id, bio, profile_picture, updated_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iss", $user_id, $userBio, $profilePicturePath);
    
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Profile created successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create profile']);
    }
}

// Close the statement
$stmt->close();
?>
