<?php 
    /*
    User

    int id auto increment unique notNull
    vchar 255 username auto increment notNull
    email VARCHAR(255) NOT NULL,
    vchar 255 password notNull
    short int 1 preorder
    vchar 255 role notNull
    
    Create
    METHOD POST
    ?>

    /*
    Error JSON
    {
        "error": "error message",
        "sucess: "false",
        "message: message, <- Case is sql errors or somthig like that
    }
    */

    // Database connection get from conn.php
    require_once '../conn.php';

    // Get data from the url
    /*
    Params
    ?jsonpost=true / false

    This is for how send the post if its true
    the method for send post is with json body on POST request
    {
        "username": "username",
        "password": "password",
    }

    if false send from form
    */
    $jsonpost = isset($_GET['jsonpost']) ? $_GET['jsonpost'] : "";

    // Get data from the post
    if ($jsonpost == "true") {
        // Get data from the json body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $username = $data['username'];
        $password = $data['password'];
    } else {
        // Get data from the form
        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";
    }

    // Check if the password sent and the password on database match
    try {
        $sql = "SELECT * FROM user WHERE username = '$username'"; // Sql query
        
        // Sql query
        $passwordDB = $conn->query($sql);

        // Hash psw
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Check if the password match
        if (password_verify($password, $passwordHash)) {
            // Delete the user
            $sql = "DELETE FROM user WHERE username = '$username'";
            $conn->query($sql);

            // Return the data
            $data = array(
                "success" => "true",
                "message" => "User deleted"
            );
        } else {
            // Return the data
            $data = array(
                "error" => "Password not match",
                "success" => "false",
            );
        }

        // Return the data
        echo json_encode($data);
    } catch (PDOException $e) {
        // Return the data
        $data = array(
            "error" => "Error",
            "success" => "false",
            "message" => $e->getMessage()
        );

        // Return the data
        echo json_encode($data);
    }
    
    // Close the connection
    $conn = null;
?>