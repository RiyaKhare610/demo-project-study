<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-step Form - Step 3</title>
    <style>
        /* CSS styles for the form */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h2 {
            color: #333;
        }
        .form-container {
            background-color: #fff;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        form {
            background-color: #fff;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"],
        input[type="checkbox"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="checkbox"] {
            width: auto;
            margin-top: 10px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h2>Step 3: Additional Information</h2>
    <form method="post" action="final-step.php">
        <?php
        // Check if form fields are set in $_POST before accessing their values
        $username = isset($_POST['username']) ? $_POST['username'] : '';
        $email = isset($_POST['email']) ? $_POST['email'] : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        ?>
        <!-- Hidden input fields to pass the previously entered data -->
        <input type="hidden" name="username" value="<?php echo htmlspecialchars($username); ?>">
        <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <input type="hidden" name="password" value="<?php echo htmlspecialchars($password); ?>">
        <input type="hidden" name="dob" value="<?php echo htmlspecialchars($dob); ?>">
        <input type="hidden" name="gender" value="<?php echo htmlspecialchars($gender); ?>">
        
        Address: <input type="text" name="address"><br>
        Phone: <input type="text" name="phone"><br>
        Hobbies: <input type="text" name="hobbies"><br>
        Occupation: <input type="text" name="occupation"><br>
        <input type="checkbox" name="terms" value="accepted"> I accept the terms and conditions<br>
        <input type="submit" value="Submit">
    </form>
    </div>
</body>
</html>
