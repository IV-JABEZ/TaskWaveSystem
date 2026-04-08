<?php
session_start();
include "../include/db.php"; // same path as employee_list.php

if(!isset($_SESSION['user_id']) || $_SESSION['position'] !== 'manager'){
    header("Location: ../index.php");
    exit();
}

// Assign Task
if(isset($_POST['assign'])){
    $employee_id = $_POST['employee_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $manager_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO tasks (title, description, manager_id, employee_id, deadline, status) 
        VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->bind_param("ssiis", $title, $description, $manager_id, $employee_id, $deadline);
    $stmt->execute();
    $stmt->close();

    header("Location: task_assign.php");
    exit();
}

// Fetch employees for dropdown
$employees = $conn->query("SELECT * FROM user WHERE position='employee'");

// Fetch tasks assigned by this manager
$tasks = $conn->query("SELECT t.*, u.name AS employee_name 
                       FROM tasks t 
                       JOIN user u ON t.employee_id = u.id 
                       WHERE t.manager_id = ".$_SESSION['user_id']."
                       ORDER BY t.deadline ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Assign - TaskWave</title>
    <link rel="stylesheet" href="manager_sidebar.css">
    <style>
        .content { margin-left: 240px; padding: 30px; font-family: Arial, sans-serif; background: #f4f6f9; min-height: 100vh; }
        form { background: #fff; padding: 25px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); max-width: 600px; margin-bottom: 30px; }
        form input, form select, form textarea, form button { display: block; margin: 10px 0; padding: 12px; width: 100%; border-radius: 6px; border: 1px solid #ccc; font-size: 14px; }
        form textarea { resize: vertical; height: 100px; }
        form button { background: #3498db; color: #fff; border: none; cursor: pointer; font-weight: 500; }
        form button:hover { background: #2980b9; }
        h2 { margin-bottom: 20px; }

        table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.08); }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #2c3e50; color: #fff; }
        tr:hover { background: #f1f1f1; }
        .status-pending { color: orange; font-weight: bold; }
        .status-completed { color: green; font-weight: bold; }
    </style>
</head>
<body>

<?php include "manager_sidebar.php"; ?>

<div class="content">
    <h2>📋 Assign Task</h2>
    <form method="POST">
        <label>Employee:</label>
        <select name="employee_id" required>
            <?php while($emp = $employees->fetch_assoc()): ?>
                <option value="<?= $emp['id']; ?>"><?= htmlspecialchars($emp['name']); ?></option>
            <?php endwhile; ?>
        </select>

        <label>Task Title:</label>
        <input type="text" name="title" placeholder="Task Title" required>

        <label>Description:</label>
        <textarea name="description" placeholder="Task Description" required></textarea>

        <label>Deadline:</label>
        <input type="date" name="deadline" required>

        <button type="submit" name="assign">Assign Task</button>
    </form>

    <h2>📝 Tasks Already Assigned</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Employee</th>
            <th>Title</th>
            <th>Description</th>
            <th>Deadline</th>
            <th>Status</th>
        </tr>
        <?php while($task = $tasks->fetch_assoc()): ?>
            <tr>
                <td><?= $task['id']; ?></td>
                <td><?= htmlspecialchars($task['employee_name']); ?></td>
                <td><?= htmlspecialchars($task['title']); ?></td>
                <td><?= htmlspecialchars($task['description']); ?></td>
                <td><?= $task['deadline']; ?></td>
                <td class="<?= $task['status'] === 'Pending' ? 'status-pending' : 'status-completed'; ?>"><?= $task['status']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>