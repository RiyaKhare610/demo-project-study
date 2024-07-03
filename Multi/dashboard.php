<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
   
}

// Welcome message
echo "Welcome, " . $_SESSION['username'] . "!";

 echo '<br><a href="profile.php">Profile</a>';
// echo '<br><a href="logout.php">Profile</a>';
?>