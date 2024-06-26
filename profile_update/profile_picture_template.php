<?php
/*
Template Name: Edit Profile Picture
*/

if (!is_user_logged_in()) {
    echo '<p>You must be logged in to view this page.</p>';
    return;
}

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

if (isset($_POST['update_profile'])) {
    // Update user first name and last name
    $firstname = sanitize_text_field($_POST['user_firstname']);
    $lastname = sanitize_text_field($_POST['user_lastname']);
    
    // Update user data
    $user_args = array(
        'ID' => $user_id,
        'first_name' => $firstname,
        'last_name' => $lastname
    );
    wp_update_user($user_args);

    // Handle profile picture upload
    if (isset($_FILES['profile_picture'])) {
        $file = $_FILES['profile_picture'];
        if ($file['error'] == 0) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('profile_picture', $user_id);
            if (is_wp_error($attachment_id)) {
                echo "Error uploading profile picture: " . $attachment_id->get_error_message();
            } else {
                update_user_meta($user_id, 'profile_picture', $attachment_id);
            }
        }
    }
}

// Retrieve user data and profile picture URL
$user_firstname = get_user_meta($user_id, 'first_name', true);
$user_lastname = get_user_meta($user_id, 'last_name', true);
$profile_picture_attachment_id = get_user_meta($user_id, 'profile_picture', true);
$profile_picture_url = wp_get_attachment_url($profile_picture_attachment_id);

get_header();
?>

<div class="container">
    <div style="text-align:center">
        <form method="post" class="form-control shadow" enctype="multipart/form-data">
            <div class="d-flex justify-content-center align-items-center">
                <img src="<?php echo esc_url($profile_picture_url); ?>" class="img-fluid rounded-circle" alt="Profile Picture" width="200" height="200">
            </div>
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture">
            <label for="user_id">User Id:</label>
            <input type="text" name="user_id" value="<?php echo esc_attr($user_id); ?>" disabled><br><br>
            <label>First Name</label>
            <input type="text" name="user_firstname" id="first_name" value="<?php echo esc_attr($user_firstname); ?>">
            <label>Last Name</label>
            <input type="text" name="user_lastname" id="last_name" value="<?php echo esc_attr($user_lastname); ?>"><br>
            <div style="text-align: center;">
                <button type="submit" class="btn btn-primary mb-4" name="update_profile">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<?php
get_footer();
?>
