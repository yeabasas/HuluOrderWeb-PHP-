<?php
session_start();
error_reporting(0);
include('dbcon.php');

function redirectWithMessage($location, $message) {
    echo "<script>alert('$message'); window.location.href = '$location';</script>";
    exit();
}

// Session timeout management
if (isset($_SESSION['timeout'])) {
    $inactiveTime = time() - $_SESSION['timeout'];
    $sessionTimeout = 30 * 60; // 30 minutes in seconds

    if ($inactiveTime >= $sessionTimeout) {
        session_unset();
        session_destroy();
        redirectWithMessage('index.php', 'Session expired. Please log in again.');
    }
    $_SESSION['timeout'] = time();
} else {
    $_SESSION['timeout'] = time();
}

// CSRF token generation
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify'])) {
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        redirectWithMessage('index.php', 'Invalid CSRF token');
    }

    $userId = $_SESSION['mtid'];
    $trade = $_FILES['trade'];
    $kebele = $_FILES['kebele'];

    if (empty($trade['name']) || empty($kebele['name'])) {
        redirectWithMessage('verification.php', 'Required fields are not filled');
    }

    if ($trade['error'] !== UPLOAD_ERR_OK || $kebele['error'] !== UPLOAD_ERR_OK) {
        redirectWithMessage('verification.php', 'Error uploading files');
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
    $tradeExtension = pathinfo($trade['name'], PATHINFO_EXTENSION);
    $kebeleExtension = pathinfo($kebele['name'], PATHINFO_EXTENSION);

    if (!in_array($tradeExtension, $allowedExtensions) || !in_array($kebeleExtension, $allowedExtensions)) {
        redirectWithMessage('verification.php', 'Invalid file type. Only JPG, JPEG, PNG, and PDF are allowed.');
    }

    $tradeFilename = "trade-" . time() . "." . $tradeExtension;
    $kebeleFilename = "kebele-" . time() . "." . $kebeleExtension;
    $tradeFolder = "../images/" . $tradeFilename;
    $kebeleFolder = "../images/" . $kebeleFilename;

    $tradeMoved = move_uploaded_file($trade['tmp_name'], $tradeFolder);
    $kebeleMoved = move_uploaded_file($kebele['tmp_name'], $kebeleFolder);

    if ($tradeMoved && $kebeleMoved) {
        $sql = "INSERT INTO `verifingdoc`(`UserId`, `TradeLicense`, `KebeleId`) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $userId, $tradeFilename, $kebeleFilename);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            redirectWithMessage('verification.php', 'Verification documents uploaded successfully!');
        } else {
            redirectWithMessage('verification.php', 'Failed to insert into database');
        }

        mysqli_stmt_close($stmt);
    } else {
        redirectWithMessage('verification.php', 'Error moving uploaded files');
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/e2c97eca5a.js" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcC3O+1pu1AnmA5LOI5G5Mu79Eq1E21bdl/9VEJFpfy/5UTr+4O+VPE8adVw8zT" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            <?php
            $uId = $_SESSION['mtid'];
            $query = mysqli_query($con, "SELECT * FROM verifingdoc WHERE UserId = '$uId'");
            while ($fetches = mysqli_fetch_array($query)) {
                if ($fetches['TradeLicense'] != '' && $fetches['KebeleId'] != '') {
                    $query = mysqli_query($con, "SELECT * FROM user WHERE ID = '$uId' AND Verified = 1");
                    while ($fetched = mysqli_fetch_array($query)) {
                        if ($fetched['ID']) {
                            echo '<div class="alert alert-success" role="alert">Your account is Verified</div>';
                        } else {
                            echo '<div class="alert alert-info" role="alert">Your Account Verification request is being processed. We will reach you soon.</div>';
                        }
                    }
                } else {
                    echo '<div class="alert alert-warning" role="alert">To verify your account please submit the following documents:</div>';
                    echo '<div class="mb-3"><label for="trade" class="form-label">Trade Registration & License (image)</label>';
                    echo '<input class="form-control" type="file" name="trade" id="trade" required></div>';
                    echo '<div class="mb-3"><label for="kebele" class="form-label">Kebele ID/Passport/Driving License</label>';
                    echo '<input class="form-control" type="file" name="kebele" id="kebele" required></div>';
                    echo '<button type="submit" name="verify" class="btn btn-success mt-3">Submit</button>';
                }
            }
            ?>
        </form>
    </div>
</body>
</html>
