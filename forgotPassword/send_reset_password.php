<!-- Include SweetAlert library from CDN -->
<style>
    body {
        background-color: #292929;
        margin: 0;
        padding: 0;
        font-family: 'Roboto', sans-serif;
        box-sizing: border-box;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="../css/sweetAlert.css">

<?php
$email = $_POST['email'];
$userSelects = $_POST['userselects'];
$randomNumberOTP = mt_rand(100000, 999999);
$otp_hash = hash("sha256", $randomNumberOTP);
$expiry = date("y-m-d H:i:s", time() + 60 * 10);

require '../backend/databaseconnection.php';

session_start();
$_SESSION['userSelects'] = $userSelects; // Set the userSelects in the session

$table = ($userSelects === "carrier") ? "carrierdetails" : "consignordetails";
$sql = "UPDATE $table 
        SET reset_otp_hash = ? ,
            reset_otp_expires_at = ?
        WHERE email = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $otp_hash, $expiry, $email);
$stmt->execute();

if ($conn->affected_rows) {
    $mail = require __DIR__ . "/mailer.php";
    $mail->setFrom("gantabyaproject@gmail.com"); // Replace with your from email
    $mail->addAddress($email);
    $mail->Subject = "Password Reset";
    $mail->Body = <<<END
Your OTP to reset password: $randomNumberOTP. OTP expires in 10 minutes.
END;

    try {
        $mail->send();
        // Redirect to the OTP verification page
        ?>
        "<script>
        Swal.fire({
            title: 'Email sent.',
            text: 'Please check your inbox.',
            showCancelButton: false,
            confirmButtonText: 'OK'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'otpVerify.php';
            }
        });
        </script>";    
        <?php
        exit;
    } catch (Exception $e) {
        // Display error message using SweetAlert
        ?>
        <script>
        Swal.fire({
            title: 'Error',
            text: 'Message could not be sent. Mailer error: <?=$mail->ErrorInfo?>',
            footer: '<a href="forgot_password.php">Next</a> to go to forgot_password.php'
        });
        </script>
        <?php
    }
} else {
    // Display error message using SweetAlert
    ?>
    <script>
    Swal.fire({
        title: 'Error',
        text: 'Email update failed.',
        footer: '<a href="forgot_password.php">Next</a> to go to forgot_password.php'
    });
    </script>
    <?php
}
?>

<?php
// $email = $_POST['email'];
// $userSelects = $_POST['userselects'];
// $token = bin2hex(random_bytes(16));
// $token_hash = hash("sha256", $token);
// $expiry = date("y-m-d H:i:s", time() + 60 * 30);

// require '../backend/databaseconnection.php';

// $table = ($userSelects === "carrier") ? "carrierdetails" : "consignordetails";
// $sql = "UPDATE $table 
//         SET reset_token_hash = ? ,
//             reset_token_expires_at = ?
//         WHERE email = ?";

// $stmt = $conn->prepare($sql);
// $stmt->bind_param("sss", $token_hash, $expiry, $email);
// $stmt->execute();

// $randomNumberOTP = mt_rand(100000, 999999);

// if ($conn->affected_rows) {
//     $mail = require __DIR__ . "/mailer.php";
//     $mail->setFrom("gantabyaproject@gmail.com"); // Replace with your from email
//     $mail->addAddress($email);
//     $mail->Subject = "Password Reset";
//     $mail->Body = <<<END
// Click <a href="https://localhost:8443/forgotPassword/reset_password.php?resetToken=$token">here</a>
// to reset your password. OTP: $randomNumberOTP
// END;

//     try {
//         $mail->send();
//         // Display success message using SweetAlert
//         ?>
//         "<script>
//         Swal.fire({
//             title: 'Email sent.',
//             text: 'Please check your inbox.',
//             showCancelButton: false,
//             confirmButtonText: 'OK'
//         }).then((result) => {
//             if (result.isConfirmed) {
//                 window.location.href = 'forgot_password.php';
//             }
//         });
//         </script>";    
//         <?php
//     } catch (Exception $e) {
//         // Display error message using SweetAlert
//         ?>
//         "<script>
//         Swal.fire({
//             title: 'Error',
//             text: 'Message could not be sent. Mailer error: {$mail->ErrorInfo}',
//             footer: '<a href=\"forgot_password.php\">Next</a> to go to forgot_password.php'
//         });
//         </script>";
//         <?php
//     }
// } else {
//     // Display error message using SweetAlert
//     ?>
//     "<script>
//         Swal.fire({
//             title: 'Error',
//             text: 'Email update failed.',
//             footer: '<a href=\"forgot_password.php\">Next</a> to go to forgot_password.php'
//         });
//     </script>";
//     <?php
// }
?>
