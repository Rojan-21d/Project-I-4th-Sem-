<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/7b1b8b2fa3.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/login_reg.css">
    <title>Admin - Page</title>
</head>
<body>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" type="text/css" href="style.css"> <!-- Assuming you have a separate CSS file -->
</head>
<body>
    <div class="container">
        <div class="form-box">
            <h1>Admin Login</h1>
            <form action= "adminbackend.php" method="post">
                <div class="input-field">
                    <i class="fa fa-user"></i> <!-- Assuming you have the Font Awesome library for icons -->
                    <input type="text" name="username" placeholder="Username" required>
                </div>
                <div class="input-field">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="btn-field">
                    <button type="submit" name="login">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

</body>
</html>