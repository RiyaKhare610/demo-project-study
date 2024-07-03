<?php
/*
Plugin Name: User Blog 
Description: Custom User Blog Plugin
Author: Ak
Version: 1.0
*/



add_shortcode('add_post', 'add_post');
function add_post()
{

    if (isset($_POST['submit'])) {
        $username = sanitize_user($_POST['username']);
        $useremail = sanitize_email($_POST['useremail']);
        $userpassword = sanitize_text_field($_POST['userpassword']);
        $usernumber = sanitize_text_field($_POST['usernumber']);
        $userbio = sanitize_textarea_field($_POST['description']);
        $userdob = sanitize_text_field($_POST['userdob']);
        $useradd = sanitize_textarea_field($_POST['useradd']);
        $usercity = sanitize_text_field($_POST['usercity']);
        $userrole = sanitize_text_field($_POST['userrole']);


        $userdata = array(
            'user_login' => $username,
            'user_email' => $useremail,
            'user_pass' => $userpassword,
            'role' => $userrole,
        );

        $user_id = wp_insert_user($userdata);

        if (!is_wp_error($user_id)) {
            update_user_meta($user_id, 'usernumber', $usernumber);
            update_user_meta($user_id, 'description', $userbio);
            update_user_meta($user_id, 'userdob', $userdob);
            update_user_meta($user_id, 'useradd', $useradd);
            update_user_meta($user_id, 'usercity', $usercity);
            if (isset($_FILES['user_avatar'])) {
                $file = $_FILES['user_avatar'];
               if ($file) {
                    require_once (ABSPATH . 'wp-admin/includes/image.php');
                    require_once (ABSPATH . 'wp-admin/includes/file.php');
                    require_once (ABSPATH . 'wp-admin/includes/media.php');
    
                    $attachment_id = media_handle_upload('user_avatar', $user_id);
                    update_user_meta($user_id, 'user_avatar', $attachment_id);
                } 
            }

            $message = "User created successfully.";
            echo "<script type='text/javascript'>alert('$message');</script>";
        } else {
            $message = "Error: " . $user_id->get_error_message();
            echo "<script type='text/javascript'>alert('$message');</script>";
        }
    }
    ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>User Blog</title>
    </head>

    <body>

        <form action="" id="my-form" method="post" class="form-control shadow" enctype="multipart/form-data">
            <?php
            if (is_user_logged_in()) {

                $user = get_userdata(1);
                if ($user) {
                    echo '<pre>';
                    echo 'Username: ' . $user->user_login . '<br>';
                    echo 'Email: ' . $user->user_email . '<br>';
                    echo 'Name: ' . $user->display_name . '<br>';
                    echo '</pre>';
                }
            }
            ?>
            <div class="container my-3">
                <div class="mb-3">
                    <label for="pimg" class="form-label">Profile Image</label>
                    <input class="form-control" type="file" id="pimgid" name="user_avatar">
                </div>
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="usernameid" name="username">
                </div>
                <div class="mb-3">
                    <label for="useremail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="useremailid" name="useremail">
                </div>
                <div class="mb-3">
                    <label for="userpassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="userpasswordid" name="userpassword">
                </div>
                <div class="mb-3">
                    <label for="usernumber" class="form-label">Number</label>
                    <input type="text" class="form-control" id="usernumberid" name="usernumber">
                </div>
                <div class="mb-3">
                    <label for="userdob" class="form-label">DOB</label>
                    <input type="date" class="form-control" id="userdobid" name="userdob">
                </div>
                <div class="mb-3">
                    <label for="useradd" class="form-label">Address</label>
                    <textarea class="form-control" name="useradd" id="useraddid"></textarea>
                </div>
                <div class="mb-3">
                    <label for="usercity" class="form-label">City</label>
                    <input type="text" class="form-control" id="usercityid" name="usercity">
                </div>
                <div class="mb-3">
                    <label for="userrole" class="form-label">User Role</label>
                    <select name="userrole" id="" class="form-control">
                        <?php
                        if (!function_exists('get_editable_roles')) {
                            require_once (ABSPATH . 'wp-admin/includes/user.php');
                        }
                        $roles = get_editable_roles();
                        foreach ($roles as $role_key => $role) { ?>
                            <option value="<?php echo $role_key; ?>">
                                <?php echo 'Role: ' . $role_key . ' - Name: ' . $role['name']; ?></option>
                        <?php }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="userbio" class="form-label">Bio</label>
                    <textarea class="form-control" name="description" id="userbioid"></textarea>
                </div>
                <div>
                    <button class="btn btn-primary" id="submit" type="submit" name="submit">Submit</button>
                </div>
            </div>
        </form>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    </body>

    </html>

    <?php
}
?>