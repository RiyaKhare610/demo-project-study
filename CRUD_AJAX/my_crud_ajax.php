<?php
/*
Plugin Name: Task Crud Ajax
Version: 3.0
Description: Create a custom crud ajax
Author: Riya
*/

// Shortcode to display the form and also show a table

add_shortcode('custom_ajax', 'custom_ajax_crud');

function custom_ajax_crud()
{
    ?>
    <!-- create a form -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <div class="mb-3 row">
        <form method="post" id="crud-form">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
                       name="email">
                <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
            </div>
            <div class="mb-3">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input type="password" class="form-control" id="exampleInputPassword1" name="password">
            </div>
            <div class="mb-3">
                <label for="exampleInputconfirmPassword1" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="exampleInputconfirmPassword1" name="cpassword">
            </div>
            <div class="mb-3">
                <label for="fee" class="form-label">Admission Fee</label>
                <input type="text" class="form-control" id="fee" name="fee">
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" class="form-control" id="class" name="class">
            </div>
            <div class="mb-3">
                <label for="course" class="form-label">Course</label>
                <input type="text" class="form-control" id="course" name="course">
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" class="form-control" id="address" name="address">
            </div>
            <div class="mb-3">
                <label for="pincode" class="form-label">Pincode</label>
                <input type="text" class="form-control" id="pincode" name="pincode">
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city">
            </div>
            <div class="mb-3">
                <label for="state" class="form-label">State</label>
                <input type="text" class="form-control" id="state" name="state">
            </div>
            <input type="submit" id="submit" class="submit" value="Submit">
        </form>
    </div>
    <table>
        <thead>
        <tr>
            <th scope="col">Username</th>
            <th scope="col">Email</th>
            <th scope="col">Password</th>
            <th scope="col">Confirm Password</th>
            <th scope="col">Admission Fee</th>
            <th scope="col">Class</th>
            <th scope="col">Course</th>
            <th scope="col">Address</th>
            <th scope="col">Pincode</th>
            <th scope="col">City</th>
            <th scope="col">State</th>
        </tr>
        </thead>
        <tbody id="table-body">
        </tbody>
    </table>
    <?php
    ?>
    <script src="https://code.jquery.com/jquery-3.7.1.js"
            integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        jQuery(document).ready(function () {
            jQuery('#submit').click(function (e) {
                e.preventDefault();

                var formData = {
                    action: 'add_insert', // Action to trigger PHP function
                    username: jQuery('#username').val(),
                    email: jQuery('#exampleInputEmail1').val(),
                    password: jQuery('#exampleInputPassword1').val(),
                    cpassword: jQuery('#exampleInputconfirmPassword1').val(),
                    fee: jQuery('#fee').val(),
                    class: jQuery('#class').val(),
                    course: jQuery('#course').val(),
                    address: jQuery('#address').val(),
                    pincode: jQuery('#pincode').val(),
                    city: jQuery('#city').val(),
                    state: jQuery('#state').val(),
                };

                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php')?>',
                    type: 'POST',
                    data: formData, // Send data as an object
                    success: function (response) {
                        if (response.success) {
                            alert('Data saved successfully');
                            load_data();
                        } else {
                            alert('Data not saved: ' + response.data);
                        }
                    }
                });
            });
        });
    </script>
    <?php
}

// Handle AJAX request to add a new entry
add_action('wp_ajax_add_insert', 'add_insert');

function add_insert()
{
    if (isset($_POST['username'])) {
        $username = sanitize_text_field($_POST['username']);
        $email = sanitize_text_field($_POST['email']);
        $password = sanitize_text_field($_POST['password']);
        $cpassword = sanitize_text_field($_POST['cpassword']);
        $fee = sanitize_text_field($_POST['fee']);
        $class = sanitize_text_field($_POST['class']);
        $course = sanitize_text_field($_POST['course']);
        $address = sanitize_text_field($_POST['address']);
        $pincode = sanitize_text_field($_POST['pincode']);
        $city = sanitize_text_field($_POST['city']);
        $state = sanitize_text_field($_POST['state']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'ajax2';

        $result = $wpdb->insert($table_name, array(
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'cpassword' => $cpassword,
            'fee' => $fee,
            'class' => $class,
            'course' => $course,
            'address' => $address,
            'pincode' => $pincode,
            'city' => $city,
            'state' => $state,
        ));
        if ($result) {
            wp_send_json_success('Data inserted successfully');
        } else {
            wp_send_json_error('Data not inserted');
        }
    }
    wp_die();
}
