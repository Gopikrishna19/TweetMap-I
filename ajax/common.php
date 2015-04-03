<?php
    // Database details
    define("db_host", "tweetmap.cbzi29xmqaua.us-west-2.rds.amazonaws.com:3306");
    define("db_user", "root");
    define("db_pass", "Gopi1991");
    define("db_name", "tweetdb");

    // create database connection
    $conn = mysqli_connect(db_host, db_user, db_pass, db_name);
?>