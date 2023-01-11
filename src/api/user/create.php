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

    {
        "username": "string",
        "password": "string",
        "email": "string",
        "preorder": "int",
        "role": "string"
    }
    */

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
    "username": "teste",
    "password": "12345",
    "email": "teste@demo.com",
    "preorder": 1,
    "role": "admin"
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
        $email = $data['email'];
        $preorder = $data['preorder'];
        $role = $data['role'];
    } else {
        // Get data from the form
        $username = isset($_POST['username']) ? $_POST['username'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $preorder = isset($_POST['preorder']) ? $_POST['preorder'] : 0;
        $role = isset($_POST['role']) ? $_POST['role'] : "user";
    }

    
    // Check if the params are not null
    if ($username != "" && $password != "" && $preorder != "" && $role != "") {
        // Criptography the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // SQL query
        $sql = "INSERT INTO User (username, password, email, preorder, role) VALUES ('$username', '$password', '$email', '$preorder', '$role')";
        try{
            // Insert data
            if ($conn->query($sql) === TRUE) {
                // Success JSON
                $success = array(
                    "message" => "User created",
                    "success" => "true",
                );
                echo json_encode($success);
            //go to login 
            //start session
            
           
            $_SESSION['username'] = $username;
        
            session_start() ;
            
            header("Location: ../../login.html");

            } else {
                // Error JSON
                $error = array(
                    "error" => "Error: " . $sql . "<br>" . $conn->error,
                    "success" => "false",
                    "message" => $conn->error
                );
                echo json_encode($error);
            }
        } catch (Exception $e) {
            // Error JSON
            $error = array(
                "error" => "Error: " . $sql . "<br>" . $e,
                "success" => "false",
                "message" => $e
            );
            echo json_encode($error);
        }
    } else {
        // Error JSON
        $error = array(
            "error" => "Invalid params",
            "success" => "false",

        );
        // Show what are recived with var_dump
        var_dump($username);
        var_dump($password);
        var_dump($preorder);
        var_dump($role);

        echo json_encode($error);
    }

    // Close the connection
    $conn->close();
?>