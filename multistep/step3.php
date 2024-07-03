<?php
include 'db_connection.php';

// Process step 3 and store data in database
if (isset($_POST['email']) && isset($_POST['name']) && isset($_POST['age'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $age = $_POST['age'];

    // Insert data into database
    $sql = "INSERT INTO form (name, email, age) VALUES ('$name', '$email', '$age')";

    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!-- Step 3: Collect User's Age -->
<form method="post">
    <label for="age">Age:</label>
    <input type="number" name="age" id="age" required>
    <?php 
    // Pass the name and email data to the next step using hidden input
    if(isset($_POST['name']) && isset($_POST['email'])){
        echo '<input type="hidden" name="name" value="'.$_POST['name'].'">';
        echo '<input type="hidden" name="email" value="'.$_POST['email'].'">';
    }
    ?>
    <input type="submit" value="Submit">
</form>
