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

if (isset($_GET['student_id']) && is_numeric($_GET['student_id'])) {

    $user_id = (int) $_GET['student_id'];

    $stmt = mysqli_prepare($data, "DELETE FROM user WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION['message'] = "Student deleted successfully.";
    } else {
        $_SESSION['message'] = "Failed to delete student.";
    }
}

header("location:view_student.php");
exit();
?>