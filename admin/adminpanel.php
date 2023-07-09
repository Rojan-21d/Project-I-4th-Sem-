<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location: adminlogin.php');
    exit;
}
include 'adminheader.php';
include 'maincontent.php';
// include '../layout/header.php';
?>
    <link rel="stylesheet" href="../css/headerstyle.css">
    <link rel="stylesheet" href="../css/maincontentstyle.css">
