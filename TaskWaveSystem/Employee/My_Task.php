<?php
session_start();
include '../include/db.php';

include 'employee_sidebar.php'; 

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch tasks assigned to this employee
$sql = "SELECT t.*, u.name as manager_name 
        FROM tasks t 
        LEFT JOIN user u ON t.manager_id = u.id 
        WHERE t.employee_id = ? 
        ORDER BY 
            CASE 
                WHEN t.status = 'pending' THEN 1
                WHEN t.status = 'in-progress' THEN 2
                ELSE 3 
            END, 
            t.deadline ASC";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Count tasks by status
$count_sql = "SELECT status, COUNT(*) as count FROM tasks WHERE employee_id = ? GROUP BY status";
$count_stmt = mysqli_prepare($conn, $count_sql);
mysqli_stmt_bind_param($count_stmt, "i", $user_id);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$stats = [];
while($stat = mysqli_fetch_assoc($count_result)) {
    $stats[$stat['status']] = $stat['count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Tasks - TaskWaveSystem</title>

<style>
/* Main content */
.content{
    margin-left:300px;
    padding:20px;
    background:#f0f2f5;
    min-height:100vh;
}

/* Header */
header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    background:#2196F3;
    color:white;
    padding:15px 20px;
    border-radius:8px;
    margin-bottom:20px;
}
header h1{margin:0;}

/* Sections */
section{
    margin-bottom:20px;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

/* Stats Cards */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
    margin-bottom: 20px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
}

.stat-number {
    font-size: 2em;
    font-weight: bold;
    margin-bottom: 5px;
}

/* Task Cards */
.task-box {
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    background: #fafbfc;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.task-box:hover {
    border-color: #2196F3;
    box-shadow: 0 5px 15px rgba(33, 150, 243, 0.2);
    transform: translateY(-2px);
}

.task-box::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #4CAF50, #2196F3, #FF9800);
}

.task-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 15px;
}

.task-title {
    font-size: 1.4em;
    font-weight: bold;
    color: #1e293b;
    flex: 1;
    margin: 0;
}

.task-meta {
    text-align: right;
}

.manager-info {
    background: #e3f2fd;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.85em;
    color: #1976d2;
    margin-bottom: 5px;
    display: inline-block;
}

.deadline {
    color: #666;
    font-size: 0.9em;
    font-weight: 500;
}

.status-badge {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.9em;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending { 
    background: #fff3cd; 
    color: #856404; 
    border: 1px solid #ffeaa7;
}
.status-in-progress { 
    background: #cce5ff; 
    color: #004085; 
    border: 1px solid #99d1ff;
    animation: pulse 2s infinite;
}
.status-completed { 
    background: #d4edda; 
    color: #155724; 
    border: 1px solid #b8e6b0;
}

@keyframes pulse {
    0% { box-shadow: 0 0 0 0 rgba(33, 150, 243, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(33, 150, 243, 0); }
    100% { box-shadow: 0 0 0 0 rgba(33, 150, 243, 0); }
}

.task-description {
    color: #555;
    line-height: 1.6;
    margin-bottom: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #dee2e6;
}

.progress-container {
    margin: 15px 0;
}

.progress-bar {
    width: 100%;
    height: 25px;
    background: #e0e0e0;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 8px;
}

.progress-fill {
    height: 100%;
    background: linear-gradient(90deg, #4CAF50, #8BC34A);
    border-radius: 12px;
    transition: width 0.8s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    font-size: 0.9em;
}

.last-update {
    background: #e8f5e8;
    padding: 12px;
    border-radius: 8px;
    font-size: 0.9em;
    color: #2e7d32;
    border-left: 4px solid #4caf50;
}

.action-buttons {
    margin-top: 15px;
    text-align: right;
}

.btn {
    background: #2196F3;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    text-decoration: none;
    display: inline-block;
    margin-left: 10px;
    transition: all 0.3s ease;
}

.btn:hover {
    background: #1976d2;
    transform: translateY(-1px);
}

.btn-success {
    background: #4CAF50;
}

.btn-success:hover {
    background: #45a049;
}

.no-tasks {
    text-align: center;
    padding: 60px 20px;
    color: #666;
}

.no-tasks h3 {
    color: #2196F3;
    margin-bottom: 10px;
}

@media (max-width: 768px) {
    .content { margin-left: 0; padding: 10px; }
}
</style>
</head>
<body>

<div class="content">
    <!-- Header -->
    <header>
        <h1>📋 My Tasks</h1>
        <div>Total Tasks: <?php echo mysqli_num_rows($result); ?></div>
    </header>

    <!-- Stats Overview -->
    <?php if(mysqli_num_rows($result) > 0): ?>
    <section>
        <h2>📊 Task Overview</h2>
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
                <div>Pending</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['in-progress'] ?? 0; ?></div>
                <div>In Progress</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $stats['completed'] ?? 0; ?></div>
                <div>Completed</div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Tasks List -->
    <section>
        <h2>🎯 All Tasks (<?php echo mysqli_num_rows($result); ?>)</h2>
        
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php mysqli_data_seek($result, 0); // Reset result pointer ?>
            <?php while($task = mysqli_fetch_assoc($result)): ?>
                <div class="task-box">
                    <div class="task-header">
                        <div>
                            <h3 class="task-title"><?= htmlspecialchars($task['title']); ?></h3>
                            <div class="deadline">
                                📅 <?= date('M j, Y', strtotime($task['deadline'])); ?>
                                <?php if(strtotime($task['deadline']) < time()): ?>
                                    <span style="color:#f44336;">(Overdue)</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="task-meta">
                            <?php if($task['manager_name']): ?>
                                <div class="manager-info">👨‍💼 <?= htmlspecialchars($task['manager_name']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="status-badge status-<?= strtolower($task['status']); ?>">
                        <?= ucfirst($task['status']); ?>
                    </div>

                    <div class="task-description">
                        <?= htmlspecialchars($task['description']); ?>
                    </div>

                    <?php if(isset($task['progress']) && $task['progress'] > 0): ?>
                        <div class="progress-container">
                            <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-weight:500;">
                                <span>Progress:</span>
                                <span><?= $task['progress']; ?>%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $task['progress']; ?>%">
                                    <?= $task['progress']; ?>%
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($task['update_notes']): ?>
                        <div class="last-update">
                            📝 <?= htmlspecialchars($task['update_notes']); ?>
                        </div>
                    <?php endif; ?>

                    <div class="action-buttons">
                        <a href="Update_Progress.php" class="btn btn-success">✏️ Update Progress</a>
                        <?php if($task['status'] != 'completed'): ?>
                            <a href="#" class="btn" onclick="markComplete(<?= $task['id']; ?>)">✅ Mark Complete</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="no-tasks">
                <h3>🎉 No Tasks Assigned!</h3>
                <p>You don't have any tasks right now. Great job staying on top of your work!</p>
            </div>
        <?php endif; ?>
    </section>
</div>

<script>
// Animate progress bars
document.addEventListener('DOMContentLoaded', function() {
    const progressBars = document.querySelectorAll('.progress-fill');
    progressBars.forEach((bar, index) => {
        const width = bar.style.width;
        bar.style.width = '0%';
        setTimeout(() => {
            bar.style.width = width;
        }, index * 200);
    });
});

function markComplete(taskId) {
    if(confirm('Mark this task as completed?')) {
        // AJAX update or redirect
        window.location.href = 'mark_complete.php?id=' + taskId;
    }
}
</script>

</body>
</html>

<?php
mysqli_stmt_close($stmt);
mysqli_stmt_close($count_stmt);
?>