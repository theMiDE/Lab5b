<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE matric = ?");
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['matric'] = $matric;
            header("Location: display_users.php");
            exit();
        } else {
            $error = "Invalid credentials";
        }
    } else {
        $error = "User not found";
    }
    $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        .error { color: red; }
        .register-link { text-align: center; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Login</h2>
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form method="POST" action="">
        <input type="text" name="matric" placeholder="Matric Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" value="Login">
    </form>
    <div class="register-link">
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>