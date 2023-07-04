<?php
require 'databaseconnection.php';
session_start();
$sql = "SELECT * FROM loaddetails WHERE id = " . $_SESSION['load_id'];

        $result = $conn->query($sql);
        $row = mysqli_fetch_assoc($result);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // The form has been submitted and 'id' key exists in $_POST
    // Access the values using $_POST['id']
    $id = $_POST['id'];

    $name = $_POST["name"];
    $origin = $_POST["origin"];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $description = $_POST['description'];
    $weight = $_POST['weight'];

// Process the uploaded image only if a new file is selected
if (!empty($_FILES['image']['tmp_name'])) {
    $image = $_FILES['image'];
    $imageFileName = $image['name'];
    $imageTempName = $image['tmp_name'];
    $imageDestination = 'imageLoads/' . $imageFileName;

    // Move the uploaded image to a specific directory
    move_uploaded_file($imageTempName, $imageDestination);
} else {
    // No new image selected, retain the existing image path
    $imageDestination = $row['img_srcs'];
}
    $sql = "UPDATE loaddetails SET
            name = '$name',
            origin = '$origin',
            destination = '$destination',
            distance = '$distance',
            description = '$description',
            weight = '$weight',
            img_srcs = '$imageDestination'
            WHERE id = $id";

    if ($conn->query($sql)) {
        echo "<script> alert('Updated!');</script>";
    } else {
        echo "<script> alert('Update Failed!');</script>";
    }
    echo "<script>window.location='../home.php'</script>";
}


?>

<div class="add-main">
    <h2>Edit Load Details</h2>
    <form action="updateload.php" method="POST" enctype="multipart/form-data" class="addForm">
        <div class="data-input">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $row['name'] ?? ''; ?>">
        </div>
        <div class="data-input">
            <label for="origin">Origin:</label>
            <input type="text" id="origin" name="origin" value="<?php echo $row['origin'] ?? ''; ?>">
        </div>
        <div class="data-input">
            <label for="destination">Destination:</label>
            <input type="text" id="destination" name="destination" value="<?php echo $row['destination'] ?? ''; ?>">
        </div>
        <div class="data-input">
            <label for="distance">Distance (KM):</label>
            <input type="text" id="distance" name="distance" value="<?php echo $row['distance'] ?? ''; ?>">
        </div>
        <div class="data-input">
            <label for="description">Description:</label>
            <input type="text" id="description" name="description" value="<?php echo $row['description'] ?? ''; ?>">
        </div>
        <div class="data-input">
            <label for="weight">Weight (Tons):</label>
            <input type="number" id="weight" name="weight" value="<?php echo $row['weight'] ?? ''; ?>">
        </div>
        <div class="data-input center">
        <label for="image">Image:</label>
            <input class="inpImg" type="file" id="image" name="image" accept="image/*" value="<?php echo $row['img_srcs'] ?? ''; ?>">
        </div>

        <div class="button-input">
            <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
        </div>
        <button type="submit">EDIT</button><br>
        <a href="../"><button type="button">Home</button></a>
    </form>
</div>


<link rel="stylesheet" href="../css/addtable.css">