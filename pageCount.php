<?php
session_start();
error_reporting(0);
include('dbcon.php');

// Get post ID from the URL or any other source
$postId = $_GET['id']; // Replace 'post_id' with the actual parameter you use

// Fetch post details
$sql = "SELECT * FROM items WHERE ID = $postId";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();

    // Increment view count
    $newViewCount = $post['View'] + 1;
    $updateSql = "UPDATE items SET View = $newViewCount WHERE ID = $postId";
    $con->query($updateSql);
} else {
    echo "Post not found";
    exit();
}

// Close the database connection
$con->close();
?>