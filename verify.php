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
        header('Location:index.php');
        exit();
    }
    $_SESSION['timeout'] = time();
}
if (isset($_POST['verify'])) {
    $userId = $_SESSION['mtid'];
    $trade = $_FILES['trade'];
    $kebele = $_FILES['kebele'];

    // Check for required fields and file uploads
    if (empty($trade['name']) || empty($kebele['name'])) {
        echo '<script>alert("Required fields are not filled");</script>';
    } else {
        // Check for upload errors
        if ($trade['error'] !== UPLOAD_ERR_OK || $kebele['error'] !== UPLOAD_ERR_OK) {
            echo '<script>alert("Error uploading files");</script>';
            return;
        }

        // Process 'trade' file
        $tradeExtension = pathinfo($trade['name'], PATHINFO_EXTENSION);
        $tradeFilename = "trade-" . time() . "." . $tradeExtension;
        $tradeFolder = "../images/" . $tradeFilename;

        // Process 'kebele' file
        $kebeleExtension = pathinfo($kebele['name'], PATHINFO_EXTENSION);
        $kebeleFilename = "kebele-" . time() . "." . $kebeleExtension;
        $kebeleFolder = "../images/" . $kebeleFilename;

        // Move the uploaded files to the destination directory
        $tradeMoved = move_uploaded_file($trade['tmp_name'], $tradeFolder);
        $kebeleMoved = move_uploaded_file($kebele['tmp_name'], $kebeleFolder);

        if ($tradeMoved && $kebeleMoved) {
            // Use prepared statements to prevent SQL injection
            $sql = "INSERT INTO `verifingdoc`(`UserId`, `TradeLicense`, `KebeleId`) VALUES (?, ?, ?)";
            $stmt = mysqli_prepare($con, $sql);

            // Bind parameters
            mysqli_stmt_bind_param($stmt, "sss", $userId, $tradeFilename, $kebeleFilename);

            // Execute the statement
            $result = mysqli_stmt_execute($stmt);

            // Check for SQL errors
            if ($result) {
                echo '<script>alert("Verification documents uploaded successfully!");</script>';
            } else {
                echo '<script>alert("Failed to insert into database");</script>';
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            echo '<script>alert("Error moving uploaded files");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hulu Order</title>
    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./index.css" />
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="./images/1122.png" type="image/x-icon">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <!-- Font Awesome -->
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .full-screen-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
        }

        .centered-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 50%;
            padding: 2rem;
        }
    </style>
</head>

<body>
    <form action="" method="post" enctype="multipart/form-data">
        <?php
        $uId = $_SESSION['mtid'];
        $query = mysqli_query($con, "SELECT * FROM verifingdoc WHERE UserId = '$uId'");

        if ($fetches = mysqli_fetch_array($query)) {
            if (!empty($fetches['TradeLicense']) && !empty($fetches['KebeleId'])) {
                $queryV = mysqli_query($con, "SELECT * FROM user WHERE ID = '$uId'");
                if ($fetched = mysqli_fetch_array($queryV)) {
                    if ($fetched['Verified']) {
                        echo '
                        <div class="full-screen-container">
                            <div class="card centered-card">
                                <div style="display:flex;flex-direction:row;align-items:center">
                                    <p style="margin:0;padding:0px 10px">Your Account is Verified</p>
                                    <img  src="../images/icons8-verified-badge-48.png" height="18px" width="18px" srcset="">
                                </div>
                            </div>
                        </div>';
                    } else {
                        echo '
                        <div class="full-screen-container">
                            <div class="card centered-card">
                                <h3>Your Account Verification request is being processed. We will reach you soon.</h3>
                            </div>
                        </div>';
                    }
                } else {
                    // User record not found
                    echo '
                    <div class="full-screen-container">
                        <div class="card centered-card">
                            <p>User record not found.</p>
                        </div>
                    </div>';
                }
            } else {
        ?>
                <div class="full-screen-container">
                    <div class="card centered-card">
                        <p>To verify your account please submit the following documents:</p>
                        <p>Trade Registration & License (image)</p>
                        <input class="form-control" type="file" name="trade" id="trade" required>
                        <p>Kebele ID/Passport/Driving License</p>
                        <input class="form-control" type="file" name="kebele" id="kebele" required>
                        <button type="submit" name="verify" class="form-control mt-3 btn btn-success">Submit</button>
                    </div>
                </div>
            <?php
            }
        } else {
            // No records found in verifingdoc table for this user
            ?>
            <div class="full-screen-container">
                <div class="card centered-card">
                    <p>To verify your account please submit the following documents:</p>
                    <p>Trade Registration & License (image)</p>
                    <input class="form-control" type="file" name="trade" id="trade" required>
                    <p>Kebele ID/Passport/Driving License</p>
                    <input class="form-control" type="file" name="kebele" id="kebele" required>
                    <button type="submit" name="verify" class="form-control mt-3 btn btn-success">Submit</button>
                </div>
            </div>
        <?php
        }
        ?>
    </form>

</body>

</html>