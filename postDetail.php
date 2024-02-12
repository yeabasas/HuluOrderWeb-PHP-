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
    error_log('$_GET: ' . print_r($_GET, true));
    $receiverId = isset($_GET['mid']) ? $_GET['mid'] : null;
    error_log('$receiverId: ' . $receiverId);

    $postId = $_GET['id']; // Replace 'post_id' with the actual parameter you use
    if (!isset($_SESSION['viewed_posts'][$postId])) {
        // Fetch post details
        $sql = "SELECT * FROM posts WHERE ID = $postId";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            $post = $result->fetch_assoc();

            // Increment view count
            $newViewCount = $post['View'] + 1;
            $updateSql = "UPDATE posts SET View = $newViewCount WHERE ID = $postId";
            $con->query($updateSql);

            $_SESSION['viewed_posts'][$postId] = true;
        } else {
            echo "Post not found";
            exit();
        }
    } else {
        // If the post has already been viewed in the current session, use the stored view count
        $sql = "SELECT * FROM posts WHERE ID = $postId";
        $result = $con->query($sql);

        if ($result->num_rows > 0) {
            $post = $result->fetch_assoc();
            $newViewCount = $post['View'];
        }
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Post Detail</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
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
        <div>
            <?php require 'header.php'; ?>
            <div class="container d-flex flex-column mt-4 justify-content-center w-90">
                <?php
                if (isset($_GET['id'])) {
                    $itemId = $_GET['id'];
                    $uId = $_SESSION['mtid'];
                    $query = mysqli_query($con, "select * from posts where ID = '$itemId'");
                    while ($fetch = mysqli_fetch_array($query)) {
                ?>
                        <div class="img-con  mb-2">
                            <?php
                            $sqlImages = "SELECT image_filename FROM post_images WHERE post_id = ?";
                            $stmtImages = mysqli_prepare($con, $sqlImages);
                            mysqli_stmt_bind_param($stmtImages, "i", $postId);
                            mysqli_stmt_execute($stmtImages);
                            $resultImages = mysqli_stmt_get_result($stmtImages);

                            if ($rowImage = mysqli_fetch_assoc($resultImages)) {
                                $imageFilename = $rowImage['image_filename'];
                                echo '
                                    <a href="images/' . $imageFilename . '">
                                        <div class="img-one card">
                                            <img src="images/' . $imageFilename . '" alt="" height="100%" width="100%">
                                        </div>
                                    </a>
                                    ';
                            }
                            mysqli_stmt_close($stmtImages);
                            ?>
                            <div class="img-sec-con">
                                <?php
                                $sqlImages = "SELECT image_filename FROM post_images WHERE post_id = ? LIMIT 1, 18446744073709551615";
                                $stmtImages = mysqli_prepare($con, $sqlImages);
                                mysqli_stmt_bind_param($stmtImages, "i", $postId);
                                mysqli_stmt_execute($stmtImages);
                                $resultImages = mysqli_stmt_get_result($stmtImages);

                                while ($rowImage = mysqli_fetch_assoc($resultImages)) {
                                    $imageFilename = $rowImage['image_filename'];
                                    echo '
                                    <a href="images/' . $imageFilename . '">
                                        <div class="img-sec ">
                                            <img class="card m-2" src="images/' . $imageFilename . '" alt="" height="100%" width="100%">
                                        </div>
                                        </a>
                                        ';
                                }

                                mysqli_stmt_close($stmtImages);
                                ?>
                            </div>
                            <!-- <div class="img-one card">
                                <img src="images/<?php echo $fetching['image_filename'] ?>" alt="" height="100%" width="100%">

                            </div>
                            <div class="img-sec">
                                <img class="card m-2" src="images/<?php echo $fetching['image_filename'] ?>" alt="" height="100%" width="100%">
                                <img class="card ml-2" src="images/<?php echo $fetching['image_filename'] ?>" alt="" height="100%" width="100%">
                            </div> -->
                        </div>
                        <div class="post-details d-flex flex-column p-1 mb-2  card">
                            <div class="d-flex flex-row justify-content-around">
                                <h2><?php echo $fetch['ItemName']; ?></h2>
                                <h3>ETB <?php echo $fetch['Price']; ?></h3>
                            </div>
                            <div class="d-flex flex-row justify-content-around">
                                <p class="text-secondary fs-6"><?php echo date('F j, Y', strtotime($fetch['DateModified'])); ?></p>
                                <p class="text-secondary fs-6"><span>Views</span> <?php echo $newViewCount ?></p>
                            </div>
                        </div>
                        <div class="post-details d-flex flex-row p-1 mb-2 justify-content-around card">
                            <div class="post-details p-3 w-47  ">
                                <p class=""><Span>Condition</Span> <?php echo $fetch['Condition']; ?></p>
                                <p class=""><Span>Sim</Span> <?php echo $fetch['Sim']; ?></p>
                                <p class=""><Span>Ram</Span> <?php echo $fetch['Ram']; ?></p>
                            </div>
                            <div class="post-details p-3 w-47">
                                <p class=""><Span>Processor</Span> <?php echo $fetch['Processor']; ?></p>
                                <p class=""><Span>Color</Span> <?php echo $fetch['Color']; ?></p>
                                <p class=""> <Span>Storage</Span> <?php echo $fetch['Storage']; ?></p>
                            </div>
                        </div>
                        <div class="post-details d-flex flex-row p-1 mb-2 justify-content-around card">
                            <p class="mt-3"> <Span>Description</Span> <?php echo $fetch['Description']; ?></p>
                            <p></p>
                        </div>
                        <div class="post-details d-flex flex-column p-1 justify-content-around mb-5 card">
                            <?php
                            $uId = $fetch['userId'];
                            $query = mysqli_query($con, "select * from user where ID = '$uId'");
                            while ($fetches = mysqli_fetch_array($query)) {
                            ?>
                                <div class="d-flex justify-content-around">
                                    <div style="align-self: center;">
                                        <img src="images/No_image_available.svg.png" alt="" width="40" height="40" style="border-radius: 50%;">
                                        <p><?php echo $fetches["FirstName"] ?></p>
                                    </div>
                                    <div style="align-self: center;">
                                        <a href="messageDetail.php?uId=<?php echo $fetch['userId'] ?>&iId=<?php echo $itemId ?>" class="btn btn-primary"><i class="fa-regular fa-message"></i> Message</a>
                                        <button id="callBtn" onclick="toggleDiv()" href="" class="btn btn-success"><i class="fa-solid fa-phone"></i> Call</button>
                                    </div>
                                </div>
                                <div id="PhoneNo" class="justify-content-around" style="display: none;">
                                    <p></p>
                                    <div class="d-flex">
                                        <p id="textToCopy" style="cursor: pointer;justify-content:center;align-items:center;"> <?php echo $fetches['Phone'] ?> </p>
                                        <a class="border border-1" style="cursor: pointer;justify-content:center;align-items:center;margin-left:5px;padding:0 10px" onclick="copyText()"><i class="fa-regular fa-copy"></i></a>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div id="alert" class="alert alert-success" style="display: none; z-index:1000; position:fixed; bottom:20px;right:30px" role="alert">
                            Phone number Copied!
                        </div>
                <?php
                    }
                } ?>
            </div>
        </div>
    </body>
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
            // Get the text to copy
            var currentTime = new Date().getSeconds();
            var isWithinTimeBand = currentTime >= 0 && currentTime < 5;
            var alert = document.getElementById("alert");
            setTimeout(function() {
                alert.style.display = "none";
            }, 5000);
            var textToCopy = document.getElementById("textToCopy");

            // Create a temporary input element
            var inputElement = document.createElement("input");
            inputElement.value = textToCopy.innerText;

            // Append the input element to the body
            document.body.appendChild(inputElement);

            // Select the text in the input element
            inputElement.select();
            inputElement.setSelectionRange(0, 99999); // For mobile devices

            // Copy the selected text to the clipboard
            document.execCommand("copy");

            // Remove the temporary input element
            document.body.removeChild(inputElement);

            // Provide feedback (optional)
            alert.style.display = "flex";
        }
    </script>

    </html>