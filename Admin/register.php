<?php
session_start();
error_reporting(0);
include('../dbcon.php');

if (isset($_SESSION['timeout'])) {
    $inactiveTime = time() - $_SESSION['timeout'];
    $sessionTimeout = 30 * 60; // 30 minutes in seconds

    if ($inactiveTime >= $sessionTimeout) {
        session_unset();
        session_destroy();
        header('Location: ../login.php');
        exit();
    }
    $_SESSION['timeout'] = time();
}

if (strlen($_SESSION['mtid'] == 0)) {
    header('location:../logout.php');
} else {
    if (isset($_POST['save'])) {
        $Fname = $_POST['FirstName'];
        $Lname = $_POST['LastName'];
        $Mob = $_POST['Phone'];
        $password = $_POST['Password'];
        $role = $_POST['role'];

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if the phone number already exists
        $checkQuery = "SELECT * FROM user WHERE Phone = ?";
        $stmt = mysqli_prepare($con, $checkQuery);
        mysqli_stmt_bind_param($stmt, "s", $Mob);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            echo "<script>alert('Phone number already exists. Please use a different phone number.');</script>";
        } else {
            // Insert the new user if the phone number doesn't exist
            $insertQuery = "INSERT INTO user (FirstName, LastName, Phone, Password, Role) VALUES (?, ?, ?, ?, ?)";
            $insertStmt = mysqli_prepare($con, $insertQuery);

            // Bind parameters
            mysqli_stmt_bind_param($insertStmt, "sssss", $Fname, $Lname, $Mob, $hashedPassword, $role);

            // Execute the statement
            $result = mysqli_stmt_execute($insertStmt);

            if ($result) {
                echo "<script>alert('Your request submitted successfully');window.location.href='register.php';</script>";
            } else {
                // Handle the error
                $error = mysqli_stmt_error($insertStmt);
                echo "<script>alert('Error: $error');window.location.href='register.php';</script>";
            }

            // Close the statement
            mysqli_stmt_close($insertStmt);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}
?>


<!DOCTYPE html>
<html lang="en">


<head>
    <link href="../style.css" rel="stylesheet" type="text/css" />
    <meta charset="UTF-8">
    <title>Register | Admin</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/e2c97eca5a.js" crossorigin="anonymous"></script>
    <!-- MDB -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script>
        function validatePhoneNumber() {
            // Get the input element
            var phoneNumberInput = document.getElementById('phoneNumber');

            // Get the entered phone number
            var phoneNumber = phoneNumberInput.value.trim();

            // Define the allowed country codes
            var allowedCountryCodes = ['+251', '+86'];

            // Check if the phone number starts with an allowed country code
            var isValid = allowedCountryCodes.some(function (code) {
                return phoneNumber.startsWith(code);
            });

            // Display an error message if the phone number is not valid
            if (!isValid) {
                alert('Please enter a valid phone number with country code +251 or +86.');
                phoneNumberInput.value = ''; // Clear the input
                phoneNumberInput.focus(); // Set focus back to the input
            }

            return isValid;
        }
    </script>
    </head>

<body>

    <div class="w-100 h-100">
        <?php include('header.php')?>
        <form method="post" action="register.php" class="form-control mt-5" enctype="multipart/form-data" style="text-decoration: none; border:none;">
            <div class="d-block w-50 mx-auto form-item">
                <h1 class="mx-auto ">Register User</h1>
                <input class="form-control mb-2" type="text" name="FirstName" placeholder="FirstName" required />
                <input class="form-control mb-2" type="text" name="LastName" placeholder="LastName" required />
                <input class="form-control mb-2" type="tel" name="Phone" placeholder="Mobile No" id="phoneNumber"  required>
                <input class="form-control mb-2" type="Password" name="Password" placeholder="Password" required />
                <select class="form-select mb-2" name="role" id="" placeholder='Role' required>
                    <option value="" selected disabled hidden>Role</option>
                    <option value="admin">Admin</option>
                    <option value="china">China</option>
                    <option value="user">user</option>
                </select>

                <button type="submit" name="save" class="form-control mt-3 btn btn-primary">Add</button>
            </div>
        </form>
    </div>
</body>

</html>