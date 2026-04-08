<?php
session_start();
include '../include/db.php';

if(!isset($_SESSION['user_id']) || $_SESSION['position'] !== 'manager'){
    header("Location: ../index.php");
    exit();
}

// Fetch all tasks with employee names
$result = mysqli_query($conn, "
    SELECT t.id, t.title, t.status, u.name AS employee
    FROM tasks t
    JOIN user u ON t.employee_id = u.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Work Status - TaskWave</title>
    <link rel="stylesheet" href="manager_sidebar.css">
    <style>
        .content { margin-left: 260px; padding: 20px; font-family: 'Segoe UI', sans-serif; }
        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 12px; border: 1px solid #ddd; }
        th { background: #3498db; color: #fff; }
        .status { padding: 5px 10px; border-radius: 5px; color: #fff; }
        .pending { background: #f39c12; }
        .completed { background: #2ecc71; }
    </style>
</head>
<body>

<?php include 'manager_sidebar.php'; ?>

<div class="content">
    <h2>📊 Work Status</h2>
    <table>
        <tr>
            <th>Task ID</th>
            <th>Employee</th>
            <th>Task Title</th>
            <th>Status</th>
        </tr>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['id']; ?></td>
            <td><?= htmlspecialchars($row['employee']); ?></td>
            <td><?= htmlspecialchars($row['title']); ?></td>
            <td>
                <span class="status <?= strtolower($row['status']); ?>">
                    <?= $row['status']; ?>
                </span>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>