<?php
//delete cookies and session
setcookie("id", "", time()-3600);

session_destroy();

//redirect to login page
header("Location: ../../login.html");
?>