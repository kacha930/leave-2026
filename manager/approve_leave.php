<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Manager') {
    header("Location: ../auth/login.php");
    exit();
}

$leave_id = $_GET['id'];

// Fetch leave request
$query = "SELECT * FROM leave_requests WHERE id = $leave_id";
$leave = mysqli_fetch_assoc(mysqli_query($conn, $query));

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $comment = $_POST['comment'];

    if ($status == 'Approved') {
        // Deduct leave balance
        $balance_update = "UPDATE leave_balances
                           SET remaining_days = remaining_days - {$leave['total_days']}
                           WHERE user_id = {$leave['user_id']}
                           AND leave_type_id = {$leave['leave_type_id']}";
        mysqli_query($conn, $balance_update);
    }

    // Update leave request
    $update_request = "UPDATE leave_requests
                       SET status = '$status', manager_comment = '$comment'
                       WHERE id = $leave_id";
    mysqli_query($conn, $update_request);

    header("Location: dashboard.php");
    exit();
}
?>

<h2>Review Leave Request</h2>

<p><strong>Employee ID:</strong> <?= $leave['user_id']; ?></p>
<p><strong>Leave Type ID:</strong> <?= $leave['leave_type_id']; ?></p>
<p><strong>From:</strong> <?= $leave['start_date']; ?></p>
<p><strong>To:</strong> <?= $leave['end_date']; ?></p>
<p><strong>Total Days:</strong> <?= $leave['total_days']; ?></p>

<form method="POST">
    <label>Decision:</label><br>
    <select name="status" required>
        <option value="">Select</option>
        <option value="Approved">Approve</option>
        <option value="Rejected">Reject</option>
    </select><br><br>

    <label>Comment:</label><br>
    <textarea name="comment"></textarea><br><br>

    <button type="submit">Submit Decision</button>
</form>

<br>
<a href="dashboard.php">Back</a>
