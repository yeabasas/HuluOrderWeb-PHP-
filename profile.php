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
    if (isset($_POST['submit'])) {
        $uid = $_SESSION['mtid'];
        $Fname = $_POST['FirstName'];
        $Lname = $_POST['LastName'];

        $query = mysqli_query($con, "update user set FirstName='$Fname',LastName='$Lname' where ID='$uid'");

        if ($query) {
            echo '<script>alert("Profile updated successfully.")</script>';
        } else {
            echo '<script>alert("Something Went Wrong. Please try again.")</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>profile</title>
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
    <div class="m-auto w-75 mb-5">
        <form method="post" name='signup' onsubmit="return checkpass();" action="" class="shadow bg-body-tertiary rounded mb-5 mt-5">
            <?php
            $uid = $_SESSION['mtid'];
            $ret = mysqli_query($con, "select * from user where ID='$uid'");
            $cnt = 1;
            while ($row = mysqli_fetch_array($ret)) {
            ?>
                <div class="p-5">
                    <h3 class="text-center">Change Profile</h3>
                    <?php
                    $postId = $row['ID'];

                    $sqlImages = "SELECT image_filename FROM user_images WHERE userId = ?";
                    $stmtImages = mysqli_prepare($con, $sqlImages);
                    mysqli_stmt_bind_param($stmtImages, "i", $postId);
                    mysqli_stmt_execute($stmtImages);
                    $resultImages = mysqli_stmt_get_result($stmtImages);
                    if ($rowImage = mysqli_fetch_assoc($resultImages)) {
                        $imageFilename = $rowImage['image_filename'];
                        echo '
                        <p>"' . $imageFilename . '"</p>
                            <img src="../images/' . $imageFilename . '" alt="" height="70px" width="70px">
                            ';
                    }
                    mysqli_stmt_close($stmtImages);
                    ?>
                    <div style="padding-top: 30px;">
                        <label>First Name</label>
                        <input type="text" class="form-control" name="FirstName" value="<?php echo $row['FirstName']; ?>" required="true">
                    </div>
                    <div style="padding-top: 30px;">
                        <label>Last Name</label>
                        <input type="text" class="form-control" name="LastName" value="<?php echo $row['LastName']; ?>" required="true">
                    </div>
                    <div style="padding-top: 30px;">
                        <label>Mobile Number</label>
                        <input type="text" class="form-control" name="Phone" value="<?php echo $row['Phone']; ?>" readonly="true">
                    </div>
                <?php } ?>
                <button type="submit" class="btn btn-success m-auto mt-5 d-flex justify-content-center" name="submit">Save Change</button>
                </div>
        </form>
        <?php include('changePass.php') ?>
    </div>
</body>

</html>