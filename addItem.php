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
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($condition) || empty($storage) || empty($ram) || empty($sim) || empty($processor) || empty($color)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Condition`, `Storage`, `Ram`, `Sim`, `Description`,`Processor`,`Color`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "ssssssssss", $filename, $userId, $itemName, $condition, $storage, $ram, $sim, $description, $processor, $color);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['tablets'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $condition = $_POST['Condition'];
        $storage = $_POST['Storage'];
        $color = $_POST['Color'];
        $ram = $_POST['Ram'];
        $size = $_POST['Size'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($condition) || empty($storage) || empty($ram) || empty($color) || empty($size)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Condition`, `Storage`, `Ram`, `Description`,`Color`,`Size`) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "sssssssss", $filename, $userId, $itemName, $condition, $storage, $ram, $description, $color, $size);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['watches'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $condition = $_POST['Condition'];
        $color = $_POST['Color'];
        $size = $_POST['Size'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($condition) || empty($color) || empty($size)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Condition`, `Description`,`Color`,`Size`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "sssssss", $filename, $userId, $itemName, $condition, $description, $color, $size);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['case'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $color = $_POST['Color'];
        $description = $_POST['Description'];
        $size = $_POST['Size'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($color) || empty($size)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Description`,`Color`,`Size`) VALUES ( ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "ssssss", $filename, $userId, $itemName, $description, $color, $size);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['headset'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $color = $_POST['Color'];
        $type = $_POST['Type'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($color) || empty($type)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Description`,`Color`, `Type`) VALUES ( ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "ssssss", $filename, $userId, $itemName, $description, $color, $type);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['holder'])) {
        $userId = $_SESSION['mtid'];
        $itemName = 'Phone Holder';
        $type = $_POST['Type'];
        $size = $_POST['Size'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($type) || empty($Description)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`,`Description`,`Color`) VALUES ( ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "sssss", $filename, $userId, $itemName, $description, $color);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['charger'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $type = isset($_POST['Type']) ? implode(',', $_POST['Type']) : '';
        $color = $_POST['Color'];
        $capacity = $_POST['Capacity'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($color) || empty($type) || empty($capacity)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Description`,`Color`,`Type`,`Capacity`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "sssssss", $filename, $userId, $itemName, $description, $color, $type, $capacity);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['light'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $type = $_POST['Type'];
        $size = $_POST['Size'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($type) || empty($size)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Description`,`Type`,`Size`) VALUES ( ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "ssssss", $filename, $userId, $itemName, $description, $type, $size);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error Uploading Image")</script>';
            // }
        }
    }
    if (isset($_POST['glass'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $type = $_POST['Type'];
        $size = $_POST['Size'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($size) || empty($type)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Description`,`Size`,`Type`) VALUES ( ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "ssssss", $filename, $userId, $itemName, $description, $size, $type);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }
    if (isset($_POST['other'])) {
        $userId = $_SESSION['mtid'];
        $itemName = $_POST['ItemName'];
        $itemUsage = $_POST['ItemUsage'];
        $itemSize = $_POST['ItemSize'];
        $itemColor = $_POST['ItemColor'];
        $description = $_POST['Description'];

        $filename = $_FILES["choosefile"]["name"];
        $tempfile = $_FILES["choosefile"]["tmp_name"];
        $folder = "images/" . $filename;

        // Check for required fields
        if (empty($itemName) || empty($itemUsage) || empty($itemSize) || empty($itemColor)) {
            echo '<script>alert("Required fields are not filled");</script>';
        } else {
            // Move the uploaded file to the destination directory
            // if (move_uploaded_file($tempfile, $folder)) {
                // Use prepared statements to prevent SQL injection
                $sql = "INSERT INTO `items`(`Image`, `userId`, `ItemName`, `Description`,`Color`,`Size`,`Usage`) VALUES ( ?, ?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $sql);

                // Bind parameters
                mysqli_stmt_bind_param($stmt, "sssssss", $filename, $userId, $itemName, $description, $itemColor, $itemSize, $itemUsage);

                // Execute the statement
                $result = mysqli_stmt_execute($stmt);

                // Check for SQL errors
                if ($result) {
                    echo $successful;
                } else {
                    echo $failed;
                }

                // Close the statement
                mysqli_stmt_close($stmt);
            // } else {
            //     echo '<script>alert("Error moving uploaded file")</script>';
            // }
        }
    }

?>
    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>
    <script>
        $(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
    <h1 class="text-center mb-4">Custom Order</h1>
    <div id="accordion" class="text-decoration-none form-control">
        <div class="card ">
            <button id="headingOne" class="card-header btn btn-light" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                <h5 class=" text-start mb-0">
                    Mobile Phones
                </h5>
            </button>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                <div class="card-body">
                    <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
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
                            <lable for="choosefile">Image (Recommended)</lable>
                            <input class="form-control" type="file" name="choosefile" id="choosefile">
                            <button type="submit" name="phones" class="form-control mt-3 btn btn-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <button id="headingTwo" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                <h5 class="text-start mb-0">
                    Tablets
                </h5>
            </button>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="card-body">
                    <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                        <div class="d-inline-block w-50%">
                            <input class="form-control mb-2" type="text" placeholder="Model Name *" name="ItemName" required>
                            <select class="form-select mb-2" name="Condition" id="" placeholder='Role' required>
                                <option value="" selected disabled hidden>Condition *</option>
                                <option value="85%-90%">85%-90%</option>
                                <option value="90%-95%">90%-95%</option>
                                <option value="95%-100%">95%-100%</option>
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
                            </select>
                            <select class="form-select mb-2" name="Color" id="" placeholder='Color' required>
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
                            <select class="form-select mb-2" name="Size" id="" placeholder='Size' required>
                                <option value="" selected disabled hidden>Size (inch) *</option>
                                <option value="small">Small 8' </option>
                                <option value="medium">medium 10'-12'</option>
                                <option value="large">Large 12'-14'</option>
                                <option value="xlarge">Very Large 14'</option>
                            </select>
                            <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                            <lable for="choosefile">Image (Recommended)</lable>
                            <input class="form-control" type="file" name="choosefile" id="choosefile">
                            <button type="submit" name="tablets" class="form-control mt-3 btn btn-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <button id="headingThree" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                <h5 class="text-start mb-0">
                    Smart Watches
                </h5>
            </button>
            <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                <div class="card-body">
                    <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                        <div class="d-inline-block w-50%">
                            <input class="form-control mb-2" type="text" placeholder="Watch Model *" name="ItemName" required>
                            <select class="form-select mb-2" name="Condition" id="" placeholder='Condition' required>
                                <option value="" selected disabled hidden>Condition *</option>
                                <option value="85%-90%">85%-90% New</option>
                                <option value="90%-95%">90%-95% New</option>
                                <option value="95%-100%">95%-100% New</option>
                            </select>
                            <select class="form-select mb-2" name="Size" id="" required>
                                <option value="" selected disabled hidden>Size *</option>
                                <option value="Small">Small/Compact 38mm</option>
                                <option value="medium">Medium 38-42mm</option>
                                <option value="large">Large 42-46mm</option>
                                <option value="xlarge">Extra large 46mm</option>
                            </select>
                            <select class="form-select mb-2" name="Color" id="" placeholder='Color' required>
                                <option value="" selected disabled hidden>Bandage Color *</option>
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
                            <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                            <lable for="choosefile">Image (Recommended)</lable>
                            <input class="form-control" type="file" name="choosefile" id="choosefile">
                            <button type="submit" name="watches" class="form-control mt-3 btn btn-success">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card">
            <button id="headingFour" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                <h5 class="text-start mb-0">
                    Accessories
                </h5>
            </button>
            <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordion">
                <div class="card-body" id="accordion1">
                    <div class="card">
                        <button id="headingFive" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                            <h5 class="text-start mb-0">
                                Phone case
                            </h5>
                        </button>
                        <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                                    <div class="d-inline-block w-50%">
                                        <input class="form-control mb-2" type="text" placeholder="Model *" name="ItemName" required>

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
                                        <select class="form-select mb-2" name="Size" id="" placeholder='Amount' required>
                                            <option value="" selected disabled hidden>Amount *</option>
                                            <option value="5">5</option>
                                            <option value="6">6</option>
                                            <option value="7">7</option>
                                            <option value="8">8</option>
                                            <option value="9">9</option>
                                            <option value="10">10</option>
                                        </select>
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <lable for="choosefile">Image (Recommended)</lable>
                                        <input class="form-control" type="file" name="choosefile" id="choosefile">
                                        <button type="submit" name="case" class="form-control mt-3 btn btn-success">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <button id="headingSix" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                            <h5 class="text-start mb-0">
                                Headset / Earbuds
                            </h5>
                        </button>
                        <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                                    <div class="d-inline-block w-50%">
                                        <select class="form-select mb-2" name="Type" id="" required>
                                            <option value="" selected disabled hidden>Type *</option>
                                            <option value="headset">Headset</option>
                                            <option value="earbuds">Earbuds</option>
                                        </select>
                                        <input class="form-control mb-2" type="text" placeholder="Model *" name="ItemName" required>
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
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <lable for="choosefile">Image (Recommended)</lable>
                                        <input class="form-control" type="file" name="choosefile" id="choosefile">
                                        <button type="submit" name="headset" class="form-control mt-3 btn btn-success">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    <div class="card">
                        <button id="headingTen" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                            <h5 class="text-start mb-0">
                                Lights
                            </h5>
                        </button>
                        <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                                    <div class="d-inline-block w-50%">
                                        <select class="form-select mb-2" name="Type" id="" required>
                                            <option value="" selected disabled hidden>Type *</option>
                                            <option value="ring light">Ring Light</option>
                                            <option value="led light">Led light</option>
                                            <option value="other">Other</option>
                                        </select>
                                        <select class="form-select mb-2" name="Size" id="" required>
                                            <option value="" selected disabled hidden>Size *</option>
                                            <option value="10'">10''</option>
                                            <option value="12'">12''</option>
                                            <option value="14'">14''</option>
                                            <option value="16'">16''</option>
                                            <option value="18'">18''</option>
                                        </select>
                                        <input class="form-control mb-2" type="text" placeholder="Model" name="ItemName">
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <lable for="choosefile">Image (Recommended)</lable>
                                        <input class="form-control" type="file" name="choosefile" id="choosefile">
                                        <button type="submit" name="light" class="form-control mt-3 btn btn-success">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>






                    <div class="card">
                        <button id="headingSeven" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                            <h5 class="text-start mb-0">
                                Phone Holder
                            </h5>
                        </button>
                        <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                                    <div class="d-inline-block w-50%">
                                    <input class="form-control mb-2" type="text" placeholder="Model *" name="ItemName" required>
                                        <select class="form-select mb-2" name="Type" id="" placeholder='Type' required>
                                            <option value="" selected disabled hidden>Type *</option>
                                            <option value="Selfie Stick">Selfie Stick</option>
                                            <option value="Stabilizer">Stabilizer</option>
                                            <option value="phone holder">Phone holder</option>
                                        </select>
                                        <input class="form-control mb-2" type="text" placeholder="Size" name="Size" >
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <lable for="choosefile">Image (Recommended)</lable>
                                        <input class="form-control" type="file" name="choosefile" id="choosefile">
                                        <button type="submit" name="holder" class="form-control mt-3 btn btn-success">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <button id="headingEleven" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                            <h5 class="text-start mb-0">
                                Safety Glass
                            </h5>
                        </button>
                        <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                                    <div class="d-inline-block w-50%">
                                        <input class="form-control mb-2" type="text" placeholder="Model *" name="ItemName" required>
                                        <select class="form-select mb-2" name="Type" id="" required>
                                            <option value="" selected disabled hidden>Type *</option>
                                            <option value="full glue">Full glue</option>
                                            <option value="uv">UV</option>
                                            <option value="privacy">Privacy</option>
                                        </select>
                                        <select class="form-select mb-2" name="Size" id="" required>
                                            <option value="" selected disabled hidden>Amount *</option>
                                            <option value="10">10</option>
                                            <option value="20">20</option>
                                            <option value="30">30</option>
                                            <option value="40">40</option>
                                            <option value="50">50</option>
                                        </select>
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <lable for="choosefile">Image (Recommended)</lable>
                                        <input class="form-control" type="file" name="choosefile" id="choosefile">
                                        <button type="submit" name="glass" class="form-control mt-3 btn btn-success">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <button id="headingEight" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                            <h5 class="text-start mb-0">
                                Charger
                            </h5>
                        </button>
                        <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                                    <div class="d-inline-block w-50%">
                                        <input class="form-control mb-2" type="text" placeholder="Model *" name="ItemName" required>
                                        <select class="form-select mb-2" name="Capacity" id="" required>
                                            <option value="" selected disabled hidden>Choice *</option>
                                            <option value="cable">Cable</option>
                                            <option value="box">Box</option>
                                            <option value="both">Both</option>
                                        </select>
                                        <select class="form-select mb-2" name="Color" id="" required>
                                            <option value="" selected disabled hidden>Color *</option>
                                            <option value="black">Black</option>
                                            <option value="white">White</option>
                                            <option value="other">other</option>
                                        </select>
                                        <select class="mb-2 border border-light selectpicker" name="Type[]" id="" title='Type *' multiple data-none-selected-text="Select Colors" required style="background-color: white;">
                                            <option value="normal">Normal</option>
                                            <option value="fast">Fast</option>
                                            <option value="Ctype">C-type</option>
                                            <option value="Btype">B-type</option>
                                            <option value="iphone">iphone</option>
                                            <option value="25W">25W</option>
                                            <option value="30W">30W</option>
                                            <option value="45W">45W</option>
                                            <option value="other">other</option>
                                        </select>
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <lable for="choosefile">Image (Recommended)</lable>
                                        <input class="form-control" type="file" name="choosefile" id="choosefile">
                                        <button type="submit" name="charger" class="form-control mt-3 btn btn-success">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <button id="headingNine" class="card-header btn btn-light collapsed" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                            <h5 class="text-start mb-0">
                                Others
                            </h5>
                        </button>
                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordion1">
                            <div class="card-body">
                                <form method="post" action="dashboard.php" class="form-control" enctype="multipart/form-data" style="text-decoration: none; border:none;">
                                    <div class="d-inline-block w-50%">
                                        <input class="form-control mb-2" type="text" placeholder="Item Name" name="ItemName" required>
                                        <input class="form-control mb-2" type="text" placeholder="Item Usage" name="ItemName" required>
                                        <input class="form-control mb-2" type="text" placeholder="Item Size" name="ItemName" required>
                                        <input class="form-control mb-2" type="text" placeholder="Item Color" name="ItemName" required>
                                        <textarea class="form-control mb-2" type="text" placeholder="Description" name="Description"></textarea>
                                        <lable for="choosefile">Image (Recommended)</lable>
                                        <input class="form-control" type="file" name="choosefile" id="choosefile">
                                        <button type="submit" name="other" class="form-control mt-3 btn btn-success">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>