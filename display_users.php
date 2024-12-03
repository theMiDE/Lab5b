<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

echo "<!-- Session Status Check -->";
echo "<!-- Session Exists: " . (isset($_SESSION['loggedin']) ? 'Yes' : 'No') . " -->";

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Lab_5b";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "<!-- Database Connection Successful -->";

    $sql = "SELECT matric, name, accessLevel FROM users";
    $result = $conn->query($sql);

    if ($result === false) {
        throw new Exception("Query failed: " . $conn->error);
    }
} catch (Exception $e) {
   
    error_log($e->getMessage());
    echo "<!-- Error: " . $e->getMessage() . " -->";
}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Users List</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .action-links { margin-bottom: 10px; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="action-links">
        <a href="update.php">Update User</a> | 
        <a href="logout.php">Logout</a>
    </div>
    <h2>Users List</h2>
    <?php
    if (isset($result) && $result !== false) {
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Matric</th><th>Name</th><th>Access Level</th></tr>";
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["matric"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["accessLevel"]) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }
    } else {
        echo "<p class='error'>Error retrieving user data. Please check the database connection.</p>";
    }

    // Close connection if it was opened
    if (isset($conn)) {
        $conn->close();
    }
    ?>
</body>
</html>