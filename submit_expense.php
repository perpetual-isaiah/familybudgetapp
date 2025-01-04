<?php
include('config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    // Get form data from the request
    $name = $_POST['name'];
    $price = $_POST['price'];
    $theme = $_POST['theme'];
    $details = $_POST['details'];
    $user_id = $_SESSION['user_id'];

    // Sanitize inputs (for security)
    $name = $db->real_escape_string($name);
    $price = $db->real_escape_string($price);
    $theme = $db->real_escape_string($theme);
    $details = $db->real_escape_string($details);

    // Insert data into expenses table
    $sql = "INSERT INTO expenses (name, price, theme, details, user_id) 
            VALUES ('$name', '$price', '$theme', '$details', '$user_id')";

    if ($db->query($sql) === TRUE) {
        echo "Expense added successfully!";
    } else {
        echo "Error: " . $db->error;
    }
} else {
    echo "User not logged in.";
}
?>
