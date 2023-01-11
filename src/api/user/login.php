<?php 
    /*
    LOGIN

    Tables

    User

    int id auto increment unique notNull
    vchar 255 username auto increment notNull
    vchar 255 email unique notNull
    vchar 255 password notNull
    short int 1 preorder
    vchar 255 role notNull

    METHOD POST
    */

    // Database connection get from conn.php
    require_once '../conn.php';

    // Get params from the url
    /*
    Avaliable params

    ?jsonpost=true / false

    This is for how send the post if its true

    {
        "email": "string",
        "password": "string"
    }

    if false send from form
    */

    $jsonpost = isset($_GET['jsonpost']) ? $_GET['jsonpost'] : "";

    // Get data from the post
    if ($jsonpost == "true") {
        // Get data from the json body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        $email = $data['email'];
        $password = $data['password'];
    } else {
        // Get data from the form
        $email = isset($_POST['email']) ? $_POST['email'] : "";
        $password = isset($_POST['password']) ? $_POST['password'] : "";
    }

    // Check if the password sent and the password on database match
    try {
        $sql = "SELECT * FROM user WHERE email = '$email'"; // Sql query
        $result = $conn->query($sql); //

        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    // Password match
                    // Create a session
                    session_start();
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['email'] = $row['email'];
                    $_SESSION['preorder'] = $row['preorder'];
                    $_SESSION['role'] = $row['role'];
                    
                    //put session into a cookie
                    setcookie("id", $_SESSION['id'], time() + (86400 * 30), "/");
                    setcookie("username", $_SESSION['username'], time() + (86400 * 30), "/");
                    setcookie("email", $_SESSION['email'], time() + (86400 * 30), "/");
                    setcookie("preorder", $_SESSION['preorder'], time() + (86400 * 30), "/");
                    


                    


                    // Return the user data
                    $response = array(
                        "id" => $row['id'],
                        "username" => $row['username'],
                        "email" => $row['email'],
                        "preorder" => $row['preorder'],
                        "role" => $row['role'],
                        "success" => true
                    );
                    echo json_encode($response);
                } else {
                    // Password don't match
                    $response = array(
                        "error" => "Password don't match",
                        "success" => false
                    );
                    echo json_encode($response);
                }
            }
        } else {
            // No user found
            $response = array(
                "error" => "No user found",
                "success" => false
            );
            echo json_encode($response);
        }

    } catch (Exception $e) {
        // Error
        $response = array(
            "error" => $e->getMessage(),
            "success" => false
        );
        echo json_encode($response);
    }

    $conn->close();
?>