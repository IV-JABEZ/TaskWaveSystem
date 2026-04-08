<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['position'] !== 'manager'){
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Settings - TaskWave</title>
    <link rel="stylesheet" href="manager_sidebar.css">
    <style>
        .content { margin-left: 260px; padding: 20px; font-family: 'Segoe UI', sans-serif; }
    </style>
</head>
<body>

<?php include 'manager_sidebar.php'; ?>

<div class="content">
    <h2>⚙️ Settings</h2>
    <p>Settings page for manager. You can add profile update or password change here.</p>
</div>

</body>
</html>