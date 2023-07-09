<link rel="stylesheet" href="../css/adminMain.css">
<?php
require '../backend/databaseconnection.php';
require 'sqlFunc.php';
require 'displayresult.php';

// Create an instance of the Select class
$selectObj = new Select($conn); // Replace $conn with your database connection variable

// Check if a button is selected and assign a class to highlight it
$carrierSelected = isset($_POST['carrier']) ? 'selected' : '';
$consignorSelected = isset($_POST['consignor']) ? 'selected' : '';
$loadSelected = isset($_POST['load']) ? 'selected' : '';

// Retrieve the last selected table from session variable or set the default table
$table = isset($_SESSION['selected_table']) ? $_SESSION['selected_table'] : 'carrierdetails';

// Check if a button is selected and update the table value
if (isset($_POST['carrier'])) {
    $table = 'carrierdetails';
    $_SESSION['selected_table'] = $table;
} elseif (isset($_POST['consignor'])) {
    $table = 'consignordetails';
    $_SESSION['selected_table'] = $table;
} elseif (isset($_POST['load'])) {
    $table = 'loaddetails';
    $_SESSION['selected_table'] = $table;
} else {
    // Check if no button is selected (initial access)
    if (!isset($_SESSION['selected_table'])) {
        $_SESSION['selected_table'] = $table; // Set default table as selected
    }
}

// Create an instance of the Delete class
$deleteObj = new Delete($conn);

// Check if the delete form is submitted
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Call the deleteQueryById function with the correct table name
    $deleteResult = $deleteObj->deleteQueryById($table, $id);

    // Update the selected table after deleting the record
    $_SESSION['selected_table'] = $table;
}
?>
<div class="admin-main">
    <div class="head-table">
        <form action="" method="POST"> <!-- Added method="POST" to the form -->
            <button type="submit" name="carrier" class="<?php echo (isset($_POST['carrier']) || (!isset($_POST['carrier']) && !isset($_POST['consignor']) && !isset($_POST['load']))) ? 'selected' : ''; ?>">Carrier</button>
            <button type="submit" name="consignor" class="<?php echo isset($_POST['consignor']) ? 'selected' : ''; ?>">Consignor</button>
            <button type="submit" name="load" class="<?php echo isset($_POST['load']) ? 'selected' : ''; ?>">Loads</button>
        </form>
    </div>
    <div class="table-container">
        <table>
            <?php
                $result = $selectObj->selectQuery($table); // Assign the result to a variable
                displayUser($result);
            ?>
        </table>
    </div>
</div>
