<?php
    /*
    Message

    int id auto increment unique notNull
    vchar 4000 content
    PK for user.id
    date notNull datepattern
    */

    // Database connection
    require_once '../conn.php';

    // Get data from the post
    /*
    Params
    ?jsonpost=true / false

    This is for how send the post if its true
    the method for send post is with json body on POST request
    {
    "content": "teste",
    "user_id": 1,
    }
    */

    $jsonpost = isset($_GET['jsonpost']) ? $_GET['jsonpost'] : "";

    // Get data from the post
    if ($jsonpost == "true") {
        // Get data from the json body
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        
        $content = $data['content'];
        $user_id = $data['user_id'];
    
        // Save current date in format "2020-01-01 00:00:00"
        $date = date("Y-m-d H:i:s");
    } else {
        // Get data from the form
        $content = isset($_POST['content']) ? $_POST['content'] : "";
        $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : "";
        // Save current date in format "2020-01-01 00:00:00"
        $date = date("Y-m-d H:i:s");
    }

    // Insert
    try {
        // Insert message
        $sql = "INSERT INTO message (content, user_id, date) VALUES ('$content', '$user_id', '$date')";
        $conn->query($sql);

        // Get the last id
        $last_id = $conn->insert_id;

        // Get the message
        $sql = "SELECT * FROM message WHERE id = $last_id";
        $result = $conn->query($sql);

        // Return the message
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo json_encode($row);
            }
        } else {
            echo "0 results";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }

    // Close connection
    $conn->close();
?>