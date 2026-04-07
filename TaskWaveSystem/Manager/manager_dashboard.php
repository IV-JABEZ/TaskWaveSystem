<?php
session_start();
include "../include/db.php";

// Ensure logged-in manager
if(!isset($_SESSION['user']) || $_SESSION['position'] !== 'manager'){
    header("Location: ../login.php"); 
    exit();
}

$manager_name = $_SESSION['user'];

// Get manager ID
$manager_id = $conn->query("SELECT id FROM user WHERE name='$manager_name' AND position='manager'")->fetch_assoc()['id'];

// Fetch employees
$employees = $conn->query("SELECT id, name, email, employee_role FROM user WHERE position='employee'");

// Fetch tasks
$tasks = $conn->query("
    SELECT t.id, t.title, t.description, t.deadline, t.status, u.name AS employee_name
    FROM tasks t
    JOIN user u ON t.employee_id = u.id
    WHERE t.manager_id = '$manager_id'
");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manager Dashboard - TaskWave</title>
<link rel="stylesheet" href="assets/style.css" />
<style>
/* Sidebar styling will now come from sidebar.php */
.main{
    margin-left:260px; /* adjusted for sidebar width */
    padding:20px;
}

/* Header */
header{display:flex;justify-content:space-between;align-items:center;background:#4CAF50;color:white;padding:15px 20px;border-radius:8px;}
header button{background:#FF5252;border:none;padding:10px 15px;border-radius:6px;color:white;cursor:pointer;}

/* Sections and table */
section{margin-top:20px;background:white;padding:20px;border-radius:10px;box-shadow:0 10px 25px rgba(0,0,0,0.1);}
table{width:100%;border-collapse:collapse;}
th, td{padding:10px;border-bottom:1px solid #ddd;text-align:left;}
th{background:#f4f4f4;}
button.action-btn{background:#2196F3;color:white;border:none;padding:6px 10px;border-radius:5px;cursor:pointer;margin-right:5px;}
button.delete{background:#FF5252;}
.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);justify-content:center;align-items:center;}
.modal-content{background:white;padding:20px;border-radius:10px;width:400px;}
.modal-content input,.modal-content textarea{width:100%;padding:10px;margin:5px 0;}
</style>
</head>
<body>

<!-- Include Sidebar -->
<?php include "../sidebar.php"; ?>

<!-- Main content -->
<div class="main">
    <header>
        <h2>Manager Dashboard</h2>
    </header>

    <!-- Employee List -->
    <section>
        <h2>Employee List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php while($emp = $employees->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($emp['name']); ?></td>
                <td><?php echo htmlspecialchars($emp['email']); ?></td>
                <td><?php echo htmlspecialchars($emp['employee_role']); ?></td>
                <td>
                    <button class="action-btn" onclick="openAssignModal(<?php echo $emp['id']; ?>)">Assign Task</button>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>

    <!-- Task List -->
    <section>
        <h2>Task List</h2>
        <table>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Employee</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php while($task = $tasks->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($task['title']); ?></td>
                <td><?php echo htmlspecialchars($task['description']); ?></td>
                <td><?php echo htmlspecialchars($task['employee_name']); ?></td>
                <td><?php echo htmlspecialchars($task['deadline']); ?></td>
                <td><?php echo htmlspecialchars($task['status']); ?></td>
                <td>
                    <a href="update_task.php?id=<?php echo $task['id']; ?>"><button class="action-btn">Update</button></a>
                    <a href="delete_task.php?id=<?php echo $task['id']; ?>" onclick="return confirm('Delete this task?')"><button class="action-btn delete">Delete</button></a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </section>
</div>

<!-- Assign Task Modal -->
<div class="modal" id="taskModal">
<div class="modal-content">
<h3>Assign Task</h3>
<form method="POST" action="task_assigned.php">
<input type="hidden" name="employee_id" id="employee_id">
<input type="text" name="title" placeholder="Task Title" required>
<textarea name="description" placeholder="Task Description"></textarea>
<input type="date" name="deadline" required>
<button type="submit" name="add_task">Assign</button>
<button type="button" onclick="closeModal()">Cancel</button>
</form>
</div>
</div>

<script>
function openAssignModal(id){
    document.getElementById("employee_id").value=id;
    document.getElementById("taskModal").style.display="flex";
}
function closeModal(){
    document.getElementById("taskModal").style.display="none";
}
</script>
</body>
</html>