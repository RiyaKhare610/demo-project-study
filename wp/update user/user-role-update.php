<?php
/*
Plugin Name: Custom User Update
Description: This Is The Custom User Update Plugin
Author: Aps 
Version: 1.0
*/

// form shortcode
add_shortcode('custom_user_update', 'custom_user_update');
function custom_user_update()
{
    if (!is_user_logged_in()) {
        echo '<p>You must be logged in to view this page.</p>';
        return;
    }

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $first_name = $current_user->first_name;
    $last_name = $current_user->last_name;
    $email = $current_user->user_email;

    if (isset($_POST['update_profile'])) {
        // Handle form submission and update user data
        $user_firstname = sanitize_text_field($_POST['user_firstname']);
        $user_lastname = sanitize_text_field($_POST['user_lastname']);

        // Update user data
        $user_args = array(
            'ID' => $user_id,
            'first_name' => $user_firstname,
            'last_name' => $user_lastname,
        );

        wp_update_user($user_args);

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

        $site_url = site_url('/custom-admin-dashboard/');




        ?>

        <script type="text/javascript">
            window.location.replace("<?php echo $site_url; ?>");
        </script>
        <?php



    }

    ?>

    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>Hello, world!</title>
        <style>
            .btn-primary {
                background-color: #007bff;
                border: 1px solid #007bff;
                color: white;
                padding: 10px 20px;
                font-size: 16px;
                border-radius: 4px;
                cursor: pointer;
                text-align: center;
                text-decoration: none;
            }

            .btn-primary:hover {
                background-color: #0056b3;
                border-color: #0056b3;
            }
        </style>
    </head>

    <body>
        <div class="d-flex justify-content-center">
            <div>
                <form method="post" class="form-control shadow" action="" enctype="multipart/form-data"><br><br>
                    <input type="file" name="profile_picture" /><br><br>
                    <br>
                    <label for="user_firstname">First Name:</label>
                    <input type="text" name="user_firstname" value="<?php echo esc_attr($first_name); ?>" required><br><br>

                    <label for="user_lastname">Last Name:</label>
                    <input type="text" name="user_lastname" value="<?php echo esc_attr($last_name); ?>" required><br><br>

                    <div align="center">
                        <button class="btn btn-primary mb-4" name="update_profile">Update Profile</button>
                    </div>
                </form>
            </div>
        </div>








        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>


    </body>

    </html>
    <?php
}
?>