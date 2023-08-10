<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['email'])) {
    // Redirect the user to the login page or any other authentication page
    header("Location: ../login.php");
    exit;
}
require 'databaseconnection.php'; // Database connection

if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $_SESSION['load_id'] = $id;
    $shipment_id = isset($_POST['shipment_id']) ? $_POST['shipment_id'] : ''; // Set shipment_id to an empty string if it is not set
    $action = $_POST['action']; // Assign a value to $action
    
    if ($action == 'delete') {
        // Delete the row
        $sql = "DELETE FROM loaddetails WHERE id = '$id'";
        $result = $conn->query($sql);
        header('Location:../home.php');
    } elseif ($action == 'edit') {
        // Update Load Details
        header("Location: updateload.php");
    } elseif ($action == 'cancel'){ // Cancel Load Details
        try {
        $conn->begin_transaction();
    
        $sql = "UPDATE loaddetails SET status = 'notBooked' WHERE id = '$id'";
        $sql2 = "DELETE FROM shipment WHERE id = '$shipment_id'";
        
        $conn->query($sql);
        $conn->query($sql2);

        // Commit the transaction
        $conn->commit();

        echo "<script>alert('Canceled...');</script>";
        header ("Location: ../home.php");
        exit;
        } catch (\Throwable $th) {
            $conn->rollback();
            echo "<script language='javascript'>alert('ERROR! ". $th ."');</script>";
            header ("Location: ../home.php");
            exit();
        }
    }elseif ($action == 'more') {
        // More of the row
        $sql = "SELECT * FROM loaddetails WHERE id = '$id'";
        $result = $conn->query($sql);
        $more = mysqli_fetch_assoc($result);
        
        // Show by whom
        $sql2 = "SELECT * FROM shipment WHERE load_id = '$id'";
        $result2 = $conn->query($sql2);
        $row = mysqli_fetch_array($result2);

        // Show more
        ?>

        <div class="headdetails">
            <h2>Load Details</h2>
        </div>
        <div class="backBtn">
            <a href="../home.php"><button type="button">Back</button></a>
        </div>
        <div class="more">
        <img src="../<?php echo $more['img_srcs']; ?>" alt="Image" class="more-img">
            <div class="description-more">
                <h3><?php echo $more['name']; ?></h3>
                <ul>
                    <li>Origin: <?php echo $more['origin']; ?></li>
                    <li>Destination: <?php echo $more['destination']; ?></li>
                    <li>Distance: <?php echo $more['distance']; ?> Km</li>
                    <li>Weight: <?php echo $more['weight']; ?> Ton</li>
                    <li>Description: <?php echo $more['description']; ?></li>
                </ul>
            </div>
            
            <?php
            //what to display
            if($_SESSION['usertype'] == "carrier"){
                    echo "
                    <div class='takenby description-more'>
                        <h3>Load By</h3>";
                        
                    $sql3 = "SELECT consignordetails.id, consignordetails.name, consignordetails.email, consignordetails.address, consignordetails.contact
                    FROM consignordetails
                    INNER JOIN shipment ON consignordetails.id = shipment.consignor_id
                    WHERE shipment.load_id = '$id'";
                    
                    $result3 = $conn->query($sql3);
                    
                    if ($result3 === false) {
                        // Handle query error
                        echo "Error: " . $conn->error;
                    } else {
                        $rowShip = mysqli_fetch_assoc($result3);
                    
                        if ($rowShip === null) {
                            // No rows returned
                            echo "No booking information available.";
                        } else {
                            // Displaying
                            echo '<ul>';
                            echo '<li>Name: '. $rowShip["name"]. '</li>';
                            echo '<li>Email: '. $rowShip["email"]. '</li>';
                            echo '<li>Address: '. $rowShip["address"]. '</li>';
                            echo '<li>Contact: '. $rowShip["contact"]. '</li>';
                            echo '</ul>';
                        }
                    echo "</div>";
                }
                
                
                echo "<div class='more-action description-more'>
                <h3>Action</h3>
                <div class='td-center'>
                    <form action='' method='post' class='cancelBtn'>
                        <input type='hidden' name='action' value='cancel'>
                        <input type='hidden' name='id' value='" . $id . "'>
                        <input type='hidden' name='shipment_id' value='" . $row['id'] . "'> <!--passing shipment id-->
            
                        <button type='submit'>Cancel</button>
                    </form>
                </div>
            </div>";
            
                
            } elseif($_SESSION['usertype'] == "consignor"){

                echo "
                <div class='takenby description-more'>
                    <h3>Booked By</h3>";
                    
                $sql3 = "SELECT carrierdetails.id, carrierdetails.name, carrierdetails.email, carrierdetails.address, carrierdetails.contact
                FROM carrierdetails
                INNER JOIN shipment ON carrierdetails.id = shipment.carrier_id
                WHERE shipment.load_id = '$id'";
                
                $result3 = $conn->query($sql3);
                
                if ($result3 === false) {
                    // Handle query error
                    echo "Error: " . $conn->error;
                } else {
                    $rowShip = mysqli_fetch_assoc($result3);
                
                    if ($rowShip === null) {
                        // No rows returned
                        echo "No booking information available.";
                    } else {
                        // Displaying
                        echo '<ul>';
                        echo '<li>Name: '. $rowShip["name"]. '</li>';
                        echo '<li>Email: '. $rowShip["email"]. '</li>';
                        echo '<li>Address: '. $rowShip["address"]. '</li>';
                        echo '<li>Contact: '. $rowShip["contact"]. '</li>';
                        echo '</ul>';
                        echo "<div class='td-center'>
                        <form action='' method='post' class='cancelBtn'>
                            <input type='hidden' name='action' value='cancel'>
                            <input type='hidden' name='id' value='" . $id . "'>
                            <input type='hidden' name='shipment_id' value='" . $row['id'] . "'> <!--passing shipment id-->
                            <button type='submit' name='cancel'>Cancel</button>
                        </form>
                    </div>";
                    }
                }
                
                echo "</div>";
                
                echo "<div class='more-action description-more'>
                    <h3>Action</h3>
                    <div class='td-center'>
                        <form action='' method='post' class='moreBtn'>
                            <input type='hidden' name='action' value='edit'>
                            <input type='hidden' name='id' value='" . $id . "'>
                            <button type='submit'>Edit</button>
                        </form>
                                        
                        <form action='' method='post' class='deleteBtn'>
                            <input type='hidden' name='action' value='delete'>
                            <input type='hidden' name='id' value='" . $id . "'>
                            <button type='submit'>Delete</button>
                        </form>
                    </div>
                </div>";
            }
            ?>
        </div>

        <?php
    }
}

include '../layout/footer.php';
?>
<link rel="stylesheet" href="../css/maincontentstyle.css">
<link rel="stylesheet" href="../css/headerfooterstyle.css">
