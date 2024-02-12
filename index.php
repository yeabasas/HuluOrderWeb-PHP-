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
?>
<!DOCTYPE html>
<html lang="en">

<html>

<head>
    <title>Hulu Order</title>
    <link rel="stylesheet" href="./global.css" />
    <link rel="stylesheet" href="./index.css" />
    <meta charset="UTF-8">
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
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var floatingButton = document.getElementById("floatingButton");
        var lastScrollTop = 0;

        function hideShowFloatingButton() {
            var st = window.scrollY || document.documentElement.scrollTop;

            if (st > lastScrollTop) {
                // Scroll down, hide the button
                floatingButton.style.transform = "translateX(100%)";
            } else {
                // Scroll up, show the button
                floatingButton.style.transform = "translateX(-10%)";
            }

            lastScrollTop = st <= 0 ? 0 : st;
        }

        window.addEventListener("scroll", hideShowFloatingButton);
    });
</script>
</head>

<body>
    <?php include('header.php') ?>
    <div class="cover-index">
        <div class="header-index">
            <div class="search">
                <form action="" method="GET">
                    <!-- <i class="fa-solid fa-magnifying-glass" style="position: relative;color:#000"></i> -->
                    <input type="text" name="search" id="search" placeholder="search" style="padding-left: 30px;">
                    <button type="submit" class="search-btn">search</button>
                </form>
            </div>
        </div>
        <div class="categories">
            <div class="cat-icons">
                <div class="cat-icons-con"></div>
                <p class="cat-icons-txt">Phones</p>
            </div>
            <div class="cat-icons">
                <div class="cat-icons-con"></div>
                <p class="cat-icons-txt">Phones</p>
            </div>
            <div class="cat-icons">
                <div class="cat-icons-con"></div>
                <p class="cat-icons-txt">Phones</p>
            </div>
            <div class="cat-icons">
                <div class="cat-icons-con"></div>
                <p class="cat-icons-txt">Phones</p>
            </div>
        </div>
        <div class="grid-con-index">
            <div class="grid-wrapper-index">
                <?php

                // Check if the search form is submitted
                if (isset($_GET['search'])) {
                    $searchTerm = mysqli_real_escape_string($con, $_GET['search']);
                    $sql = "SELECT * FROM posts WHERE ItemName LIKE '%$searchTerm%'";
                } else {
                    // If not submitted, fetch all items
                    $sql = "SELECT * FROM posts";
                }

                $result2 = mysqli_query($con, $sql);

                while ($fetch = mysqli_fetch_assoc($result2)) {
                ?>
                    <div class="posts">
                        <a href="postDetail.php?id=<?php echo $fetch['ID'] ?>&uId=<?php echo $fetch['userId'] ?>" id="updateLink">
                            <div class="posts-img">
                                <?php
                                $postId = $fetch['ID'];
                                $sqlImages = "SELECT image_filename FROM post_images WHERE post_id = ?";
                                $stmtImages = mysqli_prepare($con, $sqlImages);
                                mysqli_stmt_bind_param($stmtImages, "i", $postId);
                                mysqli_stmt_execute($stmtImages);
                                $resultImages = mysqli_stmt_get_result($stmtImages);
                                if ($rowImage = mysqli_fetch_assoc($resultImages)) {
                                    $imageFilename = $rowImage['image_filename'];
                                    echo '
                                    <img src="images/' . $imageFilename . '" alt="" height="100%" width="100%">
                                    ';
                                }
                                mysqli_stmt_close($stmtImages);
                                ?>
                            </div>
                            <div class="posts-txt-btm">
                                <p class="posts-txt"><?php echo $fetch['ItemName'] ?></p>
                                <p class="posts-txt"><span>ETB </span><?php echo $fetch['Price'] ?></p>
                            </div>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
        <a href="./post.php">
            <div id="floatingButton" class="floating">
                <p class="floating-txt">Any thing to sell?</p>
                <p class="floating-txt">Post Now</p>
            </div>
        </a>
        <?php include('footer.php') ?>
    </div>
</body>

</html>