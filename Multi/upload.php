<?php
if(isset($_FILES['my_image'])) {
    include "connection.php";

    $img_name = $_FILES['my_image']['name'];
    $img_size = $_FILES['my_image']['size'];
    $tmp_name = $_FILES['my_image']['tmp_name'];
    $error = $_FILES['my_image']['error'];

    if($error === 0) {
        if($img_size > 125000) {
            $em = "Sorry, your file is too large.";
            header("Location: index.php?error=$em"); 
            exit();
        } else {
            $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
            $img_ex_lc = strtolower($img_ex);

            $allowed_exs = array("jpg", "jpeg", "png");
            if(in_array($img_ex_lc, $allowed_exs)) {
                $new_img_name = uniqid("IMG-", true) . '.' . $img_ex_lc;
                $img_upload_path = 'upload/' . $new_img_name;
                move_uploaded_file($tmp_name, $img_upload_path);

                // Insert into Database
                // Change this query according to your database schema
                $sql = "INSERT INTO your_table_name (image_url) VALUES ('$new_img_name')";
                mysqli_query($conn, $sql);
                header("Location: view.php");
                exit();
            } else {
                $em = "You can't upload files of this type";
                header("Location: index.php?error=$em");
                exit();
            }
        }
    } else {
        $em = "Unknown error occurred!";
        header("Location: index.php?error=$em");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
