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
} else {
    if (isset($_POST['submit'])) {
        $uid = $_GET['id'];
        $status = $_POST['stat'];

        $query = mysqli_query($con, "update items set status='$status' where ID='$uid'");

        if ($query) {
            echo "<script>alert('Updated successfully');</script>";
        } else {
            echo "<script>alert('unsuccessfully');</script>";
        }
    }
    if (isset($_POST['chg-stat'])) {
        $uid = $_GET['id'];
        $status = 'pending';

        $query = mysqli_query($con, "update items set status='$status' where ID='$uid'");

        if ($query) {
            echo "<script>alert('Confirmed successfully')</script>";
        } else {
            echo "<script>alert('unsuccessfully');</script>";
        }
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
    <div class="d-block mx-auto mt-5">
        <?php
        if (isset($_GET['id'])) {
            $itemId = $_GET['id'];
            $sql2 = "SELECT items.ID,items.userId,items.ItemName,items.Storage,items.Ram,items.Sim,items.Condition,items.Description,items.Image,items.status,items.ScreenImage,items.DateModified,items.Price,items.Confirmation,items.SuggestImage,items.SuggestDes,items.Color,items.Location,items.Processor,items.Size,items.Min,items.Type,items.Capacity,items.Usage,user.FirstName,user.LastName,user.Phone FROM items,user WHERE items.userId = user.ID && items.ID = $itemId";
            $query = mysqli_query($con, $sql2);
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
                            <?php if ($fetch['FirstName'] != '') {
                                $name = $fetch['FirstName'] .' '. $fetch['LastName'] .' '. '/'.' ' . $fetch['Phone'];
                                echo "
                                <tr>
                                    <td>Ordered By</td>
                                    <td>$name</td>
                                </tr>";
                            } ?>
                            <?php if ($fetch['ItemName'] != '') {
                                $name = $fetch['ItemName'];
                                echo "
                                <tr>
                                    <td>ItemName</td>
                                    <td>$name</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Condition'] != '') {
                                $con = $fetch['Condition'];
                                echo "
                                <tr>
                                    <td>Condition</td>
                                    <td>$con New</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Color'] != '') {
                                $col = $fetch['Color'];
                                echo "
                                <tr>
                                    <td>Color</td>
                                    <td>$col</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Processor'] != '') {
                                $pro = $fetch['Processor'];
                                echo "
                                <tr>
                                    <td>Processor</td>
                                    <td>$pro</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Storage'] != '') {
                                $str = $fetch['Storage'];
                                echo "
                                <tr>
                                    <td>Storage</td>
                                    <td>$str GB</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Ram'] != '') {
                                $ram = $fetch['Ram'];
                                echo "
                                <tr>
                                    <td>RAM</td>
                                    <td>$ram GB</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Sim'] != '') {
                                $sim = $fetch['Sim'];
                                echo "
                                <tr>
                                    <td>SIM</td>
                                    <td>$sim</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Size'] != '') {
                                $siz = $fetch['Size'];
                                echo "
                                <tr>
                                    <td>Size</td>
                                    <td>$siz</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Min'] != '') {
                                $min = $fetch['Min'];
                                echo "
                                <tr>
                                    <td>SIM</td>
                                    <td>$min</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Type'] != '') {
                                $type = $fetch['Type'];
                                echo "
                                <tr>
                                    <td>Type</td>
                                    <td>$type</td>
                                </tr>";
                            } ?>

                            <?php if ($fetch['Capacity'] != '') {
                                $cap = $fetch['Capacity'];
                                echo "
                                <tr>
                                    <td>Capacity</td>
                                    <td>$cap</td>
                                </tr>";
                            } ?>
                            <?php if ($fetch['Price'] != '') {
                                $cap = $fetch['Price'];
                                echo "
                                <tr>
                                    <td>Price</td>
                                    <td>$cap</td>
                                </tr>";
                            } ?>
                            <tr>
                                <td>Description</td>
                                <td><?php echo $fetch['Description'] ?></td>
                            </tr>
                            <tr>
                                <td>Order Date</td>
                                <td><?php echo $fetch['DateModified'] ?></td>
                            </tr>
                            <td>Screenshot</td>
                            <td class="d-flex">
                                <a href="../images/<?php echo $fetch['ScreenImage'] ?>">
                                    <img src="../images/<?php echo $fetch['ScreenImage'] ?>" class="img-responsive w-30 mx-auto" height="50px" width="50px" alt="">
                                </a>
                                <?php if ($fetch['status'] == 'new' && $fetch['ScreenImage'] != '') {
                                    echo '
                                        <form action="" method="post">
                                        <button type="submit" name="chg-stat" class="btn btn-primary mx-3">Confirm</button>
                                    </form>
                                        ';
                                } elseif ($fetch['status'] == 'new' && $fetch['ScreenImage'] == '') {
                                    echo '
                                        <form action="" method="post">
                                        <button disabled type="submit" name="chg-stat" class="btn btn-dark mx-3">Not payed yet</button>
                                    </form>';
                                } else {
                                    echo '
                                        <button disabled type="submit" name="chg-stat" class="btn btn-dark mx-3">Cofirmed</button>
                                    ';
                                } ?>
                            </td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td><?php echo $fetch['status'] ?></td>
                            </tr>
                            <tr>
                                <td>Change Status</td>
                                <td>
                                    <form action="" class="d-flex" method="post">
                                        <select class="form-select mb-2" name='stat' id="">
                                            <option value="<?php echo $fetch['status'] ?>" selected disabled hidden><?php echo $fetch['status'] ?></option>
                                            <option value="Shipped">Shipped</option>
                                            <option value="Arrived">Arrived</option>
                                        </select>
                                        <button type="submit" name="submit" class="btn btn-primary">Change</button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        <?php }
        } ?>
    </div>
</body>

</html>