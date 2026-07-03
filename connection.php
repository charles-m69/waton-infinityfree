<?php
    define('BASE_URL', 'http://waton.infinityfree.io/');

    // Declare database credentials
    define('DB_HOST', 'sql303.infinityfree.com');
    define('DB_USER', 'if0_42128588');
    define('DB_PASS', 'jFmL7S7yebne');
    define('DB_NAME', 'if0_42128588_db_waton');

    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    //check connection
    // if ($conn->connect_error) {
    //     echo 'Connection ERROR';
    // } else {
    //     echo 'Connected';
    // }
?>