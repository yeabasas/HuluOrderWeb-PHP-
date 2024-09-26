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
        <form method="post" action="" class="shadow bg-body-tertiary rounded mb-5 mt-5">
            <?php
            $uid = $_SESSION['mtid'];
            $ret = mysqli_query($con, "select * from posts where ID='$uid'");
            $cnt = 1;
            while ($row = mysqli_fetch_array($ret)) {
            ?>
            <div class="posts">
                        <a href="postDetail.php?id=<?php echo $fetch['ID'] ?>&uId=<?php echo $fetch['userId'] ?>" id="updateLink">
                            <div class="posts-img">
                                <?php
                                $postId = $row['ID'];
                                $sqlImages = "SELECT image_filename FROM post_images WHERE post_id = ?";
                                $stmtImages = mysqli_prepare($con, $sqlImages);
                                mysqli_stmt_bind_param($stmtImages, "i", $postId);
                                mysqli_stmt_execute($stmtImages);
                                $resultImages = mysqli_stmt_get_result($stmtImages);
                                if ($rowImage = mysqli_fetch_assoc($resultImages)) {
                                    $imageFilename = $rowImage['image_filename'];
                                    echo '
                                    <img src="images/' . $imageFilename . '" alt="" height="100%" width="100%">
                                    ';
                                }
                                mysqli_stmt_close($stmtImages);
                                ?>
                            </div>
                            <div class="posts-txt-btm">
                                <p class="posts-txt"><?php echo $row['ItemName'] ?></p>
                                <p class="posts-txt"><span>ETB </span><?php echo $row['Price'] ?></p>
                            </div>
                        </a>
                    </div>
                <!-- <div class="p-5">
                    <div style="padding-top: 30px;">
                        <label>Model</label>
                        <input type="text" class="form-control" name="FirstName" value="<?php echo $row['ItemName']; ?>" required="true">
                    </div>
                    <div style="padding-top: 30px;">
                        <label>Condition</label>
                        <input type="text" class="form-control" name="LastName" value="<?php echo $row['Condition']; ?>" required="true">
                    </div>
                    <div style="padding-top: 30px;">
                        <label>Price</label>
                        <input type="text" class="form-control" name="Phone" value="<?php echo $row['Price']; ?>" readonly="true">
                    </div> -->
                <?php } ?>
                <!-- <button type="submit" class="btn btn-success m-auto mt-5 d-flex justify-content-center" name="submit">Save Change</button> -->
                </div>
        </form>
    </div>
</body>

</html>