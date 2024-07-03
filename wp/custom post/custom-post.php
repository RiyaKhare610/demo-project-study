<?php
/*
Plugin Name: Custom Post
*/

add_shortcode('custom_post_form', 'custom_post_form');
function custom_post_form()
{
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <div class="d-flex justify-content-center align-items-center">
        <div class="w-50" style="max-width: 900px;">
            <div class="container shadow">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="post_title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="titleid" name="p_title">
                    </div>
                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea name="ps_description" id="s_descriptionid" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="long_description" class="form-label">Long Description</label>
                        <textarea name="pl_description" id="l_descriptionid" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="post_img" class="form-label">Images</label>
                        <input type="file" name="p_img[]" id="p_imgid" class="form-control" multiple="multiple">
                    </div>
                    <div class="mb-3">
                        <label for="post_category" class="form-label">Category</label>
                        <select name="p_category" id="p_categoryid" class="form-control">
                            <option value="Pollytics">Pollytics</option>
                            <option value="Entertainment">Entertainment</option>
                            <option value="Sports">Sports</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Author Name</label>
                        <input type="text" class="form-control" name="p_username" id="usernameid">
                    </div>
                    <div class="mb-3">
                        <label for="useremail" class="form-label">Email</label>
                        <input type="email" class="form-control" name="p_email" id="emailid">
                    </div>
                    <div class="mb-3">
                        <label for="userpass" class="form-label">Password</label>
                        <input type="password" class="form-control" name="p_pass" id="passid">
                    </div>
                    <div class="mb-3 d-flex justify-content-center align-items-center">
                        <button class="btn btn-primary my-4" id="submitid" name="p_submit" value="publish">Submit</button>
                        <button class="btn btn-info my-4" id="updateid" name="p_update" value="publish"
                            style="display: none;">Update</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <?php
}

add_action('init', 'create_post');
function create_post()
{
    if (isset($_POST['p_submit'])) {
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;
        } else {
            // Handle case where user is not logged in
            echo 'User is not logged in.';
            exit;
        }

        $title = sanitize_text_field($_POST['p_title']);
        $s_description = sanitize_textarea_field($_POST['ps_description']);
        $l_description = sanitize_textarea_field($_POST['pl_description']);



        // Handle multiple file uploads
        if (!empty($_FILES['p_img']['name'])) {

            require_once (ABSPATH . 'wp-admin/includes/file.php');
            require_once (ABSPATH . 'wp-admin/includes/media.php');
            require_once (ABSPATH . 'wp-admin/includes/image.php');


            $images = array();

            foreach ($_FILES['p_img']['name'] as $key => $value) {
                $file = array(
                    'name' => $_FILES['p_img']['name'][$key],
                    'type' => $_FILES['p_img']['type'][$key],
                    'tmp_name' => $_FILES['p_img']['tmp_name'][$key],
                    'error' => $_FILES['p_img']['error'][$key],
                    'size' => $_FILES['p_img']['size'][$key]
                );

                $upload_overrides = array('test_form' => false);
                $movefile = wp_handle_upload($file, $upload_overrides);

                if ($movefile && !isset($movefile['error'])) {
                    // File is uploaded successfully
                    $file_path = $movefile['file'];
                    $file_name = basename($file_path);
                    $file_type = wp_check_filetype($file_name);

                    $attachment = array(
                        'guid' => $movefile['url'],
                        'post_mime_type' => $file_type['type'],
                        'post_title' => preg_replace('/\.[^.]+$/', '', $file_name),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $attachment_id = wp_insert_attachment($attachment, $file_path);
                    require_once (ABSPATH . 'wp-admin/includes/image.php');
                    $attachment_data = wp_generate_attachment_metadata($attachment_id, $file_path);
                    wp_update_attachment_metadata($attachment_id, $attachment_data);

                    // Get the attachment URL
                    $attachment_url = wp_get_attachment_url($attachment_id);
                    $images[] = $attachment_id;
                } else {
                    echo $movefile['error'];
                }
            }


            $attachment_ids_string = implode(',', $images);

        } else {
            echo "No file uploaded or upload error.";
        }

        // Convert images array to JSON for storage
        $images_json = wp_json_encode($images);

        $category = sanitize_text_field($_POST['p_category']);
        $name = sanitize_text_field($_POST['p_username']);
        $email = sanitize_email($_POST['p_email']);
        $password = sanitize_text_field($_POST['p_pass']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_post';
        $result = $wpdb->insert(
            $table_name,
            array(
                'author_id' => $user_id,
                'title' => $title,
                'short_descriptiion' => $s_description,
                'long_description' => $l_description,
                'img' => $attachment_ids_string,
                'category' => $category,
                'author_name' => $name,
                'email' => $email,
                'password' => $password
            ),
            array(
                '%d', // for author_id (assuming it's an integer)
                '%s', // for title
                '%s', // for short_description
                '%s', // for long_description
                '%s', // for img
                '%s', // for category
                '%s', // for author_name
                '%s', // for email
                '%s'  // for password
            )
        );

        if ($result !== false) {
            echo 'Post inserted successfully.';
            wp_redirect('http://localhost/Test/custom-post-record/');
            exit;
        } else {
            echo 'Failed to insert post.';
            echo $wpdb->last_error; // Print the last error for debugging
        }
    }
}

add_shortcode('custom_post_shortcode', 'custom_post_shortcode');
function custom_post_shortcode()
{
    global $wpdb;
    $users = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'custom_post');

    if (!empty($users)) {
        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <div class="d-flex justify-content-center align-items-center">
            <div class="container shadow">
                <h1 align="center">Custom Posts</h1>
                <div class="row">
                    <?php foreach ($users as $user) {
                        $img = explode(',', $user->img);
                        if ($img) {
                            $front_id = $img[0];
                            $attachment_url = wp_get_attachment_url($front_id);

                            $post_id = $user->post_id;
                            $title = $user->title;
                            $s_des = $user->short_descriptiion;
                            $a_name = $user->author_name;
                            ?>
                            <div class="col-12 my-3">
                                <div class="row">
                                    <div class="col-2"></div>
                                    <div class="col-4 float-end">
                                        <div class="my-3">
                                            <img src="<?php echo esc_url($attachment_url); ?>" alt="fimg" style="max-width: 300px;">
                                        </div>
                                    </div>
                                    <div class="col-4 my-3">
                                        <div>
                                            <h5><?php echo "Title: $title"; ?></h5><br>
                                        </div>
                                        <div>
                                            <h5><?php echo "Short Description: $s_des"; ?></h5><br>
                                        </div>
                                        <div>
                                            <h5><?php echo "By: $a_name"; ?></h5><br>
                                        </div>
                                        <div>
                                            <a href="<?php echo esc_url(home_url('/Test/custom-post-details/?post_id=' . $post_id)); ?>"
                                                class="btn btn-danger btnloadmore" id="loadmore">Load More</a>
                                        </div>

                                    </div>
                                    <div class="col-2">

                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <?php
                        }
                    } ?>
                </div>
            </div>
        </div>
        <?php

    }
}
add_shortcode('custom_shortcode', 'load_more');
function load_more()
{
    if (isset($_GET['post_id'])) {

        $post_id = intval($_GET['post_id']);

        global $wpdb;
        $post = $wpdb->get_results($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "custom_post WHERE post_id = %d", $post_id));
        $image = explode(',', $post[0]->img);
        $img1 = $image[0];
        $attachment_url1 = wp_get_attachment_url($img1);
        $img2 = $image[1];
        $attachment_url2 = wp_get_attachment_url($img2);
        $img3 = $image[2];
        $attachment_url3 = wp_get_attachment_url($img3);
        $title = $post[0]->title;
        $short_des = $post[0]->short_descriptiion;
        $category = $post[0]->category;
        $long_des = $post[0]->long_description;
        $author_name = $post[0]->author_name;
        $author_email = $post[0]->email;

        ?>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <div class="d-flex justify-content-center align-items-center">
            <div class="container shadow">
                <div>
                    <h2 align="center">Custom Post</h2>
                </div>
                <div class="row">
                    <div class="col-2"></div>
                    <div class="col-4 my-3">
                       <div class="my-3">
                       <img src="<?php echo esc_url($attachment_url1); ?>" alt="img1" style="max-width: 400px;">
                       </div>
                    </div>
                    <div class="col-4 my-5">
                        <div class="my-2 mx-5">
                            <h5><?php echo "title: $title"; ?></h5><br>
                        </div>
                        <div  class="mx-5">
                            <h5><?php echo "Description: $short_des"; ?></h5><br>
                        </div>
                        <div class="my-2 mx-5">
                            <h5><?php echo "Category: $category"; ?></h5>
                        </div>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-2 my-3"></div>
                    <div class="col-8 my-3">
                       <h6><p><?php echo $long_des; ?></p></h6>
                    </div>
                    <div class="col-2"></div>
                    <div class="col-2 my-3"></div>
                    <div class="col-4 my-3">
                        <div>
                            <img src="<?php echo esc_url($attachment_url2); ?>" alt="img2" style="max-width: 400px;">
                        </div>
                    </div>
                    <div class="col-4 my-3 mx-5">
                        <div class="mx-2">
                            <img src="<?php echo esc_url($attachment_url3); ?>" alt="img3" style="max-width: 400px; height: 300px;">
                        </div>
                    </div>
                    <div class="col2"></div>
                    <div class="col-2"></div>
                    <div class="col-8 my-3">
                      <div>
                        <h5 align="center"><?php echo "Author: $author_name"; ?></h5><br>
                      </div>
                      <div>
                         <h5 align="center"><?php echo "Email: $author_email"; ?></h5>
                      </div>
                      <div align="center" class="my-5">
                        <a href="<?php echo esc_url(home_url('/Test/custom-post-record/')); ?>" class="btn btn-success">Go Back</a>
                      </div>
                    </div>
                    
                </div>
            </div>
        </div>



        <?php








    }
}


