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
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="grid">
<?php
                $sql2 = "SELECT * FROM `stoke` ORDER BY DateModified DESC";
                $result2 = mysqli_query($con, $sql2);
                $cnt = 1;
                while ($fetch = mysqli_fetch_assoc($result2)) {
                ?>
                    <div class="colms">
                        <img src="images/<?php echo $fetch['Image'] ?>" alt="">
                        <p class=""><?php echo $fetch['ItemName'] ?></p>
                    </div>
                    <?php }?>
                </div>
                </body>
            </html>
            <?php }?>