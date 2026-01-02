<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<h2>Admin Dashboard</h2>

<ul>
    <li><a href="create_user.php">Create User</a></li>
    <li><a href="manage_leave_types.php">Manage Leave Types</a></li>
    <li><a href="reports.php">View Reports</a></li>
    <li><a href="../auth/logout.php">Logout</a></li>
</ul>
