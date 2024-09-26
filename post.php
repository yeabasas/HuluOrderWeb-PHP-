<?php
session_start();
include('dbcon.php');

// Check if GD library is available
if (!extension_loaded('gd')) {
    echo 'The GD library is not installed or enabled. Watermarking functionality will not be available.';
    exit;
}

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
    $ufId = $_SESSION['mtid'];
    $queryy = mysqli_query($con, "select * from user where ID = '$ufId'");
    $feedFetch = mysqli_fetch_array($queryy);
    $username = `$feedFetch[FirstName] $feedFetch[LastName]`;
    if (isset($_POST['phones'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $category = $_POST['Category'];
        $brand = $_POST['Brand'];
        $condition = $_POST['Condition'];
        $storage = $_POST['Storage'];
        $processor = $_POST['Processor'];
        $color = $_POST['Color'];
        $ram = $_POST['Ram'];
        $sim = $_POST['Sim'];
        $price = $_POST['Price'];
        $description = $_POST['Description'];

        // Check for required fields
        if (empty($itemName) || empty($condition) || empty($brand) || empty($category) || empty($storage) || empty($ram) || empty($sim) || empty($processor) || empty($color) || empty($price)) {
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
                        // Add watermark to the image using GD library
                        if (addWatermark($targetFile, 'Hulu Order')) {
                            // Save the filename to an array
                            $filenames[] = $filename;
                        } else {
                            echo '<script>alert("Error adding watermark to the file")</script>';
                        }
                    } else {
                        echo '<script>alert("Error moving uploaded file")</script>';
                    }
                }

                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `posts`(`userId`,`Category`, `Brand`, `ItemName`, `Condition`, `Storage`, `Ram`, `Sim`, `Description`, `Processor`, `Color`,`Price`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);
                mysqli_stmt_bind_param($stmt, "ssssssssssss", $userId, $category, $brand, $itemName, $condition, $storage, $ram, $sim, $description, $processor, $color, $price);
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
}

// Function to add watermark using GD
function addWatermark($filePath, $watermarkText)
{
    $imageType = exif_imagetype($filePath);
    $image = null;

    // Create an image resource from file based on the type
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            $image = imagecreatefromjpeg($filePath);
            break;
        case IMAGETYPE_PNG:
            $image = imagecreatefrompng($filePath);
            break;
        case IMAGETYPE_GIF:
            $image = imagecreatefromgif($filePath);
            break;
        default:
            return false; // Unsupported file type
    }

    if (!$image) {
        return false;
    }

    // Define the color for the watermark text (white)
    $textColor = imagecolorallocate($image, 255, 255, 255);

    $fontSize = 30; // Size of the font
    $x = (imagesx($image) - (imagefontwidth($fontSize) * strlen($watermarkText))) / 2; // Center horizontally
    $y = imagesy($image) * 0.9; // 90% height from the top

    // Set the text values
    $username = "Username"; // Replace with actual username variable
    $watermarkText = "Posted on HuluOrder";

    // Calculate the horizontal center
    $imageWidth = imagesx($image);
    $usernameWidth = imagefontwidth($fontSize) * strlen($username);
    $watermarkWidth = imagefontwidth($fontSize) * strlen($watermarkText);

    $xUsername = ($imageWidth - $usernameWidth) / 2; // Center horizontally for username
    $xWatermark = ($imageWidth - $watermarkWidth) / 2; // Center horizontally for "Posted on HuluOrder"

    // Calculate the vertical position
    $y = imagesy($image) * 0.9; // 90% height from the top

    // Draw the username slightly above the watermark text
    imagestring($image, $fontSize, $xUsername, $y - 15, $username, $textColor); // Username is drawn first, 15px above the base
    imagestring($image, $fontSize, $xWatermark, $y, $watermarkText, $textColor); // "Posted on HuluOrder" is drawn at the base

    // Save the watermarked image back to the file
    switch ($imageType) {
        case IMAGETYPE_JPEG:
            imagejpeg($image, $filePath);
            break;
        case IMAGETYPE_PNG:
            imagepng($image, $filePath);
            break;
        case IMAGETYPE_GIF:
            imagegif($image, $filePath);
            break;
    }

    // Free up memory
    imagedestroy($image);

    return true;
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
</head>

<body>
    <?php include('header.php') ?>
    <div class="container justify-content-center mx-auto pt-5">
        <div class="postAdd">
            <h2 class="header-txt">Post Phones</h2>
            <form method="post" action="" class="form-control" enctype="multipart/form-data">
                <div class="d-inline-block">
                    <select class="form-select mb-2" name="Category" id="" placeholder='Role' required>
                        <option value="" selected disabled hidden>Categories *</option>
                        <option value="Phone">Phone</option>
                        <option value="Tablet">Tablet</option>
                        <option value="Computer">Computer</option>
                    </select>
                    <select class="form-select mb-2" name="Brand" id="brand" placeholder='Role' required>
                        <option value="" selected disabled hidden>Brand *</option>
                    </select>
                    <select class="form-select mb-2" name="ItemName" id="model" placeholder='Model' required>
                        <option value="" selected disabled hidden>Model *</option>
                    </select>
                    <select class="form-select mb-2" name="Condition" id="" placeholder='Role' required>
                        <option value="" selected disabled hidden>Condition *</option>
                        <option value="brand new">Brand New</option>
                        <option value="used">Used</option>
                        <option value="repack">Repack</option>
                        <option value="refurbished">Refurbished</option>
                    </select>
                    <select class="form-select mb-2" name="Storage" id="" required>
                        <option value="" selected disabled hidden>Storage *</option>
                        <option value="16 GB">16 GB</option>
                        <option value="32 GB">32 GB</option>
                        <option value="64 GB">64 GB</option>
                        <option value="128 GB">128 GB</option>
                        <option value="256 GB">256 GB</option>
                        <option value="512 GB">512 GB</option>
                        <option value="1 TB ">1 TB</option>
                    </select>
                    <select class="form-select mb-2" name="Ram" id="" placeholder='Ram' required>
                        <option value="" selected disabled hidden>Ram *</option>
                        <option value="3 GB">3 GB</option>
                        <option value="4 GB">4 GB</option>
                        <option value="6 GB">6 GB</option>
                        <option value="8 GB">8 GB</option>
                        <option value="12 GB">12 GB</option>
                        <option value="16 GB">16 GB</option>
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
                        <option value="snapdragon">SnapDragon</option>
                        <option value="exynos">Exynos</option>
                        <option value="any">Any</option>
                    </select>
                    <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                    <input type="text" name="Price" class="form-control mb-2" placeholder="Price *" required>
                    <input class="form-control" type="file" name="choosefile[]" id="choosefile" multiple=true>
                    <button type="submit" name="phones" class="form-control mt-3 btn btn-success">Post</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Fetch and populate the brand options
            fetch('./public/BrandList.json')
                .then(response => response.json())
                .then(data => {
                    const brandSelect = document.getElementById('brand');
                    data.forEach(brand => {
                        const option = document.createElement('option');
                        option.value = brand;
                        option.textContent = brand;
                        brandSelect.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching brand data:', error));

            // Fetch and store the models data
            let devicesData = {};
            fetch('./public/Devices.json')
                .then(response => response.json())
                .then(data => {
                    devicesData = data;
                })
                .catch(error => console.error('Error fetching device data:', error));

            // Update model options based on selected brand
            document.getElementById('brand').addEventListener('change', function() {
                const brand = this.value;
                const modelSelect = document.getElementById('model');
                modelSelect.innerHTML = '<option value="" selected disabled hidden>Model *</option>'; // Reset options

                if (devicesData[brand]) {
                    devicesData[brand].forEach(model => {
                        const option = document.createElement('option');
                        option.value = model;
                        option.textContent = model;
                        modelSelect.appendChild(option);
                    });
                }
            });
        });

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