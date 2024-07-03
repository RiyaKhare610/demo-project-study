<?php
/*
Plugin Name: Custom User Role Editor
*/

// Shortcode for the custom post form
add_shortcode('my_custom_post_form', 'my_custom_post_form');


function enqueue_custom_script()
{
    wp_enqueue_script('my-custom-script', plugins_url('myrole.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('my-custom-script', 'custom_script_params', array(
        'ajax_url' => admin_url('admin-ajax.php'),
    ));
    wp_enqueue_style('bootstrap-css', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css');
    wp_enqueue_script('bootstrap-js', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_script');




function my_custom_post_form()
{
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();

        // $user = get_userdata(1); getdata for userid
        // if ($user) {
        //     echo '<pre>';
        //     echo 'Username: ' . $user->user_login . '<br>';
        //     echo 'Email: ' . $user->user_email . '<br>';
        //     echo 'Name: ' . $user->display_name . '<br>';
        //     echo '</pre>';
        // }


        if (isset($_POST['submit_user_role_form'])) {
            // Retrieve form data
            $post_title = sanitize_text_field($_POST['post']);
            $post_content = sanitize_textarea_field($_POST['postcontent']);
            $post_category = intval($_POST['category']);
            $user_name = sanitize_text_field($_POST['user_name']);
            $user_email = sanitize_email($_POST['user_email']);
            $user_password = sanitize_text_field($_POST['user_password']);
            $user_message = sanitize_textarea_field($_POST['user_message']);

            // Create post object
            $new_post = array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_status' => 'publish',
                'post_author' => $current_user->ID,
                'post_category' => array($post_category),
                'meta_input' => array(
                    'user_name' => $user_name,
                    'user_email' => $user_email,
                    'user_password' => $user_password,
                    'user_message' => $user_message
                )
            );

            // Insert the post into the database
            $post_id = wp_insert_post($new_post);

            // Handle featured image upload code
            if ($post_id && !empty($_FILES['fimage']['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = media_handle_upload('fimage', $post_id);
                if (is_wp_error($attachment_id)) {
                    return '<p>Error occurred while uploading image.</p>';
                }
                set_post_thumbnail($post_id, $attachment_id);
                update_post_meta($post_id, '_thumbnail_id', $attachment_id);
            }

            if ($post_id) {
                return '<p>Post submitted successfully!</p>';
            } else {
                return '<p>Error occurred while submitting post.</p>';
            }
        }

        if (isset($_POST['category']) && !empty($_POST['category'])) {
            $args['category_name'] = $_POST['category'];
        }
        ob_start();
?>
        <div class="container d-flex justify-content-center align-items-center">
            <div class="w-100" style="max-width: 500px;">
                <form method="post" class="shadow my-5" id="my_custom_authorbase" enctype="multipart/form-data">
                    <div class="container">
                        <div class="mb-3">
                            <label for="post" class="form-label">Post Title: </label>
                            <input type="text" class="form-control" id="post" name="post" required>
                            <input type="hidden" id="post_id" name="post_id">
                        </div>
                        <div class="mb-3">
                            <label for="postcontent" class="form-label">Post Content: </label>
                            <input type="text" class="form-control" id="postcontent" name="postcontent" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">All Categories:</label>
                            <select name="category" id="modal_category" class="form-control">
                                <option value="">All Categories</option>
                                <?php
                                $categories = get_categories(array(
                                    'taxonomy' => 'category',
                                    'hide_empty' => false,
                                ));
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="fimage" class="form-label">Featured Image: </label>
                            <input type="file" class="form-control" id="fimage" name="fimage">
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username:</label>
                            <input type="text" class="form-control" id="username" name="user_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="user_email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="user_password" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea id="message" name="user_message" class="form-control" required></textarea>
                        </div>
                        <input type="submit" name="submit_user_role_form" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    <?php
        return ob_get_clean();
    } else {
        return '<p>You need to be logged in to submit a post.</p>';
    }
}
add_shortcode('my_custom_post_form', 'my_custom_post_form');

function my_custom_listing()
{
    if (!is_user_logged_in()) {
        return '<p>You need to be logged in to view your posts.</p>';
    }

    $current_user = wp_get_current_user();
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => 4,
        'paged' => get_query_var('paged', 1),
        'author' => $current_user->ID
    );

    if (isset($_POST['category']) && !empty($_POST['category'])) {
        $args['category_name'] = $_POST['category'];
    }
    $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : '';
    $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : '';
    if (!empty($start_date) && !empty($end_date)) {
        $args['date_query'] = array(
            array(
                'after'     => $start_date,
                'before'    => $end_date,
                'inclusive' => true,
            ),
        );
    }
    if (isset($_GET['title']) && !empty($_GET['title'])) {
        $args['s'] = sanitize_text_field($_GET['title']);
    }

    if (isset($_POST['submit_updated_post_form'])) {
        $post_id = sanitize_text_field($_POST['post_id']);
        $post_title = sanitize_text_field($_POST['post']);
        $post_content = sanitize_textarea_field($_POST['postcontent']);
        $post_category = intval($_POST['category']);
        $user_name = sanitize_text_field($_POST['user_name']);
        $user_email = sanitize_email($_POST['user_email']);

        $updated_post = array(
            'ID' => $post_id,
            'post_title' => $post_title,
            'post_content' => $post_content,
            'post_status' => 'publish',
            'post_category' => array($post_category),
            'post_author' => $current_user->ID,
        );

        $update_result = wp_update_post($updated_post, true);

        if (!is_wp_error($update_result)) {
            update_post_meta($post_id, 'user_name', $user_name);
            update_post_meta($post_id, 'user_email', $user_email);

            // Handle featured image update
            if (!empty($_FILES['fimage']['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $attachment_id = media_handle_upload('fimage', $post_id);
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($post_id, $attachment_id);
                }
            }

            echo '<p>Post updated successfully!</p>';
        } else {
            echo '<p>Failed to update post. Please try again.</p>';
        }
    }


    if (isset($_POST['delete_post_button'])) {
        $post_id_to_delete = intval($_POST['delete_post_id']);
        if (current_user_can('delete_post', $post_id_to_delete)) {
            $deleted = wp_delete_post($post_id_to_delete, true);
            if ($deleted !== false) {
                echo '<p>Post deleted successfully!</p>';
            } else {
                echo '<p>Error occurred while deleting post.</p>';
            }
        } else {
            echo '<p>You do not have permission to delete this post.</p>';
        }
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start();
    ?>
        <div class="new" style="margin-bottom: 26px;">
        <div class="container-fluid">
            <div class="row">
              <div class="col-sm-12" style="display: flex; align-items: center;">
                <div class="col-sm-4" style="padding: 0;">
                    <form method="POST" action="">
                        <div class="seee">
                            <input type="hidden" name="page" value="">
                            <select name="category" fdprocessedid="n7ba7">
                                <option value="">All Categories</option>
                                <?php
                                // Get categories
                                $categories = get_categories(array(
                                    'taxonomy' => 'category',
                                    'hide_empty' => false,
                                ));
                                foreach ($categories as $category) {
                                    echo '<option value="' . $category->slug . '">' . $category->name . '</option>';
                                }
                                ?>
                            </select>
                            <button type="submit" class="btn btn-primary" fdprocessedid="5qurgn">Choose a Category</button>
                        </div>
                    </form>
                  </div>
                    <!-- starting date to end date filter -->
                    <div class="col-sm-4">
                        <form action="" method="get">
                          <div style="display: flex;align-items: center;justify-content: center;">
                          <div style="width: 70%;">
                          <input  type="hidden" name="page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>">
                            <label class="text-black" for="start_date">Starting Date:</label>
                            <input  type="date" name="start_date" id="start_date">
                            <label class="text-black" for="end_date">Ending Date:</label>
                            <input  type="date" name="end_date" id="end_date">
                          </div>
                            
                            <span><button type="submit" class="btn btn-primary btn-sm" style="width: 100px;">Apply Date</button></span>
                          
                        </div>
                        </form>
                    </div>
                    <div class="col-sm-4">
                        <form method="GET" action="" style="display: flex;">
                            <input type="hidden" name="page" value="<?php echo isset($_GET['page']) ? $_GET['page'] : ''; ?>">
                            <input type="text" class="form-control" id="search" name="title" placeholder="Search..." value="<?php echo isset($_GET['s']) ? $_GET['s'] : ''; ?>">
                            <button type="submit" class="btn btn-primary" style="margin-left: 9px;">Search</button>
                        </form>
                    </div>
                
            </div>
        </div>
      </div>
        </div>
        <table class="table table-light">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Categories</th>
                    <th>Featured Images</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <tr>
                        <td><?php echo get_the_ID(); ?></td>
                        <td><?php the_title(); ?></td>
                        <td><?php the_author(); ?></td>
                        <td><?php the_category(', '); ?></td>
                        <td><?php the_post_thumbnail('thumbnail'); ?></td>
                        <td><?php echo get_the_date('Y-m-d'); ?></td>
                        <td>
                            <button type="button" class="btn btn-primary edit-btn" data-bs-toggle="modal" data-bs-target="#exampleModal" data-post-id="<?php echo get_the_ID(); ?>" data-post-title="<?php echo esc_attr(get_the_title()); ?>" data-post-content="<?php echo esc_attr(get_the_content()); ?>" data-post-category="<?php echo esc_attr(get_the_category()[0]->term_id); ?>" data-user-name="<?php echo esc_attr(get_the_author_meta('user_login')); ?>" data-user-email="<?php echo esc_attr(get_the_author_meta('user_email')); ?>">
                                Edit
                            </button>
                        </td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="delete_post_id" value="<?php echo get_the_ID(); ?>">
                                <input type="submit" name="delete_post_button" class="btn btn-danger" value="Delete">
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php
            $total_pages = $query->max_num_pages;
            if ($total_pages > 1) {
                $current_page = max(1, get_query_var('paged'));
                echo paginate_links(array(
                    'base' => get_pagenum_link(1) . '%_%',
                    'format' => '/page/%#%',
                    'current' => $current_page,
                    'total' => $total_pages,
                    'prev_text' => __('<< prev'),
                    'next_text' => __('next  >>'),
                ));
            }
            ?>
        </div>

        <?php
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        ?>

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="post" class="shadow my-5" id="my_custom_authorbase" enctype="multipart/form-data">
                            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                            <input type="hidden" id="post_id" name="post_id">
                            <div class="container">
                                <div class="mb-3">
                                    <label for="post" class="form-label">Post Title: </label>
                                    <input type="text" class="form-control" id="post" name="post" required>
                                </div>
                                <div class="mb-3">
                                    <label for="postcontent" class="form-label">Post Content: </label>
                                    <input type="text" class="form-control" id="postcontent" name="postcontent" required>
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">All Categories:</label>
                                    <select name="category" id="modal_category" class="form-control">
                                        <option value="">All Categories</option>
                                        <?php
                                        $categories = get_categories(array(
                                            'taxonomy' => 'category',
                                            'hide_empty' => false,
                                        ));
                                        foreach ($categories as $category) {
                                            echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="fimage" class="form-label">Featured Image: </label>
                                    <input type="file" class="form-control" id="fimage" name="fimage">
                                </div>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username:</label>
                                    <input type="text" class="form-control" id="username" name="user_name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="user_email" required>
                                </div>
                                <input type="submit" id="update" class="update" name="submit_updated_post_form" value="Update">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
<?php
        return ob_get_clean();
    } else {
        return '<p>No posts found.</p>';
    }
}
add_shortcode('my_listing', 'my_custom_listing');
