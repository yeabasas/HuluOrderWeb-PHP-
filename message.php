<?php
session_start();
error_reporting(0);
include('dbcon.php');
if (isset($_SESSION['timeout'])) {
    $inactiveTime = time() - $_SESSION['timeout'];
    $sessionTimeout = 30 * 60; // 30 minutes in seconds

    if ($inactiveTime >= $sessionTimeout) {
        session_unset();
        session_destroy();
        header('Location:login.php');
        exit();
    }
    $_SESSION['timeout'] = time();
}
if (strlen($_SESSION['mtid'] == 0)) {
    header('location:login.php');
} else {

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Messages</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <!-- Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
        <!-- MDB -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/e2c97eca5a.js" crossorigin="anonymous"></script>
        <!-- MDB -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    </head>

    <body>
        <?php include('header.php') ?>
        <div class="contain">
            <div class="contain-one card">
                <h3 class="text-center m-4 "><i class="far fa-comments mr-2"></i> Message Center</h3>
                <?php
                $userId = $_SESSION['mtid'];
                $sql = "SELECT 
            contact.*, 
            user.FirstName, 
            posts.ID, 
            messages.message AS lastMessage
        FROM 
            contact
        JOIN 
            user ON (contact.receiverId = user.ID)
        JOIN 
            posts ON (contact.itemId = posts.ID)
        LEFT JOIN 
            messages ON (
                (contact.senderId = messages.sender_id AND contact.receiverId = messages.receiver_id) OR
                (contact.senderId = messages.receiver_id AND contact.receiverId = messages.sender_id)
            )
        WHERE 
            contact.senderId = ? OR contact.receiverId = ?
        GROUP BY 
            contact.ID
        ORDER BY 
            messages.timestamp DESC";
                $stmt = $con->prepare($sql);
                if ($stmt) {
                    $stmt->bind_param("ii", $userId, $userId);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc()) {
                        $itemId = $row['ID'];
                ?>
                        <a href="messageDetail.php?uId=<?php echo $row['receiverId'] ?>&iId=<?php echo $itemId ?>" style="text-decoration: none;color:#000">
                            <div class="container-detail text-center">
                                <img src="images/170125857797454450888284921663.jpg" alt="" width="40px" height="40px" style="border-radius: 50%; margin-right:10px">
                                <div class="d-flex flex-column m-0">
                                    <p class="m-0"><?php echo $row['FirstName']; ?></p>
                                    <p class="m-0">
                                        <?php
                                        if ($row['lastMessage'] !== null) {
                                            echo $row['lastMessage'];
                                        } else {
                                            echo "No messages yet";
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </a>
                <?php
                    }
                    $stmt->close();
                } else {
                    echo "Error in preparing SQL statement";
                }
                ?>

            </div>
        </div>

    </body>

    </html>
<?php } ?>