<?php
error_reporting(0);
session_start();

// Read message first, then clear session
$loginMessage = isset($_SESSION['loginMessage']) ? $_SESSION['loginMessage'] : '';
unset($_SESSION['loginMessage']);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</head>
<body background="piic-2.jpg" class="body_deg">
    <center>
        <div class="form_deg">
            <center class="title_deg">
                Login Form
                <?php if (!empty($loginMessage)): ?>
                    <h4 style="color:red;"><?php echo htmlspecialchars($loginMessage); ?></h4>
                <?php endif; ?>
            </center>

            <form action="login_check.php" method="POST" class="login_form">
                <div>
                    <label class="label_deg">Username</label>
                    <input type="text" name="username" required>
                </div>
                <div>
                    <label class="label_deg">Password</label>
                    <input type="password" name="password" required>
                </div>
                <div>
                    <input class="btn btn-primary" type="submit" name="submit" value="Login">
                </div>
            </form>
        </div>
    </center>
</body>
</html>