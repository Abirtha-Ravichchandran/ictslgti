<div class="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-calendar-check-fill me-2"></i>Admin Panel
    </div>
    <a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF'])=='dashboard.php')?'active':''; ?>">
        <i class="bi bi-speedometer2 me-2"></i>Dashboard
    </a>
    <a href="students.php" class="<?php echo (basename($_SERVER['PHP_SELF'])=='students.php')?'active':''; ?>">
        <i class="bi bi-people me-2"></i>Students
    </a>
    <a href="mark_attendance.php" class="<?php echo (basename($_SERVER['PHP_SELF'])=='mark_attendance.php')?'active':''; ?>">
        <i class="bi bi-calendar-check me-2"></i>Attendance
    </a>
    <a href="view_all_attendance.php" class="<?php echo (basename($_SERVER['PHP_SELF'])=='view_all_attendance.php')?'active':''; ?>">
        <i class="bi bi-table me-2"></i>View Records
    </a>
    <div class="mt-auto">
        <a href="../logout.php" class="logout-link">
            <i class="bi bi-box-arrow-left me-2"></i>Logout
        </a>
    </div>
</div>
