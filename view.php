<?php
include 'db.php';


function strToBin($str) {
    return implode(' ', array_map(fn($char) => sprintf("%08b", ord($char)), str_split($str)));
}

$sql = "SELECT profile_picture FROM upload";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h3>Image Names in Binary</h3><ul>";
    while($row = $result->fetch_assoc()) {
        $name = $row['profile_picture'];
        echo "<li>$name<br><b>Binary:</b> " . strToBin($name) . "</li>";
    }
    echo "</ul>";
} else {
    echo "No images found.";
}

$conn->close();
?>

