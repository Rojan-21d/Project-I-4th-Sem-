<?php
if (isset($_POST['action']) && isset($_POST['load_id']) && isset($_POST['consignor_id']) && isset($_POST['carrier_id'])) {
    $load_id = $_POST['load_id'];
    require 'databaseconnection.php';

    try {
        // Begin the transaction
        $conn->begin_transaction();

        $sql1 = "UPDATE loaddetails SET status = 'booked' WHERE id = '$load_id'";
        $sql2 = "INSERT INTO shipment (load_id, carrier_id, consignor_id) VALUES ('".$_POST['load_id']."', '".$_POST['carrier_id']."', '".$_POST['consignor_id']."')";
        
        // Execute the statements
        $conn->query($sql1);
        $conn->query($sql2);
        
        // Commit the transaction
        $conn->commit();
        
        echo "<script>alert('Booking Successful');</script>";
        echo "<script>window.location.href = '../home.php';</script>";
        exit;
    } catch (Exception $e) {
        // Rollback in case of error
        $conn->rollback();
        echo "<script>alert('Error: ".$e->getMessage()."');</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- Transferring the load id to furtherpage -->
</body>
</html>
