<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

include 'connection.php';

// Fetch user information from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM hide WHERE id = $user_id";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $user_data = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

      <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        
        .profile-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
        }
        
        .profile-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        
        .profile-container p {
            font-size: 16px;
            margin-bottom: 10px;
            color: #555;
        }
        
        a {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        
        a:hover {
            background-color: #45a049;
        }
    </style>  
</head>
<body>
    <div class="profile-container">
    
        <h1>User Profile</h1>
        <p><strong>Profile:</strong> <img src="upload/<?php echo $user_data['image_url']; ?>" style="height: 10px; width: 100px;" alt=""></p>
        <p><strong>FirstName:</strong> <?php echo $user_data['firstname']; ?></p>
        <p><strong>LastName:</strong> <?php echo $user_data['lastname']; ?></p>
        <p><strong>Email:</strong> <?php echo $user_data['email']; ?></p>
        <p><strong>Phone:</strong> <?php echo $user_data['phone']; ?></p>
        <p><strong>Username:</strong> <?php echo $user_data['username']; ?></p>

        <a href="logout.php">Logout</a>
    </div>
 
</body>
</html>
<?php
} else {
    echo "User not found or database error.";
}

$conn->close();
?>
