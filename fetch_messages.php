<?php
session_start();
error_reporting(0);
include 'dbcon.php';

$receiverId = isset($_GET['uId']) ? $_GET['uId'] : null;
$postId=$_GET["iId"]; 
$senderId = $_SESSION['mtid'];
// Fetch messages from the database
$sql = "SELECT * FROM messages WHERE postId = ? && (sender_id = ? OR receiver_id = ?) ORDER BY timestamp";
$stmt = $con->prepare($sql);

if ($stmt) {
    $stmt->bind_param("iii", $postId, $senderId, $senderId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch messages as an associative array
    $messages = $result->fetch_all(MYSQLI_ASSOC);

    // Send messages as JSON
    http_response_code(200);
    echo json_encode($messages);
    exit();
} else {
    // Handle database error
    http_response_code(500);
    echo json_encode(array("error" => "Error fetching messages from the database"));
    exit();
}
?>
