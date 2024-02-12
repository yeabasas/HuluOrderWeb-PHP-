<?php
session_start();
error_reporting(0);
include('dbcon.php');

// Check if the login form is submitted
if (isset($_POST['login'])) {
    $phone = mysqli_real_escape_string($con, $_POST['Phone']);
    $password = mysqli_real_escape_string($con, $_POST['Password']);

    // Retrieve the hashed password from the database based on the provided username/email
    $query = mysqli_query($con, "SELECT * FROM user WHERE Phone='$phone'");
    $row = mysqli_fetch_array($query);

    if ($row) {
        // Verify the entered password against the stored hash
        $hashedPasswordFromDatabase = $row['Password'];

        if (password_verify($password, $hashedPasswordFromDatabase)) {
            // Passwords match, user is authenticated
            $_SESSION['mtid'] = $row['ID'];

            // Check the role and redirect accordingly
            $role = $row['Role'];
            switch ($role) {
                case 'admin':
                    header('location:./Admin/index.php');
                    break;
                case 'user':
                    $_SESSION['Phone'] = $row['Phone'];
                    header('location:index.php');
                    break;
                case 'china':
                    header('location:./China/index.php');
                    break;
                    // Add more cases for other roles if needed
                default:
                    // Handle unexpected role
                    break;
            }
        } else {
            // Passwords do not match
            $error_message = "Incorrect password. Please try again.";
        }
    } else {
        // Phone number not found in the database
        $error_message = "The phone number does not exist. Please check your phone number or register for an account.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hulu Order | login</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./images/Eo_circle_green_white_letter-h.svg.png" type="image/x-icon">
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
        <div class="mx-auto d-flex flex-column align-items-center justify-content-center w-50 shadow bg-body-tertiary rounded" style="color: #243B2E; padding: 50px 0; height:50%">
            <h1 class="mb-3" style="color: #243B2E;">Login</h1>
            <div class="w-75 text-center">
                <?php if (isset($error_message)) : ?>
                    <div class="alert alert-danger w-100" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="">
                    <input class="form-control mb-2" type="text" placeholder="Phone" name="Phone" required>
                    <input class="form-control mb-2" type="password" placeholder="Password" name="Password" required>
                    <input class="form-control mb-2 text-white" style="background-color: #243B2E;" type="submit" value="Login" name="login">
                </form>
                <p>Don't have an account? <a href="register.php" style="text-decoration: none; color: #243B2E; font-size: medium">Register</a></p>
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