<?php 
    /*
    User

    int id auto increment unique notNull
    vchar 255 username auto increment notNull
    vchar 255 email unique notNull
    vchar 255 password notNull
    short int 1 preorder
    vchar 255 role notNull

    */

    // Database connection
    require_once '../conn.php';


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
        $preorder = isset($_POST['preorder']) ? $_POST['preorder'] : "";
        $role = isset($_POST['role']) ? $_POST['role'] : "";
    }

    // Update
    try {
        // Hash password
        $password = password_hash($password, PASSWORD_DEFAULT);

        // Update user
        $sql = "UPDATE user SET username = '$username', password = '$password', email = '$email', preorder = '$preorder', role = '$role' WHERE username = '$username'"; // Sql query
        $stmt = $conn->prepare($sql); // Prepare the query
        $stmt->execute(); // Execute the query

        // Return sucess
        $data = [
            "sucess" => "true",
            "message" => "User updated sucessfully"
        ];
        echo json_encode($data);
    } catch (PDOException $e) {
        // Return error
        $data = [
            "sucess" => "false",
            "message" => "Error: " . $e->getMessage()
        ];
        echo json_encode($data);
    }

    // Close the connection
    $conn = null;
?>