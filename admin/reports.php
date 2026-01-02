<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

$query = "SELECT u.full_name, lt.leave_name, 
                 SUM(lr.total_days) AS total_days_taken
          FROM leave_requests lr
          JOIN users u ON lr.user_id = u.id
          JOIN leave_types lt ON lr.leave_type_id = lt.id
          WHERE lr.status = 'Approved'
          GROUP BY u.id, lt.id";

$result = mysqli_query($conn, $query);
?>

<h2>Leave Utilization Report</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Employee</th>
        <th>Leave Type</th>
        <th>Total Days Taken</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['full_name']; ?></td>
        <td><?= $row['leave_name']; ?></td>
        <td><?= $row['total_days_taken']; ?></td>
    </tr>
    <?php } ?>
</table>

<br>
<a href="dashboard.php">Back</a>
