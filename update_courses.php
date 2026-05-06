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

if (!isset($_GET['courses_id']) || !is_numeric($_GET['courses_id'])) {
    header("location:admin_view_courses.php");
    exit();
}

$id = (int) $_GET['courses_id'];

$stmt = mysqli_prepare($data, "SELECT * FROM courses WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$info   = mysqli_fetch_assoc($result);

if (!$info) {
    header("location:admin_view_courses.php");
    exit();
}

$error = '';

if (isset($_POST['update_course'])) {

    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);

    if (empty($name) || empty($description)) {
        $error = "Name and description are required.";
    } else {
        if (!empty($_FILES['image']['name'])) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif'];
            $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowed)) {
                $error = "Only JPG, JPEG, PNG, GIF files are allowed.";
            } else {
                $file  = basename($_FILES['image']['name']);
                $dst   = "./image/" . $file;
                move_uploaded_file($_FILES['image']['tmp_name'], $dst);
                $image = $dst;
            }
        } else {
            $image = $info['image'];
        }

        if (empty($error)) {
            $query = mysqli_prepare($data, "UPDATE courses SET name=?, description=?, image=? WHERE id=?");
            mysqli_stmt_bind_param($query, "sssi", $name, $description, $image, $id);
            $result2 = mysqli_stmt_execute($query);

            if ($result2) {
                header("location:admin_view_courses.php");
                exit();
            } else {
                $error = "Update failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Update Course</title>
    <?php include 'admin_css.php'; ?>
    <style type="text/css">
        label {
            display: inline-block;
            width: 110px;
            text-align: right;
            padding-top: 10px;
            padding-bottom: 10px;
        }
        .div_deg {
            background-color: skyblue;
            width: 500px;
            padding: 50px 70px;
        }
        .msg-error { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <?php include 'admin_sidebar.php'; ?>

    <div class="content">
        <center>
            <h1>Update Course</h1>

            <?php if (!empty($error)): ?>
                <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <div class="div_deg">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div>
                        <label>Course Name:</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($info['name']); ?>" required>
                    </div>
                    <br>
                    <div>
                        <label>Description:</label>
                        <textarea name="description" required><?php echo htmlspecialchars($info['description']); ?></textarea>
                    </div>
                    <br>
                    <div>
                        <label>Current Image:</label>
                        <img src="<?php echo htmlspecialchars($info['image']); ?>" height="60" width="60" alt="Current Image">
                    </div>
                    <br>
                    <div>
                        <label>New Image:</label>
                        <input type="file" name="image" accept="image/*">
                        <small>(Leave empty to keep current image)</small>
                    </div>
                    <br>
                    <div>
                        <input class="btn btn-primary" type="submit" name="update_course" value="Update Course">
                    </div>
                </form>
            </div>
        </center>
    </div>
</body>
</html>