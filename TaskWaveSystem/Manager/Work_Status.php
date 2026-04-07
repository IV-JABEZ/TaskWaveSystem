<?php include "db.php"; ?>
<?php include "sidebar.php"; ?>

<div style="margin-left:240px; padding:20px;">
    <h2>Work Status</h2>

    <?php
    $result = $conn->query("
        SELECT employees.name, tasks.title, tasks.description, tasks.deadline, tasks.status 
        FROM tasks
        JOIN employees ON tasks.employee_id = employees.id
    ");

    echo "<table border='1' cellpadding='10'>";
    echo "<tr>
            <th>Employee</th>
            <th>Title</th>
            <th>Description</th>
            <th>Deadline</th>
            <th>Status</th>
          </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['title']}</td>
                <td>{$row['description']}</td>
                <td>{$row['deadline']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }

    echo "</table>";
    ?>
</div>