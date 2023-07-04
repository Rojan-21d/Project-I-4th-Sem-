<?php
$carrier_id = $_SESSION['id'];

$sql = "SELECT * FROM loaddetails where status = 'notBooked'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7b1b8b2fa3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/maincontentstyle.css">
    <title><?php echo $name;?></title>
</head>
<body>
    <div class="main-content">
        <h2>Loads for You</h2>
        <?php
            if ($result->num_rows > 0) {
                while ($loadrow = $result->fetch_assoc()) {
                    $i = 1; // Initialize $i variable

                    $consignor_id = $loadrow['consignor_id'];
                    $sql = mysqli_query($conn,"SELECT * FROM consignordetails WHERE id='$consignor_id'");
                    $gotconsignor = mysqli_fetch_assoc($sql);

                    echo '
                    <div class="post-container">
                        <div class="user-info">
                            <img src="img/images/profile-pic.png" alt="">
                            <div>
                                <p>'.$gotconsignor['name'].'</p>
                                <small>'.$loadrow['dateofpost'].'</small>
                            </div>
                        </div>
                        <hr>
                
                        <div class="content-detail">
                            <div class="content-image">
                                <img src="'.$loadrow['img_srcs'].'" alt="Image" class="post-img">
                            </div>
                            <div class="content-description">
                                <h3>'.$loadrow['name'].'</h3>
                                <ul>
                                    <li>Origin: '.$loadrow['origin'].'</li>
                                    <li>Destination: '.$loadrow['destination'].'</li>
                                    <li>Distance: '.$loadrow['distance'].' Km</li>
                                    <li>Weight: '.$loadrow['weight'].' Ton</li>
                                    <li>Description: '.$loadrow['description'].'</li>
                                </ul>
                            </div>
                        </div>
                        <hr>
                        <div class="activity-icon booked">
                            <form action="backend/booking.php" method="post">
                                <!-- Transferring the load id to furtherpage -->
                                <input type="hidden" name="action" value="book">
                                <input type="hidden" name="load_id" value="'.$loadrow['id'].'">
                                <!-- Transferring the carrier id to furtherpage -->
                                <input type="hidden" name="action" value="book">
                                <input type="hidden" name="carrier_id" value="'.$carrier_id.'">
                                <!-- Transferring the load id to furtherpage -->
                                <input type="hidden" name="action" value="book">
                                <input type="hidden" name="consignor_id" value="'.$gotconsignor['id'].'">
                                <button type="submit">
                                    <i class="fa-solid fa-handshake-simple"> Book</i>
                                </button>
                            </form>
                        </div>                  
                    </div>';
                }
            }                
        ?>
    </div>
</body>
</html>
<?php 
                
                

?>
