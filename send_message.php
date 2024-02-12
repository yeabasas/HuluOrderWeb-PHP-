<?php
session_start();
error_reporting(0);
include 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senderId = $_SESSION['mtid'];
    $itemId = $_GET['iId'];
    $receiverId = isset($_GET['uId']) ? $_GET['uId'] : null;

    $message = isset($_POST['message']) ? trim($_POST['message']) : '';

    if (!empty($message) && $receiverId !== null) {
        // Prepare and execute the messages query
        $sql = "INSERT INTO messages (sender_id, receiver_id, message, postId) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("iisi", $senderId, $receiverId, $message, $itemId);
            $stmt->execute();
            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error inserting message into the database"));
            exit();
        }

        $checkContactSql = "SELECT COUNT(*) FROM contact WHERE itemId = ? AND (senderId = ? OR receiverId = ?)";
        $checkStmt = $con->prepare($checkContactSql);
        if ($checkStmt) {
            $checkStmt->bind_param("iii", $itemId, $senderId, $senderId);
            $checkStmt->execute();
            $checkStmt->bind_result($count);
            $checkStmt->fetch();
            $checkStmt->close();

            // If the combination doesn't exist, insert a new row in the contact table
            if ($count == 0) {
                $contactSql = "INSERT INTO contact (senderId, receiverId, userId, itemId) VALUES (?,?,?,?)";
                $stmt1 = $con->prepare($contactSql);

                if ($stmt1) {
                    $stmt1->bind_param("iiii", $senderId, $receiverId, $senderId, $itemId);
                    $stmt1->execute();
                    $stmt1->close();

                    $con->commit(); // Commit the transaction
                    http_response_code(200);
                    echo json_encode(array("message" => "Message sent successfully"));
                    exit();
                } else {
                    $con->rollback(); // Rollback if the contactSql query fails
                    http_response_code(500);
                    echo json_encode(array("error" => "Error inserting contact into the database"));
                    exit();
                }
            } else {
                http_response_code(200);
                echo json_encode(array("message" => "Message sent successfully (but contact already exists)"));
                exit();
            }
        } else {
            http_response_code(500);
            echo json_encode(array("error" => "Error checking contact in the database"));
            exit();
        }
    } else {
        http_response_code(400);
        echo json_encode(array("error" => "Message cannot be empty"));
        exit();
    }
} else {
    http_response_code(405);
    echo json_encode(array("error" => "Method Not Allowed"));
    exit();
}