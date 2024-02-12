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
$accounting = mysqli_query($con, "SELECT * FROM accounting");
$result = mysqli_fetch_array($accounting);

if (isset($_POST['submit'])) {
    $uid = $_GET['id'];
    $price = $_POST['Price'];
    // Calculation variables
    $com1 = $result['Com1'];
    $com2 = $result['Com2'];
    $delivery1 = $result['Delivery1'];
    $rate = $result['Rate'];
    $carrier = $result['Carrier'];
    $cut = $result['ProfitInPercent'];
    // calculations 
    $sum = $price + $com1 + $com2 + $delivery1;
    $prod = $sum * $rate;
    $arrive = $prod + $carrier;
    if ($price < 300) {
        $profitInPer = 0.05;
    }
    if ($price >= 300 && $price < 500) {
        $profitInPer = 0.05;
    }
    if ($price >= 500 && $price < 1000) {
        $profitInPer = 0.07;
    }
    if ($price >= 1000 && $price < 1500) {
        $profitInPer = 0.1;
    }
    if ($price >= 1500 && $price < 2000) {
        $profitInPer = 0.05;
    }
    if ($price >= 2000 && $price < 2500) {
        $profitInPer = 0.05;
    }
    if ($price >= 2500 && $price < 3000) {
        $profitInPer = 0.08;
    }
    if ($price >= 3000 && $price < 3500) {
        $profitInPer = 0.07;
    }
    if ($price >= 3500 && $price < 10000) {
        $profitInPer = 0.1;
    }
    $percent = $arrive * $profitInPer;
    $final = $percent + $arrive;

    $query = mysqli_query($con, "update items set Price='$final', orgPrice='$price' where ID='$uid'");

    if ($query) {
        echo '<script>alert("Great! notification send successfully.")</script>';
    } else {
        echo '<script>alert("Something Went Wrong. Please try again.")</script>';
    }
}
if (isset($_POST['suggest'])) {
    $uid = $_GET['id'];
    $description = $_POST['Description'];
    $price = $_POST['Price'];
    // Calculation variables
    $com1 = $result['Com1'];
    $com2 = $result['Com2'];
    $delivery1 = $result['Delivery1'];
    $rate = $result['Rate'];
    $carrier = $result['Carrier'];
    $cut = $result['ProfitInPercent'];
    // calculations 
    $sum = $price + $com1 + $com2 + $delivery1;
    $prod = $sum * $rate;
    $arrive = $prod + $carrier;
    if ($price < 300) {
        $profitInPer = 0.05;
    }
    if ($price >= 300 && $price < 500) {
        $profitInPer = 0.05;
    }
    if ($price >= 500 && $price < 1000) {
        $profitInPer = 0.07;
    }
    if ($price >= 1000 && $price < 1500) {
        $profitInPer = 0.1;
    }
    if ($price >= 1500 && $price < 2000) {
        $profitInPer = 0.05;
    }
    if ($price >= 2000 && $price < 2500) {
        $profitInPer = 0.05;
    }
    if ($price >= 2500 && $price < 3000) {
        $profitInPer = 0.08;
    }
    if ($price >= 3000 && $price < 3500) {
        $profitInPer = 0.07;
    }
    if ($price >= 3500 && $price < 10000) {
        $profitInPer = 0.1;
    }
    $percent = $arrive * $profitInPer;
    $final = $percent + $arrive;
    $filename = $_FILES["choosefile"]["name"];
    $tempfile = $_FILES["choosefile"]["tmp_name"];
    $folder = "../images/" . $filename;

    $sql = "update items set SuggestImage='$filename',SuggestDes = '$description', Price='$final', orgPrice='$price' where ID='$uid'";
    if (!$sql) {
        echo '<script>alert("something went wrong")</script>';
    } else {
        $result = mysqli_query($con, $sql);
        move_uploaded_file($tempfile, $folder);
        echo '<script>alert("Good! notification send successfully.")</script>';
    }
}
if (isset($_POST['noSuggest'])) {
    $uid = $_GET['id'];
    $stat = 'Denied';

    $sql = "update items set status = '$stat' where ID='$uid'";
    if (!$sql) {
        echo '<script>alert("something went wrong")</script>';
    } else {
        $result = mysqli_query($con, $sql);
        echo '<script>alert("Sad! notification send successfully.")</script>';
    }
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
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
    <?php include('header.php') ?>
    <div class="container mt-5">
        <?php
        if (isset($_GET['id'])) {
            $itemId = $_GET['id'];
            $query = mysqli_query($con, "SELECT*FROM items WHERE ID= $itemId");

            while ($fetch = mysqli_fetch_array($query)) {
        ?>
                <div class="d-block w-75 mx-auto table-responsive">
                    <table class="table mx-auto">
                        <thead>
                            <tr>
                                <th scope="col" colspan="2">
                                    <?php if ($fetch['Image']) {
                                        $img=$fetch['Image'];
                                echo "
                            <img src='../images/$img' class='img-responsive mx-auto' height='150px' width='100%' alt='' style='object-fit: scale-down;'>
                                ";
                            } else {
                                echo '
                            <img src="../images/No_image_available.svg.png" class="img-responsive mx-auto" height="150px" width="100%" alt="" style="object-fit: scale-down;">
                                ';
                            } ?>
                                </th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
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

                            <tr>
                                <td>Description</td>
                                <td><?php echo $fetch['Description'] ?></td>
                            </tr>
                            <tr>
                                <td>Order Date</td>
                                <td><?php echo $fetch['DateModified'] ?></td>
                            </tr>

                            <?php if (($fetch['Price'] == '' && $fetch['Confirmation'] == '' && $fetch['ScreenImage'] == '') || (($fetch['SuggestDes']== '' || $fetch['SuggestImage'] == '')&&$fetch['Price'] == '')) {

                                echo ' <tr>
                                        <td>Is available?</td>
                                        <td class="d-flex">
                                            <button type="button" class="btn btn-primary mr-3" data-toggle="modal" data-target="#forYes">Yes</button>
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#forNo">No</button>
                                        </td>
                                        </tr>
                                    ';
                            } elseif ($fetch['Price'] != '' && $fetch['status'] == 'pending' && $fetch['Confirmation'] != 'no') {
                                echo "<tr>
                                        <td colspan='2' class='text-white bg-success text-center'>Confirmed, place the order!</td>
                                        </tr>
                                    ";
                            } elseif (($fetch['status'] == 'new' && $fetch['Confirmation'] != 'no') || (($fetch['SuggestDes'] != '' || $fetch['SuggestImage'] != '')&& $fetch['Confirmation'] != 'no')) {
                                echo "<tr>
                                        <td colspan='2' class='text-white bg-secondary text-center'>Please Wait Response</td>
                                        </tr>
                                    ";
                            } elseif ($fetch['status'] != 'pending' && $fetch['Price'] != '' && $fetch['Confirmation'] != 'no') {
                                echo "<tr>
                                        <td colspan='2' class='text-white bg-secondary text-center'>Please Wait for response</td>
                                        </tr>
                                    ";
                            } elseif ($fetch['Confirmation'] == 'no') {
                                echo "<tr>
                                        <td colspan='2' class='text-white bg-secondary text-center'>Order CANCELLED from the user</td>
                                        </tr>
                                    ";
                            } elseif ($fetch['status'] = 'Denied') {
                                echo "<tr>
                                        <td colspan='2' class='text-white bg-dark text-center'>Order Cancelled</td>
                                        </tr>
                                    ";
                            }
                            ?>


                        </tbody>
                    </table>
                    <div class="modal fade" id="forNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Any suggestion?</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" action="" class="form-control" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <input class="form-control" type="text" name="Price" id="Price" placeholder="Price" required>
                                        <input class="form-control" type="file" name="choosefile" id="">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-contact" data-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#forNoSuggest">No Suggest</button>
                                        <button type="submit" name="suggest" class="btn btn-primary">Suggest</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="forNoSuggest" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">you are cancelling!</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" action="" class="form-control" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <h2>Are you sure? you can't get this item?</h2>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="noSuggest" class="btn btn-primary">No Suggestion</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="forYes" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Please enter the price</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form method="post" action="" class="form-control" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <input class="form-control" type="text" name="Price" id="Price" placeholder="Price" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-contact" data-dismiss="modal">Close</button>
                                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <?php }
        } ?>
    </div>
</body>

</html>