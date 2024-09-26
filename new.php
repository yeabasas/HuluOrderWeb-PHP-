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
    // Fetch ratings from the database
    $ratings = [];
    $sqlRate = "SELECT Rate FROM feedback";
    $result = mysqli_query($con, $sqlRate);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ratings[] = $row['Rate'];
        }
    }

    // Calculate rating counts
    $ratingCounts = [0, 0, 0, 0, 0];
    foreach ($ratings as $rating) {
        if ($rating > 0) {
            $index = 5 - $rating;
            $ratingCounts[$index]++;
        }
    }

    // Calculate average rating
    $totalRating = array_sum($ratings);
    $count = count($ratings);
    $averageRating = $count === 0 ? 0 : $totalRating / $count;
    // Calculate sum of ratings
    $sumRating = $count;

    function countRatings($feedbackArray)
    {
        // Initialize the rating counts array
        $ratingCounts = [0, 0, 0, 0, 0];

        // Loop through each feedback in the array
        foreach ($feedbackArray as $feedback) {
            if ($feedback['Rate'] > 0) {
                // Adjust index calculation to match the rating system
                $index = 5 - $feedback['Rate'];
                $ratingCounts[$index]++;
            }
        }

        return $ratingCounts;
    }

    // Example feedback data (you can replace this with actual data)
    $feedBackTxtCon = [
        ['Rate' => 5],
        ['Rate' => 4],
        ['Rate' => 3],
        ['Rate' => 5],
        ['Rate' => 1],
        ['Rate' => 2],
        ['Rate' => 4],
    ];

    // Call the function with example data
    $ratingCount = countRatings($feedBackTxtCon);
    // Function to calculate width based on count
    function calculateWidth($ratingCount)
    {
        // Assuming max count is 30 for calculation purposes
        $maxCount = 50;
        return ($ratingCount / $maxCount) * 100;
    }

    // Function to get color based on rating
    function getColor($rating)
    {
        switch ($rating) {
            case 5:
                return 'green';
            case 4:
                return 'blue';
            case 3:
                return 'yellow';
            case 2:
                return 'orange';
            case 1:
                return 'red';
            default:
                return 'gray';
        }
    }
} else {
    // If the post has already been viewed in the current session, use the stored view count
    $sql = "SELECT * FROM posts WHERE ID = $postId";
    $result = $con->query($sql);

    if ($result->num_rows > 0) {
        $post = $result->fetch_assoc();
        $newViewCount = $post['View'];
    }
    // Fetch ratings from the database
    $ratings = [];
    $sqlRate = "SELECT Rate FROM feedback WHERE PostId = $postId";
    $result = mysqli_query($con, $sqlRate);
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ratings[] = $row['Rate'];
        }
    }

    // Calculate rating counts
    $ratingCounts = [0, 0, 0, 0, 0];
    foreach ($ratings as $rating) {
        if ($rating > 0) {
            $index = 5 - $rating;
            $ratingCounts[$index]++;
        }
    }

    // Calculate average rating
    $totalRating = array_sum($ratings);
    $count = count($ratings);
    $averageRating = $count === 0 ? 0 : $totalRating / $count;

    // Calculate sum of ratings
    $sumRating = $count;

    $rates = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
    $total_ratings = 0;

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $rates[$row['rating']] = $row['count'];
            $total_ratings += $row['count'];
        }
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
            padding-left: 70px;
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

        .average-rating {
            font-size: 5rem;
            font-weight: bold;
        }

        .container {
            display: flex;
            flex-direction: column;
            width: 50%;
            margin: 0 auto;
        }

        .rating-container {
            justify-content: space-around;
            align-items: center;
            background-color: blue;
        }

        .row {
            display: flex;
            flex-direction: row;
            align-items: center;
            margin-bottom: 5px;
        }

        .rating-bar-container {
            width: 100%;
            background-color: #f1f1f1;
            border-radius: 5px;
            margin: 5px 0;
        }
        .rating-bar {
            height: 20px;
            border-radius: 5px;
            text-align: center;
            color: white;
            padding: 0 10px;
            width: 20%; /* Default width for 1-star rating */
        }
        .rating-bar-1 { background-color: #ff4c4c; width: 20%; }
        .rating-bar-2 { background-color: #ff9800; width: 40%; }
        .rating-bar-3 { background-color: #ffeb3b; width: 60%; }
        .rating-bar-4 { background-color: #8bc34a; width: 80%; }
        .rating-bar-5 { background-color: #4caf50; width: 100%; }
    </style>
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
                    <div class="img-con mb-2">
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
                    </div>
                    <div class="post-details d-flex flex-column p-1 mb-2 card">
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
                        <div class="post-details p-3 w-47">
                            <p><span>Condition</span> <?php echo $fetch['Condition']; ?></p>
                            <p><span>Sim</span> <?php echo $fetch['Sim']; ?></p>
                            <p><span>Ram</span> <?php echo $fetch['Ram']; ?></p>
                        </div>
                        <div class="post-details p-3 w-47">
                            <p><span>Processor</span> <?php echo $fetch['Processor']; ?></p>
                            <p><span>Color</span> <?php echo $fetch['Color']; ?></p>
                            <p><span>Storage</span> <?php echo $fetch['Storage']; ?></p>
                        </div>
                    </div>
                    <div class="post-details d-flex flex-row p-1 mb-2 justify-content-around card">
                        <div class="post-details p-3 w-47">
                            <p class="mt-3"><span>Description</span> <?php echo $fetch['Description']; ?> </p>
                        </div>
                        <div class="post-details p-3 w-47 d-none">
                            <p class="mt-3"><span>Description</span> <?php echo $fetch['Description']; ?></p>
                        </div>
                    </div>
                    <div class="post-details d-flex flex-column p-1 justify-content-around mb-2 card">
                        <?php
                        $uId = $fetch['userId'];
                        $query = mysqli_query($con, "select * from user where ID = '$uId'");
                        while ($fetches = mysqli_fetch_array($query)) {
                        ?>
                            <div class="d-flex justify-content-around">
                                <a href="messageDetail.php?uId=<?php echo $fetch['userId'] ?>&iId=<?php echo $itemId ?>" class="btn btn-primary"><i class="fa-regular fa-message"></i> Message</a>
                                <button id="callBtn" onclick="toggleDiv()" class="btn btn-success"><i class="fa-solid fa-phone"></i> Call</button>
                            </div>
                            <div id="PhoneNo" class="justify-content-around mt-3" style="display: none;">
                                <p></p>
                                <div class="d-flex">
                                    <p id="textToCopy" style="cursor: pointer;justify-content:center;align-items:center;"> <?php echo $fetches['Phone'] ?> </p>
                                    <a style="cursor: pointer;margin-left: 10px" onclick="copyText()"><i class="fa-regular fa-copy"></i></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <!-- Rating Section -->
                    <div class="post-details d-flex flex-column p-1 justify-content-around mb-2 card">
                        <?php
                        $uId = $fetch['userId'];
                        $query = mysqli_query($con, "select * from user where ID = '$uId'");
                        while ($fetches = mysqli_fetch_array($query)) {
                        ?>
                            <div class="d-flex align-middle px-5 py-3">
                                <div class="d-flex flex-row" style="align-items: center;">
                                    <img src="images/No_image_available.svg.png" alt="" width="40" height="40" style="border-radius: 50%;">
                                    <div>
                                        <p class="align-middle mx-2 m-0"><?php echo $fetches["FirstName"] ?> <?php echo $fetches["LastName"] ?></p>
                                        <div class="d-flex flex-row mx-2">
                                            <p class="align-middle m-0" style="font-size: x-small; margin-right: 5px">Hulu Verified</p>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-patch-check mx-2" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M10.354 6.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708 0" />
                                                <path d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a type="button" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <h3 class="px-5">Rate & Review</h3>
                            </a>
                            <div class="modal modal-dialog modal-dialog-centered fade" id="exampleModal" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            This is the body of the modal.
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="rating-display">
                                <h3>Average Rating: <span id="average-rating"><?php echo number_format($averageRating, 1); ?></span></h3>
                                <div id="star-container">
                                    <?php
                                    for ($i = 0; $i < round($averageRating); $i++) {
                                        echo '<span style="color: gold;">★</span>'; // Filled star
                                    }
                                    for ($i = round($averageRating); $i < 5; $i++) {
                                        echo '<span style="color: gold;">☆</span>'; // Empty star
                                    }
                                    ?>
                                </div>
                                <p>Total Ratings: <span id="sum-rating"><?php echo number_format($sumRating); ?></span></p>
                            </div> -->
                            <div style="display: flex; align-items:center; justify-content:space-around; margin:auto; padding:0 20px;width:100%">
                                <div id="rating-display" class=" d-flex flex-column justify-content-center" style="width:40%;">
                                    <div id="average-rating" class="average-rating" style="margin:auto"><?php echo number_format($averageRating, 1); ?></div>
                                    <div id="star-container" class="star-container" style="margin:auto">
                                        <?php
                                        for ($i = 0; $i < round($averageRating); $i++) {
                                            echo '<span style="color: gold;">★</span>'; // Filled star
                                        }
                                        for ($i = round($averageRating); $i < 5; $i++) {
                                            echo '<span style="color: gold;">☆</span>'; // Empty star
                                        }
                                        ?>
                                    </div>
                                    <div id="sum-rating" style="margin: auto;"><?php echo number_format($sumRating); ?></div>
                                </div>
                                <!-- <div id="rating-container" class="container" style="width:60%">
                                    <?php for ($i = 5; $i >= 1; $i--) : ?>
                                        <div class="row">
                                            <div><?php echo $i ?></div>
                                            <div class="bar"></div>
                                        </div>
                                </div>
                            <?php endfor; ?> -->
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo "<div>
            <span>Rating $i</span>
            <div class='rating-bar-container'>
                <div class='rating-bar rating-bar-$i'>$ratings[$i]</div>
            </div>
          </div>";
                                }
                                ?>
                            </div>
                    </div>
                    <h5 class="px-5 mt-5">Feedbacks</h5>
                    <?php
                            $uId = $fetch['ID'];
                            $query = mysqli_query(
                                $con,
                                "SELECT feedback.*,user.FirstName,user.LastName,user_images.image_filename,user_images.userId FROM feedback JOIN user ON feedback.UserId = user.ID LEFT JOIN user_images ON user_images.userId = user.ID WHERE feedback.PostId = '$uId' ORDER BY DateModified DESC"
                            );
                            while ($feedback = mysqli_fetch_array($query)) {
                    ?>
                        <div class="mx-5">
                            <div class="d-flex flex-row" style="align-items: center;">
                                <?php
                                $ufId = $feedback['UserId'];
                                $queryy = mysqli_query($con, "select * from user where ID = '$ufId'");
                                while ($feedFetch = mysqli_fetch_array($queryy)) {
                                ?>
                                    <img src="images/No_image_available.svg.png" alt="" width="40" height="40" style="border-radius: 50%;">
                                    <div>
                                        <p class="align-middle mx-2 m-0" style="color: gray;"><?php echo $feedFetch["FirstName"] ?> <?php echo $feedFetch["LastName"] ?></p>
                                    </div>
                                <?php } ?>
                            </div>
                            <p class="px-5"><?php echo $feedback['Feedback'] ?></p>
                        </div>
                    <?php } ?>
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
    <!-- <script>
        const ratingCounts = [3]; // Example rating counts, replace with actual data

        function calculateWidth(count) {
            const totalCount = ratingCounts.reduce((sum, count) => sum + count, 0);
            return totalCount ? (count / totalCount) * 100 : 0;
        }

        function getColor(rating) {
            switch (rating) {
                case 5:
                    return 'green';
                case 4:
                    return 'blue';
                case 3:
                    return 'yellow';
                case 2:
                    return 'orange';
                case 1:
                    return 'red';
                default:
                    return 'gray';
            }
        }

        const container = document.getElementById('rating-container');

        [5, 4, 3, 2, 1].forEach((rating) => {
            const row = document.createElement('div');
            row.className = 'row';

            const text = document.createElement('span');
            text.textContent = rating;

            const bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.width = `${calculateWidth(ratingCounts[5 - rating])}%`;
            bar.style.backgroundColor = getColor(rating);

            row.appendChild(text);
            row.appendChild(bar);
            container.appendChild(row);
        });
    </script> -->
    <!-- <script>
        const feedBackTxtCon = [{
                Rate: 5
            }, {
                Rate: 4
            }, {
                Rate: 3
            }, {
                Rate: 5
            }, {
                Rate: 1
            }, // Example feedback data
            {
                Rate: 4
            }, {
                Rate: 2
            }, {
                Rate: 3
            }, {
                Rate: 5
            }, {
                Rate: 2
            }
        ];
        const fetchedpost = fetch('https://app.huluorder.com')

        const countRatings = () => {
            const ratingCounts = [0, 0, 0, 0, 0];
            feedBackTxtCon.forEach((feedback) => {
                if (feedback.Rate > 0) {
                    const index = 5 - feedback.Rate;
                    ratingCounts[index]++;
                }
            });
            return ratingCounts;
        };

        const calculateAverageRating = () => {
            let totalRating = 0;
            let count = 0;

            feedBackTxtCon.forEach((feedback) => {
                if (feedback.Rate > 0) {
                    totalRating += feedback.Rate;
                    count++;
                }
            });

            return count === 0 ? 0 : totalRating / count;
        };

        const calculateSumRating = () => {
            let count = 0;

            feedBackTxtCon.forEach((feedback) => {
                if (feedback.Rate > 0) {
                    count++;
                }
            });

            return count;
        };

        const displayRatings = () => {
            const averageRating = calculateAverageRating();
            const sumRating = calculateSumRating();

            document.getElementById('average-rating').innerText = averageRating.toFixed(1);
            document.getElementById('sum-rating').innerText = sumRating.toLocaleString();

            const starContainer = document.getElementById('star-container');
            starContainer.innerHTML = '';
            for (let i = 0; i < Math.round(averageRating); i++) {
                const star = document.createElement('span');
                star.innerText = '★'; // Unicode for filled star
                star.style.color = 'gold';
                starContainer.appendChild(star);
            }
            for (let i = Math.round(averageRating); i < 5; i++) {
                const star = document.createElement('span');
                star.innerText = '☆'; // Unicode for empty star
                star.style.color = 'gold';
                starContainer.appendChild(star);
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            displayRatings();
        });
    </script> -->
    <!-- <script>
        const ratingCounts = [1, 2, 3, 4, 5]; // Example rating counts for 1-5 stars

        function calculateWidth(count) {
            const totalCount = ratingCounts.reduce((sum, count) => sum + count, 0);
            return totalCount ? (count / totalCount) * 100 : 0;
        }

        function getColor(rating) {
            switch (rating) {
                case 5:
                    return 'green';
                case 4:
                    return 'blue';
                case 3:
                    return 'yellow';
                case 2:
                    return 'orange';
                case 1:
                    return 'red';
                default:
                    return 'gray';
            }
        }

        const container = document.getElementById('rating-container');

        [5, 4, 3, 2, 1].forEach((rating) => {
            const row = document.createElement('div');
            row.className = 'row';

            const text = document.createElement('span');
            text.textContent = rating;

            const bar = document.createElement('div');
            bar.className = 'bar';
            bar.style.width = `${calculateWidth(ratingCounts[5 - rating])}%`;
            bar.style.backgroundColor = getColor(rating);

            row.appendChild(text);
            row.appendChild(bar);
            container.appendChild(row);
        });
    </script> -->
</body>

</html>