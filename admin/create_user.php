<?php
session_start();
require_once "../config/db.php";

if ($_SESSION['role'] != 'Admin') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $role_id = $_POST['role_id'];
    $department = $_POST['department'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "INSERT INTO users (full_name, email, password, role_id, department)
              VALUES ('$full_name', '$email', '$password', $role_id, '$department')";

    mysqli_query($conn, $query);
    $success = "User created successfully";
}
?>

<h2>Create User</h2>

<?php if(isset($success)) echo "<p style='color:green'>$success</p>"; ?>

<form method="POST">
    <input type="text" name="full_name" required placeholder="Full Name"><br><br>
    <input type="email" name="email" required placeholder="Email"><br><br>

    <select name="role_id" required>
        <option value="">Select Role</option>
        <option value="1">Employee</option>
        <option value="2">Manager</option>
        <option value="3">Admin</option>
    </select><br><br>

    <input type="text" name="department" placeholder="Department"><br><br>
    <input type="password" name="password" required placeholder="Temporary Password"><br><br>

    <button type="submit">Create User</button>
</form>
