<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "multistepform";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$hobbies = $_POST['hobbies'];
$occupation = $_POST['occupation'];
$terms = isset($_POST['terms']) ? $_POST['terms'] : '';

$sql = "INSERT INTO multiregistration (username, email, password, dob, gender, address, phone, hobbies, occupation, terms)
        VALUES ('$username', '$email', '$password', '$dob', '$gender', '$address', '$phone', '$hobbies', '$occupation', '$terms')";

if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>
