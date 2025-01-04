<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    // Get form data from the request
    $amount = $_POST['amount'];
    $user_id = $_SESSION['user_id'];

    // Sanitize input
    $amount = $db->real_escape_string($amount);

    // Insert data into earnings table
    $sql = "INSERT INTO earnings (amount, user_id) 
            VALUES ('$amount', '$user_id')";

    if ($db->query($sql) === TRUE) {
        echo "Earnings added successfully!";
    } else {
        echo "Error: " . $db->error;
    }
} else {
    echo "User not logged in.";
}
?>
