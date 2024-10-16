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
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $conn->query("SELECT task, completed FROM tasks WHERE id = $id");

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $task = $row['task'];
        $completed = $row['completed'];
    } else {
        echo "Task not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['task'], $_POST['id'])) {
    $id = $_POST['id'];
    $task = $_POST['task'];
    $completed = isset($_POST['completed']) ? 1 : 0;

    // Update the task in the database
    $sql = "UPDATE tasks SET task = ?, completed = ? WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('sii', $task, $completed, $id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}
?>

<!-- Form to edit the task -->
<form method="POST" action="edit.php">
    <input type="hidden" name="id" value="<?= $id ?>">
    <input type="text" name="task" value="<?= htmlspecialchars($task) ?>" required>
    
    <!-- Checkbox for completed status -->
    <label>
        Completed:
        <input type="checkbox" name="completed" <?= $completed ? 'checked' : '' ?>>
    </label>
    
    <button type="submit">Update Task</button>
</form>
