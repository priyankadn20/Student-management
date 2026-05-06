<?php
error_reporting(0);
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

$sql    = "SELECT * FROM user WHERE usertype = 'student'";
$result = mysqli_query($data, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>View Students</title>
    <?php include 'admin_css.php'; ?>
    <style type="text/css">
        .table_th { padding: 20px; font-size: 18px; }
        .table_td { padding: 15px; background-color: skyblue; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <center>
            <h1>Student Data</h1>

            <?php if (!empty($_SESSION['message'])): ?>
                <p style="color:green; font-weight:bold;">
                    <?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?>
                </p>
            <?php endif; ?>

            <br><br>

            <table border="1">
                <tr>
                    <th class="table_th">Username</th>
                    <th class="table_th">Email</th>
                    <th class="table_th">Phone</th>
                    <th class="table_th">Delete</th>
                    <th class="table_th">Update</th>
                </tr>

                <?php while ($info = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="table_td"><?php echo htmlspecialchars($info['username']); ?></td>
                    <td class="table_td"><?php echo htmlspecialchars($info['email']); ?></td>
                    <td class="table_td"><?php echo htmlspecialchars($info['phone']); ?></td>
                    <td class="table_td">
                        <a onclick="return confirm('Are you sure you want to delete this student?')"
                           href="delete.php?student_id=<?php echo $info['id']; ?>"
                           style="background-color:red;color:white;padding:5px 10px;text-decoration:none;border-radius:3px;">
                            Delete
                        </a>
                    </td>
                    <td class="table_td">
                        <a href="update_student.php?student_id=<?php echo $info['id']; ?>"
                           style="background-color:#1e90ff;color:white;padding:5px 10px;text-decoration:none;border-radius:5px;">
                            Update
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </center>
    </div>
</body>
</html>