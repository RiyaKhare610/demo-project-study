<?php
/*
Plugin Name: Profile Updated
Description: Create a custom user profile update form
Version: 3.0
Author: Mr. Profile
*/

add_shortcode('my_profile_update', 'my_profile_update');

function my_profile_update()
{
    if (!is_user_logged_in()) {
        echo '<p>You must be logged in to view this page.</p>';
        return;
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $firstname = $current_user->first_name;
    $lastname = $current_user->last_name;

    if (isset($_POST['submit'])) {
        // Sanitize user input
        $firstname = sanitize_text_field($_POST['user_firstname']);
        $lastname = sanitize_text_field($_POST['user_lastname']);

        // Update user data
        $user_args = array(
            'ID' => $user_id,
            'first_name' => $firstname,
            'last_name' => $lastname,
        );
        $updated_user = wp_update_user($user_args);

        if (is_wp_error($updated_user)) {
            echo '<div class="alert alert-danger">Failed to update profile.</div>';
        } else {
            echo '<div class="alert alert-success">Profile updated successfully.</div>';

            // Handle profile picture upload
            if ($_FILES['profile_picture']['error'] === 0) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = media_handle_upload('profile_picture', $user_id);

                if (is_wp_error($attachment_id)) {
                    echo '<div class="alert alert-danger">Failed to upload profile picture.</div>';
                } else {
                    update_user_meta($user_id, 'profile_picture', $attachment_id);
                }
            }
        }
    }

    // Display the profile update form
?>
    <div class="d-flex justify-content-center">
        <div>
            <form method="post" class="form-control shadow" enctype="multipart/form-data">
                <label>Profile Picture</label>
                <input type="file" name="profile_picture" id="profile_picture">
                <label>First Name</label>
                <input type="text" name="user_firstname" id="first_name" value="<?php echo esc_attr($firstname); ?>">
                <label>Last Name</label>
                <input type="text" name="user_lastname" id="last_name" value="<?php echo esc_attr($lastname); ?>"><br>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-danger mb-4" name="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
<?php
}
