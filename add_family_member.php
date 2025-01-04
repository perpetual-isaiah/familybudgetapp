<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    include('config.php');

    $data = json_decode(file_get_contents('php://input'), true);
    $name = trim($data['name']);
    $relationship = trim($data['relationship']);  // Getting relationship from select input
    $user_id = $_SESSION['user_id'];

    if (empty($name) || empty($relationship)) {
        echo json_encode(['success' => false, 'message' => 'Name and Relationship are required.']);
        exit;
    }

    $stmt = $db->prepare("INSERT INTO family_members (user_id, name, relationship) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $name, $relationship);  // Binding relationship as well

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Family member added successfully!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error adding family member.']);
    }

    $stmt->close();
}
?>
