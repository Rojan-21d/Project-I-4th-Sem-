<?php
require '../backend/databaseconnection.php';

?>
<div class="head-table">
<form action="adminpanel.php">
<button type="submit" name="carrier" default>Carrier</button>
<button type="submit" name="consignor">Consignor</button>
<button type="submit" name="shipment">Shipment</button>
</form>
</div>
<?php
if(isset($_post['carrier']))
{
    include 'carriertable.php';
}

elseif(isset($_POST['consignor']))
include 'consignortable.php';

elseif(isset($_POST['shipment']))
include 'shipmenttable.php';

?>

