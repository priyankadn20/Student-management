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

$sql    = "SELECT * FROM admission ORDER BY id DESC";
$result = mysqli_query($data, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admission Requests</title>
    <?php include 'admin_css.php'; ?>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <center>
            <h1>Admission Applications</h1>
            <br><br>

            <table border="1">
                <tr>
                    <th style="padding:20px;">Name</th>
                    <th style="padding:20px;">Email</th>
                    <th style="padding:20px;">Phone</th>
                    <th style="padding:20px;">Message</th>
                    <th style="padding:20px;">Date</th>
                </tr>

                <?php while ($info = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td style="padding:15px;"><?php echo htmlspecialchars($info['name']); ?></td>
                    <td style="padding:15px;"><?php echo htmlspecialchars($info['email']); ?></td>
                    <td style="padding:15px;"><?php echo htmlspecialchars($info['phone']); ?></td>
                    <td style="padding:15px;"><?php echo htmlspecialchars($info['message']); ?></td>
                    <td style="padding:15px;"><?php echo htmlspecialchars($info['created_at']); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </center>
    </div>
</body>
</html>