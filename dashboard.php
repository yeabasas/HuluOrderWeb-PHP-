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
        <title>Custom order</title>
        <link rel="stylesheet" href="style.css">
            <link rel="shortcut icon" href="./images/Eo_circle_green_white_letter-h.svg.png" type="image/x-icon">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
        <!-- Google Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
        <!-- MDB -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://kit.fontawesome.com/e2c97eca5a.js" crossorigin="anonymous"></script>
        <!-- MDB -->
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

        <!-- Bootstrap-select CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

    </head>

    <body>
        <?php include('header.php') ?>

        <div class="container just pt-5">
            <div class="ItemAdd">
                <?php include('addItem.php') ?>
            </div>
            <div class="gridash">
                <?php
                $userId = $_SESSION['mtid'];
                $sql2 = "SELECT*FROM `items` WHERE userId='$userId' ORDER BY DateModified DESC";
                $result2 = mysqli_query($con, $sql2);
                $cnt = 1;
                while ($fetch = mysqli_fetch_assoc($result2)) {
                ?>
                    <div class="card colmsdash">
                        <a href="historyDetail.php?id=<?php echo $fetch['ID'] ?>" class="text-decoration-none">
                            <?php if($fetch['Image']){
                                $img = $fetch['Image'];
                                echo"
                            <img src='images/$img' class='card-img-top' alt='image'>
                                ";
                            }else{
                                echo'
                            <img src="images/No_image_available.svg.png" class="card-img-top" alt="image">
                                ';}?>
                            <div class="card-body">
                                <p class="card-title text-black"><?php echo $fetch['ItemName'] ?></p>
                                <?php if ($fetch['status'] == 'new' && $fetch['Price'] != '') { ?>
                                    <p class="card-text bg-secondary text-white">Confirm Price</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'new' && $fetch['Price'] == '') { ?>
                                    <p class="card-text bg-secondary text-white">Wait Confirmation</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Denied') { ?>
                                    <p class="card-text bg-danger">Denied</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'pending') { ?>
                                    <p class="card-text bg-warning">Pending</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Shipped') { ?>
                                    <p class="card-text bg-primary text-white">Shipped</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Arrived') { ?>
                                    <p class="card-text bg-success text-white">Arrived</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Cancelled') { ?>
                                    <p class="card-text text-muted">Cancelled</p>
                                <?php } ?>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>

            <!-- <div class="row" style="height:80vh; overflow:auto; margin:0 auto;">
                <?php
                $userId = $_SESSION['mtid'];
                $sql2 = "SELECT*FROM `items` WHERE userId='$userId' ORDER BY DateModified DESC";
                $result2 = mysqli_query($con, $sql2);
                $cnt = 1;
                while ($fetch = mysqli_fetch_assoc($result2)) {
                ?>
                    <div class="card col-lg-3 col-md-4 col-sm-12 shadow rounded border my-2 mx-3 text-start h-90" method='post' style="text-decoration: none; transition: transform .2s; ">
                        <a href="historyDetail.php?id=<?php echo $fetch['ID'] ?>" class="text-decoration-none mx-auto">
                            <img src="images/<?php echo $fetch['Image'] ?>" class="img-responsive rounded mx-auto" width="200px" height="150px" alt="image">
                            <div class="card-body">
                                <p class="text-dark"><?php echo $fetch['ItemName'] ?></p>
                                <?php if ($fetch['status'] == 'new' && $fetch['Price'] != '') { ?>
                                    <p class="bg-secondary text-white">Confirm Price</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'new' && $fetch['Price'] == '') { ?>
                                    <p class="bg-secondary text-white">Wait Confirmation</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Denied') { ?>
                                    <p class="bg-danger">Denied</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Pending') { ?>
                                    <p class="bg-warning">Pending</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Shipped') { ?>
                                    <p class="bg-primary text-white">Shipped</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Arrived') { ?>
                                    <p class="bg-success">Arrived</p>
                                <?php } ?>
                                <?php if ($fetch['status'] == 'Cancelled') { ?>
                                    <p class="text-muted">Cancelled</p>
                                <?php } ?>
                            </div>
                        </a>
                    </div>
                <?php

                }
                ?>
            </div> -->

        </div>
        

        <!-- Bootstrap JS (Popper.js is required for dropdowns) -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <!-- Bootstrap-select JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

        <!-- Initialize the Bootstrap-select plugin -->
        <script>
            $(document).ready(function() {
                $('.selectpicker').selectpicker();
            });
        </script>

    </body>

    </html>
<?php } ?>