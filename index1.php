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
        header('Location:index.php');
        exit();
    }
    $_SESSION['timeout'] = time();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Hulu Order</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./images/Eo_circle_green_white_letter-h.svg.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" />
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
</head>

<body>
    <?php include('header.php') ?>
    <div class="HomePage" style="width: 100%; height: 80vh; background: #fff; flex-direction: column; justify-content: flex-start; align-items: flex-start; gap: 100px; display: inline-flex;overflow:hidden;">
        <div class="DesktopHeader05" style="width: 100%; height: 100%; background: #D4D4D4; flex-direction: column; justify-content: flex-start; align-items: center; gap: 35px; display: flex">
            <div class="Text" style="align-self: stretch; height: 317px; padding-top: 9px; padding-bottom: 15px; flex-direction: column; justify-content: flex-start; align-items: center; gap: 17px; display: flex">
                <div class="Headline" style="padding: 10px; justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                    <div class="WorkAtTheSpeedOfThought">BEST PHONES <br />AT BEST PRICE </div>
                </div>
                <div class="SubHeading" style="padding: 10px; justify-content: flex-start; align-items: center; gap: 10px; display: inline-flex">
                    <div class="MostCalendarsAreDesignedForTeamsSlateIsDesignedForFreelancersWhoWantASimpleWayToPlanTheirSchedule" style="text-align: center; color: #212121; font-size: 20px; font-family: Abyssinica SIL; font-weight: 400; text-transform: capitalize; line-height: 30px; letter-spacing: 0.20px; word-wrap: break-word">mobile tera offers the best price on the best quality <br />we deliver at you're phone store </div>
                </div>
            </div>
            <div class="Buttons" style="justify-content: flex-start; align-items: center; gap: 35px; display: inline-flex">
                <?php if (strlen($_SESSION['mtid'] == 0)) { ?>
                    <a href="login.php">
                        <div class="Button bg-success" style="padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; box-shadow: 0px 4px 31px rgba(0, 0, 0, 0.15); justify-content: flex-start; align-items: center; display: flex">
                            <div class="Text" style="padding: 10px; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                                <div class="TryForFree text-decoration-none" style="color: white; font-size: 17px; font-family: Abyssinica SIL; font-weight: 400; text-transform: uppercase; line-height: 25px; letter-spacing: 0.20px; word-wrap: break-word">log in </div>
                            </div>
                        </div>
                    </a> <?php } ?>
                <a href="" class="text-decoration-none">
                    <div class="Button" style="padding-left: 30px; padding-right: 30px; padding-top: 10px; padding-bottom: 10px; box-shadow: 0px 4px 31px rgba(0, 0, 0, 0.15); border: 1px black solid; justify-content: flex-start; align-items: center; display: flex">
                        <div class="Text" style="padding: 10px; justify-content: flex-start; align-items: center; gap: 10px; display: flex">
                            <div class="LearnMore text-decoration-none" style="color: black; font-size: 17px; font-family: Abyssinica SIL; font-weight: 400; text-transform: uppercase; line-height: 25px; letter-spacing: 0.20px; word-wrap: break-word">contact us</div>
                        </div>
                    </div>
                </a>
            </div>
            <!-- <div class="Screen w-100">
                <div class="Screens">
                    <div class="Rectangle5 text-white mx-auto p-4 w-25 h-50" style=" background: #1F1F1F; border-radius: 32px">
                        <h3 class="pt-3">partnership with china </h3>
                        <p class="">Build an unlimited partnership with China for direct access to Chinese sellers. Empower your business with an intelligent alliance for a strong presence in one of the world's largest markets.</p>
                    </div>
                    <div class="Rectangle8 text-white mx-auto p-4 w-25 h-50" style=" background: #1F1F1F; border-radius: 32px">
                        <h3 class="pt-3">free shipment</h3>
                        <p>Revolutionize logistics with our innovative, cost-effective shipping solutions. Unlock your business potential with this introduction to limitless, hassle-free shipping.</p>
                    </div>
                    <div class="Rectangle9 text-white mx-auto p-4 w-25 h-50" style=" background: #1F1F1F; border-radius: 32px">
                        <h3 class="pt-3">lowest market price</h3>
                        <p>Discover unbeatable prices in the market! Unlock exceptional savings with our budget-friendly solutions. Dive into a world of affordability with this introduction to unparalleled value.</p>
                    </div>
                </div>
            </div> -->
        </div>
    </div>

    <?php include('footer.php') ?>
</body>

</html>