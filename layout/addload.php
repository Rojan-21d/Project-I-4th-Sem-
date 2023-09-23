<?php
session_start();
include '../backend/databaseconnection.php';

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/addtable.css">
    
    <title>Add Load Details</title>
</head>
<body>
    <div class="add-main">
        <h2>Add Load</h2>
        <form action="../backend/addloadbackend.php" method="POST" enctype="multipart/form-data" class = "addForm">
        <div class="data-input">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" >
        </div>
        <div class="data-input">
            <label for="origin">Origin:</label>
            <input type="text" id="origin" name="origin" >
        </div>
        <div class="data-input">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination">
        </div>
        <div class="data-input">
            <label for="distance">Distance (KM):</label>
            <input type="number" id="distance" name="distance">
        </div>
        <div class="data-input">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description">
        </div>
        <div class="data-input">
            <label for="weight">Weight (Tons):</label>
            <input type="number" id="weight" name="weight">
        </div>
        <div class="data-input center">
        <label for="image">Image:</label>
            <input class="inpImg" type="file" id="image" name="image" accept="image/*" placeholder="Image">
        </div>

        <div class="button-input">
            <input type="hidden" name="id" value="">
        </div>
        <button type="submit">ADD LOAD</button><br>
        <a href="../"><button type="button">Home</button></a>
        </form>
    </div>
</body>
</html>
