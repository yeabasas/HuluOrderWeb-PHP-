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
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Post Detail</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="./images/1122.png" type="image/x-icon">
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
        <style>
            .star-rating {
                display: flex;
                flex-direction: row-reverse;
                justify-content: flex-end;
                font-size: 2rem;
            }

            .star-rating input {
                display: none;
            }

            .star-rating label {
                color: #ddd;
                cursor: pointer;
            }

            .star-rating input:checked~label,
            .star-rating input:checked~label~label {
                color: #f5b301;
            }

            .star-rating input:not(:checked)~label:hover,
            .star-rating input:not(:checked)~label:hover~label {
                color: #f5b301;
            }

            .cont {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
                background-color: #f5b301;
                height: 100vh;
            }
            .contLow{
                border: 1px #ddd solid;
                padding: 50px;
            }
        </style>
    </head>

    <body>
        
        <script>
            function toggleDiv() {
                var myDiv = document.getElementById("PhoneNo");

                if (myDiv.style.display === "none" || myDiv.style.display === "") {
                    myDiv.style.display = "flex";
                    copyText();
                } else {
                    myDiv.style.display = "none";
                    alert.style.display = "none";
                }
            }

            function copyText() {
                var currentTime = new Date().getSeconds();
                var isWithinTimeBand = currentTime >= 0 && currentTime < 5;
                var alert = document.getElementById("alert");
                setTimeout(function() {
                    alert.style.display = "none";
                }, 5000);
                var textToCopy = document.getElementById("textToCopy");

                var inputElement = document.createElement("input");
                inputElement.value = textToCopy.innerText;

                document.body.appendChild(inputElement);

                inputElement.select();
                inputElement.setSelectionRange(0, 99999);

                document.execCommand("copy");

                document.body.removeChild(inputElement);

                alert.style.display = "flex";
            }
        </script>
    </body>

    </html>
<?php } ?>