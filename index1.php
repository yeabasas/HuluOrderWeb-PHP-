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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
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
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
</head>

<body>
    <div style="display: flex;flex-direction: column;flex-grow: 1;">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex flex-column" style="height: max-content;">
            <div class="container-fluid d-flex flex-row justify-content-between">
                <div>
                    <a href="index.php" style="text-decoration: none; color:#fff; display:'flex';flex-direction:'row';">
                        <div class="logo-con">
                            <div class="logo">
                                <img alt="" src="./images/1122.png" height="70px" width="70px" />
                            </div>
                            <div class="logos">
                                <h2 class="order">Hulu</h2>
                                <p class="order">Order</p>
                            </div>
                        </div>
                    </a>
                </div>
                <div>
                    <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link" href="#" id="navbarDarkDropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                    <?php
                                    $ufId = $_SESSION['mtid'];
                                    $queryy = mysqli_query($con, "select * from user where ID = '$ufId'");
                                    while ($feedFetch = mysqli_fetch_array($queryy)) {
                                    ?>
                                        <div class="d-flex flex-row align-center justify-items-center">
                                            <img src="images/No_image_available.svg.png" alt="" width="40" height="40" style="border-radius: 50%;">
                                            <div>
                                                <p class="align-middle mx-2 m-0" style="color: gray;"><?php echo $feedFetch["FirstName"] ?> <?php echo $feedFetch["LastName"] ?></p>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </a>
                                <?php if ($_SESSION('mtid')) {
                                    echo `
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                                        <li><a class="dropdown-item" <?php if ($current_page == 'index.php') echo 'class="active"'; ?> href="index.php">Home</a></li>
                                        <li><a class="dropdown-item" <?php if ($current_page == 'dashboard.php') echo 'class="active"'; ?> href="dashboard.php">Order</a></li>
                                        <li><a class="dropdown-item" <?php if ($current_page == 'message.php') echo 'class="active"'; ?> href="message.php">Message</a></li>
                                        <li><a class="dropdown-item" <?php if ($current_page == 'history.php') echo 'class="active"'; ?> href="history.php">History</a></li>
                                        <li><a class="dropdown-item" <?php if ($current_page == 'profile.php') echo 'class="active"'; ?> href="profile.php">Profile</a></li>
                                        <li><a class="dropdown-item" <?php if ($current_page == 'verify.php') echo 'class="active"'; ?> href="verify.php">Verify</a></li>
                                        <li><a class="dropdown-item" <?php if ($current_page == 'logout.php') echo 'class="active"'; ?> href="logout.php">Logout</a></li>
                                    </ul>`;
                                } else {
                                    echo `<li><a class="dropdown-item" <?php if ($current_page == 'login.php') echo 'class="active"'; ?> href="login.php">Login</a></li>`;
                                } ?>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div style="width: 80%;">
                <div class="header-index">
                    <form action="" method="GET">
                        <input type="text" id="search-input" name="search" placeholder="search by Name" style="padding-left: 30px;">
                        <button type="submit" class="search-btn">search</button>
                    </form>
                </div>
                <div class="categories">
                    <div class="cat-icons">
                        <div class="cat-icons-con">
                            <img src="./images/phone.png" alt="" width="55px" height="55px" style="justify-self: center;margin:auto">
                        </div>
                        <p class="cat-icons-txt">Phones</p>
                    </div>
                    <div class="cat-icons">
                        <div class="cat-icons-con">
                            <img src="./images/asset.png" alt="" width="55px" height="55px" style="justify-self: center;margin:auto">
                        </div>
                        <p class="cat-icons-txt">Tablet</p>
                    </div>
                    <div class="cat-icons">
                        <div class="cat-icons-con">
                            <img src="./images/asset.png" alt="" width="55px" height="55px" style="justify-self: center;margin:auto">
                        </div>
                        <p class="cat-icons-txt">Electronics</p>
                    </div>
                </div>
            </div>
        </nav>
        <main class="cover-index w-75 mx-auto" style="flex-grow: 1;">
            <div class="grid-con-index">
                <div class="grid-wrapper-index">
                    <?php
                    if (isset($_GET['search'])) {
                        $searchTerm = mysqli_real_escape_string($con, $_GET['search']);
                        $sql = "SELECT * FROM posts WHERE ItemName LIKE '%$searchTerm%'";
                    } else {
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
                                        echo '<img src="../images/' . $imageFilename . '" alt="" height="100%" width="100%" style="border-radius:10px 10px 0 0">';
                                    }
                                    mysqli_stmt_close($stmtImages);
                                    ?>
                                </div>
                                <div class="posts-txt-btm">
                                    <p class="posts-txt" style="color: blue;"><span>ETB </span><?php echo $fetch['Price'] ?></p>
                                    <p class="posts-txt"><?php echo $fetch['Brand'] ?> </p>
                                    <p class="posts-txt"><?php echo $fetch['ItemName'] ?></p>
                                    <div class="d-flex flex-row justify-content-between align-items-end">
                                        <p class="posts-txt-low"><?php echo $fetch['Condition'] ?></p>
                                        <p class="posts-txt-low"><?php echo $fetch['Verify'] ?></p>
                                    </div>
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
        </main>
        <footer class="" style="background: #394E42;color: white;padding: 10px;text-align: center;">
            <p class="footer-txt">HuluOrder&copy; | 2024</p>
            <a href="https://www.tiktok.com/@nati_mobile" class="text-decoration-none">
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="50px" height="50px">
                    <path d="M 9 4 C 6.2495759 4 4 6.2495759 4 9 L 4 41 C 4 43.750424 6.2495759 46 9 46 L 41 46 C 43.750424 46 46 43.750424 46 41 L 46 9 C 46 6.2495759 43.750424 4 41 4 L 9 4 z M 9 6 L 41 6 C 42.671576 6 44 7.3284241 44 9 L 44 41 C 44 42.671576 42.671576 44 41 44 L 9 44 C 7.3284241 44 6 42.671576 6 41 L 6 9 C 6 7.3284241 7.3284241 6 9 6 z M 26.042969 10 A 1.0001 1.0001 0 0 0 25.042969 10.998047 C 25.042969 10.998047 25.031984 15.873262 25.021484 20.759766 C 25.016184 23.203017 25.009799 25.64879 25.005859 27.490234 C 25.001922 29.331679 25 30.496833 25 30.59375 C 25 32.409009 23.351421 33.892578 21.472656 33.892578 C 19.608867 33.892578 18.121094 32.402853 18.121094 30.539062 C 18.121094 28.675273 19.608867 27.1875 21.472656 27.1875 C 21.535796 27.1875 21.663054 27.208245 21.880859 27.234375 A 1.0001 1.0001 0 0 0 23 26.240234 L 23 22.039062 A 1.0001 1.0001 0 0 0 22.0625 21.041016 C 21.906673 21.031216 21.710581 21.011719 21.472656 21.011719 C 16.223131 21.011719 11.945313 25.289537 11.945312 30.539062 C 11.945312 35.788589 16.223131 40.066406 21.472656 40.066406 C 26.72204 40.066409 31 35.788588 31 30.539062 L 31 21.490234 C 32.454611 22.653646 34.267517 23.390625 36.269531 23.390625 C 36.542588 23.390625 36.802305 23.374442 37.050781 23.351562 A 1.0001 1.0001 0 0 0 37.958984 22.355469 L 37.958984 17.685547 A 1.0001 1.0001 0 0 0 37.03125 16.6875 C 33.886609 16.461891 31.379838 14.012216 31.052734 10.896484 A 1.0001 1.0001 0 0 0 30.058594 10 L 26.042969 10 z M 27.041016 12 L 29.322266 12 C 30.049047 15.2987 32.626734 17.814404 35.958984 18.445312 L 35.958984 21.310547 C 33.820114 21.201935 31.941489 20.134948 30.835938 18.453125 A 1.0001 1.0001 0 0 0 29 19.003906 L 29 30.539062 C 29 34.707538 25.641273 38.066406 21.472656 38.066406 C 17.304181 38.066406 13.945312 34.707538 13.945312 30.539062 C 13.945312 26.538539 17.066083 23.363182 21 23.107422 L 21 25.283203 C 18.286416 25.535721 16.121094 27.762246 16.121094 30.539062 C 16.121094 33.483274 18.528445 35.892578 21.472656 35.892578 C 24.401892 35.892578 27 33.586491 27 30.59375 C 27 30.64267 27.001859 29.335571 27.005859 27.494141 C 27.009759 25.65271 27.016224 23.20692 27.021484 20.763672 C 27.030884 16.376775 27.039186 12.849206 27.041016 12 z" />
                </svg>
            </a>
            <a href="" class="text-decoration-none">
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="50px" height="50px">
                    <path d="M 25 2 C 12.309288 2 2 12.309297 2 25 C 2 37.690703 12.309288 48 25 48 C 37.690712 48 48 37.690703 48 25 C 48 12.309297 37.690712 2 25 2 z M 25 4 C 36.609833 4 46 13.390175 46 25 C 46 36.609825 36.609833 46 25 46 C 13.390167 46 4 36.609825 4 25 C 4 13.390175 13.390167 4 25 4 z M 34.087891 14.035156 C 33.403891 14.035156 32.635328 14.193578 31.736328 14.517578 C 30.340328 15.020578 13.920734 21.992156 12.052734 22.785156 C 10.984734 23.239156 8.9960938 24.083656 8.9960938 26.097656 C 8.9960938 27.432656 9.7783594 28.3875 11.318359 28.9375 C 12.146359 29.2325 14.112906 29.828578 15.253906 30.142578 C 15.737906 30.275578 16.25225 30.34375 16.78125 30.34375 C 17.81625 30.34375 18.857828 30.085859 19.673828 29.630859 C 19.666828 29.798859 19.671406 29.968672 19.691406 30.138672 C 19.814406 31.188672 20.461875 32.17625 21.421875 32.78125 C 22.049875 33.17725 27.179312 36.614156 27.945312 37.160156 C 29.021313 37.929156 30.210813 38.335938 31.382812 38.335938 C 33.622813 38.335938 34.374328 36.023109 34.736328 34.912109 C 35.261328 33.299109 37.227219 20.182141 37.449219 17.869141 C 37.600219 16.284141 36.939641 14.978953 35.681641 14.376953 C 35.210641 14.149953 34.672891 14.035156 34.087891 14.035156 z M 34.087891 16.035156 C 34.362891 16.035156 34.608406 16.080641 34.816406 16.181641 C 35.289406 16.408641 35.530031 16.914688 35.457031 17.679688 C 35.215031 20.202687 33.253938 33.008969 32.835938 34.292969 C 32.477938 35.390969 32.100813 36.335938 31.382812 36.335938 C 30.664813 36.335938 29.880422 36.08425 29.107422 35.53125 C 28.334422 34.97925 23.201281 31.536891 22.488281 31.087891 C 21.863281 30.693891 21.201813 29.711719 22.132812 28.761719 C 22.899812 27.979719 28.717844 22.332938 29.214844 21.835938 C 29.584844 21.464938 29.411828 21.017578 29.048828 21.017578 C 28.923828 21.017578 28.774141 21.070266 28.619141 21.197266 C 28.011141 21.694266 19.534781 27.366266 18.800781 27.822266 C 18.314781 28.124266 17.56225 28.341797 16.78125 28.341797 C 16.44825 28.341797 16.111109 28.301891 15.787109 28.212891 C 14.659109 27.901891 12.750187 27.322734 11.992188 27.052734 C 11.263188 26.792734 10.998047 26.543656 10.998047 26.097656 C 10.998047 25.463656 11.892938 25.026 12.835938 24.625 C 13.831938 24.202 31.066062 16.883437 32.414062 16.398438 C 33.038062 16.172438 33.608891 16.035156 34.087891 16.035156 z" />
                </svg>
            </a>
            <a href="" class="text-decoration-none">
                <svg fill="#fff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" width="50px" height="50px">
                    <path d="M 25 3 C 12.861562 3 3 12.861562 3 25 C 3 36.019135 11.127533 45.138355 21.712891 46.728516 L 22.861328 46.902344 L 22.861328 29.566406 L 17.664062 29.566406 L 17.664062 26.046875 L 22.861328 26.046875 L 22.861328 21.373047 C 22.861328 18.494965 23.551973 16.599417 24.695312 15.410156 C 25.838652 14.220896 27.528004 13.621094 29.878906 13.621094 C 31.758714 13.621094 32.490022 13.734993 33.185547 13.820312 L 33.185547 16.701172 L 30.738281 16.701172 C 29.349697 16.701172 28.210449 17.475903 27.619141 18.507812 C 27.027832 19.539724 26.84375 20.771816 26.84375 22.027344 L 26.84375 26.044922 L 32.966797 26.044922 L 32.421875 29.564453 L 26.84375 29.564453 L 26.84375 46.929688 L 27.978516 46.775391 C 38.71434 45.319366 47 36.126845 47 25 C 47 12.861562 37.138438 3 25 3 z M 25 5 C 36.057562 5 45 13.942438 45 25 C 45 34.729791 38.035799 42.731796 28.84375 44.533203 L 28.84375 31.564453 L 34.136719 31.564453 L 35.298828 24.044922 L 28.84375 24.044922 L 28.84375 22.027344 C 28.84375 20.989871 29.033574 20.060293 29.353516 19.501953 C 29.673457 18.943614 29.981865 18.701172 30.738281 18.701172 L 35.185547 18.701172 L 35.185547 12.009766 L 34.318359 11.892578 C 33.718567 11.811418 32.349197 11.621094 29.878906 11.621094 C 27.175808 11.621094 24.855567 12.357448 23.253906 14.023438 C 21.652246 15.689426 20.861328 18.170128 20.861328 21.373047 L 20.861328 24.046875 L 15.664062 24.046875 L 15.664062 31.566406 L 20.861328 31.566406 L 20.861328 44.470703 C 11.816995 42.554813 5 34.624447 5 25 C 5 13.942438 13.942438 5 25 5 z" />
                </svg>
            </a>
        </footer>
    </div>
</body>

</html>