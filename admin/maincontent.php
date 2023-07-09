<?php
require '../backend/databaseconnection.php';
require 'sqlFunc.php';
require 'displayresult.php';

$selectObj = new Select($conn); // Replace $conn with your database connection variable
?>

<div class="head-table">
    <form action="adminpanel.php" method="POST"> <!-- Added method="POST" to the form -->
        <button type="submit" name="carrier">Carrier</button>
        <button type="submit" name="consignor">Consignor</button>
        <button type="submit" name="shipment">Shipment</button>
    </form>
</div>

<?php
if (isset($_POST['carrier'])) {
    $result = $selectObj->selectQuery('carrierdetails'); // Assign the result to a variable
    displayUser($result);
} elseif (isset($_POST['consignor'])) {
    $result = $selectObj->selectQuery('consignordetails'); // Assign the result to a variable
    displayUser($result);
}

// elseif(isset($_POST['shipment']))
// include 'shipmenttable.php';

?>
