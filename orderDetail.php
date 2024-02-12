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
        $uid = $_GET['id'];
        $status = 'pending';

        $query = mysqli_query($con, "update stoke set status='$status' where sID='$uid'");

        if ($query) {
            echo '<script>alert("Ordered successfully. Please Wait Confirmation")</script>';
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
        <div class="d-flex flex-col mt-5">
            <?php
            if (isset($_GET['id'])) {
                $itemId = $_GET['id'];
                $uId = $_SESSION['mtid'];
                $query = mysqli_query($con, "select * from stoke where sID = '$itemId'");
                while ($fetch = mysqli_fetch_array($query)) {
            ?>
                    <div class="d-block mx-auto w-75 table-responsive">
                        <table class="table w-100 mx-auto">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">
                                        <?php if ($fetch['Image']) {
                            $img = $fetch["Image"];
                                echo "
                            <img src='images/$img' class='img-responsive w-30 mx-auto' alt='image' height='150px' width='100%' style='object-fit: scale-down;'>
                                ";
                            } else {
                                echo "
                            <img src=images/No_image_available.svg.png' class='img-responsive w-30 mx-auto' alt='image' height='150px' width='100%' style='object-fit: scale-down;'>
                                ";
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

                            <tr>
                                <td>Description</td>
                                <td><?php echo $fetch['Description'] ?></td>
                            </tr>
                            <tr>
                                <td>Order Date</td>
                                <td><?php echo $fetch['DateModified'] ?></td>
                            </tr>
                                <tr>
                                    <td colspan="2">
                                        <form action="" method="POST" enctype="multipart/form-data">
                                            <?php if ($fetch['status'] == '') {
                                                $fetched = $fetch['sID'];
                                                echo "<a href='payment.php?id=$fetched'class='btn btn-primary'>Order Now</a>";
                                            } elseif($fetch['status'] == 'pending') {
                                                echo '<button class="btn btn-primary disabled">Order Now</button>';
                                            }elseif($fetch['status'] == 'ordered') {
                                                echo '<h3 class="text-center bg-danger text-white">Sold Out</h3>';
                                            } ?>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
            <?php }
            }else{
                echo'<h1 class="text-center mx-auto">No Data</h1>';
            } ?>
        </div>
    </body>

    </html>
<?php }
?>