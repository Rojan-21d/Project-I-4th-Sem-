<?php
session_start();
// Checking if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirecting to Login Page
    exit;
}

require 'backend/databaseconnection.php';
include 'layout/header.php';
$userselects = $_SESSION['usertype']; 

$errors = []; // Initialize an empty array to store validation errors

if ($userselects == "carrier") {
    $sql = "SELECT * FROM carrierdetails WHERE id = '".$_SESSION['id']."'";
} elseif ($userselects == "consignor") {     
    $sql = "SELECT * FROM consignordetails WHERE id = '".$_SESSION['id']."'";
}

$result = $conn->query($sql);

$row = null;
if ($result && $result->num_rows > 0) {
    // Fetch values in row
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $newPassword = $_POST['password'];

    // Check if a new image is selected
    if (!empty($_FILES['profile_pic']['name'])) {
        // Upload the new image and get the file path
        $upload_directory = 'img/uploads/';
        $img_name = $_FILES['profile_pic']['name'];
        $uploaded_file_path = $upload_directory . $img_name;
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploaded_file_path)) {
            // New image uploaded, update the image path in the database
            $updateSql = "UPDATE ";

            if ($userselects == "carrier") {
                $updateSql .= "carrierdetails SET img_srcs = '$uploaded_file_path'";
            } elseif ($userselects == "consignor") {
                $updateSql .= "consignordetails SET img_srcs = '$uploaded_file_path'";
            }

            // Add other fields to the update query
            $updateSql .= ", name = '$name', contact = '$contact', email = '$email', address ='$address'";

            if (!empty($newPassword)) {
                // Update the password field if a new password is provided
                $updateSql .= ", password = '$newPassword'";
            }

            $updateSql .= " WHERE id = " . $_SESSION['id'];

        } else {
            // Failed to upload the new image
            // Redirect to the profile page with an error message
            header("Location: profile.php?error=2");
            exit;
        }
    } else {
        // No new image selected, update other fields only
        $updateSql = "UPDATE ";

        if ($userselects == "carrier") {
            $updateSql .= "carrierdetails SET";
        } elseif ($userselects == "consignor") {
            $updateSql .= "consignordetails SET";
        }

        // Add other fields to the update query
        $updateSql .= " name = '$name', contact = '$contact', email = '$email', address ='$address'";

        if (!empty($newPassword)) {
            // Update the password field if a new password is provided
            $updateSql .= ", password = '$newPassword'";
        }

        $updateSql .= " WHERE id = " . $_SESSION['id'];
    }

    if ($conn->query($updateSql) === TRUE) {
        // Update the session variables with the new values
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        $_SESSION['contact'] = $contact;
        $_SESSION['address'] = $address;
        // Update the session variable for the profile picture
        $_SESSION['profilePic'] = $uploaded_file_path;

        // Redirect to the profile page with a success message
        header("Location: profile.php?success=1");
        exit;
    } else {
        // Redirect to the profile page with an error message
        header("Location: profile.php?error=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="css/profile.css">
</head>
<body>
    <div class="container">
        <h1>Your Profile</h1>
        <a href="home.php" class="back-button">Back</a>
        <?php if (isset($_GET['success'])) { ?>
            <div class="success-message">
                Update successful!
            </div>
        <?php } elseif (isset($_GET['error'])) { ?>
            <div class="error-message">
                Update failed!
            </div>
        <?php } ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="profile-picture">
                <img src="<?php echo $row['img_srcs']; ?>" alt="Profile Picture" id="profilePicPreview">
                <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display: none;">
                <button type="button" class="edit-button" onclick="openFileInput()">Edit</button>
            </div>
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" value="<?php echo isset($row['name']) ? $row['name'] : ''; ?>" readonly>
                <button type="button" class="edit-button" onclick="enableEdit('name')">Edit</button>
            </div>
            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" value="<?php echo isset($row['contact']) ? $row['contact'] : ''; ?>" readonly>
                <button type="button" class="edit-button" onclick="enableEdit('contact')">Edit</button>
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo isset($row['address']) ? $row['address'] : ''; ?>" readonly>
                <button type="button" class="edit-button" onclick="enableEdit('address')">Edit</button>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" readonly>
                <button type="button" class="edit-button" onclick="enableEdit('email')">Edit</button>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter new password">
                <button type="button" class="edit-button" onclick="enableEdit('password')">Edit</button>
            </div>

            <div class="form-group">
                <input type="submit" value="Save Changes">
            </div>
        </form>
    </div>

    <script>
        function enableEdit(field) {
            document.getElementById(field).readOnly = false;
        }

        function openFileInput() {
            document.getElementById('profile_pic').click();
        }

        // Preview the selected image before uploading
        document.getElementById('profile_pic').addEventListener('change', function () {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function (event) {
                    document.getElementById('profilePicPreview').src = event.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>
