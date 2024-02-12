<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="nav text-white">
    <div class="group-parent">
        <a href="index.php" style="text-decoration: none; color:#fff;">
        <div class="vector-parent">
            <img class="vector-icon1" alt="" src="./public/vector1.svg" />

            <img class="vector-icon2" alt="" src="./public/vector2.svg" />

            <div class="hulu">Hulu</div>
            <img class="vector-icon3" alt="" src="./public/vector3.svg" />

            <img class="vector-icon4" alt="" src="./public/vector4.svg" />

            <img class="vector-icon5" alt="" src="./public/vector5.svg" />

            <img class="vector-icon6" alt="" src="./public/vector6.svg" />

            <img class="vector-icon7" alt="" src="./public/vector7.svg" />

            <img class="vector-icon8" alt="" src="./public/vector8.svg" />

            <img class="vector-icon9" alt="" src="./public/vector9.svg" />

            <img class="vector-icon10" alt="" src="./public/vector10.svg" />

            <img class="vector-icon11" alt="" src="./public/vector11.svg" />

            <img class="vector-icon12" alt="" src="./public/vector12.svg" />

            <img class="vector-icon13" alt="" src="./public/vector10.svg" />

            <img class="vector-icon14" alt="" src="./public/vector13.svg" />

            <img class="vector-icon15" alt="" src="./public/vector14.svg" />

            <img class="vector-icon16" alt="" src="./public/vector15.svg" />
        </div>
        <div class="order">ORDER</div>
        </a>
    </div>
    <input type="checkbox" id="click">
    <label for="click" class="menu-btn">
        <i class="fas fa-bars"></i>
    </label>
    <ul>
        <?php if (strlen($_SESSION['mtid'] == 0)) { ?>
            <li><a <?php if ($current_page == 'login.php') echo 'class="active"'; ?> href="login.php">Login</a></li>
        <?php } ?>
        <?php if (strlen($_SESSION['mtid'] > 0)) { ?>
            <li><a <?php if ($current_page == 'index.php') echo 'class="active"'; ?> href="index.php">Home</a></li>
            <li><a <?php if ($current_page == 'dashboard.php') echo 'class="active"'; ?> href="dashboard.php">Order</a></li>
            <li><a <?php if ($current_page == 'message.php') echo 'class="active"'; ?> href="message.php">Message</a></li>
            <!-- <li><a <?php if ($current_page == 'unlock.php') echo 'class="active"'; ?> href="unlock.php">Unlock</a></li> -->
            <!-- <li><a <?php if ($current_page == 'stoke.php') echo 'class="active"'; ?> href="stoke.php">Stoke</a></li> -->
            <li><a <?php if ($current_page == 'history.php') echo 'class="active"'; ?> href="history.php">History</a></li>
            <li><a <?php if ($current_page == 'profile.php') echo 'class="active"'; ?> href="profile.php">Profile</a></li>
            <li><a <?php if ($current_page == 'logout.php') echo 'class="active"'; ?> href="logout.php">Logout</a></li>
        <?php } ?>
    </ul>
</nav>