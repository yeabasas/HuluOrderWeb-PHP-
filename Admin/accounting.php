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
if (isset($_POST['save'])) {

    $com1 = $_POST['Com1'];
    $com2 = $_POST['Com2'];
    $delivery1 = $_POST['Delivery1'];
    $rate = $_POST['Rate'];
    $carrier = $_POST['Carrier'];
    $profitInPer = $_POST['ProfitInPer'];

    $sqli = "UPDATE accounting SET Com1='$com1' ,Com2='$com2',Delivery1='$delivery1',Rate='$rate',Carrier='$carrier' ";
    $result = mysqli_query($con, $sqli);
    if ($result) {
        echo "<script>alert('Updated successfully');</script>";
    }else{
        echo "<script>alert('unsuccessfully');</script>";
       }
    }
}
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <title>Register | Admin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
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

    <div class="w-100 h-100">
        <?php include('header.php')?>
        <?php 
            $query = 'SELECT * FROM accounting';
            $results = mysqli_query($con,$query);
           while($res = mysqli_fetch_array($results)){
        ?>
        <form method="post" action="accounting.php" class="form-control mt-5" enctype="multipart/form-data" style="text-decoration: none; border:none;">
            <div class="d-block w-50 mx-auto form-item">
                <h1 class="mx-auto mb-2">Edit</h1>
                <label for="Com1">Com1</label>
                <input id="Com1" class="form-control mb-2" type="text" name="Com1" placeholder="Commission 1" required value="<?php echo $res['Com1']?>" />
                <label for="Com2">Com2</label>
                <input id='Com2' class="form-control mb-2" type="text" name="Com2" placeholder="Commission 2" required value="<?php echo $res['Com2']?>"/>
                <label for="Delivery1">Delivery1</label>
                <input id="Delivery1" class="form-control mb-2" type="text" name='Delivery1'  placeholder="Delivery" required value="<?php echo $res['Delivery1']?>"/>
                <label for="Rate">Rate</label>
                <input id="Rate" class="form-control mb-2" type="text" name="Rate" placeholder="Rate" required value="<?php echo $res['Rate']?>"/>
                <label for="Carrier" class="text">Carrier</label>
                <input id="Carrier" class="form-control mb-2" type="text" name="Carrier" placeholder="Carrier" required value="<?php echo $res['Carrier']?>"/>
                <button type="submit" name="save" class="form-control mt-3 btn btn-primary">Add</button>
            </div>
        </form><?php }?>
    </div>
</body>

</html>