<?php

if(isset($_POST['login']))
{
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    require '../backend/databaseconnection.php';
    $sql = "SELECT * FROM admininfo WHERE username = '$username' and password = '$password'";

    $result = $conn->query($sql);
    if($result->num_rows > 0){
        header ("Location: ../admin/adminpanel.php");
    }
}
?>


<h1>Welcome Admin!</h1>