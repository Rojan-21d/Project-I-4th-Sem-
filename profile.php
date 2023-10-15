<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
$errors = []; // Array to store validation errors

// Checking if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirecting to Login Page
    exit;
}

require 'backend/databaseconnection.php';
include 'layout/header.php';
$userSelects = $_SESSION['usertype'];

if ($userSelects == "carrier") {
    $sql = "SELECT * FROM carrierdetails WHERE id = '" . $_SESSION['id'] . "'";
} elseif ($userSelects == "consignor") {
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
    $email = strtolower($email);
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
            $uploadDirectory = 'img/profileUploads/';
        
            $imgName = $_FILES['profile_pic']['name'];
            $imgExtension = pathinfo($imgName, PATHINFO_EXTENSION);
        
            if (!in_array($imgExtension, $allowedExtensions)) {
                $errors[] = "Invalid image format. Allowed formats: JPG, JPEG, PNG.";
            } else {
                // Upload the new image and get the file path
                $uploadedFilePath = $uploadDirectory . $imgName;
        
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadedFilePath)) {
                    // Image uploaded successfully, prepare to update database
                    if ($userSelects == "carrier") {
                        $updateSql = "UPDATE carrierdetails SET img_srcs = '$uploadedFilePath',";
                    } elseif ($userSelects == "consignor") {
                        $updateSql = "UPDATE consignordetails SET img_srcs = '$uploadedFilePath',";
                    }
                } else {
                    // Failed to upload the new image
                    // Redirect to the profile page with an error message
                    // header("Location: profile.php?error=2");
                    // Redirect to the profile page with a specific error message
                    header("Location: profile.php?error=imageUploadError");
                    exit;
                }
            }
        } else {
            // No new image selected, update other fields only
            if ($userSelects == "carrier") {
                $updateSql = "UPDATE carrierdetails SET";
            } elseif ($userSelects == "consignor") {
                $updateSql = "UPDATE consignordetails SET";
            }
        }
        
        // Add other fields to the update query
        $updateSql .= " name = '$name', contact = '$contact', email = '$email', address = '$address'";
        
        if (!empty($newPassword)) {
            // Update the password field if a new password is provided
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql .= ", password = '$hashedPassword'";
        }
        
        // Complete the update query
        $updateSql .= " WHERE id = " . $_SESSION['id'];

        if ($conn->query($updateSql) === TRUE) {
            // Update the session variables with the new values
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['contact'] = $contact;
            $_SESSION['address'] = $address;
            // Update the session variable for the profile picture
            if (!empty($uploadedFilePath)) {
                $_SESSION['profilePic'] = $uploadedFilePath;
            }
            
            // Redirect to the profile page with a success message
            header("Location: profile.php?success=1");
            exit;
        } else {
            // Redirect to the profile page with an error message
            // header("Location: profile.php?error=1");
            // Redirect to the profile page with a specific error message
            header("Location: profile.php?error=1");
            exit;
        }
    } // Display errors using SweetAlert
    else if (!empty($errors)) {
        $errorMessages = join("\n", $errors);
        echo '<script>
        swal({title:"Error!",text:"' .$errorMessages.'",icon : "warning"});
        Swal.fire({
            icon: "error",
            title: "Sign Up Errors",
            html: "' . $errorMessages . '",
            showCloseButton: true,
        });
        </script>';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <link rel="stylesheet" type="text/css" href="css/profile.css">
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
    <link rel="stylesheet" href="css/sweetAlert.css">
    <script src="js/sweetalert.js"></script>
    <script src="js/imageValidation.js"></script>
    <script src="js/imgPreview.js"></script>
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
            
            var reName = '/^[A-Z][a-z]+ [A-Z][a-z]+$/';
            if(!preg_match(reName, name)){
                errors.push("Name must be like Rojan Dumaru");
            }
            
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
            <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display: none;" onchange="previewImage(event)">
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