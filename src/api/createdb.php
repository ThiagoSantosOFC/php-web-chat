<?php
    /*
    -- Create TABLE
    CREATE TABLE User (
        id INT AUTO_INCREMENT,
        username VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        preorder TINYINT(1) NOT NULL,
        role VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;

    CREATE TABLE Message (
        id INT AUTO_INCREMENT,
        content VARCHAR(4000) NOT NULL,
        user_id INT NOT NULL,
        date DATETIME NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES User(id)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;

    */

    // Database connection 
    $servername = "localhost";
    $port = "3306";
    $username = "root";
    $password = "";
    $dbname = "chatdb";

    // Create connection
    $conn = null;

    try {
        $conn = new mysqli($servername, $username, $password, $dbname, $port);
    } catch (Exception $e) {
        echo "Connection failed: " . $e->getMessage();
    }

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create tables if not exists
    $sqlusertable = "CREATE TABLE IF NOT EXISTS User (
        id INT AUTO_INCREMENT,
        username VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        preorder SMALLINT NOT NULL,
        role VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    $sqlmessagetable = "CREATE TABLE IF NOT EXISTS Message (
        id INT AUTO_INCREMENT,
        content VARCHAR(4000) NOT NULL,
        user_id INT NOT NULL,
        date DATETIME NOT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (user_id) REFERENCES User(id)
    )ENGINE=InnoDB DEFAULT CHARSET=utf8;";

    // Create tables
    if ($conn->query($sqlusertable) === TRUE) {
        echo "Table User created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    if ($conn->query($sqlmessagetable) === TRUE) {
        echo "Table Message created successfully";
    } else {
        echo "Error creating table: " . $conn->error;
    }

    // Close connection
    $conn->close();
?>