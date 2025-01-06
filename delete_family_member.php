<?php

// Include database connection file
require_once('config.php');

// Set response header to JSON
header('Content-Type: application/json');

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON input
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Check if the 'id' field is provided
    if (isset($data['id'])) {
        $memberId = intval($data['id']);

        // Prepare the SQL statement to delete the member
        $sql = "DELETE FROM family_members WHERE id = ?";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("i", $memberId);

            // Execute the statement
            if ($stmt->execute()) {
                // Check if any row was deleted
                if ($stmt->affected_rows > 0) {
                    echo json_encode([
                        'success' => true,
                        'message' => 'Family member deleted successfully.'
                    ]);
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'No family member found with the given ID.'
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Failed to execute the delete statement.'
                ]);
            }
            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to prepare the delete statement.'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid request: Family member ID is missing.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Use POST.'
    ]);
}

// Close the database connection
$db->close();
?>
