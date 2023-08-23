<link rel="stylesheet" href="../css/adminMain.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="../css/sweetAlert.css">
<?php
require '../backend/databaseconnection.php';

// Check if a button is selected and assign a class to highlight it
$carrierSelected = isset($_POST['carrier']) ? 'selected' : '';
$consignorSelected = isset($_POST['consignor']) ? 'selected' : '';
$loadSelected = isset($_POST['load']) ? 'selected' : '';

// Retrieve the selected button from the URL parameter
$selectedButton = isset($_GET['selected']) ? $_GET['selected'] : '';

// Determine which button was selected and set the corresponding class
if ($selectedButton === 'carrier') {
    $carrierSelected = 'selected';
    $table = 'carrierdetails'; // Set the appropriate table based on the selected button
} elseif ($selectedButton === 'consignor') {
    $consignorSelected = 'selected';
    $table = 'consignordetails'; // Set the appropriate table based on the selected button
} elseif ($selectedButton === 'load') {
    $loadSelected = 'selected';
    $table = 'loaddetails'; // Set the appropriate table based on the selected button
} else {
    $carrierSelected = 'selected';
    $table = 'carrierdetails';
}

$deleteTable = ''; // Initialize the deleteTable variable

// Check if the delete form is submitted
// Check if the delete form is submitted
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Retrieve the deleteTable value
    $deleteTable = $_POST['deleteTable'];

    // Construct the SQL DELETE query using the correct table name
    if (!empty($deleteTable)) {
        $sql = "DELETE FROM $deleteTable WHERE id=$id";
        $result = mysqli_query($conn, $sql);
    }
}


?>
<div class="admin-main">
    <div class="head-table">
    <form action="?selected=carrier" method="POST">
        <button type="submit" name="carrier" class="<?php echo $carrierSelected; ?>">Carrier</button>
    </form>
    <form action="?selected=consignor" method="POST">
        <button type="submit" name="consignor" class="<?php echo $consignorSelected; ?>">Consignor</button>
    </form>
    <form action="?selected=load" method="POST">
        <button type="submit" name="load" class="<?php echo $loadSelected; ?>">Loads</button>
    </form>
    </div>

    <div class="table-container">
        <table>
            <?php
                $sql = "SELECT * FROM `$table`";
                $result = mysqli_query($conn, $sql);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    $columns = array_keys(mysqli_fetch_assoc($result));
                    mysqli_data_seek($result, 0);

                    $excludedColumns = ['password', 'img_srcs', 'id', 'reset_otp_hash', 'reset_otp_expires_at'];

                    echo "<tr><th>SN</th>";
                    foreach ($columns as $column) {
                        if (!in_array($column, $excludedColumns)) {
                            echo "<th>" . strtoupper($column) . "</th>";
                        }
                    }        
                    echo "<th>ACTION</th>";
                    echo "</tr>";

                    $i = 1;
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr><td>$i</td>";
                        foreach ($row as $column => $value) {
                            if (!in_array($column, $excludedColumns)) {
                                echo "<td>$value</td>";
                            }
                            if ($column === 'id') {
                                $id = $value;
                            }
                        }
                        echo "<td class='td-center'>
                        <form action='' method='post' class='deleteBtn' onsubmit=\"confirmDelete(event)\">
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='id' value='" . $id . "'> <!-- Sending id -->
                            <input type='hidden' name='deleteTable' value='" . $table . "'> <!-- Sending table selected -->
                            <button type='submit'>Delete</button>
                        </form>
                        </td>";
                    
                        echo "</tr>";
                        $i++;
                    }
                } else {
                    echo "<td>No data to display.</td>";
                }
            ?>
        </table>
    </div>
</div>
<script src="../js/confirmationSA.js"></script>



