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
    header('location:../logout.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>orderDetail | china</title>
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
    <div class="d-block mx-auto mt-5">
        <?php
        if (isset($_GET['id'])) {
            $itemId = $_GET['id'];
            $query = mysqli_query($con, "SELECT*FROM orders WHERE IDOr= $itemId");

            while ($fetch = mysqli_fetch_array($query)) {
        ?>
                <div class="d-block mx-auto table-responsive">
                    <table class="table w-50 mx-auto">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2">
                                    <?php if ($fetch['Image']) {
                                echo '
                            <img src="../images/<?php echo $fetch["Image"] ?>" class="img-responsive mx-auto" height="150px" width="100%" alt="" style="object-fit: scale-down;">
                                ';
                            } else {
                                echo '
                            <img src="../images/<?php echo $fetch["Image"] ?>" class="img-responsive mx-auto" height="150px" width="100%" alt="" style="object-fit: scale-down;">
                                ';
                            } ?>
                                </th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>ItemName</td>
                                <td><?php echo $fetch['ItemNameOr'] ?></td>
                            </tr>
                            <tr>
                                <td>Condition</td>
                                <td><?php echo $fetch['ConditionOr'] ?></td>
                            </tr>
                            <tr>
                                <td>Storage</td>
                                <td><?php echo $fetch['StorageOr'] ?></td>
                            </tr>
                            <tr>
                                <td>RAM</td>
                                <td><?php echo $fetch['RamOr'] ?></td>
                            </tr>
                            <tr>
                                <td>Sim</td>
                                <td><?php echo $fetch['SimOr'] ?></td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td><?php echo $fetch['DescriptionOr'] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

        <?php }
        } ?>
    </div>
</body>

</html>