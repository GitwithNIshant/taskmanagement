<?php
$servername = "sql12.freesqldatabase.com";
$username   = "sql12737689";
$password   = "JNerPPcC6U";
$db_name    = "sql12737689";
$port       = 3306;  



$conn = new mysqli($servername, $username, $password, $db_name, $port);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'])) {
    $task = $_POST['task'];
    $completed = 0;
    

    $sql = "INSERT INTO tasks (task, completed) VALUES (?, ?)";
    
    $stmt->bind_param('si', $task, $completed); 
  
    $stmt->execute();
    
    
    $stmt->close();
    header("Location: index.php");
    exit();  
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM tasks WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
}

$sql = "SELECT * FROM tasks";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager</title>
</head>
<body>
    <h1>Task Manager</h1>

    <form action="index.php" method="POST">
        <input type="text" name="task" placeholder="Enter task" required>
        <button type="submit">Add Task</button>
    </form>

  
    <h2>Tasks List</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Task</th>
            <th>Completed</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['task']; ?></td>
                <td><?php echo $row['completed'] ? 'Yes' : 'No'; ?></td>
                <td>
                    <a href="index.php?delete=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php

$conn->close();
?>
