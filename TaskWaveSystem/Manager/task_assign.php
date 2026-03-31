<?php
session_start();
include "../include/db.php"; // Ensure path is correct

// Only managers can assign tasks
if (!isset($_SESSION['user']) || $_SESSION['position'] !== 'manager') {
    header("Location: ../login.php");
    exit();
}

// Check if form is submitted
if (isset($_POST['add_task'])) {

    // Validate required fields
    if (empty($_POST['employee_id']) || empty($_POST['title']) || empty($_POST['deadline'])) {
        header("Location: manager_dashboard.php?success=0&error=missing_fields");
        exit();
    }

    $employee_id = (int)$_POST['employee_id']; // Cast to integer
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $deadline = $conn->real_escape_string($_POST['deadline']);

    // Get manager ID
    $manager_name = $_SESSION['user'];
    $manager_query = $conn->query("SELECT id FROM user WHERE name='$manager_name' AND position='manager'");
    
    if ($manager_query && $manager_query->num_rows > 0) {
        $manager_data = $manager_query->fetch_assoc();
        $manager_id = $manager_data['id'];
    } else {
        die("Manager not found.");
    }

    // Insert task
    $sql = "INSERT INTO tasks (title, description, deadline, status, manager_id, employee_id)
            VALUES ('$title', '$description', '$deadline', 'Pending', '$manager_id', '$employee_id')";

    if ($conn->query($sql)) {
        // Redirect back with success
        header("Location: manager_dashboard.php?success=1");
        exit();
    } else {
        // Redirect back with error
        header("Location: manager_dashboard.php?success=0&error=db_error");
        exit();
    }
} else {
    // Prevent direct access
    header("Location: manager_dashboard.php");
    exit();
}
?>