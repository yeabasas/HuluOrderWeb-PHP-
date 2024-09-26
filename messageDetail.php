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
    header('location:login.php');
    exit();
} else {
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <title>Message</title>
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
        <div class="msg-con">
            <div class="msg-div">
                <div class="msg-head" style="color: white;">
                    <?php
                    $id = $_GET['iId'];
                    $sql2 = "SELECT * FROM `posts` WHERE ID=$id";
                    $result2 = mysqli_query($con, $sql2);
                    $num = mysqli_num_rows($result2);
                    $cnt = 1; ?>
                    <?php if ($num > 0) {
                        while ($fetch = mysqli_fetch_assoc($result2)) {
                    ?> <?php
                            $postId = $fetch['ID'];
                            $sqlImages = "SELECT image_filename FROM post_images WHERE post_id = ?";
                            $stmtImages = mysqli_prepare($con, $sqlImages);
                            mysqli_stmt_bind_param($stmtImages, "i", $postId);
                            mysqli_stmt_execute($stmtImages);
                            $resultImages = mysqli_stmt_get_result($stmtImages);

                            if ($rowImage = mysqli_fetch_assoc($resultImages)) {
                                $imageFilename = $rowImage['image_filename'];
                                echo '
                            <a href="images/' . $imageFilename . '">
                                
                                    <img src="images/' . $imageFilename . '" alt="" height="90px" width="90px" style="background-color:black">
                                
                            </a>
                            ';
                            }
                            mysqli_stmt_close($stmtImages);
                        ?>
                            <?php
                            $id = $_GET['uId'];
                            $sql2 = "SELECT * FROM `user` WHERE ID=$id";
                            $result2 = mysqli_query($con, $sql2);
                            $num = mysqli_num_rows($result2);
                            $cnt = 1;
                            while ($fetched = mysqli_fetch_assoc($result2)) {

                            ?>
                                <div class="d-flex flex-col">
                                    <p class="mx-2"><?php echo $fetched["FirstName"] ?></p>
                                    <p class="mx-2"><?php echo $fetch["ItemName"] ?></p>
                                </div>
                            <?php
                            } ?>
                    <?php }
                    } ?>
                </div>
                <div id="messages-container"></div>
                <form method="post" id="message-form" class="msg-form" action="">
                    <input type="text" multiline="true" name="message" placeholder="Type your message...">
                    <input type="submit" name="submit" value="Send">
                </form>
            </div>
        </div>




        <!-- <h1>Messaging Page</h1>
        <div id="messages-container"></div>
        Form to send a new message 
        <form method="post" id="message-form" action="">
            <textarea name="message" placeholder="Type your message..."></textarea>
            <input type="submit" name="submit" value="Send">
        </form> -->

        <!-- Add your JavaScript to fetch and display messages -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Fetch and display messages
                fetchMessages();

                // Handle form submission
                document.getElementById('message-form').addEventListener('submit', function(e) {
                    e.preventDefault();
                    sendMessage();
                });
            });

            function fetchMessages() {
                fetch('fetch_messages.php?uId=<?php echo $_GET["uId"] ?>&iId=<?php echo $_GET["iId"] ?>')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! Status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(messages => {
                        console.log('Fetched messages:', messages);
                        displayMessages(messages);
                    })
                    .catch(error => console.error('Error fetching messages:', error));
            }


            function displayMessages(messages) {
                const messagesContainer = document.getElementById('messages-container');

                // Clear existing messages
                messagesContainer.innerHTML = '';

                // Display each message
                messages.forEach(message => {
                    const messageContainer = document.createElement('div');
                    const messageElement = document.createElement('p');
                    const timeElement = document.createElement('p');

                    const senderId = <?php echo $_SESSION["mtid"] ?>;
                    const otherUserId = <?php echo $_GET["uId"] ?>;

                    if (message.sender_id === senderId) {
                        // Message sent by the logged-in user
                        messageElement.textContent = `${message.message}`;
                        messageElement.classList.add('sent-message');
                        messageContainer.classList.add('sent-message-con');
                    } else if (message.receiver_id === senderId) {
                        // Message received by the logged-in user
                        messageElement.textContent = ` ${message.message}`;
                        messageElement.classList.add('received-message');
                        messageContainer.classList.add('received-message-con');
                    } else if (message.sender_id === otherUserId) {
                        // Message sent by the other user
                        messageElement.textContent = ` ${message.message}`;
                        messageElement.classList.add('other-sent-message');
                        messageContainer.classList.add('other-sent-message-con');
                    } else if (message.receiver_id === otherUserId) {
                        // Message received by the other user
                        messageElement.textContent = ` ${message.message}`;
                        messageElement.classList.add('other-received-message');
                        messageContainer.classList.add('other-received-message-con');
                    }

                    // Add the send time below the message
                    timeElement.textContent = formatTimestamp(message.timestamp);
                    timeElement.classList.add('message-time');

                    messageContainer.appendChild(messageElement);
                    messageContainer.appendChild(timeElement);
                    messagesContainer.appendChild(messageContainer);
                });
            }

            // Function to format the timestamp into a readable format
            function formatTimestamp(timestamp) {
                const date = new Date(timestamp);
                const options = {
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric'
                };
                return date.toLocaleString('en-US', options);
            }







            function sendMessage() {
                const messageInput = document.querySelector('input[name="message"]');
                const message = messageInput.value.trim();

                if (message !== '') {
                    fetch('send_message.php?uId=<?php echo $_GET["uId"] ?>&iId=<?php echo $_GET["iId"] ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `message=${encodeURIComponent(message)}`,
                        })
                        .then(response => {
                            if (response.ok) {
                                // If the message is sent successfully, fetch and display updated messages
                                fetchMessages();
                            } else {
                                console.error('Error sending message:', response.statusText);
                            }
                        })
                        .catch(error => console.error('Error sending message:', error))
                        .finally(() => {
                            // Clear the input field
                            messageInput.value = '';
                        });
                }
            }
        </script>
    </body>

    </html>
<?php } ?>