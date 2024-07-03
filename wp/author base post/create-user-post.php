<?php
/*
Plugin Name: User Post
Description: This is the custom plugin for user post
Author: Ankit
Version: 1.0
*/

function enque_custom_script_and_style()
{
    wp_enqueue_script('custom-script', plugins_url('create-user-post.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script(
        'custom-script',
        'custom_script_params',
        array(
            'ajax_url' => admin_url('admin-ajax.php'),
            // 'nonce'    => wp_create_nonce('delete_post_nonce'),
        )
    );
}
add_action('wp_enqueue_scripts', 'enque_custom_script_and_style');


add_shortcode('my_shortcode', 'add_user_post_blog');
function add_user_post_blog()
{
    ?>
    <!doctype html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>User Blog Form</title>
    </head>

    <body>
        <div class="d-flex justify-content-center align-items-center">
            <div class="w-100" style="max-width: 500px;">
                <form class="form-control shadow my-5" id="user_post_form">
                    <div class="container">
                        <div class="mb-3">
                            <input type="hidden" name="post-id" id="post_id" value="">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="titleid" name="post_title">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <input type="text" class="form-control" id="contentid" name="post_content">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="post_category" id="categoryid" class="form-control">
                                <option value="">All Category</option>
                                <?php
                                $catogories = get_terms(
                                    array(
                                        'taxonomy' => 'category',
                                        'hide_empty' => false,

                                    )
                                );
                                if ($catogories) {
                                    foreach ($catogories as $cat) {
                                        echo '<option value="' . $cat->slug . '">' . $cat->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fimage" class="form-label">Featured Image</label>
                            <input type="file" class="form-control" id="fimageid" name="post_fimage">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="usernameid" name="post_username">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailid" name="post_email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="passwordid" name="post_password">
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label">Number</label>
                            <input type="text" class="form-control" id="numberid" name="post_number">
                        </div>
                        <div class="mb-3 d-flex justify-content-center">
                            <button type="button" class="btn btn-success rounded" id="submit"
                                value="publish">Submit</button><br>
                            <button type="button" class="btn btn-primary rounded" id="update"
                                style="display: none;">Update</button>

                        </div>


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


add_action('wp_ajax_nopriv_add_user_post', 'add_user_post');
add_action('wp_ajax_add_user_post', 'add_user_post');
function add_user_post()
{


    $current_user = wp_get_current_user();
    $author_id = $current_user->ID;



    $title = $_REQUEST['title'];
    $content = $_REQUEST['content'];
    $category = $_REQUEST['category'];
    $username = $_REQUEST['username'];
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password'];
    $number = $_REQUEST['number'];


    $new_post = array(
        'post_type' => 'post',
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_author' => $author_id
    );

    $result = $pid = wp_insert_post($new_post);

    if ($pid) {
        update_post_meta($pid, 'user_name', $username);
        update_post_meta($pid, 'email', $email);
        update_post_meta($pid, 'password', $password);
        update_post_meta($pid, 'number', $number);
        wp_set_object_terms($pid, $category, 'category');

        if (!empty($_FILES['image']['name'])) {
            require_once (ABSPATH . 'wp-admin/includes/image.php');
            require_once (ABSPATH . 'wp-admin/includes/file.php');
            require_once (ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('image', $pid);
            set_post_thumbnail($pid, $attachment_id);
        }
        update_post_meta($pid, '_thumbnail_id', $attachment_id);


    }
    if ($result) {
        wp_send_json_success('post created successfully.');
    } else {
        wp_send_json_error('post not created');
    }




}

add_shortcode('custom_shortcode', 'custom_shortcode');
function custom_shortcode()
{
    ob_start();
    ?>
    <!doctype html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <title>User Post Record</title>
        <style>
            .table-bordered {
                max-width: 100%;
                border-collapse: collapse;
            }

            .table-bordered th,
            .table-bordered td {
                border: 1px solid black;
                padding: 8px;
                text-align: left;
            }

            .table-bordered th {
                background-color: #f2f2f2;
            }

            .edit {
                margin-right: 10px;
                /* Adjust this value as needed */
            }
        </style> <!-- Corrected closing tag here -->
    </head>

    <body>
        <div class="container-fluid">
            <div>
                <h2 align="center">User Posts</h2>
            </div>
            <?php
            $paged = get_query_var('paged') ? get_query_var('paged') : 1;
            $current_user = wp_get_current_user();
            $user_id = $current_user->ID;

            $first_name = get_user_meta($user_id, 'first_name', true);
            $last_name = get_user_meta($user_id, 'last_name', true);

            $full_name = $first_name . ' ' . $last_name;

            $args = array(
                'post_type' => 'post',
                'author' => $user_id,
                'paged' => $paged,
                'post_status' => 'publish',
                'posts_per_page' => -1 // Get all posts
            );

            $custom_query = new WP_Query($args);

            if ($custom_query->have_posts()) {
                ?>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Post ID</th>
                            <th>Featured Image</th>
                            <th>Title</th>
                            <th>Content</th>
                            <th>Category</th>
                            <th>Author</th>
                            <th>User Name</th>
                            <th>Email</th>
                            <th>Password</th>
                            <th>Number</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($custom_query->have_posts()) {
                            $custom_query->the_post();
                            $post_id = get_the_ID();
                            $post_title = get_the_title();
                            $post_content = get_the_content();
                            $categories = get_the_terms($post_id, 'category');
                            $category_names = array();
                            if ($categories) {
                                foreach ($categories as $category) {
                                    $category_names[] = $category->name;
                                }
                            }
                            $name = get_post_meta($post_id, 'user_name', true);
                            $email = get_post_meta($post_id, 'email', true);
                            $password = get_post_meta($post_id, 'password', true);
                            $number = get_post_meta($post_id, 'number', true);
                            $profile = get_post_meta($post_id, '_thumbnail_id', true);
                            $image_url = wp_get_attachment_image_src($profile, 'full');
                            ?>
                            <tr>
                                <td><?php echo esc_html($post_id); ?></td>
                                <td><?php if ($image_url) {
                                    echo '<img src="' . esc_url($image_url[0]) . '" alt="Profile Image" width="150" height="50">';
                                } ?>
                                </td>
                                <td><?php echo esc_html($post_title); ?></td>
                                <td><?php echo esc_html($post_content); ?></td>
                                <td><?php echo esc_html(implode(', ', $category_names)); ?></td>
                                <td><?php echo esc_html($full_name); ?></td>
                                <td><?php echo esc_html($name); ?></td>
                                <td><?php echo esc_html($email); ?></td>
                                <td><?php echo esc_html($password); ?></td>
                                <td><?php echo esc_html($number); ?></td>
                                <td><?php echo esc_html(get_the_date()); ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary edit ms-2" data-bs-toggle="modal"
                                        data-bs-target="#staticBackdrop" data-post-id="<?php echo esc_attr($post_id) ?>">
                                        EDIT
                                    </button>
                                    <button class="btn btn-danger del ms-2"
                                        data-post-id="<?php echo esc_attr($post_id) ?>">DELETE</button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <?php
                wp_reset_postdata();
            } else {
                echo '<p>No posts found</p>';
            }
            ?>


        </div>

        <div>
            <!-- Button trigger modal -->


            <!-- Modal -->
            <div class="modal fade rounded" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Update Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                         <div class="d-flex justify-content-center align-items-center">
            <div class="w-100" style="max-width: 500px;">
                <form class="form-control shadow " id="user_post_form">
                    <div class="container">
                        <div class="mb-3">
                            <input type="hidden" name="post-id" id="mpost_id" value="">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="mtitleid" value="" name="post_title">
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <input type="text" class="form-control" id="mcontentid" value="" name="post_content">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select name="post_category" id="mcategoryid" value=""  class="form-control">
                                <option id="categoryoption" value="">All Category</option>
                                <?php
                                $catogories = get_terms(
                                    array(
                                        'taxonomy' => 'category',
                                        'hide_empty' => false,

                                    )
                                );
                                if ($catogories) {
                                    foreach ($catogories as $cat) {
                                        echo '<option value="' . $cat->slug . '">' . $cat->name . '</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fimage" class="form-label">Featured Image</label>
                            <input type="file" class="form-control" id="mfimageid" value="" name="post_fimage">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">User Name</label>
                            <input type="text" class="form-control" id="musernameid" value="" name="post_username">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="memailid" value="" name="post_email">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="mpasswordid" value="" name="post_password">
                        </div>
                        <div class="mb-3">
                            <label for="number" class="form-label">Number</label>
                            <input type="text" class="form-control" id="mnumberid" value="" name="post_number">
                        </div>
                        <div class="mb-3 d-flex justify-content-center">
                            <button type="button" class="btn btn-success rounded" id="submit"
                                value="publish">Submit</button><br>
                            <button type="button" class="btn btn-primary rounded" id="update"
                                style="display: none;">Update</button>

                        </div>


                    </div>
                </form>
            </div>

        </div>.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Understood</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>









        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    </body>

    </html>

    <?php
    return ob_get_clean();
}

add_action('wp_ajax_nopriv_delete_post', 'delete_post');
add_action('wp_ajax_delete_post', 'delete_post');
function delete_post()
{
    // check_ajax_referer('delete_post_nonce', 'nonce');

    if (isset($_POST['post_id'])) {
        $post_id = intval($_POST['post_id']);

        if (current_user_can('delete_post', $post_id)) {
            if (wp_delete_post($post_id, true)) {
                wp_send_json_success('Post deleted successfully.');
            } else {
                wp_send_json_error('Failed to delete the post.');
            }
        } else {
            wp_send_json_error('You do not have permission to delete this post.');
        }
    } else {
        wp_send_json_error('Invalid post ID.');
    }
}

add_action('wp_ajax_nopriv_edit_user_post', 'edit_user_post');
add_action('wp_ajax_edit_user_post', 'edit_user_post');
function edit_user_post()
{


}



