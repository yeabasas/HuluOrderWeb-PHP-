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
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $condition = $_POST['Condition'];
        $storage = $_POST['Storage'];
        $ram = $_POST['Ram'];
        $sim = $_POST['Sim'];
        $color = $_POST['Color'];
        $processor = $_POST['Processor'];
        $price = $_POST['Price'];
        $description = $_POST['Description'];
        $section = $_POST['Section'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "../images/" . $filename;

    
        // Check for required fields
        if (empty($itemName) || empty($price)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
       
                // Move the uploaded file to the destination directory
                if (move_uploaded_file($tempfile, $folder)) {
                    // Use prepared statements to prevent SQL injection
                    $sql = "INSERT INTO `stoke`(`Image`, `UserID`, `ItemName`, `Condition`, `Storage`, `Ram`, `Sim`, `Price`,`Color`,`Processor`, `Description`,`Section`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $stmt = mysqli_prepare($con, $sql);

                    // Bind parameters
                    mysqli_stmt_bind_param($stmt, "ssssssssssss", $filename, $userId, $itemName, $condition, $storage, $ram, $sim, $price, $color,$processor,$description,$section);

                    // Execute the statement
                    $result = mysqli_stmt_execute($stmt);

                    // Check for SQL errors
                    if ($result) {
                        echo '<script>alert("Item added successfully")</script>';
                    } else {
                        echo '<script>alert("Error executing SQL query")</script>';
                    }

                    // Close the statement
                    mysqli_stmt_close($stmt);
                } else {
                    echo '<script>alert("Error moving uploaded file")</script>';
                }
            
        }
    }

?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Addstoke | Admin</title>
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
        <div class=" d-block w-100 h-100 mt-5 ">
            <form method="post" action="addStoke.php" class="form-control mx-auto" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                <div class="d-block mx-auto w-50">
                    <h1>Add Stoke</h1>
                    <select class="form-select mb-2" name="Section" id="" disabled required>
                                <option value="" selected disabled hidden>Section *</option>
                                <option value="stoke">Stoke</option>
                                <option value="preorder">Preorder</option>
                            </select>
                    <input class="form-control mb-2" type="text" placeholder="Item Name" name="ItemName" value=''>
                    <input class="form-control mb-2" type="text" placeholder="Condition" name="Condition" value=''>
                    <input class="form-control mb-2" type="text" placeholder="Storage" name="Storage" value=''>
                    <input class="form-control mb-2" type="text" placeholder="Ram" name="Ram" value=''>
                    <input class="form-control mb-2" type="text" placeholder="Sim" name="Sim" value=''>
                    <input class="form-control mb-2" type="text" placeholder="Color" name="Color" value=''>
                    <input class="form-control mb-2" type="text" placeholder="Processor" name="Processor" value=''
                    <input class="form-control mb-2" type="text" placeholder="Price" name="Price" value=''>
                    <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description" value=''></textarea>
                    <input class="form-control" type="file" name="choosefile" id="choosefile" accept="image/*">
                    <button type="submit" name="submit" class="form-control mt-3 btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </body>

    </html>
<?php } ?>