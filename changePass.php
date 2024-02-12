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
    if (isset($_POST['change'])) {
        $userId = $_SESSION['mtid'];
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $query1 = mysqli_query($con, "select * from user where ID='$userId' and   Password='$currentPassword'");
        $row = mysqli_fetch_array($query1);
        if ($row > 0) {
            $ret = mysqli_query($con, "update user set Password='$newPassword' where ID='$userId'");
            echo '<script>alert("Your password successfully changed.");</script>';
        } else {
            echo '<script>alert("Your current password is wrong.");</script>';
        }
    }
?>

    <script type="text/javascript">
        function checkpass() {
            if (document.changePassword.newPassword.value != document.changePassword.confirmPassword.value) {
                alert('New Password and Confirm Password field does not match');
                document.changePassword.confirmPassword.focus();
                return false;
            }
            return true;
        }
    </script>

    <div class="map-content-9 mt-lg-0 mt-4 shadow bg-body-tertiary rounded p-5">
        <form method="post" name="changePassword" onsubmit="return checkpass();">
            <h3 class="mx-auto text-center">Change Password</h3>
            <div style="padding-top: 30px;">
                <label>Current Password</label>
                <input type="password" class="form-control" placeholder="Current Password" id="currentPassword" name="currentPassword" value="" minlength="8" required="true">
                <div class="invalid-feedback">
                    Please enter password more than 8
                </div>
            </div>
            <div style="padding-top: 30px;">
                <label>New Password</label>
                <input type="password" class="form-control" placeholder="New Password" id="newPassword" name="newPassword" value="" minlength="8" required="true">
                <div class="invalid-feedback">
                    Please enter password more than 8
                </div>
            </div>
            <div style="padding-top: 30px;" class="">
                <label>Confirm Password</label>
                <input type="password" class="form-control mb-4" placeholder="Confirm Password" id="confirmPassword" name="confirmPassword" value="" minlength="8" required="true">
                <button type="submit" class="btn btn-success m-auto mt-5 d-flex justify-content-center" name="change">Change Password</button>
                <div class="invalid-feedback">
                    Please enter password more than 8
                </div>
            </div>
        </form>
    </div>

<?php } ?>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>