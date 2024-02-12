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
        <title>Stoke</title>
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="global.css">
        <link rel="shortcut icon" href="./images/Eo_circle_green_white_letter-h.svg.png" type="image/x-icon">
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
        <div class="containers">
            <h4 class="text-start">Stokes</h4>
            <div class="grid">
                <?php
                $sql2 = "SELECT * FROM `stoke` WHERE status!='hidden' ORDER BY DateModified DESC";
                $result2 = mysqli_query($con, $sql2);
                $num = mysqli_num_rows($result2);
                $cnt = 1; ?>
                <?php if ($num > 0) {
                    while ($fetch = mysqli_fetch_assoc($result2)) {
                ?>
                        <div class="card colms">
                            <a href="orderDetail.php?id=<?php echo $fetch['sID'] ?>" style="text-decoration: none;">
                                <img src="images/<?php echo $fetch['Image'] ?>" class="card-img-top" alt="">
                                <div class="card-body">
                                    <h5 class="card-title text-black"><?php echo $fetch['ItemName'] ?></h5>
                                    <p class="card-text text-success">ETB <?php echo $fetch['Price'] ?></p>
                                </div>
                            </a>
                        </div> <?php }
                        } else {
                            echo "
                    <div class='d-block text-center'>
                        <p>Items will Come Soon!</p>
                        <p>Thanks for your patience</p>
                    </div>";
                        } ?>
            </div>
        </div>
    </body>

    </html>
<?php } ?>