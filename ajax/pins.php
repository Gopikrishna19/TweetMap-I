<?php include "common.php"; ?>
<?php
    // set output heaader to json type
    header("Content-Type: application/json");

    // Get the pin keyword from url
    $like = "";
    $tweet = "tweet, ";
    if(isset($_GET['q'])) $like = $_GET['q'];
    if(isset($_GET['nt'])) $tweet = "";

    // query the database for the given keyword
    $query = "select $tweet latitude, longitude from tweets where tweet regexp '[[:<:]]".$like."[[:>:]]'";

    // get the list of tweets containing the keyword
    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));
    $rows = [];

    // compile the list of tweets with latitude and longitude
    while($row = mysqli_fetch_row($result)) {
        $row[0] = utf8_encode($row[0]);
        $rows[] = $row;
    }

    // output the result
    echo json_encode($rows, JSON_PRETTY_PRINT);
?>