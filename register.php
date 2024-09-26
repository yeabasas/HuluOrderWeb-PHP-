<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
error_reporting(0);
include_once('dbcon.php');

// Include PHPMailer classes
require 'vendor/autoload.php';

if (isset($_POST['register'])) {
    $Fname = $_POST['FirstName'];
    $Lname = $_POST['LastName'];
    $Email = $_POST['Email'];
    $Mob = $_POST['Phone'];
    $password = $_POST['Password'];
    $role = 'user';

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Generate verification code
    $verificationCode = rand(100000, 999999);

    // Check if the phone number already exists
    $checkQuery = "SELECT * FROM user WHERE Phone = ?";
    $stmt = mysqli_prepare($con, $checkQuery);
    mysqli_stmt_bind_param($stmt, "s", $Mob);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        $error_message = 'Phone number already exists. Please use a different phone number.';
    } else {
        // Insert the new user if the phone number doesn't exist
        $insertQuery = "INSERT INTO user (FirstName, LastName, Email, Phone, Password, Role, verificationCode, isVerified) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
        $insertStmt = mysqli_prepare($con, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, "ssssssi", $Fname, $Lname, $Email, $Mob, $hashedPassword, $role, $verificationCode);
        $result = mysqli_stmt_execute($insertStmt);

        if ($result) {
            // Send verification email using PHPMailer
            $mail = new PHPMailer(true);

            try {
                //Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Set the SMTP server to send through
                $mail->SMTPAuth = true;
                $mail->Username = 'huluorder@gmail.com'; // Replace with your Gmail address
                $mail->Password = 'brqy wyuw kicf qrun'; // Replace with your Gmail app password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
                $mail->SMTPOptions = array(
                    'ssl' => array(
                        'verify_peer' => false,
                        'verify_peer_name' => false,
                        'allow_self_signed' => true
                    )
                );

                //Recipients
                $mail->setFrom('huluorder@gmail.com', 'Hulu Order'); // Sender info
                $mail->addAddress($Email, $Fname . ' ' . $Lname); // Add recipient

                // Content
                $mail->isHTML(true); // Set email format to HTML
                $mail->Subject = 'Email Verification';
                $mail->Body = "Your verification code is: <b>$verificationCode</b>";

                $mail->send();
                $_SESSION['email'] = $Email;
                header('Location: verifyAcc.php');
                exit();
            } catch (Exception $e) {
                $error_message = "Error: Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            $error_message = 'Error: ' . mysqli_stmt_error($insertStmt);
        }

        // Close the statement
        mysqli_stmt_close($insertStmt);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hulu Order | login</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./images/1122.png" type="image/x-icon">
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

</head>

<body>
    <div class="d-flex mx-auto align-items-center justify-content-center text-bg-light flex-row m-auto vh-100">
        <div class="mx-auto d-flex flex-column align-items-center justify-content-center w-50 shadow bg-body-tertiary rounded" style="color: #243B2E; padding: 50px 0; height:60%">
            <h1 class="mb-3" style="color: #243B2E;">Register</h1>
            <div class="w-75 text-center">
                <?php if (isset($error_message)) : ?>
                    <div class="alert alert-danger w-75" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="">
                    <div class="d-flex flex-row justify-content-between mb-2 ">
                        <label class="file-input-label" id="fileInputLabel">
                            <input class="file-input" type="file" name="ProfilePic" accept="image/*" onchange="displayImage(this)">
                            <div class="file-circle">
                                <span class="plus-sign">+</span>
                                <img id="previewImage" src="" alt="Selected">
                            </div>
                        </label>
                        <div class="d-flex flex-column w-75 align-self-end">
                            <input class="form-control mb-2 w-100" type="text" placeholder="First Name" name="FirstName" required>
                            <input class="form-control mb-2 w-100" type="text" placeholder="Last Name" name="LastName" required>
                        </div>
                    </div>
                    <div>
                        <input class="form-control mb-2" type="email" placeholder="Email" name="Email" required>
                        <input class="form-control mb-2" type="text" placeholder="Phone" name="Phone" required>
                        <input class="form-control mb-2" type="password" placeholder="Password" name="Password" required>
                        <input class="form-control mb-2 text-white" style="background-color: #243B2E;" type="submit" value="Register" name="register">
                    </div>
                </form>
                <p>Already have an account? <a href="login.php" style="text-decoration: none; color: #243B2E; font-size: medium">Login</a></p>
            </div>
        </div>
        <!-- <div class="bg-white mx-auto d-flex align-items-center justify-content-center h-50 w-30 shadow bg-body-tertiary rounded">
            <form method="post" class="p-5 row g-1 needs-validation" novalidate>
                <h3 class="m-4">Register</h3>
                <div class="mb-3">
                    <?php if (isset($error_message)) : ?>
                        <div class="alert alert-danger w-75" role="alert">
                            <?php echo $error_message; ?>
                        </div>
                    <?php endif; ?>

                    <label for="phone" class="form-label">Phone Number</label>
                    <input type="tel" name='Phone' class="form-control" placeholder="Mobile no use(+251....)" id="phone" name="phone" required>
                    <div class="invalid-feedback">
                        Please enter a formal phone no (+251....)
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name='Password' placeholder="Password" class="form-control" id="password" minlength="8" required>
                    <div class="invalid-feedback">
                        Please enter a password with at least 8 characters.
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" name='login' class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div> -->
    </div>
    <style>
        .file-input-label {
            position: relative;
            overflow: hidden;
            display: inline-block;
            cursor: pointer;
        }

        .file-input {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
        }

        .file-circle {
            width: 90px;
            height: 90px;
            background-color: #ddd;
            border-radius: 50%;
            position: relative;
            overflow: hidden;
        }

        .plus-sign {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 24px;
            color: #333;
        }

        #previewImage {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
        }
    </style>

    <script>
        function displayImage(input) {
            var fileInput = input;
            var previewImage = document.getElementById('previewImage');
            var fileInputLabel = document.getElementById('fileInputLabel');

            if (fileInput.files && fileInput.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.style.display = 'block';
                    fileInputLabel.style.background = 'none';
                };

                reader.readAsDataURL(fileInput.files[0]);
            }
        }
    </script>
</body>
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

</html>