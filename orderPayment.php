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
        header('Location: login.php');
        exit();
    }
    $_SESSION['timeout'] = time();
}

if (strlen($_SESSION['mtid'] == 0)) {
    header('location:logout.php');
}
if (isset($_POST['suggest'])) {
    $uid = $_SESSION['mtid'];
    $sid = $_GET['id'];
    $location = $_GET['location'];
    $filename = $_FILES["choosefile"]["name"];
    $tempfile = $_FILES["choosefile"]["tmp_name"];
    $folder = "images/" . $filename;

    $query = "update items set ScreenImage='$filename', Location='$location' where ID='$sid'";

    if ($query) {
        $result = mysqli_query($con, $query);
        move_uploaded_file($tempfile, $folder);
        echo "<script>alert('Please Wait a moment to confirm');</script>";
        echo "<script>window.location.href='dashboard.php';</script>";
        exit();
    } else {
        echo "<script>alert('unsuccessfully');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Payment</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./images/Eo_circle_green_white_letter-h.svg.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="global.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
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
    <?php include("header.php") ?>
    <div class="asdf">
        <h2 class="head-text">Choose Payment</h2>
        <div>
            <a href="" data-toggle="modal" data-target="#forNo">
                <div class="payer">
                    <img src="./images/untitled2-1@2x.png" alt="" height="110px">
                </div>
            </a>
        </div>
        <div>
            <a href="" data-toggle="modal" data-target="#forNo">
                <div class="payer">
                    <img src="./images/image-2@2x.png" alt="">
                </div>
            </a>
        </div>
    </div>

    <!-- Modal---------------------------- -->
    <div class="modal fade" id="forNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Please send the screenshot</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php
                $itemId = $_GET['id'];
                $query = mysqli_query($con, "select * from items where ID = '$itemId'");
                while ($fetch = mysqli_fetch_array($query)) {
                    $price = $fetch['Price'];
                    $per = round($price * 0.25,2);
                    $remain = $price - $per;
                ?>

                    <form method="post" action="" class="form-control" enctype="multipart/form-data">
                        <div class="modal-body">
                            <p class="mb-3">Please pay the 25% and send the Screenshot below</p>
                            <p class="text-center">25% would be<strong style="font-size: larger;"> <?php echo $per ?></strong> birr</p>
                            <p class="text-center">remaining fee<strong style="font-size: larger;"> <?php echo $remain ?></strong> birr</p>
                            <p class="m-0 bg-light w-50 mx-auto text-center" id="content-copy">acc name YEABKAL DANIEL KAHSAY <br> acc no 1000554672357</p>
                            <a id="btn" class=" m-0 d-flex align-center mx-auto bg-light " style="width:60px;height:50px;">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><!--! Font Awesome Pro 6.4.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2023 Fonticons, Inc. -->
                                    <path d="M384 336H192c-8.8 0-16-7.2-16-16V64c0-8.8 7.2-16 16-16l140.1 0L400 115.9V320c0 8.8-7.2 16-16 16zM192 384H384c35.3 0 64-28.7 64-64V115.9c0-12.7-5.1-24.9-14.1-33.9L366.1 14.1c-9-9-21.2-14.1-33.9-14.1H192c-35.3 0-64 28.7-64 64V320c0 35.3 28.7 64 64 64zM64 128c-35.3 0-64 28.7-64 64V448c0 35.3 28.7 64 64 64H256c35.3 0 64-28.7 64-64V416H272v32c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V192c0-8.8 7.2-16 16-16H96V128H64z" />
                                </svg>
                                copy
                            </a>
                            <input class="form-control mb-3" type="file" name="choosefile" id="">
                            <input class="form-control mb-3" type="text" name="location" placeholder="Location (Bole Sheger building ground)" id="">
                            <p class="bg-secondary text-white">Note: ordered processed only after the payment verified!</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-contact" data-dismiss="modal">Close</button>
                            <button type="submit" name="suggest" class="btn btn-primary">Submit</button>
                        </div>
                        <script>
                            const copyBtn = document.getElementById("btn");

                            copyBtn.addEventListener('click', async (event) => {
                                const content = document.getElementById('content-copy').textContent;
                                await navigator.clipboard.writeText(content);
                            })
                        </script>
                    </form>
                <?php }
                ?>
            </div>
        </div>


    </div>
</body>

</html>