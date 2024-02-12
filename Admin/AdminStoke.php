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
$query = mysqli_query($con, "SELECT * FROM stoke");
$row = mysqli_fetch_array($query);

if (strlen($_SESSION['mtid'] == 0)) {
    header('Location:../logout.php');
} elseif (isset($_POST['hidden'])) {
    $row_id = $_POST['row_id'];
    $stat = 'hidden';
    $sql = "UPDATE stoke SET status='$stat' WHERE sID=$row_id";
    mysqli_query($con, $sql);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stoke | Admin</title>
    <link rel="stylesheet" href="../style.css">
    <!-- <link rel="shortcut icon" href="../images/BYZF1309.JPG" type="image/x-icon"> -->
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
    <script>
        function indexN(cell, row, enumObject, index) {
            return ( <div> {index + 1} </div>)
            }
    </script>
    <?php include('header.php') ?>
    <div class="d-block mx-auto  mt-5" style="width: 90vw;">
        <div class="d-block mx-auto table-responsive">
            <table class="table w-100 mx-auto">
                <caption>Orders from Stoke</caption>
                <thead>
                    <tr>
                        <th scope="col" dataField="any" dataFormat={indexN}>#</th>
                        <th scope="col">Posted/Ordered By</th>
                        <th scope="col">ItemName</th>
                        <th scope="col">Condition</th>
                        <th scope="col">Storage</th>
                        <th scope="col">Ram</th>
                        <th scope="col">Sim</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col">Image</th>
                        <th scope="col">DateModified</th>
                        <th scope="col">Screenshot</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM stoke join user where stoke.UserID = user.ID ORDER BY DateModified DESC");
                    while ($fetch = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <th scope="row"></th>
                            <td><?php echo $fetch['FirstName'] ?></td>
                            <td><?php echo $fetch['ItemName'] ?></td>
                            <td><?php echo $fetch['Condition'] ?></td>
                            <td><?php echo $fetch['Storage'] ?></td>
                            <td><?php echo $fetch['Ram'] ?></td>
                            <td><?php echo $fetch['Sim'] ?></td>
                            <td><?php echo $fetch['Description'] ?></td>
                            <td><?php echo $fetch['status'] ?></td>
                            <td> <img src="../images/<?php echo $fetch['Image'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt=""></td>
                            <td><?php echo $fetch['DateModified'] ?></td>
                            <td> <img src="../images/<?php echo $fetch['ScreenImage'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt=""></td>
                            <td><?php echo $fetch['Price'] ?></td>
                            <td>
                                <form action="" method="post">
                                    <input type="hidden" name="row_id" value="<?php echo $fetch['sID']; ?>">
                                    <button type="submit" name="hidden" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>