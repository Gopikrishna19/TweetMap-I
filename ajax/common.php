<?php
    // Database details
    define("db_host", "");
    define("db_user", "root");
    define("db_pass", "password");
    define("db_name", "tweetdb");

    // create database connection
    $conn = mysqli_connect(db_host, db_user, db_pass, db_name);
?>