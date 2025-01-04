<?php
// session_start();
// include('config.php');

// if (isset($_SESSION['user_id'])) {
//     $userId = $_SESSION['user_id'];

//     // Update last_seen
//     $stmt = $db->prepare("UPDATE users SET last_seen = NOW() WHERE id = ?");
//     $stmt->bind_param("i", $userId);
//     $stmt->execute();
//     $stmt->close();

//     echo json_encode(['status' => 'success']);
// } else {
//     echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
// }

session_start();
include('config.php');

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $currentTime = date('Y-m-d H:i:s');

    $stmt = $db->prepare("UPDATE users SET last_seen = ? WHERE id = ?");
    $stmt->bind_param("si", $currentTime, $userId);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Last seen updated.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to update last seen.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'No user session.']);
}

?>
