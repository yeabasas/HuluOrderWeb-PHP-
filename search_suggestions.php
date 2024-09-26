<?php
include('dbcon.php');

if (isset($_GET['query'])) {
    $searchTerm = mysqli_real_escape_string($con, $_GET['query']);
    $sql = "SELECT * FROM posts WHERE ItemName LIKE '%$searchTerm%'";
    $result = mysqli_query($con, $sql);
    
    $resultsArray = [];
    while ($fetch = mysqli_fetch_assoc($result)) {
        $resultsArray[] = [
            'ID' => $fetch['ID'],
            'userId' => $fetch['userId'],
            'ItemName' => $fetch['ItemName'],
            'Price' => $fetch['Price'],
            'Brand' => $fetch['Brand'],
            'Condition' => $fetch['Condition']
        ];
    }
    echo json_encode($resultsArray);
}
?>
