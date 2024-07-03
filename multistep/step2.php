<?php include 'db_connection.php'; ?>

<!-- Step 2: Collect User's Email -->
<form method="post" action="step3.php">
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <?php 
    // Pass the name data to the next step using hidden input
    if(isset($_POST['name'])){
        echo '<input type="hidden" name="name" value="'.$_POST['name'].'">';
    }
    ?>
    <input type="submit" value="Next">
</form>
