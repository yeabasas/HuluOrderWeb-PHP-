<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="nav">
    <a href="index.php" style="text-decoration:none;">
        <div class="logo">Hulu Order<p style="font-size: x-small;">AD</p></div>
    </a>
    <input type="checkbox" id="click">
    <label for="click" class="menu-btn">
        <i class="fas fa-bars"></i>
    </label>
    <ul>
        <?php if (strlen($_SESSION['mtid'] == 0)) { ?>
            <li><a <?php if ($current_page == 'login.php') echo 'class="active"'; ?> href="../login.php">Login</a></li>
        <?php } ?>
        <?php if (strlen($_SESSION['mtid'] > 0)) { ?>
            <li><a <?php if ($current_page == 'index.php') echo 'class="active"'; ?> href="index.php">Home</a></li>
            <li><a <?php if ($current_page == 'addStoke.php') echo 'class="active"'; ?> href="addStoke.php">Add Stoke</a></li>
                        <li><a <?php if ($current_page == 'AdminStoke.php') echo 'class="active"'; ?> href="AdminStoke.php">Stoke</a></li>
            <li><a <?php if ($current_page == 'order.php') echo 'class="active"'; ?> href="order.php">Order</a></li>
            <li><a <?php if ($current_page == 'orderSummary.php') echo 'class="active"'; ?> href="orderSummary.php">Summary</a></li>
            <li><a <?php if ($current_page == 'register.php') echo 'class="active"'; ?> href="register.php">Register</a></li>
            <li><a <?php if ($current_page == 'accounting.php') echo 'class="active"'; ?> href="accounting.php">EditNo</a></li>
            <li><a <?php if ($current_page == 'adminProfile.php') echo 'class="active"'; ?> href="adminProfile.php">Profile</a></li>

            <?php
            $ret1 = mysqli_query($con, "select user.FirstName,user.LastName,items.ID as bid,items.ItemName from items join user on user.ID=items.userId where items.Status= 'new'");
            $num = mysqli_num_rows($ret1);

            ?>
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i> <?php echo $num; ?>
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                    <?php if ($num > 0) {
                        while ($result = mysqli_fetch_array($ret1)) {
                    ?>
                            <a class="dropdown-item" href="orderDetailAdmin.php?id=<?php echo $result['bid']; ?>">received from <?php echo $result['FirstName']; ?> <?php echo $result['LastName']; ?> (<?php echo $result['ItemName']; ?>)</a>
                        <?php }
                    } else { ?>
                        <a class="dropdown-item" href="#">No new order received</a>
                    <?php } ?>
                </div>
                <div class="clearfix"> </div>
            </div>
            <li><a <?php if ($current_page == 'logout.php') echo 'class="active"'; ?> href="../logout.php">Logout</a></li>
        <?php } ?>
    </ul>
</nav>
