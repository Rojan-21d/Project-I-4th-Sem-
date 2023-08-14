<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$otp = $_SESSION['otp'];
$otp_hash = hash("sha256", $otp);

require '../backend/databaseconnection.php';
$userselects = ($_SESSION['userSelects'] === "carrier") ? "carrier" : "consignor";

$table = ($userselects === "carrier") ? "carrierdetails" : "consignordetails";
    
$sql = "SELECT * FROM $table WHERE reset_otp_hash = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $otp_hash);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc(); // Retrive and store user info
    
if($user === null) {
    die("OTP not found");
}   
if(strtotime($user["reset_otp_expires_at"]) <= time()){
    die("OTP has expired");
}

$errors = [];

if(isset($_POST["verify"])){
    // Validation
    $password = $_POST['password'];
    $password_confirmation = $_POST['password_confirmation'];
    
    if (strlen($password) < 8 || strlen($password) > 24) {
        $errors[] = "Password must be between 8 and 24 characters";
    }
    
    if ($password !== $password_confirmation){
        $errors[] = 'Passwords do not match!';
    }

    if (empty($errors)) {
        // Hashing
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
    
        // Updating in Database
        $sql = "UPDATE $table 
                SET password = ?,
                    reset_otp_hash = NULL,
                    reset_otp_expires_at = NULL
                WHERE ID = ?";
    
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password_hash, $user['id']); // Use "si" for string and integer bindings
        $stmt->execute();
    
        if ($stmt->affected_rows > 0) {
            // Password changed successfully
            ?>
            <script>
                Swal.fire({
                    title: "Success",
                    text: "Password Changed",
                    icon: "success",
                    confirmButtonText: "OK"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "login.php";
                    }
                });
            </script>
            <?php
        } else {
            // Password change failed
            ?>
            <script>
                Swal.fire({
                    title: "Error",
                    text: "Password change failed",
                    icon: "error",
                    confirmButtonText: "OK"
                });
            </script>
            <?php
        }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7b1b8b2fa3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.20/dist/sweetalert2.all.min.js"></script>
    <title>Reset Password</title>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="login">    
        <div class="input-field ">
            <i class="fa-solid fa-key"></i>
            <input type="password" placeholder="Password *" name="password" id="password" required>
            <i class="fa-regular fa-eye toggle-password"></i>
        </div>  
        <div class="input-field ">
            <i class="fa-solid fa-key"></i>
            <input type="password" placeholder="Confirm Password *" name="password_confirmation" id="password_confirmation" required>
            <i class="fa-regular fa-eye toggle-password"></i>
        </div>  
        <button type="submit" name="verify">Reset Password</button>
    </form>

    <script src="../js/showpwd.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const form = document.querySelector(".login");
            const passwordField = document.getElementById("password");
            const confirmPasswordField = document.getElementById("password_confirmation");
            
            form.addEventListener("submit", function (event) {
                event.preventDefault();
                
                const password = passwordField.value;
                const confirmPassword = confirmPasswordField.value;
                const errors = [];
                
                if (password !== confirmPassword) {
                    errors.push("Passwords do not match.");
                }
                if (password.length < 8 || password.length > 24) {
                    errors.push("Password must be between 8 and 24 characters.");
                }
                
                if (errors.length > 0) {
                    const errorMessage = errors.join("\n");
                    Swal.fire("Error", errorMessage, "error");
                    return;
                }
                
                // If validation passes, submit the form
                form.submit();
            });
        });
    </script>
</body>
</html>