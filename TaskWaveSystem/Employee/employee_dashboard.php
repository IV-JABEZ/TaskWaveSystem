<?php
session_start();
include "../include/db.php";

// Ensure logged-in employee
if(!isset($_SESSION['user']) || $_SESSION['position'] !== 'employee'){
    header("Location: ../login.php"); 
    exit();
}

$employee_name = $_SESSION['user'];
$employee = $conn->query("SELECT id FROM user WHERE name='$employee_name' AND position='employee'")->fetch_assoc();

// Fetch tasks assigned to this employee
$tasks = $conn->query("SELECT t.id, t.title, t.description, t.deadline, t.status, u.name as manager_name
                       FROM tasks t JOIN user u ON t.manager_id=u.id
                       WHERE t.employee_id=".$employee['id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Employee Dashboard - TaskWaveSystem</title>
<link rel="stylesheet" href="assets\style.css" />

<style>
/* Sidebar */
.sidebar{
    width:250px;
    height:100vh;
    background:#1e293b;
    color:white;
    position:fixed;
    top:0;
    left:0;
    padding:20px;
}

.sidebar h2{
    text-align:center;
    margin-bottom:30px;
}

.sidebar a{
    display:block;
    color:white;
    text-decoration:none;
    padding:12px;
    margin-bottom:10px;
    border-radius:6px;
    transition:0.3s;
}

.sidebar a:hover{
    background:#334155;
}

/* Main content */
.main{
    margin-left:300px; /* match sidebar width */
    padding:10px;
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
}
header h1{margin:0;}
header button{
    background:#FF5252;
    border:none;
    padding:10px 15px;
    border-radius:6px;
    cursor:pointer;
    color:white;
    font-weight:500;
}

/* Sections and table */
section{
    margin-top:20px;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}
table{
    width:100%;
    border-collapse:collapse;
}
th, td{
    padding:10px;
    border-bottom:1px solid #ddd;
    text-align:left;
}
th{
    background:#f4f4f4;
}
button.action-btn{
    background:#4CAF50;
    color:white;
    border:none;
    padding:6px 10px;
    border-radius:5px;
    cursor:pointer;
    margin-right:5px;
}
</style>
</head>
<body>

<!-- Include Employee Sidebar -->
<?php include "../Employee/employee_sidebar.php"; ?>

<!-- Main content -->
<div class="main">
    <header>
        <h1>Employee Dashboard</h1>
    
    </header>

    <!-- Assigned Tasks -->
    <section>
        <h2>Your Tasks</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Manager</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($task=$tasks->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($task['title']); ?></td>
                <td><?php echo htmlspecialchars($task['description']); ?></td>
                <td><?php echo htmlspecialchars($task['manager_name']); ?></td>
                <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                <td><?php echo htmlspecialchars($task['status']); ?></td>
                <td><button class="action-btn" onclick="updateStatus(<?php echo $task['id']; ?>)">Update Status</button></td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</div>

<script>
function updateStatus(taskId){
    alert("Update status for task ID: "+taskId);
}
</script>

</body>
</html>