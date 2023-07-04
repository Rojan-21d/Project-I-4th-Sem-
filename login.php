<?php
//checking if user is already loged in
session_start();
if(isset($_SESSION['email'])){
    //Redirect user to home page
    header ("Location: home.php");
    exit;    
}

if (isset($_POST['loginbtn'])) {
    // Get the username and password from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $userselects = $_POST['userselects'];

    $errors = array();

    if (empty($email) || empty($password)) {
        $errors[] = "Email or Password Empty";
    }

    if (count($errors) > 0) {
        foreach ($errors as $errmsg) {
            echo "<script>alert('$errmsg');</script>";
        }
        echo "<script>window.location.href = 'login.php';</script>";
        exit;
    } else {
        // Database connection
        require 'backend/databaseconnection.php';

        // Checking userselects
        if ($userselects == "carrier") {
            $sql = "SELECT * FROM carrierdetails WHERE email = '$email' and password = '$password'";
        } elseif ($userselects == "consignor") {     
            $sql = "SELECT * FROM consignordetails WHERE email = '$email' and password = '$password'";
        }

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Fetch the row and store id and email values
            $row = $result->fetch_assoc();
            $id = $row['id'];
            $email = $row['email'];
            $usertype = $userselects;

            // Store the id and email in session variables
            $_SESSION['id'] = $id;
            $_SESSION['email'] = $email;
            $_SESSION['usertype'] = $usertype;

            // Redirect the user to the home page
            header("Location: home.php");
            exit;
        } else {
            echo "<script>alert('Email or Password Invalid');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
            exit;
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
    <script src="js/loginreg.js"></script>
    <title>Gantabya - Log in</title>
</head>
<body>
    <div class="container"> 
        <div class="form-box">
            <h1>Log In</h1>         
            <form method="post" action="" class="login">
            <div class="input-group-login" >
                    <div class="input-field " >
                        <i class="fa-solid fa-user"></i>
                        <input type="email" placeholder="Email" name="email" >
                    </div>
                    <div class="input-field ">
                        <i class="fa-solid fa-key"></i>
                        <input type="password" placeholder="Password" name="password" >
                    </div>     
                    <div class="error-hint hidden"><small>Email or Password Invalid.</small></div>
                </div>          
                <div class="user-selects">
                    <div class="carrier-part">
                    <input type="radio" id="carrier" name="userselects" value="carrier" checked>
                    <label for="carrier">Carrier</label>
                    </div>
                    <div class="consignor-part">
                    <input type="radio" id="consignor" name="userselects" value="consignor">
                    <label for="consignor">Consignor</label>
                    </div>
                </div>
                
                <small><a href="#">Forgot Password?</a></small>

                <div class="btn-field">
                    <button type="submit" name="loginbtn" value="login">Log In</button>
                </div> 
                
                <small><a href="signup.php"> Sign Up Here!</a> </small>

            </form>
        </div>    
    </div>


</body>
</html>