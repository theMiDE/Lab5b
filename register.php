<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_5b";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $accessLevel = $_POST['accessLevel'];


    $stmt = $conn->prepare("INSERT INTO users (matric, name, password, accessLevel) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $matric, $name, $password, $accessLevel);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>User Registration</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 400px; margin: 0 auto; padding: 20px; }
        input { width: 100%; padding: 10px; margin: 10px 0; }
        input[type='submit'] { background-color: #4CAF50; color: white; }
    </style>
</head>
<h2>User Registration</h2>
    <form method="POST" action="">
        <input type="text" name="matric" placeholder="Matric Number" required>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="password" name="password" placeholder="Password" required>
        <select name="accessLevel" required>
            <option value="">Select Access Level</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        <input type="submit" value="Register">
    </form>
</body>
<body>
</body>
</html>