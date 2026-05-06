<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location:login.php");
    exit();
}
if ($_SESSION['usertype'] == 'student') {
    header("location:login.php");
    exit();
}

include 'dbcon.php';

$error   = '';
$success = '';

if (isset($_POST['add_student'])) {

    $username  = trim($_POST['name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $password  = trim($_POST['password']);
    $usertype  = "student";

    if (empty($username) || empty($email) || empty($phone) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } else {
        // Check duplicate username
        $check = mysqli_prepare($data, "SELECT id FROM user WHERE username = ?");
        mysqli_stmt_bind_param($check, "s", $username);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $error = "Username already exists. Try another one.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($data, "INSERT INTO user (username, email, phone, usertype, password) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $phone, $usertype, $hashed);
            $result = mysqli_stmt_execute($stmt);

            if ($result) {
                $success = "Student added successfully.";
            } else {
                $error = "Failed to add student. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Student</title>
    <style type="text/css">
        label {
            display: inline-block;
            text-align: right;
            width: 100px;
            padding-top: 10px;
            padding-bottom: 5px;
        }
        .div_deg {
            background-color: skyblue;
            width: 350px;
            padding-top: 70px;
            padding-bottom: 70px;
        }
        .btn_primary {
            background-color: #1e90ff;
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
            font-size: 15px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .btn_primary:hover {
            background-color: #1c86ee;
        }
        .msg-success { color: green; font-weight: bold; }
        .msg-error   { color: red;   font-weight: bold; }
    </style>
    <?php include 'admin_css.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <center>
            <h1>Add Student</h1>

            <?php if (!empty($success)): ?>
                <p class="msg-success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <div class="div_deg">
                <form action="" method="POST">
                    <div>
                        <label>Username</label>
                        <input type="text" name="name" required>
                    </div>
                    <div>
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>
                    <div>
                        <label>Phone</label>
                        <input type="number" name="phone" required>
                    </div>
                    <div>
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div>
                        <input type="submit" class="btn btn_primary" name="add_student" value="Add Student">
                    </div>
                </form>
            </div>
        </center>
    </div>
</body>
</html>