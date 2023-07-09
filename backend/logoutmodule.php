<?php

session_start(); // Start the session

if($_SESSION['usertype']){
    // Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect the user to the login page or any desired page
header("Location: ../login.php");
exit();
}else{
        // Clear all session variables
$_SESSION = array();

// Destroy the session
session_destroy();
    header("Location: ../admin/adminlogin.php");
    exit();
}

?>
