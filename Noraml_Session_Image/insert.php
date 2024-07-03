


<?php
include 'connection.php';

// Check if all necessary fields are set
if(isset($_POST['name'], $_POST['email'], $_POST['phone'], $_POST['username'], $_POST['password'], $_FILES['my_image'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // File upload handling
    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];

    // Check for upload errors
    if($error === 0) {
        $img_upload_path = 'upload/' . $img_name;

        // Move uploaded file to destination directory
        if(move_uploaded_file($tmp_name, $img_upload_path)) {
            // Insert data into database
            $sql = "INSERT INTO register (name, email, phone, username, password, my_image)
                    VALUES ('$name', '$email', '$phone', '$username', '$password', '$img_name')";

            if ($conn->query($sql) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "File upload failed";
        }
    } else {
        echo "Error uploading file";
    }
} else {
    echo "Incomplete form submission";
}

// Close database connection
$conn->close();
// header('Location: login.php');
?>
