<?php
session_start();
error_reporting(0);

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
if ($_SESSION['usertype'] == 'student') {
    header("location:login.php");
    exit();
}

include 'dbcon.php';

if (!isset($_GET['student_id']) || !is_numeric($_GET['student_id'])) {
    header("location:view_student.php");
    exit();
}

$id = (int) $_GET['student_id'];

$stmt = mysqli_prepare($data, "SELECT * FROM user WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$info   = mysqli_fetch_assoc($result);

if (!$info) {
    header("location:view_student.php");
    exit();
}

$error   = '';
$success = '';

if (isset($_POST['update'])) {

    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $phone    = trim($_POST['phone']);
    $password = trim($_POST['password']);

    if (empty($name) || empty($email) || empty($phone) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $query = mysqli_prepare($data, "UPDATE user SET username=?, email=?, phone=?, password=? WHERE id=?");
        mysqli_stmt_bind_param($query, "ssssi", $name, $email, $phone, $hashed, $id);
        $result2 = mysqli_stmt_execute($query);

        if ($result2) {
            $_SESSION['message'] = "Student updated successfully.";
            header("location:view_student.php");
            exit();
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
    <title>Update Student</title>
    <?php include 'admin_css.php'; ?>
    <style type="text/css">
        label {
            display: inline-block;
            width: 100px;
            text-align: right;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .div_deg {
            background-color: skyblue;
            width: 400px;
            padding-bottom: 70px;
            padding-top: 70px;
        }
        .msg-error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <center>
            <h1>Update Student</h1>

            <?php if (!empty($error)): ?>
                <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <div class="div_deg">
                <form action="" method="POST">
                    <div>
                        <label>Username</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($info['username']); ?>" required>
                    </div>
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
                        <input class="btn btn-success" type="submit" name="update" value="Update">
                    </div>
                </form>
            </div>
        </center>
    </div>
</body>
</html>