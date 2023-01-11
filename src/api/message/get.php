<?php 
    /*
    Message

    int id auto increment unique notNull
    vchar 4000 content
    PK for user.id
    date notNull datepattern
    */

    /*
    Error JSON
    {
        "error": "error message",
        "sucess: "false"
        "message": err stack
    }
    */

    // Database connection
    require_once '../conn.php';

    // Get params from url
    /*
    Params
    
    ?order=asc / desc The order is based on the date
    ?limit=10
    ?getby= id / user_id / date
    ?getbyvalue= 1 / 2 / 2020-01-01 00:00:00 / "content"
    ?join= true / false
    The join just add a field for name based on user_id 
    */

    $order = isset($_GET['order']) ? $_GET['order'] : "";
    $limit = isset($_GET['limit']) ? $_GET['limit'] : "";
    $getby = isset($_GET['getby']) ? $_GET['getby'] : "";
    $getbyvalue = isset($_GET['getbyvalue']) ? $_GET['getbyvalue'] : "";
    $join = isset($_GET['join']) ? $_GET['join'] : "";

    // Get data from database
    $sql = "SELECT * FROM message";

    // Check if the params are not null
    if ($getby != "" && $getbyvalue != "") {
        $sql .= " WHERE $getby = $getbyvalue";
    }

    if ($order != "") {
        $sql .= " ORDER BY date $order";
    }

    if ($limit != "") {
        $sql .= " LIMIT $limit";
    }

    // Get the result

    $result = null;

    if ($join == "true") {
        $sql = "SELECT message.id, message.content, message.user_id, message.date, user.username FROM message JOIN user ON message.user_id = user.id";
        $result = $conn->query($sql);
    } else {
        $result = $conn->query($sql);
    }

    // Check if the result is not null
    if ($result != null) {
        // Check if the result is not empty
        if ($result->num_rows > 0) {
            // Get the data
            $data = array();
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            // Return the data
            echo json_encode($data);
        } else {
            // Return error
            echo json_encode(array(
                "error" => "No data found",
                "success" => "false"
            ));
        }
    } else {
        // Return error
        echo json_encode(array(
            "error" => "No data found",
            "success" => "false"
        ));
    }

    // Close the connection
    $conn->close();
?>