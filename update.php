<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


ini_set('session.use_strict_mode', 1);
session_start();


echo "<!-- Debug Information -->";
echo "<!-- Session Started: " . (session_status() == PHP_SESSION_ACTIVE ? 'Yes' : 'No') . " -->";
echo "<!-- Login Status: " . (isset($_SESSION['loggedin']) ? 'Logged In' : 'Not Logged In') . " -->";

try {
    
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "Lab_5b";

   
    $conn = new mysqli($servername, $username, $password, $dbname);

    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    echo "<!-- Database Connection Successful -->";

    
    if (isset($_POST['update'])) {
        $matric = $conn->real_escape_string($_POST['matric']);
        $name = $conn->real_escape_string($_POST['name']);
        $accessLevel = $conn->real_escape_string($_POST['accessLevel']);

        $update_query = "UPDATE users SET name = '$name', accessLevel = '$accessLevel' WHERE matric = '$matric'";
        
        if ($conn->query($update_query) === TRUE) {
            echo "<!-- User Update Successful -->";
        } else {
            throw new Exception("Update failed: " . $conn->error);
        }
    }

    
    if (isset($_POST['delete'])) {
        $matric = $conn->real_escape_string($_POST['matric']);

        $delete_query = "DELETE FROM users WHERE matric = '$matric'";
        
        if ($conn->query($delete_query) === TRUE) {
            echo "<!-- User Delete Successful -->";
        } else {
            throw new Exception("Delete failed: " . $conn->error);
        }
    }

    
    $users_query = "SELECT matric, name FROM users";
    $users_result = $conn->query($users_query);

    if ($users_result === false) {
        throw new Exception("Users fetch failed: " . $conn->error);
    }

} catch (Exception $e) {
    
    error_log($e->getMessage());
    echo "<!-- Error: " . $e->getMessage() . " -->";
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Update/Delete User</title>
    <style type="text/css">
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; }
        input, select { width: 100%; padding: 10px; margin: 10px 0; }
        .action-links { margin-bottom: 10px; }
    </style>
    <script type="text/javascript">
        function confirmDelete() {
            return confirm('Are you sure you want to delete this user?');
        }
    </script>
</head>
<body>
    <div class="action-links">
        <a href="display_users.php">Back to Users List</a> | 
        <a href="logout.php">Logout</a>
    </div>
    
    <h2>Update/Delete User</h2>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <select name="matric" id="userSelect" required>
            <option value="">Select User</option>
            <?php
            if (isset($users_result) && $users_result->num_rows > 0) {
                while($row = $users_result->fetch_assoc()) {
                    echo "<option value='" . htmlspecialchars($row['matric']) . "'>" 
                         . htmlspecialchars($row['name']) . " (" . htmlspecialchars($row['matric']) . ")</option>";
                }
            } else {
                echo "<option>No users found</option>";
            }
            ?>
        </select>
        
        <input type="text" name="name" placeholder="Name" required />
        
        <select name="accessLevel" required>
            <option value="">Select Access Level</option>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
        
        <input type="submit" name="update" value="Update User" />
        <input type="submit" name="delete" value="Delete User" onclick="return confirmDelete();" />
    </form>

    <?php
    // Close connection
    if (isset($conn)) {
        $conn->close();
    }
    ?>
</body>
</html>