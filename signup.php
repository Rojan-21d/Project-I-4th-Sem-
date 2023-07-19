<?php
if (isset($_POST['signupBtn'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $userselects = $_POST['userselects'];
    
    $errors = array(); // A single array to store all validation errors

    // Validate form inputs
    if (empty($name) || empty($email) || empty($contact) || empty($address) || empty($password)) {
        $errors[] = "All fields are required";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (strlen($password) < 8 || strlen($password) > 24) {
        $errors[] = "Password must be between 8 and 24 characters";
    }

    if (strlen($contact) !== 10) {
        $errors[] = "Contact Number Length must be 10";
    }

    // If no errors, move the uploaded image file to the desired location
    if (!empty($_FILES['profile_pic']['name'])) {
        $upload_directory = 'img/uploads/'; // Replace 'uploads/' with the desired upload directory.
        $img_name = $_FILES['profile_pic']['name'];
        $uploaded_file_path = $upload_directory . $img_name;
        if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploaded_file_path)) {
            echo '<script>alert("Error uploading the image.");</script>';
            return;
        }
    } else {
        // If no image is selected, set 'img_srcs' to the default value
        $uploaded_file_path = 'img/images/user-regular.png';
    }

    // Database connection
    require 'backend/databaseconnection.php';

    // Prepare and execute the SQL query
    $table = ($userselects === "carrier") ? "carrierdetails" : "consignordetails";
    $sql = "INSERT INTO $table (name, img_srcs, email, contact, address, password) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        echo '<script>alert("Error in database connection.");</script>';
        return;
    }

    $stmt->bind_param("ssssss", $name, $uploaded_file_path, $email, $contact, $address, $password);
    if ($stmt->execute()) {
        echo '<script>alert("Signup Sucessfully");</script>';
        header('Location: login.php');
        exit;
    } else {
        echo '<script>alert("Error in database query: ' . mysqli_error($conn) . '");</script>';
    }
}
?>
<!-- Rest of the HTML code remains the same -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7b1b8b2fa3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/registration.css">
    <!-- <script src="js/imgPreview.js"></script> -->
    <!-- <script src="js/validation.js"></script> -->

    <title>Gantabya - Sign up</title>
</head>
<body>
    <div class="container"> 
        <div class="form-box">
            <div class="topic">
                <h1>Sign Up</h1>
            </div>
            <div class="content">
                <div class="logo-section">
                    <div class="logo">
                        <img class="logo-img" src="img/mainLogo2.png" alt="logo">
                    </div>
                </div>
                <div class="input-section">
                <form method="post" action="" class="login" enctype="multipart/form-data" > <!-- Added enctype for file upload -->
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" placeholder="Name *" name="name" id="name" value="<?php echo isset($name) ? $name : ''; ?>" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-envelope"></i>
                                <input type="email" placeholder="Email *" name="email" id="email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-key"></i>
                                <input type="password" placeholder="Password *" name="password" password="password" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-phone"></i>
                                <input type="text" placeholder="Phone *" name="phone" id="phone" value="<?php echo isset($phone) ? $phone : ''; ?>" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-regular fa-address-card"></i>
                                <input type="text" placeholder="Address *" name="address" id="address" value="<?php echo isset($address) ? $address : ''; ?>" required>
                            </div>
                        </div>
                        <div class="input-group">
                            <div class="input-field">
                                <i class="fa-solid fa-image"></i>
                                <input type="file" placeholder="Your Photo" name="profile_pic" id="profile_pic" accept="image/*">
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
</body>
</html>
