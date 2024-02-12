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
if (isset($_POST['order'])) {
    $sid = $_GET['sid'];
    $uid = $_SESSION['mtid'];
    $status = 'ordered';

    $query = mysqli_query($con, "update stoke set status='$status' where stoke.sID='$sid'");

    if ($query) {
        echo '<script>alert("successfully.")</script>';
    } else {
        echo '<script>alert("Something Went Wrong. Please try again.")</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orderDetail | Admin</title>
    <link rel="stylesheet" href="../style.css">
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
    <div class="d-block mx-auto  mt-5">
        <?php
        if (isset($_GET['sid'])) {
            $sid = $_GET['sid'];
            $uid = $_SESSION['mtid'];
            $query = mysqli_query($con, "SELECT * from stoke join user on stoke.UserID = user.ID where sID='$sid'");
            while ($fetch = mysqli_fetch_array($query)) {
        ?>
                <div class="d-block mx-auto table-responsive">
                    <table class="table w-50 mx-auto">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2">
                                    <img src="../images/<?php echo $fetch['Image'] ?>" class="img-responsive w-30 mx-auto" height="150px" alt="">
                                </th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ItemName</td>
                                <td><?php echo $fetch['ItemName'] ?></td>
                            </tr>
                            <tr>
                                <td>Condition</td>
                                <td><?php echo $fetch['Condition'] ?></td>
                            </tr>
                            <tr>
                                <td>Storage</td>
                                <td><?php echo $fetch['Storage'] ?></td>
                            </tr>
                            <tr>
                                <td>RAM</td>
                                <td><?php echo $fetch['Ram'] ?></td>
                            </tr>
                            <tr>
                                <td>Sim</td>
                                <td><?php echo $fetch['Sim'] ?></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><?php echo $fetch['Description'] ?></td>
                            </tr>
                            <tr>
                                <td>Ordered from</td>
                                <td><?php echo $fetch['FirstName'] ?></td>
                            </tr>
                            <tr>
                                <td>Payment screenshot</td>
                                <td>
                                    <a href="../images/<?php echo $fetch['ScreenImage'] ?>">
                                        <img src="../images/<?php echo $fetch['ScreenImage'] ?>" alt="screenshot" height="90px" width="90px">
                                    </a>
                                </td>
                            </tr>
                            <?php
                            $statuses = 'ordered';
                            if ($fetch['status'] == $statuses) {
                                echo '
                                <tr>
                                <form action="" method="post">
                                    <td>
                                        <button disabled type="submit" class="btn btn-primary mx-auto">Confirmed</button>
                                    </td>
                                    <td></td>
                                </form>
                                </tr>';
                            } elseif ($fetch['status'] != $statuses) {
                                echo '
                                <tr>
                                <form action="" method="post">
                                    <td>
                                        <button type="button" data-toggle="modal" data-target="#forConfirm"  class="btn btn-primary mx-auto">Confirm</button>
                                    </td>
                                    <td></td>
                                </form>
                                </tr>';
                            } ?>
                        </tbody>
                    </table>
                </div>
        <?php }
        } else {
            echo 'NO Data';
        } ?>
    </div>
    <div class="modal fade" id="forConfirm" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="" class="form-control" enctype="multipart/form-data">
                    <div class="modal-body">
                        <p class="mb-3">Are you sure?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Wait</button>
                        <button type="submit" name="order" class="btn btn-primary">Yes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>