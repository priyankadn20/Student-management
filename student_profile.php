<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
if ($_SESSION['usertype'] == 'admin') {
    header("location:login.php");
    exit();
}

include 'dbcon.php';

$name = $_SESSION['username'];

$stmt = mysqli_prepare($data, "SELECT * FROM user WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $name);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$info   = mysqli_fetch_assoc($result);

$error   = '';
$success = '';

if (isset($_POST['update_profile'])) {

    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($phone) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt2 = mysqli_prepare($data, "UPDATE user SET email=?, phone=?, password=? WHERE username=?");
        mysqli_stmt_bind_param($stmt2, "ssss", $email, $phone, $hashed, $name);
        $result2 = mysqli_stmt_execute($stmt2);

        if ($result2) {
            $success = "Profile updated successfully.";
            // Refresh info
            $stmt3 = mysqli_prepare($data, "SELECT * FROM user WHERE username = ?");
            mysqli_stmt_bind_param($stmt3, "s", $name);
            mysqli_stmt_execute($stmt3);
            $result3 = mysqli_stmt_get_result($stmt3);
            $info    = mysqli_fetch_assoc($result3);
        } else {
            $error = "Update failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Profile</title>
    <?php include 'student_css.php'; ?>
    <style type="text/css">
        label {
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .div_deg {
            background-color: skyblue;
            width: 500px;
            padding-top: 70px;
            padding-bottom: 70px;
        }
        .msg-success { color: green; font-weight: bold; }
        .msg-error   { color: red;   font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'student_sidebar.php'; ?>

    <div class="content">
        <center>
            <h1>Update Profile</h1>
            <br><br>

            <?php if (!empty($success)): ?>
                <p class="msg-success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="div_deg">
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($info['email']); ?>" required>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input type="number" name="phone" value="<?php echo htmlspecialchars($info['phone']); ?>" required>
                    </div>
                    <div>
                        <label>Password</label>
                        <input type="password" name="password" placeholder="Enter new password" required>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-primary" name="update_profile" value="Update">
                    </div>
                </div>
            </form>
        </center>
    </div>
</body>
</html>