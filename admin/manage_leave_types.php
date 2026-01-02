<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_name = $_POST['leave_name'];
    $max_days = $_POST['max_days'];

    mysqli_query($conn, "INSERT INTO leave_types (leave_name, max_days) VALUES ('$leave_name', $max_days)");
}

$leave_types = mysqli_query($conn, "SELECT * FROM leave_types");
?>

<h2>Manage Leave Types</h2>

<form method="POST">
    <input type="text" name="leave_name" placeholder="Leave Name" required>
    <input type="number" name="max_days" placeholder="Max Days" required>
    <button type="submit">Add Leave Type</button>
</form>

<br>

<table border="1" cellpadding="8">
    <tr>
        <th>Leave Name</th>
        <th>Max Days</th>
    </tr>

    <?php while($row = mysqli_fetch_assoc($leave_types)) { ?>
    <tr>
        <td><?= $row['leave_name']; ?></td>
        <td><?= $row['max_days']; ?></td>
    </tr>
    <?php } ?>
</table>

<br>
<a href="dashboard.php">Back</a>
