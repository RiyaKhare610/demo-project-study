<?php
/*
Template Name: Custom Admin Dashboard
*/

if (!is_user_logged_in()) {
    echo '<p>You must be logged in to view this page.</p>';
    return;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;
$email = $current_user->user_email;
    
    



if (isset($_POST['update_profile'])) {

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;


    // Handle form submission and update user data
    $user_firstname = sanitize_text_field($_POST['user_firstname']);
    $user_lastname = sanitize_text_field($_POST['user_lastname']);
    $email = sanitize_email($_POST['user_email']);

    // Update user data
    $user_args = array(
        'ID' => $user_id,
        'user_email' => $email
    );

    
    $user_id = wp_update_user($user_args);
    if($user_id){
        update_user_meta($user_id,'first_name',$user_firstname);
        update_user_meta($user_id,'last_name',$user_lastname);
       
     
        
    }

    if (isset($_FILES['profile_picture'])) {
        $file = $_FILES['profile_picture'];
        if ($file['error'] == 0) {
            require_once (ABSPATH . 'wp-admin/includes/image.php');
            require_once (ABSPATH . 'wp-admin/includes/file.php');
            require_once (ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('profile_picture', $user_id);
            update_user_meta($user_id, 'profile_picture', $attachment_id);
        }
    }

  
}

get_header();
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Custom Admin Dashboard</title>
</head>

<body>
    <?php 
    $first_name = get_user_meta($user_id, 'first_name', true);
    $last_name = get_user_meta($user_id, 'last_name', true);
   
    $profile_picture_attachment_id = get_user_meta($user_id, 'profile_picture', true);
    $profile_picture_url = wp_get_attachment_url($profile_picture_attachment_id);
    ?>
    <div class="container">
        <div class="text-center">
            <h1>Dashboard</h1>
        </div>

        <div style="text-align: center;">
    <form method="post" class="form-control shadow" action="" enctype="multipart/form-data">
        

        <div class="d-flex justify-content-center align-items-center">
            <img src="<?php echo $profile_picture_url; ?>" class="img-fluid rounded-circle" alt="Profile Picture" width="200" height="200">
        </div>
        <br><br>

        <input type="file" name="profile_picture" /><br><br>
        <br>
         
        <label for="user_id">User Id:</label>
        <input type="text" name="user_id" value="<?php echo esc_attr($user_id); ?>" disabled><br><br>

        <label for="user_firstname">First Name:</label>
        <input type="text" name="user_firstname" value="<?php echo esc_attr($first_name); ?>" required><br><br>

        <label for="user_lastname">Last Name:</label>
        <input type="text" name="user_lastname" value="<?php echo esc_attr($last_name); ?>" required><br><br>

        <label for="user_email">Email:</label>
        <input type="email" name="user_email" value="<?php echo esc_attr($email); ?>" required><br><br>

        <div align="center">
            <button class="btn btn-primary mb-4" name="update_profile">Update Profile</button>
        </div>
    </form>
</div>

        
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>

</body>

</html>



<?php


get_footer();

?>