<?php
error_reporting(0);
session_start();

$message = '';
if (!empty($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
}

include 'dbcon.php';

$sql_teacher = "SELECT * FROM teacher";
$result      = mysqli_query($data, $sql_teacher);

$sql_courses  = "SELECT * FROM courses";
$result_course = mysqli_query($data, $sql_courses);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Student Management System</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap-theme.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js"></script>
</head>
<body>

    <?php if (!empty($message)): ?>
        <script>alert('<?php echo addslashes($message); ?>');</script>
    <?php endif; ?>

    <nav>
        <label class="logo">X-School</label>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="login.php" class="btn btn-success">Login</a></li>
        </ul>
    </nav>

    <div class="section1">
        <label class="img_text">We Teach Students With Care</label>
        <img class="main_img" src="pic-1.jpg" alt="School Banner">
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img class="welcome_img" src="piic-2.jpg" alt="Welcome">
            </div>
            <div class="col-md-8">
                <h1>Welcome to X-School</h1>
                <p>MEMS has been committed to global learning long before it became
                    an indispensable feature of contemporary education. Established in 1997,
                    we proudly stand as the first English medium school in Bangladesh to adopt
                    both Pearson Edexcel and Cambridge curriculum (in O and A levels),
                    drawing together students in a vibrant, academically rich environment
                    where manifold viewpoints are prized and celebrated.</p>
            </div>
        </div>

        <center><h1>Our Teachers</h1></center>
    </div>

    <div class="container">
        <div class="row">
            <?php while ($info = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4">
                <img class="teacher" src="<?php echo htmlspecialchars($info['image']); ?>" alt="<?php echo htmlspecialchars($info['name']); ?>">
                <h3><?php echo htmlspecialchars($info['name']); ?></h3>
                <h5><?php echo htmlspecialchars($info['description']); ?></h5>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <center><h1>Our Courses</h1></center>

    <div class="container">
        <div class="row">
            <?php while ($course = mysqli_fetch_assoc($result_course)): ?>
            <div class="col-md-4">
                <img class="course" src="<?php echo htmlspecialchars($course['image']); ?>" alt="<?php echo htmlspecialchars($course['name']); ?>">
                <h3><?php echo htmlspecialchars($course['name']); ?></h3>
                <p><?php echo htmlspecialchars($course['description']); ?></p>
            </div>
            <?php endwhile; ?>
        </div>
    </div>

    <center><h1 class="adm">Admission</h1></center>

    <div align="center" class="admission_form">
        <form action="data_check.php" method="POST">
            <div class="adm_int">
                <label class="label_text">Name</label>
                <input class="input_deg" type="text" name="name" required>
            </div>
            <div class="adm_int">
                <label class="label_text">Email</label>
                <input class="input_deg" type="email" name="email" required>
            </div>
            <div class="adm_int">
                <label class="label_text">Phone</label>
                <input class="input_deg" type="text" name="phone" required>
            </div>
            <div class="adm_int">
                <label class="label_text">Message</label>
                <textarea class="input_txt" name="message" required></textarea>
            </div>
            <div class="adm_int">
                <input class="btn btn-primary" id="submit" type="submit" value="Apply" name="apply">
            </div>
        </form>
    </div>

    <footer>
        <h4 class="footer_text">All &copy; copyright reserved by X-School</h4>
    </footer>

</body>
</html>