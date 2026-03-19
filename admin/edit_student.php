<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$id = intval($_GET['id']);
$student = $conn->query("SELECT * FROM student WHERE student_id='$id'")->fetch_assoc();
$courses = $conn->query("SELECT * FROM course");

if (!$student) { header("Location: students.php"); exit(); }

$error = $success = "";

if (isset($_POST['update'])) {
    $name   = $conn->real_escape_string(trim($_POST['name']));
    $reg    = $conn->real_escape_string(trim($_POST['reg']));
    $nic    = $conn->real_escape_string(trim($_POST['nic']));
    $gender = $conn->real_escape_string($_POST['gender']);
    $course = intval($_POST['course_id']);

    $sql = "UPDATE student SET full_name='$name', reg_no='$reg', nic='$nic', gender='$gender', course_id='$course'
            WHERE student_id='$id'";
    if ($conn->query($sql)) {
        header("Location: students.php?msg=Student updated successfully");
        exit();
    } else {
        $error = "Update failed.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="d-flex">

    <?php include("sidebar.php"); ?>

    <div class="content">
        <h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Student</h2>

        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

        <div class="card shadow-sm" style="max-width:600px;">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control"
                               value="<?php echo htmlspecialchars($student['full_name']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Registration No</label>
                        <input type="text" name="reg" class="form-control"
                               value="<?php echo htmlspecialchars($student['reg_no']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIC</label>
                        <input type="text" name="nic" class="form-control"
                               value="<?php echo htmlspecialchars($student['nic']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male"   <?php echo $student['gender']=='Male'  ?'selected':''; ?>>Male</option>
                            <option value="Female" <?php echo $student['gender']=='Female'?'selected':''; ?>>Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-select">
                            <option value="">-- Select Course --</option>
                            <?php while ($c = $courses->fetch_assoc()): ?>
                            <option value="<?php echo $c['course_id']; ?>"
                                <?php echo $student['course_id']==$c['course_id']?'selected':''; ?>>
                                <?php echo htmlspecialchars($c['course_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="update" class="btn btn-success">
                            <i class="bi bi-save me-1"></i>Update
                        </button>
                        <a href="students.php" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>
