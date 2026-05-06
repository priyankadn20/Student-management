<?php
$host     = "localhost";
$user     = "root";
$password = "";
$db       = "schoolproject";
 
$data = mysqli_connect($host, $user, $password, $db);
 
if (!$data) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
 