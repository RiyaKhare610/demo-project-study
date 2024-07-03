<?php
/*
Plugin Name: Crud using ajax
Description: Crud plugin
Version: 1.0
Author: Ankit Singh
*/

function enqueue_script_and_style()
{
    wp_enqueue_script('custom-script', plugins_url('ajax-crud.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('custom-script', 'custom_script_params', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_script_and_style');


function my_crud_shortcode()
{
    ob_start();
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Page Title</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    </head>


    <body>
        <div class="container mt-5">
            <div class="">
                <form action="" class="" id="myform" method="post">

                    <h3 class="alert-warning text-center p-2">Add/Update Student</h3>
                    <input type="hidden" name="user-id" id="user-id" value="">
                    <div>
                        <label for="uname" class="form-label">User Name</label>
                        <input type="text" class="form-control" name="uname" id="unameid">
                    </div>
                    <div>
                        <label for="pass" class="form-label">Password</label>
                        <input type="password" class="form-control" name="pass" id="passid">
                    </div><br>
                    <div>
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="emailid" name="email">
                    </div>
                    <div>
                        <label for="addfee" class="form-label">Admission Fee</label>
                        <input type="text" class="form-control" id="addfeeid" name="addfee">
                    </div><br>
                    <div>
                        <label for="class" class="form-label">Class</label>
                        <select class="form-select" id="classid" name="class">
                            <option value="9th">9th</option>
                            <option value="10th">10th</option>
                            <option value="11th">11th</option>
                            <option value="12th">12th</option>
                        </select>
                    </div><br>
                    <div>
                        <label for="stream" class="form-label">Stream</label>
                        <select class="form-select" id="streamid" name="stream">
                            <option value="pcm">PCM</option>
                            <option value="pcb">PCB</option>
                            <option value="arts">Arts</option>
                            <option value="commerce">Commerce</option>
                        </select>
                    </div><br>

                    <div>
                        <label for="phone" class="form-label">Phone</label>
                        <input type="tel" class="form-control" id="phoneid" name="phone">
                    </div>
                    <div>
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="addressid" name="address"></textarea>
                    </div>
                    <div>
                        <label for="city" class="form-label">City</label>
                        <input type="text" class="form-control" id="cityid" name="city">
                    </div>
                    <div>
                        <label for="state" class="form-label">State</label>
                        <input type="text" class="form-control" id="stateid" name="state">
                    </div><br>
                    <div class="mt-5">
                        <button type="button" id="add">Add</button>
                        <button type="button" id="update" style="display: none;">Update</button>
                    </div><br><br>
                </form>
                <div class="text-center">
                    <h3 class="alert-warning p-2">Show Student Information</h3>
                    <table class="table" id="student-table">
                        <thead>
                            <tr>
                                <th scope="col">User Id</th>
                                <th scope="col">User Name</th>
                                <th scope="col">Password</th>
                                <th scope="col">Email</th>
                                <th scope="col">Admission Fee</th>
                                <th scope="col">Class</th>
                                <th scope="col">Stream</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Address</th>
                                <th scope="col">City</th>
                                <th scope="col">State</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>

                </div>
            </div>
        </div>

    </body>

    </html>
    <?php
    $output = ob_get_clean();
    return $output;
}

add_shortcode('my_crud_shortcode', 'my_crud_shortcode');


//insert

function add_student_ajax()
{

    if (isset($_POST['user_data'])) {
        $user_data = $_POST['user_data'];

        $username = sanitize_text_field($user_data['username']);
        $password = sanitize_text_field($user_data['password']);
        $email = sanitize_text_field($user_data['email']);
        $addfee = sanitize_text_field($user_data['addfee']);
        $class = sanitize_text_field($user_data['class']);
        $stream = sanitize_text_field($user_data['stream']);
        $phone = sanitize_text_field($user_data['phone']);
        $address = sanitize_text_field($user_data['address']);
        $city = sanitize_text_field($user_data['city']);
        $state = sanitize_text_field($user_data['state']);

        global $wpdb;
        $table_name = $wpdb->prefix . 'student_info';
        $result = $wpdb->insert(
            $table_name,
            array(
                'user_name' => $username,
                'password' => $password,
                'email' => $email,
                'add_fee' => $addfee,
                'class' => $class,
                'stream' => $stream,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
                'state' => $state,
            ),
            array(

                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
            )
        );
        if ($result !== false) {
            wp_send_json_success('Student added successfully.');
        } else {
            wp_send_json_error('Error adding student: ' . $wpdb->last_error);
        }

    } else {
        wp_send_json_error('Invalid data');
    }
}
add_action('wp_ajax_add_student_ajax', 'add_student_ajax');
add_action('wp_ajax_nopriv_add_student_ajax', 'add_student_ajax'); // For non-logged in users



// Retrieve student data
function get_student_data_ajax()
{
    global $wpdb;
    $student = $wpdb->get_results("SELECT * FROM " . $wpdb->prefix . 'student_info');

    if ($student !== false) {
        wp_send_json($student);
    } else {
        wp_send_json_error('Error retrieving student data.');
    }
}
add_action('wp_ajax_get_student_data_ajax', 'get_student_data_ajax');
add_action('wp_ajax_nopriv_get_student_data_ajax', 'get_student_data_ajax');


//UPDATE STUDENT DATA
function update_stu_data()
{
    if (isset($_POST['stu_data'])) {
        global $wpdb;
        $update_data = $_POST['stu_data'];

        $userId = sanitize_text_field($update_data['userId']);
        $username = sanitize_text_field($update_data['username']);
        $password = sanitize_text_field($update_data['password']);
        $email = sanitize_text_field($update_data['email']);
        $addfee = sanitize_text_field($update_data['addFee']); // Changed from 'addfee' to 'addFee'
        $Class = sanitize_text_field($update_data['Class']); // Changed from 'Class' to 'studentClass'
        $stream = sanitize_text_field($update_data['stream']);
        $phone = sanitize_text_field($update_data['phone']);
        $address = sanitize_text_field($update_data['address']);
        $city = sanitize_text_field($update_data['city']);
        $state = sanitize_text_field($update_data['state']);

        $table_name = $wpdb->prefix . 'student_info';
        $result = $wpdb->update(
            $table_name,
            array(
                'user_name' => $username,
                'password' => $password,
                'email' => $email,
                'add_fee' => $addfee,
                'class' => $Class, // Changed from 'Class' to 'studentClass'
                'stream' => $stream,
                'phone' => $phone,
                'address' => $address,
                'city' => $city,
                'state' => $state
            ),
            array('user_id' => $userId),
            array(
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ),
            array('%d')
        );

        if ($result !== false) {
            wp_send_json_success('User updated successfully');
        } else {
            wp_send_json_error('User not updated');
        }
    } else {
        wp_send_json_error('Invalid data');
    }
}
add_action('wp_ajax_update_stu_data', 'update_stu_data');
add_action('wp_ajax_nopriv_update_stu_data', 'update_stu_data');


function delete_stu_data()
{
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'student_info';
        $result = $wpdb->delete(
            $table_name,
            array(
                'user_id' => $user_id
            ),
            array(
                '%d'
            )
        );
        if ($result !== false) {
            wp_send_json_success('Student data deleted successfully.');
        } else {
            wp_send_json_error('Student data not deleted.');
        }
    } else {
        wp_send_json_error('Invalid data');
    }
}
add_action('wp_ajax_delete_stu_data', 'delete_stu_data');
add_action('wp_ajax_nopriv_delete_stu_data', 'delete_stu_data');


?>