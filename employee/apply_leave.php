<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Employee') {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch leave types
$leave_types = mysqli_query($conn, "SELECT * FROM leave_types");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_type_id = $_POST['leave_type_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    $start = new DateTime($start_date);
    $end = new DateTime($end_date);
    $total_days = $start->diff($end)->days + 1;

    // Check leave balance
    $balance_query = "SELECT remaining_days FROM leave_balances 
                      WHERE user_id = $user_id AND leave_type_id = $leave_type_id";
    $balance_result = mysqli_query($conn, $balance_query);
    $balance = mysqli_fetch_assoc($balance_result)['remaining_days'];

    if ($total_days > $balance) {
        $error = "Insufficient leave balance.";
    } else {
        // Check overlapping leave
        $overlap_query = "SELECT * FROM leave_requests 
                          WHERE user_id = $user_id 
                          AND status IN ('Pending','Approved')
                          AND (start_date <= '$end_date' AND end_date >= '$start_date')";
        $overlap_result = mysqli_query($conn, $overlap_query);

        if (mysqli_num_rows($overlap_result) > 0) {
            $error = "You already have a leave during this period.";
        } else {
            // Insert leave request
            $insert = "INSERT INTO leave_requests 
                       (user_id, leave_type_id, start_date, end_date, total_days, reason)
                       VALUES ($user_id, $leave_type_id, '$start_date', '$end_date', $total_days, '$reason')";

            mysqli_query($conn, $insert);
            $success = "Leave application submitted successfully.";
        }
    }
}
?>

<h2>Apply for Leave</h2>

<?php
if (isset($error)) echo "<p style='color:red'>$error</p>";
if (isset($success)) echo "<p style='color:green'>$success</p>";
?>

<form method="POST">
    <label>Leave Type:</label><br>
    <select name="leave_type_id" required>
        <option value="">Select Leave</option>
        <?php while($lt = mysqli_fetch_assoc($leave_types)) { ?>
            <option value="<?= $lt['id']; ?>"><?= $lt['leave_name']; ?></option>
        <?php } ?>
    </select><br><br>

    <label>Start Date:</label><br>
    <input type="date" name="start_date" required><br><br>

    <label>End Date:</label><br>
    <input type="date" name="end_date" required><br><br>

    <label>Reason:</label><br>
    <textarea name="reason"></textarea><br><br>

    <button type="submit">Submit Leave</button>
</form>

<br>
<a href="dashboard.php">Back to Dashboard</a>
