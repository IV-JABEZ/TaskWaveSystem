<?php include "db.php"; ?>
<?php include "../sidebar.php"; ?>

<div style="margin-left:240px; padding:20px;">
    <h2>Assign Task</h2>

    <form method="POST">
        <label>Employee:</label>
        <select name="employee_id" required>
            <?php
            $emp = $conn->query("SELECT * FROM employees");
            while ($e = $emp->fetch_assoc()) {
                echo "<option value='{$e['id']}'>{$e['name']}</option>";
            }
            ?>
        </select><br><br>

        <input type="text" name="title" placeholder="Task Title" required><br><br>

        <textarea name="description" placeholder="Task Description" required></textarea><br><br>

        <label>Deadline:</label>
        <input type="date" name="deadline" required><br><br>

        <button type="submit" name="assign">Assign Task</button>
    </form>

    <?php
    if (isset($_POST['assign'])) {
        $employee_id = $_POST['employee_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $deadline = $_POST['deadline'];

        // For now, static manager_id = 1
        $manager_id = 1;

        $conn->query("INSERT INTO tasks 
        (title, description, manager_id, employee_id, deadline, status) 
        VALUES ('$title', '$description', '$manager_id', '$employee_id', '$deadline', 'Pending')");
    }
    ?>
</div>