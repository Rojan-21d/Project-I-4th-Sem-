<?php
session_start();
if(!isset($_SESSION['username'])){
    header('location: adminlogin.php');
    exit;
}
include 'adminheader.php';
include 'maincontent.php';
?>
