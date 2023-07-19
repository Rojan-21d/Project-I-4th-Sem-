<?php
    session_start();

    // Check if the user is not logged in
    if(!isset($_SESSION['email'])) {
        // Redirect the user to the login page or any other authentication page
        header("Location: login.php");
        exit;
    }
    // Database connection
    require 'backend/databaseconnection.php';
    include 'layout/header.php';

    $sql = "SELECT loaddetails.id, loaddetails.name, loaddetails.img_srcs, loaddetails.dateofpost, loaddetails.status, shipment.id AS shipment_id
        FROM loaddetails 
        INNER JOIN shipment ON loaddetails.id = shipment.load_id 
        WHERE shipment.carrier_id = '" . $_SESSION['id'] . "'";
    $result = $conn->query($sql);
?>

<link rel="stylesheet" href="css/headerfooterstyle.css">
<link rel="stylesheet" href="css/maincontentstyle.css">

<title>History</title>
<div class="congmain">
    <div class="table-container">
        <div class="head">
             <h2>Your History</h2>
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

                        // Access the columns using $row['column_name']
                        $load_id = $row['id'];
                        $shipment_id = $row['shipment_id'];
                        
                        // Getting status
                        $stat = "";

                        if ($row['status'] == "booked") {
                            $stat = "Booked";
                        } elseif ($row['status'] == "delivered") {
                            $stat = "Delivered";
                        }

                        echo "<tr class='tr-bottom'>
                        <td>" . $i . "</td>
                        <td>" .$row['name'] . "</td>
                        <td><img src='" . $row['img_srcs'] . "' > </td>
                        <td>" . $row['dateofpost'] . "</td>
                        <td>" . $stat . "</td>";
                        echo "</td>
                        <td>
                            <div class='td-center'>
                                <form action='backend/moredeleteload.php' method='post' class='moreBtn'>
                                    <input type='hidden' name='action' value='more'>
                                    <input type='hidden' name='id' value='" . $load_id . "'>
                                    <input type='hidden' name='shipment_id' value='" . $shipment_id ."'>
                                    <button type='submit'>More</button>
                                </form>
                    
                                <form action='backend/moredeleteload.php' method='post' class='cancelBtn'>
                                    <input type='hidden' name='action' value='cancel'>
                                    <input type='hidden' name='id' value='" . $load_id . "'>
                                    <input type='hidden' name='shipment_id' value='" . $shipment_id . "'>
                                    <button type='submit'>Cancel</button>
                                </form>
                            </div>
                        </td>
                    </tr>";
                        $i++; // Increment $i after each iteration
                    }
                }else{
                    echo "<tr><td colspan='6'>No Records Found</td></tr>";                    
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</div>
<?php
    include 'layout/footer.php';
?>