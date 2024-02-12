<?php
session_start();
// error_reporting(0); // Commenting out this line to enable error reporting
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
    header('location:login.php');
} else {
    $successful = '<script>alert("Item added successfully")</script>';
    $failed = '<script>alert("Error Uploading Item")</script>';

    if (isset($_POST['phones'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $condition = $_POST['Condition'];
        $storage = $_POST['Storage'];
        $processor = $_POST['Processor'];
        $color = $_POST['Color'];
        $ram = $_POST['Ram'];
        $sim = $_POST['Sim'];
        $price = $_POST['Price'];
        $description = $_POST['Description'];

        // Check for required fields
        if (empty($itemName) || empty($condition) || empty($storage) || empty($ram) || empty($sim) || empty($processor) || empty($color) || empty($price)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            $maxFiles = 4;
            if (count($_FILES["choosefile"]["name"]) > $maxFiles) {
                echo '<script>alert("You can only upload up to ' . $maxFiles . ' files.");</script>';
            } else {
                $filenames = array();
                $targetDir = "images/";

                // Loop through each file input
                foreach ($_FILES["choosefile"]["name"] as $key => $filename) {
                    $tempfile = $_FILES["choosefile"]["tmp_name"][$key];
                    $targetFile = $targetDir . $filename;

                    // Move the uploaded file to the destination directory
                    if (move_uploaded_file($tempfile, $targetFile)) {
                        // Save the filename to an array
                        $filenames[] = $filename;
                    } else {
                        echo '<script>alert("Error moving uploaded file")</script>';
                    }
                }

                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `posts`(`userId`, `ItemName`, `Condition`, `Storage`, `Ram`, `Sim`, `Description`, `Processor`, `Color`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "sssssssss", $userId, $itemName, $condition, $storage, $ram, $sim, $description, $processor, $color);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    // Get the ID of the inserted post
                    $postId = mysqli_insert_id($con);

                    // Insert image filenames into the post_images table
                    foreach ($filenames as $filename) {
                        $sqlImage = "INSERT INTO `post_images`(`post_id`, `image_filename`) VALUES (?, ?)";
                        $stmtImage = mysqli_prepare($con, $sqlImage);
                        mysqli_stmt_bind_param($stmtImage, "is", $postId, $filename);
                        $resultImage = mysqli_stmt_execute($stmtImage);

                        if (!$resultImage) {
                            echo $failed;
                            break; // Break out of the loop if an error occurs
                        }

                        mysqli_stmt_close($stmtImage);
                    }
header('Location: ./index.php');
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            }
        }
    }
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Custom order</title>
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
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <!-- Bootstrap-select CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

        <!-- Bootstrap-select JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    </head>

    <body>
        <?php include('header.php') ?>
        <div class="container justify-content-center mx-auto pt-5">
            <div class="postAdd">
                <h2 class="header-txt">Post Phones</h2>
                <form method="post" action="" class="form-control" enctype="multipart/form-data">
                    <div class="d-inline-block">
                        <input class="form-control mb-2" type="text" placeholder="Model Name *" name="ItemName" required>
                        <select class="form-select mb-2" name="Condition" id="" placeholder='Role' required>
                            <option value="" selected disabled hidden>Condition *</option>
                            <option value="85%-90%">85%-90% New</option>
                            <option value="90%-95%">90%-95% New</option>
                            <option value="95%-100%">95%-100% New</option>
                        </select>
                        <select class="form-select mb-2" name="Storage" id="" required>
                            <option value="" selected disabled hidden>Storage *</option>
                            <option value="16">16 GB</option>
                            <option value="32">32 GB</option>
                            <option value="64">64 GB</option>
                            <option value="128">128 GB</option>
                            <option value="256">256 GB</option>
                            <option value="512">512 GB</option>
                            <option value="1TB">1 TB</option>
                        </select>
                        <select class="form-select mb-2" name="Ram" id="" placeholder='Ram' required>
                            <option value="" selected disabled hidden>Ram *</option>
                            <option value="3">3 GB</option>
                            <option value="4">4 GB</option>
                            <option value="6">6 GB</option>
                            <option value="8">8 GB</option>
                            <option value="12">12 GB</option>
                            <option value="16">16 GB</option>
                            <option value="other">Other</option>
                        </select>
                        <select class="form-select mb-2" name="Color" id="" required>
                            <option value="" selected disabled hidden>Color *</option>
                            <option value="black">Black</option>
                            <option value="blue">Blue</option>
                            <option value="bronze">Bronze</option>
                            <option value="gold">Gold</option>
                            <option value="gray">Gray</option>
                            <option value="green">Green</option>
                            <option value="orange">Orange</option>
                            <option value="pink">Pink</option>
                            <option value="purple">Purple</option>
                            <option value="red">Red</option>
                            <option value="roseGold">Rose Gold</option>
                            <option value="silver">Silver</option>
                            <option value="white">White</option>
                            <option value="yellow">Yellow</option>
                            <option value="other">other</option>
                        </select>
                        <select class="form-select mb-2" name="Sim" id="" placeholder='Sim' required>
                            <option value="" selected disabled hidden>Sim *</option>
                            <option value="dual">Dual</option>
                            <option value="single">Single</option>
                            <option value="esim">esim</option>
                            <option value="any">any</option>
                        </select>
                        <select class="form-select mb-2" name="Processor" id="" placeholder='Processor' required>
                            <option value="" selected disabled hidden>Processor *</option>
                            <option value="snapdrogon">SnapDragon</option>
                            <option value="exnosse">Exnosse</option>
                            <option value="any">Any</option>
                        </select>
                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                        <input type="text" name="Price" class="form-control mb-2" placeholder="Price *" required>
                        <input class="form-control" type="file" name="choosefile[]" id="choosefile[]" multiple=true>
                        <button type="submit" name="phones" class="form-control mt-3 btn btn-success">Post</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            function validateFileUpload() {
                var fileInput = document.getElementById('choosefile');
                var maxFiles = 5;

                if (fileInput.files.length > maxFiles) {
                    alert('You can only upload up to ' + maxFiles + ' files.');
                    return false;
                }

                return true;
            }
        </script>
    </body>

    </html>
<?php } ?>