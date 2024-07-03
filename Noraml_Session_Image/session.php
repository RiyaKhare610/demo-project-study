<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    // Validate input
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        echo "Username or password is empty.";
    } else {
        // Check database
        $sql = "SELECT id, username FROM register WHERE username = '$username' AND password = '$password'";
        $result = $conn->query($sql);
        
       
        

        if ($result->num_rows == 1) {
            // Correct credentials, start session
            $row = $result->fetch_assoc();
            
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            header("Location: dashboard.php"); // Redirect to dashboard
            exit();
        } else {
            echo "Invalid email or password.";
        }
    }

   
}
?>