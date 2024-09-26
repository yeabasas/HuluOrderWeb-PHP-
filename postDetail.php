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
error_log('$_GET: ' . print_r($_GET, true));
$receiverId = isset($_GET['mid']) ? $_GET['mid'] : null;
error_log('$receiverId: ' . $receiverId);

$postId = $_GET['id']; // Replace 'post_id' with the actual parameter you use
if (isset($_POST['feed'])) {
    $userId = $_SESSION['mtid'];
    $feedback = $_POST['feedback'];
    $rate = $_POST['rating'];
    $successful = '<script>alert("Feedback sent successfully")</script>';
    $failed = '<script>alert("Error Sending")</script>';

    // Check for required fields
    if (empty($feedback) || empty($rate)) {
        echo '<script>alert("Required fields are not filled");</script>';
    } else {

        // Use prepared statements to prevent SQL injection
        $sql = "INSERT INTO `feedback`(`UserId`, `PostId`, `Rate`, `Feedback`) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $sql);

        // Bind parameters
        mysqli_stmt_bind_param($stmt, "ssss", $userId, $postId, $rate, $feedback);

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
    }
}
if (!isset($_SESSION['viewed_posts'][$postId])) {
    // Fetch post details
    $sql = "SELECT * FROM posts WHERE ID = $postId";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();

        // Increment view count
        $newViewCount = $post['View'] + 1;
        $updateSql = "UPDATE posts SET View = $newViewCount WHERE ID = $postId";
        $con->query($updateSql);

        $_SESSION['viewed_posts'][$postId] = true;
    } else {
        echo "Post not found";
        exit();
    }
    // Fetch ratings from the database
    $ratings = [];
    $sqlRate = "SELECT Rate FROM feedback WHERE PostId = $postId";
    $result = mysqli_query($con, $sqlRate);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ratings[] = $row['Rate'];
        }
    }

    // Calculate average rating
    $totalRating = array_sum($ratings);
    $count = count($ratings);
    $averageRating = $count === 0 ? 0 : $totalRating / $count;
    // Calculate sum of ratings
    $sumRating = $count;



    // Example feedback data (you can replace this with actual data)
    $sql = "SELECT Rate, COUNT(*) as count FROM feedback Where PostId =$postId GROUP BY Rate";
    $result = $con->query($sql);

    $barRating = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $total_ratings = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ratingss[$row['Rate']] = $row['count'];
            $total_ratings += $row['count'];
        }
    }
} else {
    // If the post has already been viewed in the current session, use the stored view count
    $sql = "SELECT * FROM posts WHERE ID = $postId";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        $newViewCount = $post['View'];
    }
    // Fetch ratings from the database
    $ratings = [];
    $sqlRate = "SELECT Rate FROM feedback WHERE PostId = $postId";
    $result = mysqli_query($con, $sqlRate);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ratings[] = $row['Rate'];
        }
    }

    // Calculate rating counts
    $ratingCounts = [0, 0, 0, 0, 0];
    foreach ($ratings as $rating) {
        if ($rating > 0) {
            $index = 5 - $rating;
            $ratingCounts[$index]++;
        }
    }

    // Calculate average rating
    $totalRating = array_sum($ratings);
    $count = count($ratings);
    $averageRating = $count === 0 ? 0 : $totalRating / $count;

    // Calculate sum of ratings
    $sumRating = $count;

    $sql = "SELECT Rate, COUNT(*) as count FROM feedback Where PostId =$postId GROUP BY Rate";
    $result = $con->query($sql);

    $barRating = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $total_ratings = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $ratingss[$row['Rate']] = $row['count'];
            $total_ratings += $row['count'];
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Post Detail</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="./images/1122.png" type="image/x-icon">
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
    <style>
        .stars {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .stars input {
            display: none;
        }

        .stars label {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }

        .stars input:checked~label,
        .stars label:hover,
        .stars label:hover~label {
            color: #f7d106;
        }

        .average-rating {
            font-size: 5rem;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div>
        <?php require 'header.php'; ?>
        <div class="container d-flex flex-column mt-4 justify-content-center w-90">
            <?php
            if (isset($_GET['id'])) {
                $itemId = $_GET['id'];
                $uId = $_SESSION['mtid'];
                $query = mysqli_query($con, "select * from posts where ID = '$itemId'");
                while ($fetch = mysqli_fetch_array($query)) {
            ?>
                    <div class="img-con mb-2">
                        <?php
                        $sqlImages = "SELECT image_filename FROM post_images WHERE post_id = ?";
                        $stmtImages = mysqli_prepare($con, $sqlImages);
                        mysqli_stmt_bind_param($stmtImages, "i", $postId);
                        mysqli_stmt_execute($stmtImages);
                        $resultImages = mysqli_stmt_get_result($stmtImages);

                        if ($rowImage = mysqli_fetch_assoc($resultImages)) {
                            $imageFilename = $rowImage['image_filename'];
                            echo '
                                <a href="images/' . $imageFilename . '">
                                    <div class="img-one card">
                                        <img src="images/' . $imageFilename . '" alt="" height="100%" width="100%">
                                    </div>
                                </a>
                                ';
                        }
                        mysqli_stmt_close($stmtImages);
                        ?>
                        <div class="img-sec-con">
                            <?php
                            $sqlImages = "SELECT image_filename FROM post_images WHERE post_id = ? LIMIT 1, 18446744073709551615";
                            $stmtImages = mysqli_prepare($con, $sqlImages);
                            mysqli_stmt_bind_param($stmtImages, "i", $postId);
                            mysqli_stmt_execute($stmtImages);
                            $resultImages = mysqli_stmt_get_result($stmtImages);

                            while ($rowImage = mysqli_fetch_assoc($resultImages)) {
                                $imageFilename = $rowImage['image_filename'];
                                echo '
                                <a href="images/' . $imageFilename . '">
                                    <div class="img-sec ">
                                        <img class="card m-2" src="images/' . $imageFilename . '" alt="" height="100%" width="100%">
                                    </div>
                                    </a>
                                    ';
                            }

                            mysqli_stmt_close($stmtImages);
                            ?>
                        </div>
                    </div>
                    <div class="post-details d-flex flex-column p-1 mb-2 card">
                        <div class="d-flex flex-row justify-content-around">
                            <h2><?php echo $fetch['ItemName']; ?></h2>
                            <h3>ETB <?php echo $fetch['Price']; ?></h3>
                        </div>
                        <div class="d-flex flex-row justify-content-around">
                            <p class="text-secondary fs-6"><?php echo date('F j, Y', strtotime($fetch['DateModified'])); ?></p>
                            <p class="text-secondary fs-6"><span>Views</span> <?php echo $newViewCount ?></p>
                        </div>
                    </div>
                    <div class="post-details d-flex flex-row p-1 mb-2 justify-content-around card">
                        <div class="post-details p-3 w-47">
                            <p><span>Condition</span> <?php echo $fetch['Condition']; ?></p>
                            <p><span>Sim</span> <?php echo $fetch['Sim']; ?></p>
                            <p><span>Ram</span> <?php echo $fetch['Ram']; ?></p>
                        </div>
                        <div class="post-details p-3 w-47">
                            <p><span>Processor</span> <?php echo $fetch['Processor']; ?></p>
                            <p><span>Color</span> <?php echo $fetch['Color']; ?></p>
                            <p><span>Storage</span> <?php echo $fetch['Storage']; ?></p>
                        </div>
                    </div>
                    <div class="post-details d-flex flex-row p-1 mb-2 justify-content-around card">
                        <div class="post-details p-3 w-47">
                            <p class="mt-3"><span>Description</span> <?php echo $fetch['Description']; ?> </p>
                        </div>
                        <div class="post-details p-3 w-47 d-none">
                            <p class="mt-3"><span>Description</span> <?php echo $fetch['Description']; ?></p>
                        </div>
                    </div>
                    <div class="post-details d-flex flex-column p-1 justify-content-around mb-2 card">
                        <?php
                        $uId = $fetch['userId'];
                        $query = mysqli_query($con, "select * from user where ID = '$uId'");
                        while ($fetches = mysqli_fetch_array($query)) {
                        ?>
                            <div class="d-flex justify-content-around">
                                <a href="messageDetail.php?uId=<?php echo $fetch['userId'] ?>&iId=<?php echo $itemId ?>" class="btn btn-primary"><i class="fa-regular fa-message"></i> Message</a>
                                <button id="callBtn" onclick="toggleDiv()" class="btn btn-success"><i class="fa-solid fa-phone"></i> Call</button>
                            </div>
                            <div id="PhoneNo" class="justify-content-around mt-3" style="display: none;">
                                <p></p>
                                <div class="d-flex">
                                    <p id="textToCopy" style="cursor: pointer;justify-content:center;align-items:center;"> <?php echo $fetches['Phone'] ?> </p>
                                    <a style="cursor: pointer;margin-left: 10px" onclick="copyText()"><i class="fa-regular fa-copy"></i></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- Rating Section -->
                    <div class="post-details d-flex flex-column p-1 justify-content-around mb-2 card">
                        <?php
                        $uId = $fetch['userId'];
                        $query = mysqli_query($con, "select * from user where ID = '$uId'");
                        while ($fetches = mysqli_fetch_array($query)) {
                        ?>
                            <div class="d-flex align-middle px-5 py-3">
                                <div class="d-flex flex-row" style="align-items: center;">
                                    <img src="images/No_image_available.svg.png" alt="" width="40" height="40" style="border-radius: 50%;">
                                    <div>
                                        <p class="align-middle mx-2 m-0"><?php echo $fetches["FirstName"] ?> <?php echo $fetches["LastName"] ?></p>
                                        <?php if ($fetches["Verified"]) {
                                            echo '
                                            <div class="d-flex flex-row mx-2">
                                                <p class="align-middle m-0 px-1" style="font-size: x-small; margin-right: 5px">Hulu Verified</p>
                                                <img  src="../images/icons8-verified-badge-48.png" height="15px" width="15px" srcset="">
                                            </div>
                                            ';
                                        } else {
                                            echo "";
                                        } ?>
                                    </div>
                                </div>
                            </div>
                            <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <h3 class="px-5">Rate & Review</h3>
                            </a>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h3>Rate this seller</h3>
                                            <p style="font-weight: 100; font-size:medium;color:#4d4d4d;margin:0">Tell others what you think</p>
                                            <form action="" method="POST">
                                                <div class="stars">
                                                    <input type="radio" name="rating" id="star-5" value="5"><label for="star-5">★</label>
                                                    <input type="radio" name="rating" id="star-4" value="4"><label for="star-4">★</label>
                                                    <input type="radio" name="rating" id="star-3" value="3"><label for="star-3">★</label>
                                                    <input type="radio" name="rating" id="star-2" value="2"><label for="star-2">★</label>
                                                    <input type="radio" name="rating" id="star-1" value="1"><label for="star-1">★</label>
                                                </div>
                                                <?php
                                                $uId = $fetch['ID'];
                                                $query = mysqli_query(
                                                    $con,
                                                    "SELECT feedback.*,user.FirstName,user.LastName,user_images.image_filename,user_images.userId FROM feedback JOIN user ON feedback.UserId = user.ID LEFT JOIN user_images ON user_images.userId = user.ID WHERE feedback.PostId = '$uId' ORDER BY DateModified DESC"
                                                );
                                                while ($feedback = mysqli_fetch_array($query)) {
                                                ?>
                                                    <div class="mx-3">
                                                        <div class="d-flex flex-row" style="align-items: center;">
                                                            <?php
                                                            $ufId = $feedback['UserId'];
                                                            $queryy = mysqli_query($con, "select * from user where ID = '$ufId'");
                                                            while ($feedFetch = mysqli_fetch_array($queryy)) {
                                                            ?>
                                                                <img src="images/No_image_available.svg.png" alt="" width="40" height="40" style="border-radius: 50%;">
                                                                <div>
                                                                    <p class="align-middle mx-2 m-0" style="color: gray;"><?php echo $feedFetch["FirstName"] ?> <?php echo $feedFetch["LastName"] ?></p>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                        <p class="px-5"><?php echo $feedback['Feedback'] ?></p>
                                                    </div>
                                                <?php } ?>
                                                <div class=" d-grid gap-2 w-100 d-md-flex justify-content-around align-items-end">
                                                    <textarea id="comments" class="multi-line-textarea w-75 border-1 p-2" name="feedback" placeholder="Leave your feedback" rows="3"></textarea>
                                                    <button class="feedbackButton" type="submit" name="feed">Submit </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div style="display: flex; align-items:center; justify-content:space-around; margin:auto; padding:0 20px;width:100%">
                                <div id="rating-display" class=" d-flex flex-column justify-content-center" style="width:40%;">
                                    <div id="average-rating" class="average-rating" style="margin:auto"><?php echo number_format($averageRating, 1); ?></div>
                                    <div id="star-container" class="star-container" style="margin:auto">
                                        <?php
                                        for ($i = 0; $i < round($averageRating); $i++) {
                                            echo '<span style="color: #243b2e;">★</span>'; // Filled star
                                        }
                                        for ($i = round($averageRating); $i < 5; $i++) {
                                            echo '<span style="color: #243b2e;">☆</span>'; // Empty star
                                        }
                                        ?>
                                    </div>
                                    <div id="sum-rating" style="margin: auto;"><?php echo number_format($sumRating); ?></div>
                                </div>
                                <div class="rating-container">
                                    <?php
                                    for ($i = 1; $i <= 5; $i++) {
                                        $percentage = ($total_ratings > 0) ? ($ratingss[$i] / $total_ratings) * 100 : 0;
                                        echo "<div class='rating-item'>
                            <div class='rating-label'>$i</div>
                            <div class='rating-bar-container'>
                                <div class='rating-bar rating-bar-$i' style='width: $percentage%;'></div>
                            </div>
                        </div>";
                                    }
                                    ?>
                                </div>
                            </div>
                            <h5 class="px-5 mt-5">Feedbacks</h5>
                            <?php
                            $uId = $fetch['ID'];
                            $query = mysqli_query(
                                $con,
                                "SELECT feedback.*,user.FirstName,user.LastName,user_images.image_filename,user_images.userId FROM feedback JOIN user ON feedback.UserId = user.ID LEFT JOIN user_images ON user_images.userId = user.ID WHERE feedback.PostId = '$uId' ORDER BY DateModified DESC LIMIT 3"
                            );
                            while ($feedback = mysqli_fetch_array($query)) {
                            ?>
                                <div class="mx-5">
                                    <div class="d-flex flex-row mx-3" style="align-items: center;">
                                        <?php
                                        $ufId = $feedback['UserId'];
                                        $queryy = mysqli_query($con, "select * from user where ID = '$ufId'");
                                        while ($feedFetch = mysqli_fetch_array($queryy)) {
                                        ?>
                                            <img src="images/No_image_available.svg.png" alt="" width="40" height="40" style="border-radius: 50%;">
                                            <div>
                                                <p class="align-middle mx-2 m-0" style="color: gray;"><?php echo $feedFetch["FirstName"] ?> <?php echo $feedFetch["LastName"] ?></p>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <p class="px-5"><?php echo $feedback['Feedback'] ?></p>
                                </div>
                            <?php } ?>
                    </div>
                <?php } ?>
        </div>
        <div id="alert" class="alert alert-success" style="display: none; z-index:1000; position:fixed; bottom:20px;right:30px" role="alert">
            Phone number Copied!
        </div>
<?php }
            } ?>
    </div>
    </div>
    <script>
        function toggleDiv() {
            var myDiv = document.getElementById("PhoneNo");

            if (myDiv.style.display === "none" || myDiv.style.display === "") {
                myDiv.style.display = "flex";
                copyText();
            } else {
                myDiv.style.display = "none";
                alert.style.display = "none";
            }
        }

        function copyText() {
            var currentTime = new Date().getSeconds();
            var isWithinTimeBand = currentTime >= 0 && currentTime < 5;
            var alert = document.getElementById("alert");
            setTimeout(function() {
                alert.style.display = "none";
            }, 5000);
            var textToCopy = document.getElementById("textToCopy");

            var inputElement = document.createElement("input");
            inputElement.value = textToCopy.innerText;

            document.body.appendChild(inputElement);

            inputElement.select();
            inputElement.setSelectionRange(0, 99999);

            document.execCommand("copy");

            document.body.removeChild(inputElement);

            alert.style.display = "flex";
        }
    </script>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist"></script>
</body>

</html>