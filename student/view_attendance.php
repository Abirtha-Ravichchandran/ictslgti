<?php
session_start();
if (!isset($_SESSION['student_id'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$id   = $_SESSION['student_id'];
$name = $_SESSION['student_name'];

// Filter by month
$month = isset($_GET['month']) ? $conn->real_escape_string($_GET['month']) : date("Y-m");

$records = $conn->query("SELECT * FROM attendance WHERE student_id='$id' AND DATE_FORMAT(date,'%Y-%m')='$month' ORDER BY date DESC");

// Monthly stats
$m_total   = $conn->query("SELECT * FROM attendance WHERE student_id='$id' AND DATE_FORMAT(date,'%Y-%m')='$month'")->num_rows;
$m_present = $conn->query("SELECT * FROM attendance WHERE student_id='$id' AND DATE_FORMAT(date,'%Y-%m')='$month' AND status='Present'")->num_rows;
$m_pct     = $m_total > 0 ? round(($m_present / $m_total) * 100, 1) : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<nav class="navbar navbar-dark" style="background:#2c3e50;">
    <div class="container-fluid px-4">
        <span class="navbar-brand"><i class="bi bi-calendar-check-fill me-2"></i>Attendance System</span>
        <div class="d-flex align-items-center gap-3">
            <a href="dashboard.php" class="btn btn-sm btn-outline-light">
                <i class="bi bi-arrow-left me-1"></i>Dashboard
            </a>
            <a href="../logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h4 class="mb-4"><i class="bi bi-calendar3 me-2"></i>My Attendance</h4>

    <!-- Month filter -->
    <form method="GET" class="d-flex align-items-center gap-2 mb-4">
        <input type="month" name="month" class="form-control" style="width:200px;"
               value="<?php echo $month; ?>">
        <button class="btn btn-outline-primary">Filter</button>
    </form>

    <!-- Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Classes</h6>
                    <h3 class="text-primary"><?php echo $m_total; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h6 class="text-muted">Present</h6>
                    <h3 class="text-success"><?php echo $m_present; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-danger">
                <div class="card-body">
                    <h6 class="text-muted">Absent</h6>
                    <h3 class="text-danger"><?php echo $m_total - $m_present; ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center <?php echo $m_pct>=75?'border-success':($m_pct>=50?'border-warning':'border-danger'); ?>">
                <div class="card-body">
                    <h6 class="text-muted">Percentage</h6>
                    <h3 class="<?php echo $m_pct>=75?'text-success':($m_pct>=50?'text-warning':'text-danger'); ?>">
                        <?php echo $m_pct; ?>%
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Records table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Day</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                if ($records->num_rows > 0):
                    while ($row = $records->fetch_assoc()):
                ?>
                    <tr>
                        <td><?php echo $i++; ?></td>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo date("l", strtotime($row['date'])); ?></td>
                        <td>
                            <span class="badge <?php echo $row['status']=='Present'?'bg-success':'bg-danger'; ?>">
                                <i class="bi <?php echo $row['status']=='Present'?'bi-check-circle':'bi-x-circle'; ?> me-1"></i>
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No records found for this month.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
