<?php

session_start(); // Start the session

// // Clear all session variables
// $_SESSION = array();

// // Destroy the session
// session_destroy();

// // Redirect the user to the login page or any desired page
// header("Location: ../login.php");
// exit();



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
// session_start(); // Start the session

// function logout($userType) {
//     // Clear all session variables
//     $_SESSION = array();

//     // Destroy the session
//     session_destroy();

//     // Redirect the user based on their type
//     if ($userType === 'admin') {
//         header("Location: ../admin/adminlogin.php");
//     } else {
//         header("Location: ../login.php");
//     }
//     exit();
// }



?>
