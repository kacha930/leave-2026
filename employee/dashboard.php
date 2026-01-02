<?php
session_start();
if ($_SESSION['role'] != 'Employee') {
    header("Location: ../auth/login.php");
}
echo "<h2>Employee Dashboard</h2>";
echo "<a href='../auth/logout.php'>Logout</a>";
