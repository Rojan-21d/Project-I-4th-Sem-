<?php

session_start();

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    // Redirect the user to the login page or any other authentication page
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $name = $_POST['name'];
    $origin = $_POST['origin'];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $description = $_POST['description'];
    $weight = $_POST['weight'];

    // Process the uploaded image
    $image = $_FILES['image'];
    $imageFileName = $image['name'];
    $imageTempName = $image['tmp_name'];
    $imageDestination = 'img/loadUploads/' . $imageFileName;

    // Move the uploaded image to a specific directory
    move_uploaded_file($imageTempName, '../'.$imageDestination);


    // Insert the data into the database
    require 'databaseconnection.php';
    $sql = "INSERT INTO loaddetails (name, origin, destination, distance, description, weight, status, consignor_id, img_srcs)
                    VALUES ('$name', '$origin', '$destination', '$distance', '$description', '$weight', 'notBooked', '{$_SESSION['id']}', '$imageDestination')";
    $result = $conn->query($sql);

    if ($result) {
        echo "<script>alert('Inserted')</script>";
        echo "<script>window.location.href = '../layout/addload.php';</script>";
    } else {
        echo "<script>alert('Not Inserted! " . $conn->error . "');</script>";
        echo "<script>window.location.href = '../layout/addload.php';</script>";
    }
}
?>
