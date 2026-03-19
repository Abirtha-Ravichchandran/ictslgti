<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$success = $error = "";
$date = isset($_GET['date']) ? $conn->real_escape_string($_GET['date']) : date("Y-m-d");

$students = $conn->query("SELECT s.*, c.course_name FROM student s LEFT JOIN course c ON s.course_id=c.course_id ORDER BY s.full_name");

if (isset($_POST['save'])) {
    $att_date = $conn->real_escape_string($_POST['att_date']);

    if (empty($_POST['attendance'])) {
        $error = "Please mark attendance for at least one student.";
    } else {
        foreach ($_POST['attendance'] as $sid => $status) {
            $sid    = intval($sid);
            $status = $conn->real_escape_string($status);

            // Delete existing for same date and student
            $conn->query("DELETE FROM attendance WHERE student_id='$sid' AND date='$att_date'");

            $conn->query("INSERT INTO attendance(student_id, date, status) VALUES('$sid','$att_date','$status')");
        }
        $success = "Attendance saved for $att_date!";
    }
}

// Get already-marked attendance for selected date
$marked = [];
$res = $conn->query("SELECT student_id, status FROM attendance WHERE date='$date'");
while ($r = $res->fetch_assoc()) {
    $marked[$r['student_id']] = $r['status'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mark Attendance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="d-flex">

    <?php include("sidebar.php"); ?>

    <div class="content">
        <h2 class="mb-4"><i class="bi bi-calendar-check me-2"></i>Mark Attendance</h2>

        <?php if ($error):   ?><div class="alert alert-danger"><?php echo $error;   ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?php echo $success; ?></div><?php endif; ?>

        <!-- Date Picker -->
        <form method="GET" class="mb-4 d-flex align-items-center gap-2">
            <label class="form-label mb-0 fw-semibold">Select Date:</label>
            <input type="date" name="date" class="form-control" style="width:200px;"
                   value="<?php echo $date; ?>">
            <button class="btn btn-outline-primary">Load</button>
        </form>

        <form method="post">
            <input type="hidden" name="att_date" value="<?php echo $date; ?>">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Date: <span class="text-primary"><?php echo $date; ?></span></h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="markAll('Present')">
                        <i class="bi bi-check-all me-1"></i>All Present
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="markAll('Absent')">
                        <i class="bi bi-x-circle me-1"></i>All Absent
                    </button>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Reg No</th>
                                <th>Student Name</th>
                                <th>Course</th>
                                <th class="text-center text-success">Present</th>
                                <th class="text-center text-danger">Absent</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $i = 1;
                        $students->data_seek(0);
                        while ($row = $students->fetch_assoc()):
                            $sid = $row['student_id'];
                            $current = isset($marked[$sid]) ? $marked[$sid] : '';
                        ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['course_name'] ?? '-'); ?></td>
                                <td class="text-center">
                                    <input type="radio"
                                           class="form-check-input att-radio"
                                           name="attendance[<?php echo $sid; ?>]"
                                           value="Present"
                                           <?php echo $current=='Present'?'checked':''; ?>>
                                </td>
                                <td class="text-center">
                                    <input type="radio"
                                           class="form-check-input att-radio"
                                           name="attendance[<?php echo $sid; ?>]"
                                           value="Absent"
                                           <?php echo $current=='Absent'?'checked':''; ?>>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <button type="submit" name="save" class="btn btn-success mt-3">
                <i class="bi bi-save me-1"></i>Save Attendance
            </button>
        </form>
    </div>
</div>

<script>
function markAll(status) {
    document.querySelectorAll('.att-radio').forEach(r => {
        if (r.value === status) r.checked = true;
    });
}
</script>

</body>
</html>
