<?php
// $token = $_POST["token"];

// $token_hash = hash("sha256", $token);

// require '../backend/databaseconnection.php';

// $table = ($userselects === "carrier") ? "carrierdetails" : "consignordetails";
// $sql = "SELECT * FROM $table WHERE reset_token_hash = ?";
// $stmt = $conn->prepare($sql);

// $stmt = bind_param("s",$token_hash);

// $stmt->execute();

// $result = $stmt->get_result();
// $user = $result->fetch_assoc();
// if($user === null) {
//     die("token not found");
// }
// if(strtotime($user["reset_token_expires_at"]) <= time()){
//     die("token has expired");
// }

// // Validation
// if (strlen($password) < 8 || strlen($password) > 24) {
//     $errors[] = "Password must be between 8 and 24 characters";
// }
// if ($_POST['password'] !== $_POST['password_confirmation']){
//     $errors[] = 'passwords do not match!';
// }

// // Hashing
// $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);


// // Updating in Database
// $sql = "UPDATE $table 
//         SET passsword =?,
//             reset_token_hash = NULL,
//             reset_token_expires_at = NULL
//         WHERE ID = ?";

// $stmt = $conn->prepare($sql);
// $stmt = bind_param("ss",$password_hash,$user['id']);
// $stmt->execute();

// echo "Password Changed";
?>
<?php
if(isset($_POST["verify"])){
$token = $_POST["otp"];

$otp_hash = hash("sha256", $otp);

require '../backend/databaseconnection.php';

$table = ($userselects === "carrier") ? "carrierdetails" : "consignordetails";
$sql = "SELECT * FROM $table WHERE reset_otp_hash = ?";
$stmt = $conn->prepare($sql);

$stmt = bind_param("s",$otp_hash);

$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();
if($user === null) {
    die("otp not found");
}
if(strtotime($user["reset_otp_expires_at"]) <= time()){
    die("otp has expired");
}

// Validation
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

echo "Password Changed";
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
        <input type="hidden" name="otp" value="otp">
        <button type="submit" name="verify">Verify</button>
    </form>
</body>
</html>