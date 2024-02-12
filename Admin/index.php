<?php
session_start();
error_reporting(0);
include('../dbcon.php');
if (isset($_SESSION['timeout'])) {
    $inactiveTime = time() - $_SESSION['timeout'];
    $sessionTimeout = 30 * 1; // 30 minutes in seconds

    if ($inactiveTime >= $sessionTimeout) {
        session_unset();
        session_destroy();
        header('Location: ../login.php');
        exit();
    }
    $_SESSION['timeout'] = time();
}
if (strlen($_SESSION['mtid'] == 0)) {
    header('Location:../logout.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hulu Order | Ad</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <link rel="shortcut icon" href="../images/Eo_circle_green_white_letter-h.svg.png" type="image/x-icon">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/e2c97eca5a.js" crossorigin="anonymous"></script>
    <!-- MDB -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
    <?php include('header.php') ?>
    <div class="pt">
        <section class="text-end py-3 d-flex justify-content-around" style="text-decoration: none; background:linear-gradient(-31.32deg, #192f23, #2c463a)">
            <div class="dropdown">
                <a href="index.php#Customer" class=" mx-3 text-white dropdown-toggle" dropdown-toggle data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Customer Order</a>
                <div class="dropdown-menu dropdown-menu-right mt-3" aria-labelledby="dropdownMenuButton">
                    <a href="#new" class="dropdown-item  mb-2 border-bottom">New</a>
                    <a href="#waiting" class="dropdown-item border-bottom mb-2">Waiting</a>
                    <a href="#cancelled" class="dropdown-item border-bottom mb-2">Cancelled</a>
                    <a href="#denied" class="dropdown-item border-bottom mb-2">Denied</a>
                    <a href="#confirmed" class="dropdown-item">Confirmed</a>
                </div>
            </div>
            <div class="dropdown">
                <a href="index.php#Customer" class=" mx-3 text-white dropdown-toggle" dropdown-toggle data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Stoke Order</a>
                <div class="dropdown-menu dropdown-menu-right mt-3" aria-labelledby="dropdownMenuButton">
                    <a href="#stwaiting" class="dropdown-item border-bottom mb-2">Waiting</a>
                    <a href="#stconfirmed" class="dropdown-item">Confirmed</a>
                </div>
            </div>
        </section>
        <h1 class="text-center bg-light py-4" id="Customer"><strong>Customer Orders</strong></h1>
        <div class="grid" id="new">
            <h1 class=" text-center "><strong>New</strong></h1>

            <?php
            $userId = $_SESSION['mtid'];
            $sql2 = "SELECT * FROM items WHERE status='new' && Price='' && ScreenImage = '' && (SuggestImage = ''|| SuggestDes = '') ORDER BY DateModified DESC";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="card colms">
                    <a href="orderDetailAdmin.php?id=<?php echo $fetch['ID'] ?>" class="text-decoration-none">
                        <img src="../images/<?php echo $fetch['Image'] ?>" class="card-img-top img-responsive" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $fetch['ItemName'] ?></h5>
                            <p class="bg-dark text-start text-white">New</p>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="grid" id="waiting">
            <h1 class="text-center"><strong>Waiting</strong></h1>
            <?php
            $userId = $_SESSION['mtid'];
            $sql2 = "SELECT * FROM items where (status ='new' && Price !='' ) || ((SuggestImage != '' || SuggestDes != '') && Confirmation != 'no' && ScreenImage = '') ORDER BY DateModified DESC";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="card colms">
                    <a href="orderDetailAdmin.php?id=<?php echo $fetch['ID'] ?>" class="text-decoration-none">
                        <img src="../images/<?php echo $fetch['Image'] ?>" class="card-img-top img-responsive" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $fetch['ItemName'] ?></h5>
                            <p class="bg-dark text-start text-white">Waiting Response</p>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="grid" id="confirmed">
            <h3 class="text-center"><strong>Confirmed</strong></h3>
            <?php
            $userId = $_SESSION['mtid'];
            $sql2 = "SELECT * FROM items WHERE status != 'new' && status != 'Cancelled' && Price !=''   ORDER BY DateModified DESC";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="card colms">
                    <a href="orderDetailAdmin.php?id=<?php echo $fetch['ID'] ?>" class="text-decoration-none">
                        <img src="../images/<?php echo $fetch['Image'] ?>" class="card-img-top img-responsive" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $fetch['ItemName'] ?></h5>
                            <p class="bg-success text-start text-white">Confirmed</p>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="grid" id="denied">
            <h3 class="text-center"><strong>Denied</strong></h3>
            <?php
            $userId = $_SESSION['mtid'];
            $sql2 = "SELECT * FROM items WHERE status == 'Denied' ORDER BY DateModified DESC";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="card colms">
                    <a href="orderDetailAdmin.php?id=<?php echo $fetch['ID'] ?>" class="text-decoration-none">
                        <img src="../images/<?php echo $fetch['Image'] ?>" class="card-img-top img-responsive" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $fetch['ItemName'] ?></h5>
                            <p class="bg-danger text-start text-white">Denied</p>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="grid" id="cancelled">
            <h3 class="text-center"><strong>Cancelled</strong></h3>
            <?php
            $userId = $_SESSION['mtid'];
            $sql2 = "SELECT * FROM items WHERE status = 'Cancelled' || (status='Denied')  ORDER BY DateModified DESC";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="card colms">
                    <a href="orderDetailAdmin.php?id=<?php echo $fetch['ID'] ?>" class="text-decoration-none">
                        <img src="../images/<?php echo $fetch['Image'] ?>" class="card-img-top img-responsive" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $fetch['ItemName'] ?></h5>
                            <p class="bg-dark text-start text-white">Cancelled</p>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        <h1 class="text-center bg-light py-4"><strong>Stoke Orders</strong></h1>
        <div class="grid" id="stwaiting">
            <h3 class="text-center"><strong>Waiting</strong></h3>
            <?php
            $sql2 = "SELECT * FROM stoke where status='pending' ORDER BY DateModified DESC";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="card colms">
                    <a href="detailStoke.php?sid=<?php echo $fetch['sID'] ?>" class="text-decoration-none">
                        <img src="../images/<?php echo $fetch['Image'] ?>" class="card-img-top img-responsive" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $fetch['ItemName'] ?></h5>
                        </div>
                    </a>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="grid" id="stconfirmed">
            <h3 class="text-center"><strong>Confirmed</strong></h3>
            <?php
            $sql2 = "SELECT * FROM stoke where status='ordered' ORDER BY DateModified DESC";
            $result2 = mysqli_query($con, $sql2);
            $cnt = 1;
            while ($fetch = mysqli_fetch_assoc($result2)) {
            ?>
                <div class="card colms">
                    <a href="detailStoke.php?sid=<?php echo $fetch['sID'] ?>" class="text-decoration-none">
                        <img src="../images/<?php echo $fetch['Image'] ?>" class="card-img-top img-responsive" alt="">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $fetch['ItemName'] ?></h5>
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