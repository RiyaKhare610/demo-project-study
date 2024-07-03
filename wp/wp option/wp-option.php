<?php
/*
Plugin Name: Wp Option
*/

//create admin menu page
function registerAdminMenu()
{
    add_menu_page(
        'Wp_option Menu',
        'wp_option menu',
        'manage_options',
        'wp_option',
        'wp_option_form'

    );
}
add_action('admin_menu', 'registerAdminMenu');

function wp_option_form()
{
    ?>
    <!doctype html>
    <html lang="en">

    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

        <title>Wp OPtion Form</title>
    </head>

    <body>
        <div class="d-flex justify-content-center align-items-center">
            <div class="w-100" style="max-width: 500px;">
                <div class="container">
                    <div align="center">
                        <h1>WP-OPTION-FORM</h1>
                    </div>
                    <form action="" class="form-control rounded shadow my-5" method="POST" id="wp_form"
                        name="wp_option_form" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="data-1" class="form-label">Font Size</label>
                            <input type="text" value="<?php echo esc_attr(get_option('form_data1_option')); ?>" class="form-control" id="data1" name="form_data1">
                        </div>
                        <div class="mb-3">
                        <label for="data-2" class="form-label">Color</label>
                        <input type="color" value="<?php echo esc_attr(get_option('form_data2_option')); ?>" id="data2" name="form_data2">
                    </div
                        <div class="mb-3">
                            <label for="data-3" class="form-label">Image</label>
                            <?php
                            $attachment_id = get_option('form_data3_option');
                            $image_url = wp_get_attachment_url($attachment_id); ?>
                              <input type="file" class="form-control" id="data3" name="form_data3" value="<?php echo $image_url; ?>">
                              <div>
                                <img src="<?php echo $image_url; ?>" alt="" style="height:250px; widht: 600px;">
                              </div>
                        </div>
                        <div align="center">
                            <button class="btn btn-sm btn-primary" name="submit_form">Submit</button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

      




        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>

    </body>

    </html>
    <?php
}
add_action('init', 'submit_wp_option_form');
function submit_wp_option_form()
{
    if (isset($_POST['submit_form'])) {

        $data1 = sanitize_text_field($_POST['form_data1']);
        $data2 = sanitize_text_field($_POST['form_data2']);

        update_option('form_data1_option', $data1);
        update_option('form_data2_option', $data2);

        if (!empty($_FILES['form_data3']['name'])) {
            require_once (ABSPATH . 'wp-admin/includes/image.php');
            require_once (ABSPATH . 'wp-admin/includes/file.php');
            require_once (ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('form_data3', 0);

            if (is_wp_error($attachment_id)) {
                // Handle the error
                echo "Error uploading image: " . $attachment_id->get_error_message();
            } else {
                // Update the option with the attachment ID
                update_option('form_data3_option', $attachment_id);
            }
        }

        header("Location: http://localhost/Test/wp-admin/admin.php?page=wp_option");
        exit();
    }
}



