<?php
session_start();
error_reporting(0);

include 'dbcon.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST['username']);
    $pass = trim($_POST['password']);

    if (empty($name) || empty($pass)) {
        $_SESSION['loginMessage'] = "Username and password are required.";
        header("location:login.php");
        exit();
    }

    // Prepared statement — SQL Injection safe
    $stmt = mysqli_prepare($data, "SELECT * FROM user WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $name);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row    = mysqli_fetch_assoc($result);

    if ($row && password_verify($pass, $row['password'])) {

        $_SESSION['username'] = $row['username'];
        $_SESSION['usertype'] = $row['usertype'];

        if ($row['usertype'] == "admin") {
            header("location:adminhome.php");
        } elseif ($row['usertype'] == "student") {
            header("location:studenthome.php");
        }
        exit();

    } else {
        $_SESSION['loginMessage'] = "Username or password does not match.";
        header("location:login.php");
        exit();
    }
}
?>