<?php
include 'db.php';

if (isset($_FILES['profile_picture'])) {
    $img_name = $_FILES['profile_picture']['name'];
    $img_size = $_FILES['profile_picture']['size'];
    $tmp_name = $_FILES['profile_picture']['tmp_name'];
    $error = $_FILES['profile_picture']['error'];


    if ($error === 0) {
        $img_upload_path = 'upload/' . basename($img_name);

        if (move_uploaded_file($tmp_name, $img_upload_path)) {
            $sql = "INSERT INTO upload (profile_picture) VALUES ('$img_name')";
            
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
    echo "No file uploaded";
}


$conn->close();
?>
