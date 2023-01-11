<?php

    /*
    Tables

    User

    int id auto increment unique notNull
    vchar 255 username auto increment notNull
    vchar 255 email unique notNull
    vchar 255 password notNull
    short int 1 preorder
    vchar 255 role notNull

    */

    // Database connection get from conn.php
    require_once '../conn.php';

    // Get params from the url
    /*
    Avaliable params
    ?orderby=
    orderby and getby 
    - id 
    - username
    - password
    - preorder
    - role
    - email

    ?limit= number
    ?search= string
    
    Prevent null values
    */
    $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : "";
    $limit = isset($_GET['limit']) ? $_GET['limit'] : "";
    $search = isset($_GET['search']) ? $_GET['search'] : "";

    // Get data from the database

    /*
    Error JSON
    {
        "error": "error message",
        "sucess: "false"
    }
    */
    // SQL query
    $sql = "SELECT * FROM User";

    // Check if the params are not null
    if ($orderby != "") {
        $sql .= " ORDER BY $orderby";
    }

    if ($limit != "") {
        $sql .= " LIMIT $limit";
    }

    if ($search != "") {
        // Can only return 1
        $sql = "SELECT * FROM User WHERE email LIKE '%$search%' LIMIT 1";
    }

    // Get data from the database
    $result = null;
    try{
        $result = $conn->query($sql);
    } catch (Exception $e) {
        echo json_encode(array(
            "error" => $e->getMessage(),
            "sucess" => "false"
        ));
    }

    // Check if the query is not null
    if ($result != null) {
        // Check if the query has results
        if ($result->num_rows > 0) {
            // Get the data
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            // Return the data
            echo json_encode($data);
            //start session
            session_start();
            //set session variables
            $_SESSION["username"] = $data[0]["username"];
            $_SESSION["email"] = $data[0]["email"];

        } else {
            // Return error
            echo json_encode(array(
                "error" => "No results",
                "sucess" => "false"
            ));
        }
    } else {
        // Return error
        echo json_encode(array(
            "error" => "No results",
            "sucess" => "false"
        ));
    }

    // Close the connection
    $conn->close();
?>