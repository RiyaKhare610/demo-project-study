<?php
include "connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View</title>
</head>
<body>
    <a href="index.php">&#8592;</a>
    <?php
$sql = "SELECT  * FROM hide ORDER BY id DESC";
$res = mysqli_query($conn,$sql);

if(mysqli_num_rows($res) > 0){
    while ($images = mysqli_fetch_assoc($res)){ ?>
<div class="alb">
    <img src="upload/<?=$images['image_url'] ?>" >
</div>


  <?php  }
}?>
    
</body>
</html>