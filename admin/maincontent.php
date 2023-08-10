<link rel="stylesheet" href="../css/adminMain.css">
<?php
require '../backend/databaseconnection.php';
// require 'sqlFunc.php';
// require 'displayresult.php';
// require 'update.php';

// Create an instance of the Select class
// $selectObj = new Select($conn); // Replace $conn with your database connection variable

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
// $deleteObj = new Delete($conn);

// Check if the delete form is submitted
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Call the deleteQueryById function with table name and id
    // $deleteResult = $deleteObj->deleteQueryById($table, $id);
    $sql = "DELETE FROM $table WHERE id=$id";
    $result = mysqli_query($conn, $sql);

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
                // $result = $selectObj->selectQuery($table); // Assign the result to a variable
                        
                $sql = "SELECT * FROM `$table`";
                $result = mysqli_query($conn, $sql);
                // displayUser($result);
                
                // function displayUser($result) {
                    if ($result && mysqli_num_rows($result) > 0) {
                
                        // Get the column names from the first row of the result
                        $columns = array_keys(mysqli_fetch_assoc($result));
                        mysqli_data_seek($result, 0); // Reset the result pointer to the beginning
                
                        // Columns to exclude from the table
                        $excludedColumns = ['password', 'img_srcs', 'id'];
                
                        // Remove the excluded columns from the array of column names
                        $columns = array_filter($columns, function($column) use ($excludedColumns) {
                            return !in_array($column, $excludedColumns);
                        });
                
                        // Display column headers
                        echo "<tr><th>SN</th>";
                        foreach ($columns as $column) {
                            echo "<th>" . strtoupper($column) . "</th>";
                        }        
                        echo "<th>ACTION</th>"; // Add a placeholder for the action column
                        echo "</tr>";
                
                        // Display table rows
                        $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr><td>$i</td>";
                            foreach ($row as $column => $value) {
                                if (!in_array($column, $excludedColumns)) {
                                    echo "<td>$value</td>";
                                }
                                if ($column === 'id') {
                                    $id = $value; // Assign the id column value to the $id variable
                                }
                            }
                            echo "<td class='td-center'>                        
                                <form action='' method='post' class='deleteBtn'>
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='id' value='" . $id . "'>
                                    <button type='submit'>Delete</button>
                                </form></td>";
                            echo "</tr>";
                            $i++;
                        }
                
                    } else {
                        echo "<td>No data to display.</td>";
                    }
                // }
            ?>
        </table>
    </div>
</div>
