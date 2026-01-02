<?php
session_start();
require_once "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT users.*, roles.role_name 
              FROM users 
              JOIN roles ON users.role_id = roles.id 
              WHERE email = '$email'";

    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role_name'];

            if ($user['role_name'] == 'Admin') {
                header("Location: ../admin/dashboard.php");
            } elseif ($user['role_name'] == 'Manager') {
                header("Location: ../manager/dashboard.php");
            } else {
                header("Location: ../employee/dashboard.php");
            }
            exit();
        }
    }
    $error = "Invalid email or password";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h2>Leave Management System - Login</h2>

<?php if(isset($error)) echo "<p style='color:red'>$error</p>"; ?>

<form method="POST">
    <input type="email" name="email" required placeholder="Email"><br><br>
    <input type="password" name="password" required placeholder="Password"><br><br>
    <button type="submit">Login</button>
</form>
</body>
</html>
