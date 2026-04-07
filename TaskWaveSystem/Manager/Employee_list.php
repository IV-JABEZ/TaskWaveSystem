<?php include "../include/db.php"; ?>
<?php include "../sidebar.php"; ?>

<div class="main">
    <div class="card">
        <h2>👨‍💻 Employee Management</h2>

        <form method="POST" class="form">
            <input type="text" name="name" placeholder="Enter employee name..." required>
            <button type="submit" name="add">+ Add Employee</button>
        </form>

        <?php
        // ADD EMPLOYEE
        if (isset($_POST['add'])) {
            $name = $_POST['name'];
            $conn->query("INSERT INTO user (name) VALUES ('$name')");
        }

        // DELETE EMPLOYEE
        if (isset($_GET['delete'])) {
            $id = $_GET['delete'];
            $conn->query("DELETE FROM user WHERE id='$id'");
        }

        $result = $conn->query("SELECT * FROM user");

        echo "
        <table class='table'>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        ";

        while ($row = $result->fetch_assoc()) {
            echo "
            <tr>
                <td>{$row['id']}</td>
                <td>👤 {$row['name']}</td>
                <td>
                    <a href='task_assign.php?employee_id={$row['id']}' class='btn assign'>Assign Task</a>
                    <a href='?delete={$row['id']}' class='btn delete' onclick='return confirm(\"Delete this employee?\")'>Delete</a>
                </td>
            </tr>
            ";
        }

        echo "</table>";
        ?>
    </div>
</div>

<style>
/* Layout */
.main {
    margin-left: 240px;
    padding: 30px;
    background: #f4f6f9;
    min-height: 100vh;
    font-family: Arial, sans-serif;
}

/* Card */
.card {
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}

/* Form */
.form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.form input {
    flex: 1;
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

.form button {
    background: #3498db;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 6px;
    cursor: pointer;
}

/* Table */
.table {
    width: 100%;
    border-collapse: collapse;
}

.table th {
    background: #2c3e50;
    color: white;
    padding: 12px;
    text-align: left;
}

.table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.table tr:hover {
    background: #f1f1f1;
}

/* Buttons */
.btn {
    padding: 6px 10px;
    border-radius: 5px;
    text-decoration: none;
    color: white;
    margin-right: 5px;
    font-size: 13px;
}

.assign {
    background: #27ae60;
}

.assign:hover {
    background: #219150;
}

.delete {
    background: #e74c3c;
}

.delete:hover {
    background: #c0392b;
}
</style>