<?php
// Get the current page name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="nav">
    <div class="logo">Hulu Order <p style="font-size: x-small;">CN</p>
    </div>
    <input type="checkbox" id="click">
    <label for="click" class="menu-btn">
        <i class="fas fa-bars"></i>
    </label>
    <ul>
        <li><a <?php if ($current_page == 'index.php') echo 'class="active"'; ?> href="index.php">Orders</a></li>
        <li><a <?php if ($current_page == 'unlock.php') echo 'class="active"'; ?> href="unlock.php">Unlock</a></li>
        <li><a <?php if ($current_page == 'profileChina.php') echo 'class="active"'; ?> href="profileChina.php">Profile</a></li>
        <?php if (strlen($_SESSION['mtid'] == 0)) { ?>
            <li><a <?php if ($current_page == 'login.php') echo 'class="active"'; ?> href="../login.php">Login</a></li>
        <?php } ?>
        <?php if (strlen($_SESSION['mtid'] > 0)) { ?>
            <?php
            $itemId = $_GET['id'];
            $ret1 = mysqli_query($con, "SELECT items.ID AS bid,items.ItemName FROM items WHERE items.Status='new' AND items.SuggestDes=''");
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
                            <a class="dropdown-item" href="detail.php?id=<?php echo $result['bid']; ?>"> (<?php echo $result['ItemName']; ?>)</a>
                        <?php }
                    } else { ?>
                        <a class="dropdown-item" href="#">No new order received</a>
                    <?php } ?>
                </div>
                <div class="clearfix"> </div>
            </div>
        <?php } ?>
        <li><a <?php if ($current_page == 'logout.php') echo 'class="active"'; ?> href="../logout.php">Logout</a></li>
    </ul>
</nav>
