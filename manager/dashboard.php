<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Manager') {
    header("Location: ../auth/login.php");
    exit();
}

$query = "SELECT lr.*, u.full_name, lt.leave_name
          FROM leave_requests lr
          JOIN users u ON lr.user_id = u.id
          JOIN leave_types lt ON lr.leave_type_id = lt.id
          WHERE lr.status = 'Pending'
          ORDER BY lr.applied_at ASC";

$result = mysqli_query($conn, $query);
?>

<h2>Manager Dashboard</h2>

<table border="1" cellpadding="8">
    <tr>
        <th>Employee</th>
        <th>Leave Type</th>
        <th>From</th>
        <th>To</th>
        <th>Days</th>
        <th>Action</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td><?= $row['full_name']; ?></td>
        <td><?= $row['leave_name']; ?></td>
        <td><?= $row['start_date']; ?></td>
        <td><?= $row['end_date']; ?></td>
        <td><?= $row['total_days']; ?></td>
        <td>
            <a href="approve_leave.php?id=<?= $row['id']; ?>">Review</a>
        </td>
    </tr>
    <?php } ?>
</table>

<br>
<a href="../auth/logout.php">Logout</a>
