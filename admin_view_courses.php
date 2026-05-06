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

if (isset($_GET['courses_id']) && is_numeric($_GET['courses_id'])) {
    $t_id = (int) $_GET['courses_id'];

    $stmt = mysqli_prepare($data, "DELETE FROM courses WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $t_id);
    $result2 = mysqli_stmt_execute($stmt);

    if ($result2) {
        header('location:admin_view_courses.php');
        exit();
    }
}

$sql    = "SELECT * FROM courses";
$result = mysqli_query($data, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>View Courses</title>
    <?php include 'admin_css.php'; ?>
    <style type="text/css">
        .table_th { padding: 20px; font-size: 20px; }
        .table_td { padding: 20px; background-color: skyblue; font-size: 16px; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <center>
            <h1>View All Courses</h1>

            <table border="1">
                <tr>
                    <th class="table_th">Course Name</th>
                    <th class="table_th">About Course</th>
                    <th class="table_th">Image</th>
                    <th class="table_th">Delete</th>
                    <th class="table_th">Update</th>
                </tr>

                <?php while ($info = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td class="table_td"><?php echo htmlspecialchars($info['name']); ?></td>
                    <td class="table_td"><?php echo htmlspecialchars($info['description']); ?></td>
                    <td class="table_td">
                        <img height="100" width="100" src="<?php echo htmlspecialchars($info['image']); ?>" alt="Course Image">
                    </td>
                    <td class="table_td">
                        <a onclick="return confirm('Are you sure you want to delete this course?')"
                           href="admin_view_courses.php?courses_id=<?php echo $info['id']; ?>"
                           class="btn btn-danger">Delete</a>
                    </td>
                    <td class="table_td">
                        <a href="update_courses.php?courses_id=<?php echo $info['id']; ?>"
                           style="background-color:#1e90ff;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">
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