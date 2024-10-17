<?php
// db_connect.php

// Prevent multiple inclusions
if (!defined('DB_CONNECTED')) {
    define('DB_CONNECTED', true);
    
    $servername = "localhost";
    $username = "root"; // Your DB username
    $password = ""; // Your DB password
    $dbname = "college_project"; // Your DB name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
?>
