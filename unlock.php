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
    if (isset($_POST['unlocki'])) {
        $userId = $_SESSION['mtid'];
        $imei = $_POST['IMEI'];
        $model = $_POST['Model'];
        $sql = "INSERT INTO unlocks(IMEI,Model,UserId)VALUES('$imei','$model','$userId')";
        $result = mysqli_query($con, $sql);
        if (!$result) {
            echo '<script>alert("Something went wrong! UNSUCCESSFUL REQUEST");</script>';
        } else {
            echo'<script>alert("Request sent successfully")</script>';
        }
    }
?>
<!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Unlock</title>
        <link rel="stylesheet" href="style.css">
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
        <?php include('header.php')?>
        <div class="container mt-5 d-block mx-auto w-75 h-100 shadow text-center p-5">
            <h1 class="mx-auto ">Unlock iphone</h1>
            <form method="post" action="unlock.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                <div class="d-inline-block">
                    <input class="form-control mb-2" type="text" placeholder="IMEI Number" name="IMEI" minlength="14" maxlength="17" required>
                    <input class="form-control mb-2" type="text" placeholder="Model Number" name="Model" required>
                    <button type="submit" name="unlocki" class="form-control mt-3 btn btn-success">Add</button>
                </div>
            </form>
        </div>
    </body>
    </html>
<?php } ?>