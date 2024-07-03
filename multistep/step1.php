<?php include 'db_connection.php'; ?>

<!-- Step 1: Collect User's Name -->
<form method="post" action="step2.php">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required>
    <input type="submit" value="Next">
</form>
