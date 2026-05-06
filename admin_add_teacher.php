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

if (isset($_POST['add_teacher'])) {

    $t_name        = trim($_POST['name']);
    $t_description = trim($_POST['description']);

    if (empty($t_name) || empty($t_description) || empty($_FILES['image']['name'])) {
        $error = "All fields are required.";
    } else {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $ext     = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            $error = "Only JPG, JPEG, PNG, GIF files are allowed.";
        } else {
            $file   = basename($_FILES['image']['name']);
            $dst    = "./image/" . $file;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $dst)) {
                $stmt = mysqli_prepare($data, "INSERT INTO teacher (name, description, image) VALUES (?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "sss", $t_name, $t_description, $dst);
                $result = mysqli_stmt_execute($stmt);

                if ($result) {
                    $success = "Teacher added successfully.";
                } else {
                    $error = "Failed to add teacher.";
                }
            } else {
                $error = "Image upload failed. Make sure the 'image' folder exists.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Add Teacher</title>
    <style type="text/css">
        .div_deg {
            background-color: skyblue;
            padding-top: 70px;
            padding-bottom: 70px;
            width: 500px;
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
            <h1>Add Teacher</h1><br><br>

            <?php if (!empty($success)): ?>
                <p class="msg-success"><?php echo htmlspecialchars($success); ?></p>
            <?php endif; ?>
            <?php if (!empty($error)): ?>
                <p class="msg-error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <div class="div_deg">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div>
                        <label>Teacher name:</label>
                        <input type="text" name="name" required>
                    </div>
                    <br>
                    <div>
                        <label>Description:</label>
                        <textarea name="description" required></textarea>
                    </div>
                    <br>
                    <div>
                        <label>Image:</label>
                        <input type="file" name="image" accept="image/*" required>
                    </div>
                    <br>
                    <div>
                        <input type="submit" name="add_teacher" value="Add Teacher" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </center>
    </div>
</body>
</html>