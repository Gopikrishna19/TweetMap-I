<?php include "common.php"; ?>
<?php include "parse.php"; ?>
<?php
    // get the limits and factor from the front end
    $from = isset($_GET['from']) ? $_GET['from'] : 0;
    $limit = isset($_GET['limit']) ? $_GET['limit'] : 100;

    // set the content type of the output to json for the ajax call
    header("Content-Type: application/json");

    // query the database for tweets
    $result = mysqli_query($conn, "select tweet from tweets limit ".$from.",".$limit);
    $hash = [];
    $words = [];

    // get each tweet
    while($row = mysqli_fetch_row($result)){
        $tweet = $row[0];

        // get the keywords from the tweet using doParse
        // $keys: stores the list keywords from $tweet
        //
        Parse::doParse($tweet, $keys);

        // Loop through the keywords to compile an array of keys with their number of occurances
        // final result [{ key: number } ... ]
        foreach($keys as $i => $k) {
            // skip empty keywords
            $k = preg_replace('/(#|[0-9]+)/', '', $k);
            if($k == '' || $k == '_') continue;
            if(array_key_exists($k, $hash)) $hash[$k] = $hash[$k] + 1;
            else $hash[$k] = 1;
        }
    }

    // print out the final json to the front end
    echo json_encode($hash, JSON_PRETTY_PRINT);
?>