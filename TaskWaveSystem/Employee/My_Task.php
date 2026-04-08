<?php
session_start();
include '../includes/db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM tasks WHERE employee_id = ? ORDER BY deadline ASC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Tasks</title>
<link rel="stylesheet" href="sidebar.css">
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="content">
<h2>📋 My Tasks</h2>

<?php if(mysqli_num_rows($result) > 0): ?>
    <?php while($task = mysqli_fetch_assoc($result)): ?>
        <div class="task-box">
            <h3><?= htmlspecialchars($task['title']); ?></h3>
            <p><?= htmlspecialchars($task['description']); ?></p>
            <p><strong>Deadline:</strong> <?= $task['deadline']; ?></p>
            <span class="<?= strtolower($task['status']); ?>"><?= $task['status']; ?></span>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No tasks assigned to you.</p>
<?php endif; ?>

</div>
</body>
</html>