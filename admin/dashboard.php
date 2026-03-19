<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$total_students = $conn->query("SELECT * FROM student")->num_rows;
$total_courses  = $conn->query("SELECT * FROM course")->num_rows;
$total_present  = $conn->query("SELECT * FROM attendance WHERE status='Present' AND date=CURDATE()")->num_rows;
$total_absent   = $conn->query("SELECT * FROM attendance WHERE status='Absent'  AND date=CURDATE()")->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="d-flex">

    <!-- Sidebar -->
    <?php include("sidebar.php"); ?>

    <!-- Content -->
    <div class="content">
        <h2 class="mb-4"><i class="bi bi-speedometer2 me-2"></i>Dashboard</h2>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary p-3 stat-card">
                    <i class="bi bi-people-fill stat-icon"></i>
                    <h5>Total Students</h5>
                    <h2><?php echo $total_students; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success p-3 stat-card">
                    <i class="bi bi-book-fill stat-icon"></i>
                    <h5>Total Courses</h5>
                    <h2><?php echo $total_courses; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info p-3 stat-card">
                    <i class="bi bi-check-circle-fill stat-icon"></i>
                    <h5>Present Today</h5>
                    <h2><?php echo $total_present; ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger p-3 stat-card">
                    <i class="bi bi-x-circle-fill stat-icon"></i>
                    <h5>Absent Today</h5>
                    <h2><?php echo $total_absent; ?></h2>
                </div>
            </div>
        </div>

        <div class="mt-5">
            <h5>Quick Actions</h5>
            <a href="add_student.php" class="btn btn-outline-primary me-2">
                <i class="bi bi-person-plus me-1"></i>Add Student
            </a>
            <a href="mark_attendance.php" class="btn btn-outline-success me-2">
                <i class="bi bi-calendar-check me-1"></i>Mark Attendance
            </a>
            <a href="students.php" class="btn btn-outline-secondary">
                <i class="bi bi-list-ul me-1"></i>View Students
            </a>
        </div>
    </div>
</div>

</body>
</html>
