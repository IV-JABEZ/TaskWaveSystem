<?php include "sidebar.php"; ?>

<div style="margin-left:240px; padding:20px;">
    <h2>Settings</h2>

    <form method="POST">
        <input type="text" name="site_name" placeholder="Site Name">
        <button type="submit">Save</button>
    </form>

    <?php
    if ($_POST) {
        echo "<p>Settings saved!</p>";
    }
    ?>
</div>