<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333; /* Adding default text color */
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        img {
            max-width: 100px;
            height: auto;
        }

        .center {
            text-align: center; /* Adding text-align to center elements */
        }

        .btn {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 10px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or display an error message
    header("Location: login.php");
    exit(); // Make sure to stop executing further code
}

// Include database connection
include 'connection.php';

// Welcome message
echo "<h2>Welcome, " . $_SESSION['username'] . "!</h2>";

// Logout link
echo '<div class="center"><a href="logout.php" class="btn">Logout</a></div>';

// Fetch all records from the register table
$sql = "SELECT * FROM register";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='center'><br><br><table border='1'>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Username</th>
            <th>Password</th>
            <th>Image</th>
        </tr>";
    
    // Output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>".$row["name"]."</td>
                <td>".$row["email"]."</td>
                <td>".$row["phone"]."</td>
                <td>".$row["username"]."</td>
                <td>".$row["password"]."</td>
                <td><img src='upload/".$row["my_image"]."' width='100' height='100'></td>
            </tr>";
    }
    echo "</table>";
} else {
    echo "<div class='center'><br><br>No records found</div>";
}

// Close database connection
$conn->close();
?>

<!-- Buttons -->
<div class="center">
    <br>
    <button onclick="location.href='edit_profile.php';" class="btn">Edit Profile</button>
    <button onclick="location.href='change_password.php';" class="btn">Change Password</button>
</div>

</body>
</html>
