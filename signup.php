<?php

// Process form submission
if (isset($_POST['signupbtn'])) {
    // Get data from the form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $address = $_POST['address'];
    $password = $_POST['password'];
    $userselects = $_POST['userselects'];

    $errors = array();

    // Validate form inputs
    if (empty($name) || empty($email) || empty($contact) || empty($address) || empty($password)) {
        $errors[] = "All fields are required";
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }
    
    if (strlen($password) < 8 || strlen($password) > 24) {
        $errors[] = "Password must be between 8 and 24 characters";
    }
    
    if (strlen($contact) !== 10) {
        $errors[] = "Incorrect contact Number: Length must be 10";
    }

    // If there are errors, display them and stop further execution

    if (count($errors) > 0) {
        echo '<script>';
        foreach ($errors as $error) {
            echo 'alert("' . $error .'");';
        }
        echo '</script>';
    // Stop further execution
    return;

    } else {
        // Database connection
        require 'backend/databaseconnection.php';

        // Prepare and execute the SQL query
        $sql = "";
        if ($userselects === "carrier") {
            $stmt = $conn->prepare("INSERT INTO carrierdetails (name, email, contact, address, password) VALUES (?, ?, ?, ?, ?)");
        } elseif ($userselects === "consignor") {
            $stmt = $conn->prepare("INSERT INTO consignordetails (name, email, contact, address, password) VALUES (?, ?, ?, ?, ?)");
        }
        
        if (!$stmt) {
            echo '<script>alert("' . $conn->error . '");</script>';
        } else {
            $stmt->bind_param("sssss", $name, $email, $contact, $address, $password);
            if ($stmt->execute()) {
                header('Location: login.php');
                exit;
            } else {
                echo '<script>alert("' . $conn->error . '");</script>';
            }
        }
        
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7b1b8b2fa3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/login_reg.css">
    <title>Gantabya - Sign up</title>
</head>
<body>
    <div class="container"> 
        <div class="form-box">
            <h1>Sign Up</h1>            
            <form method="post" action="" class="login">
                <div class="input-group">
                    <div class="input-field">
                        <i class="fa-solid fa-user"></i>
                        <input type="text" placeholder="Name" name="name" value="<?php echo isset($name) ? $name : ''; ?>">
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-envelope"></i>
                        <input type="email" placeholder="Email" name="email" value="<?php echo isset($email) ? $email : ''; ?>">
                    </div>
                    <div class="input-field">
                        <i class="fa-solid fa-phone"></i>
                        <input type="text" placeholder="contact" name="contact" value="<?php echo isset($contact) ? $contact : ''; ?>">
                    </div>     
                    <div class="input-field">
                        <i class="fa-regular fa-address-card"></i>
                        <input type="text" placeholder="Address" name="address" value="<?php echo isset($address) ? $address : ''; ?>">
                    </div>     
                    <div class="input-field">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" placeholder="Password" name="password">
                    </div>               
                    <!-- <div class="input-field img">
                        <p>Your Photo</p>
                        <input class="inpImg" type="file" id="image" name="image" accept="image/*" placeholder="Your Image">
                    </div> -->
                </div>        
                <div class="user-selects">
                    <input type="radio" id="carrier" name="userselects" value="carrier" checked>
                    <label for="carrier">Carrier</label>
                    <input type="radio" id="consignor" name="userselects" value="consignor">
                    <label for="consignor">Consignor</label>
                </div>  

                <div class="btn-field">
                    <button type="submit" name="signupbtn" value="signup">Sign Up</button>
                </div>
                <small>Already have an account? <a href="login.php">Log in here!</a></small>
            </form>
        </div>
    </div>
</body>
</html>
