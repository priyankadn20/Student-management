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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <?php include 'admin_css.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
        <p>Use the sidebar to manage students, teachers, and courses.</p>
    </div>
</body>
</html>