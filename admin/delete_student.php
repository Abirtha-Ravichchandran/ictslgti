<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }
include("../config/db.php");

$id = intval($_GET['id']);

// Delete attendance records first
$conn->query("DELETE FROM attendance WHERE student_id='$id'");
// Delete enrollment
$conn->query("DELETE FROM enrollment WHERE student_id='$id'");
// Delete student
$conn->query("DELETE FROM student WHERE student_id='$id'");

header("Location: students.php?msg=Student deleted successfully");
exit();
?>
