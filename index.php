<?php
// $servername = "localhost";
// $username   = "root";
// $password   = "Nishant@12345";
// $db_name    = "NicheInterviewDB";
// $port       = 3306;
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
    $completed = isset($_POST['completed']) ? 1 : 0; 
    $sql = "INSERT INTO tasks (task, completed) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $task, $completed);
    $stmt->execute();
    $stmt->close();
    header("Location: index.php");
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
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Task Manager</h1>

    <form action="index.php" method="POST">
        <input type="text" name="task" placeholder="Enter task" required>
        <label>
        Completed:
        <input type="checkbox" name="completed">
    </label>
        <button type="submit">Add Task</button>
    </form>

    <h2>Tasks List</h2>
    
        

<?php
// Fetch tasks from the database
$result = $conn->query("SELECT id, task, completed FROM tasks");

if ($result->num_rows > 0) {
    echo "<ul class='task-list'>";
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'];
        $task = $row['task'];
        $completed = $row['completed'] ? "Yes" : "No"; // Display 'Yes' if completed is 1, otherwise 'No'

        echo "<li class='task-item'>
                <div class='task-details'>
                    <span class='task-name'>$task</span> 
                    <span class='task-status'>(Completed: $completed)</span>
                </div>
                <div class='task-actions'>
                    <a href='edit.php?id=$id' class='task-edit'>Edit</a>
                    <a href='index.php?delete=$id' class='task-delete'>Delete</a>
                </div>
              </li>";
    }
    echo "</ul>";
} else {
    echo "No tasks found.";
}
?>
    </table>
</body>
</html>

<?php

$conn->close();
?>
