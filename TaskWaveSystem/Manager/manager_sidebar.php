<?php
// sidebar.php - reusable sidebar for TaskWave system
?>

<div class="sidebar">
    <h2 class="logo">⚡ TaskWave</h2>

    <ul class="menu">
        <li><a href="manager_dashboard.php" class="active">🏠 <span>Dashboard</span></a></li>
        <li><a href="employee_list.php">👨‍💻 <span>Employees</span></a></li>
        <li><a href="task_assign.php">📋 <span>Tasks Assign</span></a></li>
        <li><a href="work_status.php">📊 <span>Work Status</span></a></li>
        <li><a href="settings.php">⚙️ <span>Settings</span></a></li>
        <a href="../logout.php">🚪 Logout</a>
    </ul>
</div>

<style>
/* Sidebar Container */
.sidebar {
    width: 240px;
    height: 100vh;
    background: linear-gradient(180deg, #2c3e50, #1a252f);
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    display: flex;
    flex-direction: column;
    padding: 20px 15px;
    font-family: 'Segoe UI', sans-serif;
}

/* Logo */
.logo {
    text-align: center;
    margin-bottom: 30px;
    font-size: 22px;
    letter-spacing: 1px;
}

/* Menu */
.menu {
    list-style: none;
    padding: 0;
    flex: 1;
}

.menu li {
    margin-bottom: 10px;
}

.menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: 8px;
    text-decoration: none;
    color: #ecf0f1;
    transition: 0.3s;
}

/* Hover Effect */
.menu a:hover {
    background: rgba(255,255,255,0.1);
    transform: translateX(5px);
}

/* Active Link */
.menu a.active {
    background: #3498db;
    color: #fff;
    font-weight: bold;
}

/* Bottom Section */
.bottom {
    margin-top: auto;
}

/* Logout Button */
.logout {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px;
    border-radius: 8px;
    text-decoration: none;
    color: #fff;
    background: #e74c3c;
    transition: 0.3s;
}

.logout:hover {
    background: #c0392b;
}
</style>