<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$error = $success = "";
$courses = $conn->query("SELECT * FROM course");

if (isset($_POST['add'])) {
    $name    = $conn->real_escape_string(trim($_POST['name']));
    $reg     = $conn->real_escape_string(trim($_POST['reg']));
    $nic     = $conn->real_escape_string(trim($_POST['nic']));
    $gender  = $conn->real_escape_string($_POST['gender']);
    $course  = intval($_POST['course_id']);

    // Check duplicate reg_no
    $check = $conn->query("SELECT * FROM student WHERE reg_no='$reg'");
    if ($check->num_rows > 0) {
        $error = "Registration number already exists.";
    } else {
        $sql = "INSERT INTO student(full_name, reg_no, nic, gender, course_id)
                VALUES('$name','$reg','$nic','$gender','$course')";
        if ($conn->query($sql)) {
            $success = "Student added successfully!";
        } else {
            $error = "Error adding student.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="d-flex">

    <?php include("sidebar.php"); ?>

    <div class="content">
        <h2 class="mb-4"><i class="bi bi-person-plus me-2"></i>Add Student</h2>

        <?php if ($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

        <div class="card shadow-sm" style="max-width:600px;">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Enter full name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Registration No <span class="text-danger">*</span></label>
                        <input type="text" name="reg" class="form-control" placeholder="e.g. 2024/CS/001" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIC</label>
                        <input type="text" name="nic" class="form-control" placeholder="National ID Card No">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select name="gender" class="form-select">
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select name="course_id" class="form-select">
                            <option value="">-- Select Course --</option>
                            <?php
                            $courses->data_seek(0);
                            while ($c = $courses->fetch_assoc()):
                            ?>
                            <option value="<?php echo $c['course_id']; ?>">
                                <?php echo htmlspecialchars($c['course_name']); ?>
                            </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="add" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i>Add Student
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
