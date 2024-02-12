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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>History</title>
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
        <h1 class="text-center bg-light py-4"><strong>Direct Orders</strong></h1>
        <div class="grid">
            <?php
            $userId = $_SESSION['mtid'];
            $sql2 = "SELECT items.ID,items.userId,items.ItemName,items.Description,items.Image FROM items JOIN user ON user.ID=items.userId WHERE user.ID = $userId";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="colms card" >
                    <a href="historyDetail.php?id=<?php echo $fetch['ID'] ?>" style="text-decoration: none; ">
                        <?php if ($fetch['Image']) {
                            $img = $fetch["Image"];
                                echo "
                            <img src='images/$img' class='card-img-to' alt='image'>
                                ";
                            } else {
                                echo '
                            <img src="images/No_image_available.svg.png" class="card-img-top" alt="image">
                                ';
                            } ?>
                        <div class="card-body">
                            <h5 class="card-title text-dark"><?php echo $fetch['ItemName'] ?></h5>
                            <p class="card-text text-dark"><?php echo $fetch['Price'] ?></p>
                        </div>
                    </a>
                </div>
                <?php
                }?>
        </div>
        <h1 class="text-center bg-light py-4"><strong>Stoke Orders</strong></h1>
        <div class="grid">
            <?php
            $userId = $_SESSION['mtid'];
            $sql2 = "SELECT * FROM stoke where UserID='$userId' && (status='pending' or status='ordered')";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="colms card" >
                    <a href="stokeHistory.php?sid=<?php echo $fetch['sID'] ?>" style="text-decoration: none;">
                        <?php if ($fetch['Image']) {
                            $img = $fetch["Image"];
                                echo "
                            <img src='images/$img' class='card-img-to' alt='image'>
                                ";
                            } else {
                                echo '
                            <img src="images/No_image_available.svg.png" class="card-img-top" alt="image">
                                ';
                            } ?>
                        <div class="card-body">
                            <p class="card-title text-dark"><?php echo $fetch['ItemName'] ?></p>
                        </div>
                    </a>
                </div>
                <?php
                }
                    ?>
        </div>
    </div>
</body>

</html>