<?php
$errors = array(); // A single array to store all validation errors

// ... Database connection ...
require 'backend/databaseconnection.php';

if (isset($_POST['signupBtn'])) {
    $name = $_POST['name'];
    $email = strtolower($_POST['email']);
    $contact = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $userselects = $_POST['userselects'];
    $table = ($userselects === "carrier") ? "carrierdetails" : "consignordetails";

    // Validate form inputs
    if (empty($name) || empty($email) || empty($contact) || empty($address) || empty($password)) {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    $reName = '/^[A-Z][a-z]+ [A-Z][a-z]+$/';
    if(!preg_match($reName, $name)){
        $errors[] = "Name must be like Rojan Dumaru";
    }

    if (strlen($password) < 8 || strlen($password) > 24) {
        $errors[] = "Password must be between 8 and 24 characters";
    }

    if (strlen($contact) !== 10) {
        $errors[] = "Contact Number Length must be 10";
    }

    // Uniqye Key Email Validation 
    $sql_check_mail = "SELECT * FROM $table WHERE email = '$email'";
    $result_check_mail = $conn->query($sql_check_mail);
    if ($result_check_mail->num_rows > 0){
        $errors[] = "Email Already Registered";
    }


    // ... Image upload validation ...
    if (!empty($_FILES['profile_pic']['name'])) {
        $allowed_formats = array('jpg', 'jpeg', 'png');
        $upload_directory = 'img/profileUploads/';
        $img_name = $_FILES['profile_pic']['name'];
        $img_extension = pathinfo($img_name, PATHINFO_EXTENSION);

        // Validate the file extension
        if (!in_array(strtolower($img_extension), $allowed_formats)) {
            $errors[] = "Only JPG, JPEG, and PNG images are allowed.";
        } else {
            $uploaded_file_path = $upload_directory . $img_name;
            if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploaded_file_path)) {
                $errors[] = "Error uploading the image.";
            }
        }
    } else {
        $uploaded_file_path = 'img/defaultImg/user-regular.png';
    }

    if (empty($errors)) {

        // Prepare and execute the SQL query
        $sql = "INSERT INTO $table (name, img_srcs, email, contact, address, password) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            $errors[] = "Error in database connection.";
        } else {
            // Sanitize user inputs
            $name = mysqli_real_escape_string($conn, $name);
            $email = mysqli_real_escape_string($conn, $email);
            $contact = mysqli_real_escape_string($conn, $contact);
            $address = mysqli_real_escape_string($conn, $address);
            $password = mysqli_real_escape_string($conn, $password);

            // Hashing password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Bind parameters and execute
            $stmt->bind_param("ssssss", $name, $uploaded_file_path, $email, $contact, $address, $hashedPassword);
            if ($stmt->execute()) {
                header("Location: signup.php?success=1");
                exit;
            } else {
                $errors[] = "An error occurred while processing your request. Please try again later.";
            }
        }
    }
}

// Display errors using SweetAlert
if (!empty($errors)) {
    $errorMessages = join("\n", $errors);
    echo '.<script>
    document.addEventListener("DOMContentLoaded", function() {
    Swal.fire({
        icon: "error",
        title: "Sign Up Errors",
        html: "' . $errorMessages . '",
        showCloseButton: true,
    });
});

    </script>';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>   -->
    <script src="js/sweetalert.js"></script>
    <script src="js/imageValidation.js"></script>
    <script src="https://kit.fontawesome.com/7b1b8b2fa3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/registration.css">
    <link rel="stylesheet" href="css/sweetAlert.css">
    <title>Gantabya - Sign up</title>
</head>
<body>
    <div class="container">
        <div class="form-box">
            <div class="topic">
                <h1>Sign Up</h1>
            </div>
            <?php if (isset($_GET['success'])) { ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            title: 'Signup Successful!',
                            showConfirmButton: true
                        }).then(function() {
                            window.location.href = 'login.php';
                        });
                    });
                    </script>
            <?php } ?> 

            <div class="content">
                <div class="logo-section">
                    <div class="logo">
                        <img class="logo-img" src="img/defaultImg/mainLogo2.png" alt="logo">
                    </div>
                </div>
                <div class="input-section">
                    <form method="post" action="" class="login" enctype="multipart/form-data" onsubmit="return validateForm();">
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-user left"></i>
                                <input type="text" placeholder="Name *" name="name" id="name" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-envelope left"></i>
                                <input type="email" placeholder="Email *" name="email" id="email" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-key left"></i>
                                <input type="password" placeholder="Password *" name="password" id="password" required>
                                <i class="fa-regular fa-eye" id="togglePassword"></i>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-phone left"></i>
                                <input type="text" placeholder="Phone *" name="phone" id="phone" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-regular fa-address-card left"></i>
                                <input type="text" placeholder="Address *" name="address" id="address" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-image left"></i>
                                <input type="file"  name="profile_pic" id="profile_pic" accept="image/*">
                            </div>
                        </div>
                        <div class="user-selects">
                            <div class="input-field">
                                <input type="radio" id="carrier" name="userselects" value="carrier" checked>
                                <label for="carrier">Carrier</label>
                            </div>
                            <div class="input-field">
                                <input type="radio" id="consignor" name="userselects" value="consignor">
                                <label for="consignor">Consignor</label>
                            </div>
                        </div>
                        <div class="btn-field">
                            <button type="submit" name="signupBtn" value="signup">Sign Up</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="last">
                <small>Already have an account? <a href="login.php">Log in here!</a></small>
            </div>
        </div>
    </div>
    <script src="js/imgPreview.js"></script>
    <script src="js/formValidation.js"></script>
    <script src="js/showpwd.js"></script>
</body>
</html>
