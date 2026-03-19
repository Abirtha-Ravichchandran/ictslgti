<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$filter_date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : '';

if ($filter_date) {
    $records = $conn->query("SELECT a.*, s.full_name, s.reg_no, c.course_name
        FROM attendance a
        JOIN student s ON a.student_id = s.student_id
        LEFT JOIN course c ON s.course_id = c.course_id
        WHERE a.date = '$filter_date'
        ORDER BY s.full_name");
} else {
    $records = $conn->query("SELECT a.*, s.full_name, s.reg_no, c.course_name
        FROM attendance a
        JOIN student s ON a.student_id = s.student_id
        LEFT JOIN course c ON s.course_id = c.course_id
        ORDER BY a.date DESC, s.full_name
        LIMIT 200");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendance Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="d-flex">

    <?php include("sidebar.php"); ?>

    <div class="content">
        <h2 class="mb-4"><i class="bi bi-table me-2"></i>Attendance Records</h2>

        <form method="GET" class="mb-4 d-flex align-items-center gap-2">
            <input type="date" name="date" class="form-control" style="width:200px;"
                   value="<?php echo $filter_date; ?>">
            <button class="btn btn-outline-primary">Filter</button>
            <?php if ($filter_date): ?>
                <a href="view_all_attendance.php" class="btn btn-outline-secondary">Clear</a>
            <?php endif; ?>
        </form>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Date</th>
                            <th>Reg No</th>
                            <th>Student Name</th>
                            <th>Course</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    while ($row = $records->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $row['date']; ?></td>
                            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['course_name'] ?? '-'); ?></td>
                            <td>
                                <span class="badge <?php echo $row['status']=='Present'?'bg-success':'bg-danger'; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>
