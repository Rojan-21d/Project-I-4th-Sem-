<?php
session_start();
$errors = []; // Array to store validation errors

// Checking if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirecting to Login Page
    exit;
}

require 'backend/databaseconnection.php';
include 'layout/header.php';
$userselects = $_SESSION['usertype'];

if ($userselects == "carrier") {
    $sql = "SELECT * FROM carrierdetails WHERE id = '" . $_SESSION['id'] . "'";
} elseif ($userselects == "consignor") {
    $sql = "SELECT * FROM consignordetails WHERE id = '" . $_SESSION['id'] . "'";
}

$result = $conn->query($sql);

$row = null;
if ($result && $result->num_rows > 0) {
    // Fetch values in row
    $row = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $contact = trim($_POST['contact']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $newPassword = $_POST['password'];

    if (empty($name) || empty($email) || empty($contact) || empty($address)) {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (!empty($newPassword) && (strlen($newPassword) < 8 || strlen($newPassword) > 24)) {
        $errors[] = "Password must be between 8 and 24 characters";
    }

    if (strlen($contact) !== 10) {
        $errors[] = "Contact Number Length must be 10";
    }

    if (empty($errors)) {
        // Check if a new image is selected
        if (!empty($_FILES['profile_pic']['name'])) {
            // Validate and process the image upload
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $upload_directory = 'img/uploads/';

            $img_name = $_FILES['profile_pic']['name'];
            $img_extension = pathinfo($img_name, PATHINFO_EXTENSION);

            if (!in_array($img_extension, $allowedExtensions)) {
                $errors[] = "Invalid image format. Allowed formats: JPG, JPEG, PNG.";
            } else {
                // Upload the new image and get the file path
                $uploaded_file_path = $upload_directory . uniqid() . '.' . $img_extension;

                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploaded_file_path)) {
                    // Update the image path in the database
                    if ($userselects == "carrier") {
                        $updateSql = "UPDATE carrierdetails SET img_srcs = '$uploaded_file_path'";
                    } elseif ($userselects == "consignor") {
                        $updateSql = "UPDATE consignordetails SET img_srcs = '$uploaded_file_path'";
                    }
                } else {
                    // Failed to upload the new image
                    // Redirect to the profile page with an error message
                    header("Location: profile.php?error=2");
                    exit;
                }
            }
        } else {
            // No new image selected, update other fields only
            if ($userselects == "carrier") {
                $updateSql = "UPDATE carrierdetails SET";
            } elseif ($userselects == "consignor") {
                $updateSql = "UPDATE consignordetails SET";
            }
        }

        // Add other fields to the update query
        $updateSql .= " name = '$name', contact = '$contact', email = '$email', address ='$address'";

        if (!empty($newPassword)) {
            // Update the password field if a new password is provided
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql .= ", password = '$hashedPassword'";
        }
        $updateSql .= " WHERE id = " . $_SESSION['id'];

        if ($conn->query($updateSql) === TRUE) {
            // Update the session variables with the new values
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['contact'] = $contact;
            $_SESSION['address'] = $address;
            // Update the session variable for the profile picture
            if (!empty($uploaded_file_path)) {
                $_SESSION['profilePic'] = $uploaded_file_path;
            }
            // Redirect to the profile page with a success message
            header("Location: profile.php?success=1");
            exit;
        } else {
            // Redirect to the profile page with an error message
            header("Location: profile.php?error=1");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="css/sweetAlert.css">
    <script src="js/imageValidation.js"></script>

    <script>
            document.querySelector('form').addEventListener('submit', function (event) {
                if (!validateForm()) {
                    event.preventDefault();
                }
            });
            
            // Client-side validation function with SweetAlert integration
            function validateForm() {
                var errors = [];
                var name = document.getElementById("name").value;
                var email = document.getElementById("email").value;
                var password = document.getElementById("password").value;
                var phone = document.getElementById("contact").value; // Corrected ID
                
                if (name === "") {
                    errors.push("Name is required.");
                }
                
                if (email === "") {
                    errors.push("Email is required.");
                } else if (!validateEmail(email)) {
                    errors.push("Invalid email format.");
                }
                
                if (password !== "") { // Check if password is provided
                    if (password.length < 8 || password.length > 24) {
                        errors.push("Password must be between 8 and 24 characters.");
                    }
                }
                
                if (phone === "") {
                    errors.push("Phone number is required.");
                } else if (phone.length !== 10) {
                    errors.push("Phone number must be 10 digits.");
                }
                
                // Display errors using SweetAlert with bullet points
                if (errors.length > 0) {
                    var errorMessage = `<div class="error-list">${errors.map(error => `• ${error}`).join("<br>")}</div>`;
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Error',
                        html: errorMessage,
                        showCloseButton: true,
                    });
                    
                    return false; // Prevent form submission
                }
                
                return true; // Allow form submission
            }
            
            function validateEmail(email) {
                var re = /\S+@\S+\.\S+/;
                return re.test(email);
            }

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
        <form action="" method="POST" onsubmit="return validateForm();" enctype="multipart/form-data">
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
</body>
</html>
