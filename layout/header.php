<?php
$id = $_SESSION['id'];
if ($_SESSION['usertype'] == "carrier"){
$sql = "SELECT * FROM carrierdetails WHERE id = $id";
}
if ($_SESSION['usertype'] =="consignor"){
    $sql = "SELECT * FROM consignordetails where id = $id";
}
$result = $conn->query($sql);
$name = ''; // Initialize the variable with an empty value

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $name = $row['name'];
} else {
    $name = "Username";
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <script src="https://kit.fontawesome.com/7b1b8b2fa3.js" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="css/headerfooterstyle.css">
    <title></title>
</head>
<body>
    <header>  
        <nav>
        <div class="width-set">
  <a href="home.php" class="logo-link">
    <img class="logo" src="img/logowithbg.png" alt="logo">
    <h1>GANTABYA</h1>
  </a>
</div>


            <div class="width-set">
            <div class="nav__links">
                    <img src="img/images/profile.png" onclick='toggleMenu()'>
            </div>
            </div>
            

            <!-- dropdown -->
            <div class="sub-menu-wrap" id="subMenu">
                <div class="sub-menu">
                    <div class="user-info">
                        <img src="img/images/profile.png">
                         <h2><?php echo $name;?></h2>
                    </div>
                    <hr>
                    <a href="profile.php" class="sub-menu-link">
                        <img src="img/images/profile.png">
                        <p>Profile</p>                      
                    </a>
                    <a href="home.php" class="sub-menu-link">
                        <img src="img/images/home.png">
                        <p>Home</p>                      
                    </a>
                    <a href="history.php" class="sub-menu-link">
                        <img src="img/images/setting.png">
                        <p>History</p>                      
                    </a>
                    <a href="backend/logoutmodule.php" class="sub-menu-link">
                        <img src="img/images/logout.png">
                        <p>Logout</p>                      
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <script src="js/dropdownmenu.js"></script>
</body>
</html>