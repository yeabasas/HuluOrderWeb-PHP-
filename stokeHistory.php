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
    if (isset($_POST['yes'])) {
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
        <div class=" mt-5">
            <?php
            if (isset($_GET['sid'])) {
                $sid = $_GET['sid'];
                $uid = $_SESSION['mtid'];
                $query = mysqli_query($con, "select * from stoke where sID = '$sid'");
                while ($fetch = mysqli_fetch_array($query)) {
            ?>
                    <div class=" mx-auto table-responsive">
                        <table class="table w-50 mx-auto">
                            <thead>
                                <tr>
                                    <th scope="col" colspan="2">
                                        <img src="images/<?php echo $fetch['Image'] ?>" class="img-responsive w-30 mx-auto" width="150px" height="150px" alt="">
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
                                    <td>Date Ordered</td>
                                    <td><?php echo $fetch['DateModified'] ?></td>
                                </tr>
                                <?php
                                if ($fetch['status'] == 'ordered') {
                                    $fetched = $fetch['status'];
                                    echo "
                                    <tr>
                                    <td class='bg-dark text-white text-center' colspan='2'>Payment Verified</td>
                                    <td></td>
                                    </tr>";
                                }
                                if ($fetch['status'] == 'pending') {
                                    $fetched = $fetch['status'];
                                    echo "
                                    <tr>
                                    <td colspan='2' class='bg-secondary text-white text-center'>Wait Confirmation</td>
                                        <td></td>
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