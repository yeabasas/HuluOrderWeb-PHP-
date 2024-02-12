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
    if (isset($_POST['yess'])) {
        $uid = $_GET['id'];
        $confirm = 'yes';
        $stat = 'pending';

        $query = mysqli_query($con, "update items set Confirmation='$confirm',status='$stat' where ID='$uid'");

        if ($query) {
            echo '<script>alert("Great! your order will delivered soon.")</script>';
        } else {
            echo '<script>alert("Something Went Wrong. Please try again.")</script>';
        }
    }
    if (isset($_POST['no'])) {
        $uid = $_GET['id'];
        $confirm = 'no';
        $cancel = 'Cancelled';

        $query = mysqli_query($con, "update items set Confirmation='$confirm',status='$cancel' where ID='$uid'");

        if ($query) {
            echo '<script>alert("Okay!😞 Order Canceled")</script>';
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
        <title>Detail</title>
        <link rel="stylesheet" href="style.css">
        <link rel="shortcut icon" href="./images/Eo_circle_green_white_letter-h.svg.png" type="image/x-icon">
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
        <div class="pt-5" style="background-color: #e5e4e2; height: 100vh; width:100vw;">
            <?php
            if (isset($_GET['id'])) {
                $itemId = $_GET['id'];
                $uid = $_SESSION['mtid'];
                $query = mysqli_query($con, "select * from items where ID = '$itemId' && userId=$uid ");
                while ($fetch = mysqli_fetch_array($query)) {
            ?>
                    <div class="mx-auto table-responsive h-100">
                        <table class="table w-75 mx-auto bg-light px-5 shadow rounded">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">
                                        <?php if ($fetch['Image']) {
                                            $img = $fetch['Image'];
                                echo "
                            <img src='images/$img' class='card-img-top'height='150px' width='100%' alt='' style='object-fit: scale-down;'>
                                ";
                            } else {
                                echo '
                            <img src="images/No_image_available.svg.png" class="card-img-top" alt="image" height="150px" width="100%" style="object-fit: scale-down;">
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
                                <?php if ($fetch['Price'] != '' && $fetch['status'] == 'new' && $fetch['ScreenImage'] == ''&& ($fetch['SuggestImage'] == '' || $fetch['SuggestDes'] == '')) {
                                    $fetched = $fetch['Price'];
                                    echo " 
                                    <tr>
                                        <td>Price</td>
                                        <td>$fetched</td>
                                    </tr>
                                    <tr>
                                    <form action='' method='POST' enctype='multipart/form-data'>
                                        <td>about the price</td>
                                        <td>
                                            <a href='orderPayment.php?id=$itemId' class='btn btn-primary'>yes</a>
                                            <button type='submit' name='no' class='btn btn-secondary'>no</button>
                                        </td>
                                    </form>
                                    </tr>";
                                };
                                if ($fetch['status'] == 'Cancelled') {
                                    $fetched = $fetch['status'];
                                    echo "
                                    <tr>
                                    <td class='bg-dark text-white text-center' colspan='2'>$fetched</td>
                                    <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'new' && $fetch['Price'] != '' && $fetch['ScreenImage'] != '') {
                                    $fetched = $fetch['Price'];
                                    echo "
                                    <tr>
                                        <td>Price</td>
                                        <td>$fetched</td>
                                    </tr>
                                    <tr>
                                    <td colspan='2' class='bg-secondary text-white text-center'>Please Wait until payment verified</td>
                                        <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'pending') {
                                    $fetched = $fetch['Price'];
                                    echo "
                                    <tr>
                                        <td>Price</td>
                                        <td>$fetched</td>
                                    </tr>
                                    <tr>
                                    <td colspan='2' class='bg-warning text-white text-center'>Pending</td>
                                        <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'new' && $fetch['Price'] == '' && ($fetch['SuggestImage'] == '' || $fetch['SuggestDes'] == '')) {
                                    echo "
                                    <tr>
                                    <td colspan='2' class='bg-secondary text-white text-center'>Please wait confirmation</td>
                                        <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'Shipped') {
                                    echo "
                                    <tr>
                                    <td colspan='2' class='bg-primary text-white text-center'>Your Order is Shipped!</td>
                                        <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'Denied') {
                                    echo "
                                    <tr>
                                    <td colspan='2' class='bg-primary text-white text-center'>Your Order Denied</td>
                                        <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'Arrived') {
                                    echo "
                                    <tr>
                                    <td colspan='2' class='bg-primary text-white text-center'>Your Order is Arrived!</td>
                                        <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'new' && $fetch['ScreenImage'] == '' && ($fetch['SuggestImage'] != '' || $fetch['SuggestDes'] != '')) {
                                    $fetched = $fetch['SuggestImage'];
                                    $fetchedDes = $fetch['SuggestDes'];
                                    $fetPrice = $fetch['Price'];
                                    echo "
                                    <tr>
                                        <td colspan='2' class='bg-secondary text-white text-center'>Sorry, we couldn't find the item, but can get this one</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Suggested Item</td>
                                        <td><a href='images/$fetched'>
                                            <img src='images/$fetched' alt='screenshot' height='90px' width='90px'>
                                        </a></td>
                                    </tr>
                                    <tr>
                                        <td>Suggested Item Description</td>
                                        <td>$fetchedDes</td>
                                    </tr>
                                    <tr>
                                        <td>Price</td>
                                        <td>$fetPrice</td>
                                    </tr>
                                    <tr>
                                    <form action='' method='POST' enctype='multipart/form-data'>
                                        <td>do you still order?</td>
                                        <td>
                                            <a href='orderPayment.php?id=$itemId' class='btn btn-primary'>yes</a>
                                            <button type='submit' name='no' class='btn btn-secondary'>no</button>
                                        </td>
                                    </form>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'new' && $fetch['SuggestImage'] != '' && $fetch['SuggestDes'] != '' && $fetch['ScreenImage'] != '') {
                                    $fetched = $fetch['SuggestImage'];
                                    $fetchedDes = $fetch['SuggestDes'];
                                    $fetPrice = $fetch['Price'];
                                    echo "
                                        <tr>
                                            <td colspan='2' class='bg-secondary text-white text-center'>Sorry, we couldn't find the item, but can get this one</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>Suggested Item</td>
                                             <td><a href='images/$fetched'>
                                                <img src='images/$fetched' alt='screenshot' height='90px' width='90px'>
                                            </a></td>
                                        </tr>
                                        <tr>
                                            <td>Suggested Item Description</td>
                                            <td>$fetchedDes</td>
                                        </tr>
                                        <tr>
                                            <td>Price</td>
                                            <td>$fetPrice</td>
                                        </tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
            <?php }
            } ?>
        </div>
    </body>

    </html>
<?php }
?>