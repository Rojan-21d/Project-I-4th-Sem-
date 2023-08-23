<link rel="stylesheet" href="css/maincontentstyle.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="css/sweetAlert.css">

<?php
$sql = "SELECT * FROM loaddetails WHERE consignor_id = '" . $_SESSION['id'] . "'";
$result = $conn->query($sql);
?>
<div class="congmain">
    <form action="layout/addload.php" method="post" class="addForm">
        <button type="submit" name="addLoad">ADD LOAD</button>
    </form>
    <div class="table-container">
        <div class="head">
             <h2>Your Loads</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th width="2%">S.N.</th>
                    <th width="40%">Name</th>
                    <th width="5%">Photo</th>
                    <th width="10%">Date of Uploaded</th>
                    <th width="15%">Status</th>
                    <th width="20%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    $i = 1; // Initialize $i variable
                    while ($row = $result->fetch_assoc()) {

                        //Getting a id to work for delete and more
                        $load_id = $row['id'];

                        // Getting status
                        $stat = "";
                        if ($row['status'] == "notBooked") {
                            $stat = "Not Booked";
                        } elseif ($row['status'] == "booked") {
                            $stat = "Booked";
                        } elseif ($row['status'] == "delivered") {
                            $stat = "Delivered";
                        }

                        echo "<tr class='tr-bottom'>
                        <td>" . $i . "</td>
                        <td>" .$row['name'] . "</td>
                        <td><img src='" . $row['img_srcs'] . "' > </td>
                        <td>" . $row['dateofpost'] . "</td>
                        <td>";
                        
                        if ($row['status'] == "notBooked") {
                            echo "<p class='status notBooked'>Not Booked</p>";
                        } elseif ($row['status'] == "booked") {
                            echo "<p class='status booked'>Booked</p>";
                        } elseif ($row['status'] == "delivered") {
                            echo "<p class='status delivered'>Delivered</p>";
                        }

                        echo "</td>
                        <td>
                            <div class='td-center'>
                                <form action='backend/moredeleteload.php' method='post' class='moreBtn'>
                                    <input type='hidden' name='action' value='more'>
                                    <input type='hidden' name='id' value='" . $load_id . "'>
                                    <input type='hidden' name='shipment_id' value=''>
                                    <button type='submit'>More</button>
                                </form>
                    
                                <form action='backend/moredeleteload.php' method='post' class='deleteBtn' onsubmit=\"confirmDelete(event)\">
                                    <input type='hidden' name='action' value='delete'>
                                    <input type='hidden' name='id' value='" . $load_id . "'>
                                    <button type='submit'>Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>";
                        $i++; // Increment $i after each iteration
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<script src="js/confirmationSA.js"></script>

