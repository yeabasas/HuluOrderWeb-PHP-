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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary | Admin</title>
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
            return ( <div> {
                    index + 1
                } </div>)
            }
    </script>
    <?php include('header.php') ?>
    <div class="d-block mx-auto  mt-5" style="width: 90vw;">

        <div class="d-block mx-auto table-responsive ">
            <table class="table w-100 mx-auto">
                <caption>orders from customer to china</caption>
                <thead>
                    <tr>
                        <th scope="col" dataField="any" dataFormat={indexN}>#</th>
                        <th scope="col">Ordered By</th>
                        <th scope="col">ItemName</th>
                        <th scope="col">Condition</th>
                        <th scope="col">Storage</th>
                        <th scope="col">Ram</th>
                        <th scope="col">Sim</th>
                        <th scope="col">Color</th>
                        <th scope="col">Processor</th>
                        <th scope="col">Size</th>
                        <th scope="col">Type</th>
                        <th scope="col">Capacity</th>
                        <th scope="col">Usage</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col">Image</th>
                        <th scope="col">DateModified</th>
                        <th scope="col">Screenshot</th>
                        <th scope="col">Price</th>
                        <th scope="col">OrgPrice</th>
                        <th scope="col">Sug-Img</th>
                        <th scope="col">Sug-Des</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM items join user where items.userId = user.ID");
                    while ($fetch = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <th scope="row"></th>
                            <td><?php $a = $fetch['FirstName'] ? $fetch['FirstName'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['ItemName'] ? $fetch['ItemName'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Condition'] ? $fetch['Condition'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Storage'] ? $fetch['Storage'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Ram'] ? $fetch['Ram'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Sim'] ? $fetch['Sim'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Color'] ? $fetch['Color'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Processor'] ? $fetch['Processor'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Size'] ? $fetch['Size'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Type'] ? $fetch['Type'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Capacity'] ? $fetch['Capacity'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Usage'] ? $fetch['Usage'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Description'] ? $fetch['Description'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['status'] ? $fetch['status'] : '--'; echo $a;?></td>
                            <td><a href="../images/<?php echo $fetch['Image'] ?>"> <img src="../images/<?php echo $fetch['Image'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt="--"></td></a>
                            <td><?php $a = $fetch['DateModified'] ? $fetch['DateModified'] : '--'; echo $a;?></td>
                            <td> <a href="../images/<?php echo $fetch['ScreenImage'] ?>"><img src="../images/<?php echo $fetch['ScreenImage'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt="--"></td></a>
                            <td><?php $a = $fetch['Price'] ? $fetch['Price'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['orgPrice'] ? $fetch['orgPrice'] : '--'; echo $a;?></td>
                            <td> <a href="../images/<?php echo $fetch['SuggestImage'] ?>"><img src="../images/<?php echo $fetch['SuggestImage'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt="--"></td></a>
                            <td><?php $a = $fetch['SuggestDes'] ? $fetch['SuggestDes'] : '--'; echo $a;?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-block mx-auto mt-5 table-responsive bg-secondary text-white">
            <h1>Stoke Orders</h1>
            <table class="table w-90 mx-auto text-white">
                <caption>orders from users to china</caption>
                <thead>
                    <tr>
                        <th scope="col" dataField="any" dataFormat={indexN}>#</th>
                        <th scope="col">Ordered By</th>
                        <th scope="col">Image</th>
                        <th scope="col">ItemName</th>
                        <th scope="col">Condition</th>
                        <th scope="col">Storage</th>
                        <th scope="col">Ram</th>
                        <th scope="col">Sim</th>
                        <th scope="col">Color</th>
                        <th scope="col">Processor</th>
                        <th scope="col">Size</th>
                        <th scope="col">Type</th>
                        <th scope="col">Capacity</th>
                        <th scope="col">Usage</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col">DateModified</th>
                        <th scope="col">Screenshot</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM orders JOIN user where orders.userId = user.ID");
                    while ($fetch = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <th scope="row"></th>
                            <td><?php $a = $fetch['FirstName'] ? $fetch['FirstName'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['ItemName'] ? $fetch['ItemName'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Condition'] ? $fetch['Condition'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Storage'] ? $fetch['Storage'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Ram'] ? $fetch['Ram'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Sim'] ? $fetch['Sim'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Color'] ? $fetch['Color'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Processor'] ? $fetch['Processor'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Size'] ? $fetch['Size'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Type'] ? $fetch['Type'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Capacity'] ? $fetch['Capacity'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Usage'] ? $fetch['Usage'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['Description'] ? $fetch['Description'] : '--'; echo $a;?></td>
                            <td><?php $a = $fetch['status'] ? $fetch['status'] : '--'; echo $a;?></td>
                            <td><a href="../images/<?php echo $fetch['Image'] ?>"> <img src="../images/<?php echo $fetch['Image'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt="--"></td></a>
                            <td><?php $a = $fetch['DateModified'] ? $fetch['DateModified'] : '--'; echo $a;?></td>
                            <td> <a href="../images/<?php echo $fetch['ScreenImage'] ?>"><img src="../images/<?php echo $fetch['ScreenImage'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt="--"></td></a>
                            <td><?php $a = $fetch['Price'] ? $fetch['Price'] : '--'; echo $a;?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-block mx-auto mt-5 table-responsive bg-secondary text-white">
            <h1>Direct order</h1>
            <table class="table w-90 mx-auto text-white">
                <caption>orders from admin to china</caption>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Ordered By</th>
                        <th scope="col">ItemName</th>
                        <th scope="col">Condition</th>
                        <th scope="col">Storage</th>
                        <th scope="col">Ram</th>
                        <th scope="col">Sim</th>
                        <th scope="col">Description</th>
                        <th scope="col">Image</th>
                        <th scope="col">DateModified</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM orders JOIN user where orders.userId = user.ID");
                    while ($fetch = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <th scope="row"></th>
                            <td><?php echo $fetch['FirstName'] ?></td>
                            <td><?php echo $fetch['ItemNameOr'] ?></td>
                            <td><?php echo $fetch['ConditionOr'] ?></td>
                            <td><?php echo $fetch['StorageOr'] ?></td>
                            <td><?php echo $fetch['RamOr'] ?></td>
                            <td><?php echo $fetch['SimOr'] ?></td>
                            <td><?php echo $fetch['DescriptionOr'] ?></td>
                            <td> <img src="../images/<?php echo $fetch['ImageOr'] ?>" class="img-responsive mx-auto" width="60px" height="40px" alt=""></td>
                            <td><?php echo $fetch['DateModified'] ?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="d-block mx-auto mt-5 table-responsive ">
            <h1>Users</h1>
            <table class="table w-90 mx-auto">
                <caption>users</caption>
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = mysqli_query($con, "SELECT * FROM user WHERE Role !='admin' && Role !='china' && Phone !='+251920000000' ");
                    while ($fetch = mysqli_fetch_array($query)) {
                    ?>
                        <tr>
                            <th scope="row"></th>
                            <td><?php echo $fetch['FirstName'] ?></td>
                            <td><?php echo $fetch['LastName'] ?></td>
                            <td><?php echo $fetch['Phone'] ?></td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>

</html>