<?php
/*
Plugin Name: Custom taxonomy
Description: Create a custom and also taxonomy and category are same customcpt using backend
*/

add_shortcode('my_custom_taxonomy', 'my_custom_taxonomy');

function my_custom_taxonomy()
{
?>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="post_title">Title</label>
        <input type="text" name="post_title" id="post_title">
        <label for="post_content">Content</label>
        <input type="text" name="post_content" id="post_content">
        <label for="custom_taxonomy">Custom Taxonomy</label>
        <select name="taxonomy" id="taxonomy">
            <option value="">All Custom Taxonomy</option>
            <?php
            $categories = get_categories(array(
                'taxonomy' => 'music',
                'hide_empty' => false,
            ));

            foreach ($categories as $category) {
                echo '<option value = "' . $category->term_id . '">' . $category->name . '</option>';
            }
            ?>
        </select>
        <label for="image">Featured Images</label>
        <input type="file" name="fimage" id="fimage">

        <label for=""></label>
        <input type="submit" value="Submit_Custom_Posts" name="custom_posts_submit">
    </form>
<?php
}
add_action('init', 'myformsub');
function myformsub(){
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
    }
    if (isset($_POST['custom_posts_submit'])) {
        $title = sanitize_text_field($_POST['post_title']);
        $content = sanitize_text_field($_POST['post_content']);
        $category = intval($_POST['taxonomy']);

        $custom_cpt = array(
            'post_type' => 'customcpt',
            'post_title' => $title,
            'post_content' => $content,
            'post_status' => 'publish',
            'post_author' => $current_user->ID,
            // 'tax_input' => array('category' => array($category)),
        );

        $post_id = wp_insert_post($custom_cpt);

        if ($post_id && !empty($_FILES['fimage']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('fimage', $post_id);
            if (is_wp_error($attachment_id)) {
                return '<p>Error the image was not upload</p>';
            }
            set_post_thumbnail($post_id, $attachment_id);
        }
        wp_set_object_terms($post_id, $category, 'music');
        if ($post_id) {
            // Successful submission
            // echo '<script>alert("Post Submitted Successfully")</script>';
            wp_redirect("http://localhost/JamtechPractice/custom-taxonomy/");
            exit; // Make sure to exit after wp_redirect
        } else {
            // Unsuccessful submission
            echo '<script>alert("Post Not Submitted Successfully")</script>';
        }
        
       
    }

}

add_shortcode('my_taxonomy_listing', 'my_taxonomy_listing');

function my_taxonomy_listing() {
    // Check if a taxonomy term is selected
    $selected_taxonomy = isset($_GET['taxonomy']) ? intval($_GET['taxonomy']) : '';

    ?>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <style>
        /* .card-img-top {
            width: 100%;
            height: 200px; /* Adjust height as needed */
            /* object-fit: cover; Ensure the image covers the entire area */
        /* } */ 
    </style>
    <div class="container">
        <div class="row">
            <div class="col">
                <form method="GET">
                    <!-- <select name="taxonomy" id="taxonomy" onchange="this.form.submit()" class="form-select mb-3"> -->
                        <!-- <option value="">All Custom Taxonomy</option>
                        <?php
                        $terms = get_terms(array(
                            'taxonomy' => 'music',
                            'hide_empty' => false,
                        ));
                        foreach ($terms as $term) {
                            echo '<option value="' . $term->term_id . '" ' . selected($selected_taxonomy, $term->term_id, false) . '>' . $term->name . '</option>';
                        }
                        ?>
                    </select> -->
                </form>
            </div>
        </div>
        <div class="row">
            <?php
            // Loop through each term
            foreach ($terms as $term) {
                // Query posts for each term
                $args = array(
                    'post_type' => 'customcpt',
                    'posts_per_page' => 1, // Show only 1 post per term
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'music',
                            'field'    => 'term_id',
                            'terms'    => $term->term_id,
                        ),
                    ),
                );
                $query = new WP_Query($args);

                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <?php
                                if (has_post_thumbnail()) {
                                    echo '<img src="' . esc_url(get_the_post_thumbnail_url(get_the_ID(), 'large')) . '" class="card-img-top" alt="' . esc_attr(get_the_title()) . '">';
                                }
                                ?>
                                <div class="card-body">
                                    <h5 class="card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
                                    <p class="card-text"><?php echo wp_trim_words(get_the_excerpt(), 20, '...'); ?></p>
                                    <p class="card-text"><small class="text-muted"><?php echo $term->name; ?></small></p>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                    wp_reset_postdata();
                } else {
                    // Display a placeholder card if no posts found for the term
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $term->name; ?></h5>
                                <p class="card-text">No posts found for this term.</p>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <?php
}
