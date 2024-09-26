<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="nav text-white">
    <a href="index.php" style="text-decoration: none; color:#fff; display:'flex';flex-direction:'row';">
        <div class="logo-con">
            <div class="logo">
                <img alt="" src="./images/1122.png" height="50px" width="50px" />
            </div>
            <div class="logos">
                <h2 class="order">Hulu</h2>
                <p class="order">Order</p>
            </div>
        </div>
    </a>
    <div class="menu-toggle">
        <input type="checkbox" id="click">
        <label for="click" class="menu-btn">
            <i class="fas fa-bars"></i>
        </label>
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
                                <?php
                                $postId = $feedFetch['ID'];
                                $sqlImages = "SELECT image_filename FROM user_images WHERE userId = ?";
                                $stmtImages = mysqli_prepare($con, $sqlImages);
                                mysqli_stmt_bind_param($stmtImages, "i", $postId);
                                mysqli_stmt_execute($stmtImages);
                                $resultImages = mysqli_stmt_get_result($stmtImages);
                                if ($rowImage = mysqli_fetch_assoc($resultImages)) {
                                    $imageFilename = $rowImage['image_filename'];
                                    echo '<img src="imageUpload.php?filename=' . $imageFilename . '" alt="" width="40" height="40" style="border-radius: 50%;">';
                                }
                                mysqli_stmt_close($stmtImages);
                                ?>
                                <!--<img src="imageUpload.php?filename=' . $imageFilename . '" alt="" width="40" height="40" style="border-radius: 50%;">-->
                                <div>
                                    <p class="align-middle mx-2 m-0" style="color: gray;"><?php echo $feedFetch["FirstName"] ?> <?php echo $feedFetch["LastName"] ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </a>
                    <?php if (strlen($_SESSION['mtid'] == 0)) { ?>
                        <a class="nav-link mx-2 m-0" style="color: white; font-weight: 600; font-size: large;" href="login.php">Login</a>
                    <?php } ?>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                        <?php if (strlen($_SESSION['mtid'] > 0)) { ?>
                            <li><a class="dropdown-item" <?php if ($current_page == 'index.php') echo 'class="active"'; ?> href="index.php">Home</a></li>
                            <li><a class="dropdown-item" <?php if ($current_page == 'dashboard.php') echo 'class="active"'; ?> href="dashboard.php">Order</a></li>
                            <li><a class="dropdown-item" <?php if ($current_page == 'message.php') echo 'class="active"'; ?> href="message.php">Message</a></li>
                            <li><a class="dropdown-item" <?php if ($current_page == 'history.php') echo 'class="active"'; ?> href="history.php">History</a></li>
                            <li><a class="dropdown-item" <?php if ($current_page == 'profile.php') echo 'class="active"'; ?> href="profile.php">Profile</a></li>
                            <li><a class="dropdown-item" <?php if ($current_page == 'verify.php') echo 'class="active"'; ?> href="verify.php">Verify</a></li>
                            <li><a class="dropdown-item" <?php if ($current_page == 'logout.php') echo 'class="active"'; ?> href="logout.php">Logout</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>