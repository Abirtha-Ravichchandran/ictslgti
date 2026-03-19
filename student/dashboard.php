<?php
session_start();
if (!isset($_SESSION['student_id'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$id   = $_SESSION['student_id'];
$name = $_SESSION['student_name'];

$total   = $conn->query("SELECT * FROM attendance WHERE student_id='$id'")->num_rows;
$present = $conn->query("SELECT * FROM attendance WHERE student_id='$id' AND status='Present'")->num_rows;
$absent  = $total - $present;
$pct     = $total > 0 ? round(($present / $total) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="navbar navbar-dark" style="background:#2c3e50;">
    <div class="container-fluid px-4">
        <span class="navbar-brand">
            <i class="bi bi-calendar-check-fill me-2"></i>Attendance System
        </span>
        <div class="d-flex align-items-center gap-3">
            <span class="text-white"><i class="bi bi-person me-1"></i><?php echo htmlspecialchars($name); ?></span>
            <a href="../logout.php" class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-left me-1"></i>Logout
            </a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h4 class="mb-4">Welcome, <?php echo htmlspecialchars($name); ?>!</h4>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary p-3 stat-card">
                <i class="bi bi-calendar3 stat-icon"></i>
                <h6>Total Classes</h6>
                <h2><?php echo $total; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success p-3 stat-card">
                <i class="bi bi-check-circle stat-icon"></i>
                <h6>Present</h6>
                <h2><?php echo $present; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger p-3 stat-card">
                <i class="bi bi-x-circle stat-icon"></i>
                <h6>Absent</h6>
                <h2><?php echo $absent; ?></h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white p-3 stat-card <?php echo $pct>=75?'bg-success':($pct>=50?'bg-warning':'bg-danger'); ?>">
                <i class="bi bi-percent stat-icon"></i>
                <h6>Attendance %</h6>
                <h2><?php echo $pct; ?>%</h2>
            </div>
        </div>
    </div>

    <!-- Progress bar -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h6 class="mb-2">Attendance Progress</h6>
            <div class="progress" style="height:25px;">
                <div class="progress-bar <?php echo $pct>=75?'bg-success':($pct>=50?'bg-warning':'bg-danger'); ?>"
                     style="width:<?php echo $pct; ?>%">
                    <?php echo $pct; ?>%
                </div>
            </div>
            <?php if ($pct < 75): ?>
                <small class="text-danger mt-2 d-block">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Your attendance is below 75%. Please attend more classes.
                </small>
            <?php endif; ?>
        </div>
    </div>

    <a href="view_attendance.php" class="btn btn-primary">
        <i class="bi bi-eye me-1"></i>View Detailed Attendance
    </a>
</div>

</body>
</html>
