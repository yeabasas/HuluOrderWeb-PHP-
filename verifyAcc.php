<?php
session_start();
include_once('dbcon.php');

// Check if form is submitted
if (isset($_POST['verify'])) {
    // Ensure email is set in session
    if (!isset($_SESSION['email'])) {
        $error_message = "Session expired. Please login again.";
    } else {
        $email = $_SESSION['email'];

        // Combine the individual digits from the form into a full code
        $verificationCode = $_POST['code1'] . $_POST['code2'] . $_POST['code3'] . $_POST['code4'] . $_POST['code5'] . $_POST['code6'];

        // Query to check if the verification code is correct
        $checkQuery = "SELECT * FROM user WHERE Email = ? AND verificationCode = ? AND isVerified = 0";
        $stmt = mysqli_prepare($con, $checkQuery);
        mysqli_stmt_bind_param($stmt, "ss", $email, $verificationCode);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Update user to set isVerified to 1
            $updateQuery = "UPDATE user SET isVerified = 1, verificationCode = NULL WHERE Email = ?";
            $updateStmt = mysqli_prepare($con, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "s", $email);
            mysqli_stmt_execute($updateStmt);

             // Trigger the toast and redirect to login page after 3 seconds
             echo "<script>
             document.addEventListener('DOMContentLoaded', function() {
                 var toastElement = document.getElementById('successToast');
                 var toast = new bootstrap.Toast(toastElement);
                 toast.show();
                 setTimeout(function() {
                     window.location.href = 'login.php';
                 }, 3000);
             });
           </script>";
        } else {
            $error_message = "Invalid verification code.";
        }

        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <link rel="shortcut icon" href="./images/1122.png" type="image/x-icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.0/mdb.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
        }

        .verification-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .input-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .input-box {
            width: 40px;
            height: 40px;
            font-size: 20px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .input-box:focus {
            outline: none;
            border-color: #007bff;
        }

        .submit-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="verification-container">
        <h2>Email Verification</h2>
        <?php if (isset($error_message)) : ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="input-container">
                <input type="text" name="code1" class="input-box" maxlength="1" id="code1" required>
                <input type="text" name="code2" class="input-box" maxlength="1" id="code2" required>
                <input type="text" name="code3" class="input-box" maxlength="1" id="code3" required>
                <input type="text" name="code4" class="input-box" maxlength="1" id="code4" required>
                <input type="text" name="code5" class="input-box" maxlength="1" id="code5" required>
                <input type="text" name="code6" class="input-box" maxlength="1" id="code6" required>
            </div>
            <input type="submit" class="submit-btn" name="verify" value="Verify">
        </form>
    </div>

    <!-- Bootstrap Toast Component -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Email verified successfully!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const inputs = document.querySelectorAll('.input-box');

        inputs.forEach((input, index) => {
            input.addEventListener('input', () => {
                if (input.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            });

            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && index > 0 && input.value === '') {
                    inputs[index - 1].focus();
                }
            });
        });
    </script>
</body>
</html>
