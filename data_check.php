<?php
session_start();

include 'dbcon.php';

if (isset($_POST['apply'])) {

    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $phone   = trim($_POST['phone']);
    $message = trim($_POST['message']);

    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        $_SESSION['message'] = "All fields are required.";
        header("location:index.php");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = "Invalid email address.";
        header("location:index.php");
        exit();
    }

    $stmt = mysqli_prepare($data, "INSERT INTO admission (name, email, phone, message) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $name, $email, $phone, $message);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['message'] = "Your application was sent successfully!";
    } else {
        $_SESSION['message'] = "Application failed. Please try again.";
    }

    header("location:index.php");
    exit();
}
?>