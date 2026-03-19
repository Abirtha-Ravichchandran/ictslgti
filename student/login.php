<?php
session_start();
include("../config/db.php");

$error = "";

if (isset($_POST['login'])) {
    $reg = $conn->real_escape_string(trim($_POST['reg']));
    $result = $conn->query("SELECT * FROM student WHERE reg_no='$reg'");

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $_SESSION['student_id']  = $student['student_id'];
        $_SESSION['student_reg'] = $student['reg_no'];
        $_SESSION['student_name']= $student['full_name'];
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid registration number.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="home-body">

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-card p-4">
        <div class="text-center mb-4">
            <i class="bi bi-person-circle" style="font-size:48px; color:#28a745;"></i>
            <h3 class="mt-2 fw-bold">Student Login</h3>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-3">
                <label class="form-label">Registration Number</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-card-text"></i></span>
                    <input type="text" name="reg" class="form-control"
                           placeholder="e.g. 2024/CS/001" required>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-success w-100">
                <i class="bi bi-box-arrow-in-right me-2"></i>Login
            </button>
        </form>
        <div class="text-center mt-3">
            <a href="../index.php" class="text-muted small"><i class="bi bi-arrow-left"></i> Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>
