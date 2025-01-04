<?php
// session_start();
// require_once('config.php'); // Include config.php for $db connection

// // Check if $db is established
// if (!isset($db)) {
//     echo json_encode(['success' => false, 'message' => 'Database connection not established.']);
//     exit();
// }

// // Ensure user is logged in
// if (!isset($_SESSION['user_id'])) {
//     echo json_encode(['success' => false, 'message' => 'User not logged in.']);
//     exit();
// }

// $userId = $_SESSION['user_id']; // Retrieve the logged-in user's ID

// // Start transaction
// $db->begin_transaction();

// try {
//     // Debugging: Ensure userId is being captured
//     if (empty($userId)) {
//         throw new Exception("User ID not found in session.");
//     }

//     // Delete earnings associated with the user
//     $deleteEarningsQuery = "DELETE FROM earnings WHERE user_id = ?";
//     $stmt = $db->prepare($deleteEarningsQuery);
//     if (!$stmt) {
//         throw new Exception("Error preparing earnings deletion: " . $db->error);
//     }
//     $stmt->bind_param('i', $userId);
//     if (!$stmt->execute()) {
//         throw new Exception("Error executing earnings deletion: " . $stmt->error);
//     }
//     $stmt->close();

//     // Delete expenses associated with the user
//     $deleteExpensesQuery = "DELETE FROM expenses WHERE user_id = ?";
//     $stmt = $db->prepare($deleteExpensesQuery);
//     if (!$stmt) {
//         throw new Exception("Error preparing expenses deletion: " . $db->error);
//     }
//     $stmt->bind_param('i', $userId);
//     if (!$stmt->execute()) {
//         throw new Exception("Error executing expenses deletion: " . $stmt->error);
//     }
//     $stmt->close();

//     // Delete family members associated with the user
//     $deleteFamilyMembersQuery = "DELETE FROM family_members WHERE user_id = ?";
//     $stmt = $db->prepare($deleteFamilyMembersQuery);
//     if (!$stmt) {
//         throw new Exception("Error preparing family members deletion: " . $db->error);
//     }
//     $stmt->bind_param('i', $userId);
//     if (!$stmt->execute()) {
//         throw new Exception("Error executing family members deletion: " . $stmt->error);
//     }
//     $stmt->close();

//     // Delete the user
//     $deleteUserQuery = "DELETE FROM users WHERE id = ?";
//     $stmt = $db->prepare($deleteUserQuery);
//     if (!$stmt) {
//         throw new Exception("Error preparing user deletion: " . $db->error);
//     }
//     $stmt->bind_param('i', $userId);
//     if (!$stmt->execute()) {
//         throw new Exception("Error executing user deletion: " . $stmt->error);
//     }

//     // Debugging: Check affected rows for the user deletion
//     if ($stmt->affected_rows === 0) {
//         throw new Exception("Failed to delete user. No rows affected. User ID: " . $userId);
//     }
//     $stmt->close();

//     // Commit the transaction
//     $db->commit();

//     // Log the user out
//     session_unset(); // Clear all session variables
//     session_destroy(); // Destroy the session

//     echo json_encode(['success' => true]);
// } catch (Exception $e) {
//     // Rollback transaction on error
//     $db->rollback();
//     echo json_encode(['success' => false, 'message' => $e->getMessage()]);
// }

// // Close connection
// $db->close();


session_start();
require_once('config.php'); // Include config.php for $db connection

// Check if $db is established
if (!isset($db)) {
    echo json_encode(['success' => false, 'message' => 'Database connection not established.']);
    exit();
}

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'User not logged in.']);
    exit();
}

$userId = $_SESSION['user_id']; // Retrieve the logged-in user's ID

// Start transaction
$db->begin_transaction();

try {
    // Debugging: Ensure userId is being captured
    if (empty($userId)) {
        throw new Exception("User ID not found in session.");
    }

    // Delete earnings associated with the user
    $deleteEarningsQuery = "DELETE FROM earnings WHERE user_id = ?";
    $stmt = $db->prepare($deleteEarningsQuery);
    if (!$stmt) {
        throw new Exception("Error preparing earnings deletion: " . $db->error);
    }
    $stmt->bind_param('i', $userId);
    if (!$stmt->execute()) {
        throw new Exception("Error executing earnings deletion: " . $stmt->error);
    }
    $stmt->close();

    // Delete expenses associated with the user
    $deleteExpensesQuery = "DELETE FROM expenses WHERE user_id = ?";
    $stmt = $db->prepare($deleteExpensesQuery);
    if (!$stmt) {
        throw new Exception("Error preparing expenses deletion: " . $db->error);
    }
    $stmt->bind_param('i', $userId);
    if (!$stmt->execute()) {
        throw new Exception("Error executing expenses deletion: " . $stmt->error);
    }
    $stmt->close();

    // Delete budget associated with the user
    $deleteBudgetQuery = "DELETE FROM budget WHERE user_id = ?";
    $stmt = $db->prepare($deleteBudgetQuery);
    if (!$stmt) {
        throw new Exception("Error preparing budget deletion: " . $db->error);
    }
    $stmt->bind_param('i', $userId);
    if (!$stmt->execute()) {
        throw new Exception("Error executing budget deletion: " . $stmt->error);
    }
    $stmt->close();

    // Delete family members associated with the user
    $deleteFamilyMembersQuery = "DELETE FROM family_members WHERE user_id = ?";
    $stmt = $db->prepare($deleteFamilyMembersQuery);
    if (!$stmt) {
        throw new Exception("Error preparing family members deletion: " . $db->error);
    }
    $stmt->bind_param('i', $userId);
    if (!$stmt->execute()) {
        throw new Exception("Error executing family members deletion: " . $stmt->error);
    }
    $stmt->close();

    // Delete the user
    $deleteUserQuery = "DELETE FROM users WHERE id = ?";
    $stmt = $db->prepare($deleteUserQuery);
    if (!$stmt) {
        throw new Exception("Error preparing user deletion: " . $db->error);
    }
    $stmt->bind_param('i', $userId);
    if (!$stmt->execute()) {
        throw new Exception("Error executing user deletion: " . $stmt->error);
    }

    // Debugging: Check affected rows for the user deletion
    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to delete user. No rows affected. User ID: " . $userId);
    }
    $stmt->close();

    // Commit the transaction
    $db->commit();

    // Log the user out
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

// Close connection
$db->close();




?>
