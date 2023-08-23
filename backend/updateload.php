<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="../css/sweetAlert.css">
<link rel="stylesheet" href="../css/addtable.css">

<?php
require 'databaseconnection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    // The form has been submitted and 'id' key exists in $_POST
    $id = $_POST['id'];
    $name = $_POST["name"];
    $origin = $_POST["origin"];
    $destination = $_POST['destination'];
    $distance = $_POST['distance'];
    $description = $_POST['description'];
    $weight = $_POST['weight'];

    // Process the uploaded image only if a new file is selected
    $imageDestination = '';

    if (!empty($_FILES['image']['tmp_name'])) {
        $image = $_FILES['image'];
        $imageFileName = $image['name'];
        $imageTempName = $image['tmp_name'];
        $imageDestination = 'imageLoads/' . $imageFileName;

        // Move the uploaded image to a specific directory
        if (move_uploaded_file($imageTempName, $imageDestination)) {
            // Image uploaded successfully
        } else {
            // Failed to upload image
            // echo "<script> alert('Failed to upload image.');</script>";
            echo "<script>Swal.fire('Failed to upload image.');
            </script>";
        }
    }

    $sql = "UPDATE loaddetails SET
            name = ?,
            origin = ?,
            destination = ?,
            distance = ?,
            description = ?,
            weight = ?";

    $params = [$name, $origin, $destination, $distance, $description, $weight];

    // Update the image path only if a new image was uploaded
    if (!empty($imageDestination)) {
        $sql .= ", img_srcs = ?";
        $params[] = $imageDestination;
    }

    $sql .= " WHERE id = ?";
    $params[] = $id;

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        if ($stmt->execute()) {
            echo "<script> alert('Updated Sucessfully.');</script>";
            // echo "<script> Swal.fire('Updated Sucessfully.');</script>";

        } else {
            echo "<script> alert('Update failed: " . $stmt->error . "');</script>";
            // echo "<script> Swal.fire('Update failed: " . $stmt->error . "');</script>";

        }
        $stmt->close();
    } else {
        echo "<script> alert('Update query preparation failed: " . $conn->error . "');</script>";
        // echo "<script> Swal.fire('Update query preparation failed: " . $conn->error . "');</script>";

    }
}

// Fetch the load details for editing
$sql = "SELECT * FROM loaddetails WHERE id = ?";
$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param('i', $_SESSION['load_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "<script> alert('Load details fetch query preparation failed: " . $conn->error . "');</script>";
    // echo "<script> Swal.fire('Load details fetch query preparation failed: " . $conn->error . "');</script>";
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
            <input class="inpImg" type="file" id="image" name="image" accept="image/*">
        </div>

        <div class="button-input">
            <input type="hidden" name="id" value="<?php echo $_SESSION['load_id']; ?>">
        </div>
        <button type="submit">Update</button><br>
        <a href="../"><button type="button">Home</button></a>
    </form>
</div>

