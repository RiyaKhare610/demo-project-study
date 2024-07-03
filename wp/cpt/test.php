<?php
/*
Plugin Name: Test
*/



add_action('init', 'create_post_type');
function create_post_type()
{
    register_post_type(
        'UserInformation',
        array(
            'labels' => array(
                'name' => __('UserInformation'),
                'singular_name' => __('UserInformation')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}

function add_form_shortcode()
{
    ob_start(); // Start output buffering
    my_form(); // Call your form function
    return ob_get_clean(); // Return the buffered content
}
add_shortcode('my_form', 'add_form_shortcode');

//shortcord for post form

function my_form()
{
    if (isset($_GET['post_id'])) {
        $post_id = intval($_GET['post_id']);
        // $post = get_post($post_id);

        $post_title = get_the_title($post_id);
        $post_content = get_post_field('post_content', $post_id);

        $categories = get_the_terms($post_id, 'categories');
        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                // Display the category name
                $category = esc_html($category->name);

            }
        }

        $name = get_post_meta($post_id, 'name', true);
        $email = get_post_meta($post_id, 'email', true);
        $password = get_post_meta($post_id, 'password', true);
        $number = get_post_meta($post_id, 'number', true);

    }


    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <div class="d-flex justify-content-center align-items-center">
        <div class="w-100" style="max-width: 500px;">
            <div class="container">
                <form action="" class="form-control shadow rounded" method="POST" id="my_form" name="post_form"
                    value="publish" enctype="multipart/form-data">
                    <div class="mb-3">
                        <h1 align="center">Create Post</h1>
                    </div>
                    <div class="mb-3">
                        <label for="post-id" class="form-label">Post Id</label>
                        <input type="hidden" class="form-control" id="pid" value="<?php if (isset($_GET['post_id'])) {
                            echo ($post_id);
                        } ?>" name="form-pid">
                    </div>
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="titletid" value="<?php if (isset($_GET['post_id'])) {
                            echo ($post_title);
                        } ?>" name="form-title">
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="contentid" name="form-content"><?php if (isset($_GET['post_id'])) {
                            echo ($post_content);
                        } ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select name="form-categories" id="categoryid" class="form-control">
                            <option id="catoption" value="">All Category</option>
                            <?php

                            $categories = get_terms(
                                array(
                                    'taxonomy' => 'categories',
                                    'hide_empty' => false,
                                )
                            );
                            if ($categories && !is_wp_error($categories)) {
                                foreach ($categories as $cat) {
                                    $selected = '';
                                    if (isset($_GET['post_id'])) {
                                        $post_categories = wp_get_post_terms($post_id, 'categories', array('fields' => 'ids'));
                                        if (!empty($post_categories) && in_array($cat->term_id, $post_categories)) {
                                            $selected = 'selected="selected"';
                                        }
                                    }
                                    echo '<option value="' . esc_attr($cat->slug) . '" ' . $selected . '>' . esc_html($cat->name) . '</option>';
                                }
                            }
                            ?>
                        </select>

                    </div>
                    <div class="mb-3">
                        <label for="metabox" class="form-label">Meta Box</label>
                        <input type="text" class="form-control" name="post_meta" id="metaid">
                    </div>
                    <div class="mb-3">
                        <label for="fimage" class="form-label">Featured Image</label>
                        <input type="file" class="form-control" name="form-fimage" id="fimageid">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Name</label>
                        <input type="text" name="form-username" id="usernameid" value="<?php if (isset($_GET['post_id'])) {
                            echo ($name);
                        } ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="form-uemail" id="emailid" value="<?php if (isset($_GET['post_id'])) {
                            echo ($email);
                        } ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="pass" class="form-label">Password</label>
                        <input type="password" name="form-upass" id="passid" value="<?php if (isset($_GET['post_id'])) {
                            echo ($password);
                        } ?>" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="number" class="form-label">Number</label>
                        <input type="text" name="form-unumber" id="numberid" value="<?php if (isset($_GET['post_id'])) {
                            echo ($number);
                        } ?>" class="form-control">
                    </div>
                    <div class="mb-3 d-flex justify-content-center align-items-center">
                        <button class="btn btn-primary" id="submit" name="form-submit" value="publish" <?php if (isset($_GET['post_id']))
                            echo 'style="display: none;"' ?>>Submit</button>
                            <button class="btn btn-primary " id="update" name="form-update" value="publish" <?php if (isset($_GET['post_id']))
                            echo 'style="display: block;"' ?>
                                style="display: none;">Update</button>
                        </div>

                    </form>
                </div>

            </div>

        </div>



    <?php
}



add_action('init', 'form_submition');
function form_submition()
{
    if (isset($_POST['form-submit']) && $_POST['form-submit'] == 'publish') {

        $title = $_POST['form-title'];
        $content = $_POST['form-content'];
        $category = $_POST['form-categories'];
        $meta = $_POST['post_meta'];
        $name = $_POST['form-username'];
        $email = $_POST['form-uemail'];
        $password = $_POST['form-upass'];
        $number = $_POST['form-unumber'];

        $new_post = array(
            'post_type' => 'userinformation',
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish'

        );

        $post_id = wp_insert_post($new_post);

        if ($post_id) {
            update_post_meta($post_id, 'name', $name);
            update_post_meta($post_id, 'email', $email);
            update_post_meta($post_id, 'password', $password);
            update_post_meta($post_id, 'number', $number);
            update_post_meta($post_id, '_custom_meta_key', $meta);
            wp_set_object_terms($post_id, $category, 'categories');
            if (!empty($_FILES['form-fimage']['name'])) {
                require_once (ABSPATH . 'wp-admin/includes/image.php');
                require_once (ABSPATH . 'wp-admin/includes/file.php');
                require_once (ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = media_handle_upload('form-fimage', $post_id);
                set_post_thumbnail($post_id, $attachment_id);
            }
            update_post_meta($post_id, '_thumbnail_id', $attachment_id);
        }
        header("Location: http://localhost/Test/custom-post-type-record/");
        exit();
    }
}

add_shortcode('post_record', 'post_record');
function post_record()
{
    ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <div class="container-fluid">
        <div>
            <h1 align="center">Post Record</h1>
        </div>
        <!-- Search filter -->
        <form action="" method="GET">
            <div class="container my-2">
                <div class="row">
                    <div class="col-2 d-flex justify-content-center align-items-center">
                        <label for="search-title">Title</label>
                        <input type="text" id="s-title" name="filter_title" size="20">
                    </div>
                    <div class="col-3 d-flex justify-content-center align-items-center">
                        <label for="startDate">Start Date</label>
                        <input type="date" id="s-statrtdate" name="filter-startdate" size="20">
                    </div>
                    <div class="col-3 d-flex justify-content-center align-items-center">
                        <label for="endDate">End Date</label>
                        <input type="date" id="s-enddate" name="filter-enddate" size="20">
                    </div>
                    <div class="col-2 d-flex justify-content-center align-items-center">
                        <label for="category">Category</label>
                        <select id="category" name="filter-category">
                            <option value="">All Categories</option>
                            <?php
                            $categories = get_terms(
                                array(
                                    'taxonomy' => 'categories',
                                    'hide_empty' => false,
                                )
                            );
                            if ($categories) {
                                foreach ($categories as $cat) {
                                    $selected = (isset($_GET['filter-category']) && $_GET['filter-category'] == $cat->slug) ? 'selected' : '';
                                    echo '<option value="' . $cat->slug . '" ' . $selected . '>' . $cat->name . '</option>';
                                }
                            }
                            ?>
                        </select>

                    </div>
                    <div class="col-2 d-flex justify-content-center align-items-center">
                        <input type="submit" role="btn" value="Search" />
                    </div>

                </div>
            </div>
        </form>
        <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $args = array(
            'post_type' => 'userinformation',
            'posts_per_page' => 3,
            'paged' => $paged
        );
        if (isset($_GET['filter_title']) && !empty($_GET['filter_title'])) {
            $args['s'] = sanitize_text_field($_GET['filter_title']);
        }
        if (isset($_GET['filter-startdate']) && !empty($_GET['filter-startdate']) && isset($_GET['filter-enddate']) && !empty($_GET['filter-enddate'])) {

            $start_date = date('Y-m-d', strtotime(sanitize_text_field($_GET['filter-startdate'])));
            $end_date = date('Y-m-d', strtotime(sanitize_text_field($_GET['filter-enddate'])));

            $args['date_query'] = array(
                array(
                    'after' => $start_date,
                    'before' => $end_date,
                    'inclusive' => true,
                ),
            );
        }
        if (isset($_GET['filter-category']) && !empty($_GET['filter-category'])) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'categories',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['filter-category']),
                ),
            );
        }

        $custom_query = new WP_Query($args);
        if ($custom_query->have_posts()) {
            ?>
            <table class="table table-bordered shadow">
                <thead>
                    <tr>
                        <th>Post ID</th>
                        <th>Title</th>
                        <th>Content</th>
                        <th>Category</th>
                        <th>Meta</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Number</th>
                        <th>Date</th>
                        <th>Featured Img</th>
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
                        $meta = get_post_meta($post_id, '_custom_meta_key', true);
                        $category = get_the_terms($post_id, 'categories');
                        $category_names = array();
                        if ($category) {
                            foreach ($category as $cat) {
                                $category_names[] = $cat->name;
                            }
                        }
                        $name = get_post_meta($post_id, 'name', true);
                        $email = get_post_meta($post_id, 'email', true);
                        $password = get_post_meta($post_id, 'password', true);
                        $number = get_post_meta($post_id, 'number', true);
                        $fimage_id = get_post_meta($post_id, '_thumbnail_id', true);
                        $fimage_url = '';
                        if ($fimage_id) {
                            $fimage_array = wp_get_attachment_image_src($fimage_id, 'thumbnail');
                            $fimage_url = $fimage_array[0];
                        }
                        ?>
                        <tr>
                            <td><?php echo esc_html($post_id); ?></td>
                            <td><?php echo esc_html($post_title); ?></td>
                            <td><?php echo esc_html($post_content); ?></td>
                            <td><?php echo esc_html(implode(', ', $category_names)); ?></td>
                            <td><?php echo esc_html($meta); ?></td>
                            <td><?php echo esc_html($name); ?></td>
                            <td><?php echo esc_html($email); ?></td>
                            <td><?php echo esc_html($password); ?></td>
                            <td><?php echo esc_html($number); ?></td>
                            <td><?php echo esc_html(get_the_date()); ?></td>
                            <td>
                                <?php if ($fimage_url): ?>
                                    <img src="<?php echo esc_url($fimage_url); ?>" alt="Featured Image"
                                        style="width: 50px; height: auto;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo esc_url(home_url('/Test/custom-post-type-form/?post_id=' . $post_id)); ?>"
                                    class="btn btn-primary btnedit" id="edit">Edit</a>
                                <button class="btn btn-danger" id="del">Delete</button>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>

            <!-- pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <?php
                    echo paginate_links(
                        array(
                            'total' => $custom_query->max_num_pages,
                            'current' => $paged,
                            'prev_text' => __('« Prev'),
                            'next_text' => __('Next »'),
                        )
                    );
                    ?>
                </ul>
            </nav>
            <?php
        } else {
            echo '<p>No posts found</p>';
        }
        wp_reset_postdata();
        ?>
    </div>
    <div>

        <?php
}
add_action('add_meta_boxes', 'custom_meta_box');
function custom_meta_box()
{
    add_meta_box(id: 'custom_post_id', title: 'Custom Meta Box', callback: 'rendercustom_meta_box', screen: 'userinformation', context: 'side', priority: 'high');
}
function rendercustom_meta_box($post)
{
    // Retrieve existing value from database
    $custom_value = get_post_meta($post->ID, '_custom_meta_key', true);


    // Output the HTML for the meta box
    ?>
        <label for="custom_field">Custom Field Meta Box:</label>
        <input type="text" id="custom_field" name="custom_field" value="<?php echo esc_attr($custom_value); ?>" />

        <?php
}


add_action('init', 'my_post_update');
function my_post_update()
{

    if (isset($_POST['form-update']) && $_POST['form-update'] == 'publish') {

        $post_id = $_POST['form-pid'];
        $updatetitle = $_POST['form-title'];
        $updatecontent = $_POST['form-content'];
        $updatecategory = $_POST['form-categories'];
        $updatename = $_POST['form-username'];
        $updateemail = $_POST['form-uemail'];
        $updatepassword = $_POST['form-upass'];
        $updatenumber = $_POST['form-unumber'];

        $updated_post = array(
            'ID' => $post_id,
            'post_type' => 'userinformation',
            'post_title' => $updatetitle,
            'post_content' => $updatecontent,
            'post_status' => 'publish',
            'meta_input' => array(
                'categories' => $updatecategory,
                'name' => $updatename,
                'email' => $updateemail,
                'password' => $updatepassword,
                'number' => $updatenumber,
            ),

        );

        $updated_post = wp_update_post($updated_post, true);

        if (!empty($_FILES['form-fimage']['name'])) {
            require_once (ABSPATH . 'wp-admin/includes/image.php');
            require_once (ABSPATH . 'wp-admin/includes/file.php');
            require_once (ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('form-fimage', $post_id);
            set_post_thumbnail($post_id, $attachment_id);
        }
        update_post_meta($post_id, '_thumbnail_id', $attachment_id);

        wp_redirect('/Test/custom-post-type-record/');
        exit;

    }
}
?>




    <?php
    // function create_topics_hierarchical_taxonomy()
    // {
    //     // Add new taxonomy, make it hierarchical like categories
    //     $labels = array(
    //         'name' => _x('Topics', 'taxonomy general name'),
    //         'singular_name' => _x('Topic', 'taxonomy singular name'),
    //         'search_items' => __('Search Topics'),
    //         'all_items' => __('All Topics'),
    //         'parent_item' => __('Parent Topic'),
    //         'parent_item_colon' => __('Parent Topic:'),
    //         'edit_item' => __('Edit Topic'),
    //         'update_item' => __('Update Topic'),
    //         'add_new_item' => __('Add New Topic'),
    //         'new_item_name' => __('New Topic Name'),
    //         'menu_name' => __('Topics'),
    //     );
    
    //     // Now register the taxonomy
    //     register_taxonomy(
    //         'topics',
    //         array('cpps'),
    //         array(
    //             'hierarchical' => true,
    //             'labels' => $labels,
    //             'show_ui' => true,
    //             'show_admin_column' => true,
    //             'query_var' => true,
    //             'rewrite' => array('slug' => 'topic'),
    //         )
    //     );
    // }
    // add_action('init', 'create_topics_hierarchical_taxonomy', 0);







    ?>