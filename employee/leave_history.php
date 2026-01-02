<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Employee') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT lr.*, lt.leave_name 
          FROM leave_requests lr
          JOIN leave_types lt ON lr.leave_type_id = lt.id
          WHERE lr.user_id = $user_id
          ORDER BY applied_at DESC";

$result = mysqli_query($conn, $query);
?>

<h2>My Leave History</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Leave Type</th>
        <th>From</th>
        <th>To</th>
        <th>Days</th>
        <th>Status</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['leave_name']; ?></td>
        <td><?= $row['start_date']; ?></td>
        <td><?= $row['end_date']; ?></td>
        <td><?= $row['total_days']; ?></td>
        <td><?= $row['status']; ?></td>
    </tr>
    <?php } ?>
</table>

<br>
<a href="dashboard.php">Back to Dashboard</a>
