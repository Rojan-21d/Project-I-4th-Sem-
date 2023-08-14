<?php
if(isset($_POST["verify"])){
    $otp = $_POST["otp"];

    $otp_hash = hash("sha256", $otp); // This should be $token, not $otp

    require '../backend/databaseconnection.php';

    session_start();
    $userSelects = $_SESSION['userSelects'];    // Store the verified OTP in a session variable


    $table = ($userSelects === "carrier") ? "carrierdetails" : "consignordetails";
    $sql = "SELECT * FROM $table WHERE reset_otp_hash = ?";
    $stmt = $conn->prepare($sql);

    // $stmt = bind_param("s",$otp_hash); 
    $stmt->bind_param("s", $otp_hash);  // This should be $stmt->bind_param("s", $otp_hash);

    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if($user === null) {
        ?>
        <script>
        Swal.fire({
            title: 'OTP Expired',
            text: 'The OTP Not Found.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'forgot_password.php';
            }
        });
        </script>
        <?php
        exit; // Make sure to exit after displaying the message
        }

    if(strtotime($user["reset_otp_expires_at"]) <= time()){
        ?>
        <script>
        Swal.fire({
            title: 'OTP Expired',
            text: 'The OTP has expired.',
            icon: 'error',
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'forgot_password.php';
            }
        });
        </script>
        <?php
        exit; // Make sure to exit after displaying the message
        }

    // After verifying the OTP and before redirection
    $_SESSION['otp'] = $otp; // Store the verified OTP in a session variable

    header ('location: reset_password.php'); // This will cause a redirection
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
</head>
<body>
<form action="" method="post">
    <h1>OTP Verification</h1>
    OTP: <input type="number" name="otp">
    <button type="submit" name="verify">Verify</button>
</form>
</body>
</html>


<!-- // Validation
if (strlen($password) < 8 || strlen($password) > 24) {
    $errors[] = "Password must be between 8 and 24 characters";
}
if ($_POST['password'] !== $_POST['password_confirmation']){
    $errors[] = 'passwords do not match!';
}

// Hashing
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);


// Updating in Database
$sql = "UPDATE $table 
        SET passsword =?,
            reset_otp_hash = NULL,
            reset_otp_expires_at = NULL
        WHERE ID = ?";

$stmt = $conn->prepare($sql);
$stmt = bind_param("ss",$password_hash,$user['id']);
$stmt->execute();

echo "Password Changed"; -->