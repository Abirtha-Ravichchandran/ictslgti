<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$students = $conn->query("SELECT s.*, c.course_name FROM student s LEFT JOIN course c ON s.course_id = c.course_id ORDER BY s.student_id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="d-flex">

    <?php include("sidebar.php"); ?>

    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="bi bi-people me-2"></i>Students</h2>
            <a href="add_student.php" class="btn btn-primary">
                <i class="bi bi-person-plus me-1"></i>Add Student
            </a>
        </div>

        <?php if (isset($_GET['msg'])): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Reg No</th>
                            <th>Full Name</th>
                            <th>NIC</th>
                            <th>Gender</th>
                            <th>Course</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    while ($row = $students->fetch_assoc()):
                    ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo htmlspecialchars($row['reg_no']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['nic']); ?></td>
                            <td>
                                <span class="badge <?php echo $row['gender']=='Male'?'bg-primary':'bg-pink'; ?>">
                                    <?php echo htmlspecialchars($row['gender']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($row['course_name'] ?? 'N/A'); ?></td>
                            <td>
                                <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="btn btn-sm btn-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="delete_student.php?id=<?php echo $row['student_id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Delete this student?')">
                                    <i class="bi bi-trash"></i>
                                </a>
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
