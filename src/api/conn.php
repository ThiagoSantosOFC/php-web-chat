<?php
    // Database connection
    $servername = "localhost";
    $port = "3306";
    $username = "root";
    $password = "";
    $dbname = "chatdb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // echo "Connected successfully";
?>