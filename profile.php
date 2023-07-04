<?php
session_start();
// Checking if the user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php"); // Redirecting to Login Page
    exit;
}

require 'backend/databaseconnection.php';
include 'layout/header.php';
$userselects = $_SESSION['usertype']; // Replace with your logic to determine the user type

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
    
// // Validate form inputs
// if (empty($name) || empty($email) || empty($contact) || empty($address)) {
//     $errors[] = "All fields are required";
// }

// if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
//     $errors[] = "Invalid email format";
// }

// if (!empty($newPassword) && (strlen($newPassword) < 8 || strlen($newPassword) > 24)) {
//     $errors[] = "Password must be between 8 and 24 characters";
// }

// if (!empty($contact) && strlen($contact) !== 10) {
//     $errors[] = "Contact number must be exactly 10 digits";
// }



//     // If there are errors, display them and stop further execution
//     if (count($errors) > 0) {
//         echo '<script>';
//         foreach ($errors as $error) {
//             echo 'alert("' . $error .'");';
//         }
//         echo 'window.location.href= "profile.php"';
//         echo '</script>';
//         // Stop further execution
//         return;
//     } else{
    
    // Update the corresponding table based on the user type
    if ($userselects == "carrier") {
        $updateSql = "UPDATE carrierdetails SET name = '$name', contact = '$contact', email = '$email', address ='$address'";
        if (!empty($newPassword)) {
            // New password is provided, update the password field
            // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            // $updateSql .= ", password = '$hashedPassword'";
            
            //$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);//$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql .= ", password = '$newPassword'";

        }
        $updateSql .= " WHERE id = " . $_SESSION['id'];
    } elseif ($userselects == "consignor") {
        $updateSql = "UPDATE consignordetails SET name = '$name', contact = '$contact', email = '$email', address ='$address'";
        if (!empty($newPassword)) {
            // // New password is provided, update the password field
            // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            // $updateSql .= ", password = '$hashedPassword'";
        
            //$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);//$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateSql .= ", password = '$newPassword'";
        }
        $updateSql .= " WHERE id = " . $_SESSION['id'];
    }

    if ($conn->query($updateSql) === TRUE) {
        // Redirect to the profile page with a success message
        header("Location: profile.php?success=1");
        exit;
    } else {
        // Redirect to the profile page with an error message
        header("Location: profile.php?error=1");
        exit;
    }
}
//}
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
        <form action="" method="POST">
            <div class="profile-picture">
                <img src="profile_pic.jpg" alt="Profile Picture">
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
                <input type="text" id="password" name="password" placeholder="Enter new password">
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
    </script>
</body>
</html>
