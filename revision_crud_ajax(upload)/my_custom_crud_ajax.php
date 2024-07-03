<?php
/*
Plugin Name: Custom & Revision CRUD AJAX
Description: Create a  custom revision crud operation revise to code 
Version: 2.0
Author: Mr. CRUD Operation
*/



add_shortcode('my_custom_crud_operation', 'my_custom_crud_operation');

function my_custom_crud_operation()
{
?>
    <div class="mb-3 row">
        <form method="post" id="crud-form">

            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name="email">
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
            <input type="submit" id="submit" class="submit" value="Submit" name="submit_data">
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        jQuery(document).ready(function() {
            jQuery('#submit').click(function(e) {
                e.preventDefault();
                var formData = new FormData(document.getElementById('crud-form'));
                formData.append('action', 'add_data');

                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            alert('Data saved successfully');
                        } else {
                            alert('data not inserted');
                        }
                    }
                })
            });
        });
    </script>
<?php
}
// update is pending
// Handle AJAX request to add a new entry

add_action('wp_ajax_add_data', 'add_data');

function add_data()
{

    $username = sanitize_text_field($_POST['username']);
    $email = sanitize_email($_POST['email']);
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

    $table_name = $wpdb->prefix . 'crudetailsinfo';
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
        'state' => $state
    ));
    if ($result) {
        wp_send_json_success("Data inserted successfully");
    } else {
        wp_send_json_error("Data not inserted");
    }
}


add_shortcode('my_record_show_select', 'my_record_show_select');

function my_record_show_select()
{
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th>CPassword</th>
                    <th>Fee</th>
                    <th>Class</th>
                    <th>Course</th>
                    <th>Address</th>
                    <th>Pincode</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="my_record">

            </tbody>
        </table>
    </div>

    <script>
        // Show data action
        jQuery(document).ready(function() {
            // Function to load data initially
            load_data();

            function load_data() {
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'show_data',
                    },
                    success: function(response) {
                        if (response) {
                            jQuery('#my_record').html(response);
                        } else {
                            alert('Data not found');
                        }
                    }
                });
            }

            // Handle delete action
            jQuery(document).on('click', '.btn-danger', function() {
                var confirmdelete = confirm('Are you sure you want to delete this item?');
                if (confirmdelete) {
                    var id = jQuery(this).closest('tr').find('td:first').text();
                    jQuery.ajax({
                        url: '<?php echo admin_url('admin-ajax.php'); ?>',
                        type: 'POST',
                        data: {
                            id: id,
                            action: 'delete_data'
                        },
                        success: function(response) {
                            if (response.success) {
                                alert("Data deleted successfully");
                                load_data(); 
                            } else {
                                alert("Data not deleted");
                            }
                        }
                    });
                }
            });
        });

      
        jQuery(document).on('click', '.btn-secondary', function() {
            alert('hi');
    var $row = jQuery(this).closest('tr');
    var id = jQuery(this).data('id');
    var username = $row.find('td:eq(0)').text();
    var email = $row.find('td:eq(1)').text();
    var password = $row.find('td:eq(2)').text();
    var cpassword = $row.find('td:eq(3)').text();
    var fee = $row.find('td:eq(4)').text();
    var classs = $row.find('td:eq(5)').text();
    var course = $row.find('td:eq(6)').text();
    var address = $row.find('td:eq(7)').text();
    var pincode = $row.find('td:eq(8)').text();
    var city = $row.find('td:eq(9)').text();
    var state = $row.find('td:eq(10)').text();

    jQuery('#username').val(username);
    jQuery('#email').val(email);
    jQuery('#password').val(password);
    jQuery('#cpassword').val(cpassword);
    jQuery('#fee').val(fee);
    jQuery('#class').val(classs);
    jQuery('#course').val(course);
    jQuery('#address').val(address);
    jQuery('#pincode').val(pincode);
    jQuery('#city').val(city); 
    jQuery('#state').val(state);
    jQuery('#update').show().data('id', id);
});

jQuery('#update').click(function() {
    alert('hi');
    var id = jQuery(this).data('id');
    var data = {
        username: jQuery('#username').val(),
        email: jQuery('#email').val(),
        password: jQuery('#password').val(),
        cpassword: jQuery('#cpassword').val(),
        fee: jQuery('#fee').val(),
        classs: jQuery('#class').val(),
        course: jQuery('#course').val(),
        address: jQuery('#address').val(),
        pincode: jQuery('#pincode').val(),
        city: jQuery('#city').val(), 
        state: jQuery('#state').val(),
        id: id,
        action: 'update_data'
    };

    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: data,
        success: function(response) {
            if (response.success) {
                alert('Data updated successfully');
                load_data();
                jQuery('#submit').show();
                jQuery('#update').hide();
                jQuery('#crud-form')[0].reset();
            } else {
                alert('Data not updated: ' + response.data);
            }
        }
    });
});

    </script>
<?php
}

add_action('wp_ajax_show_data', 'show_data');


function show_data()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'crudetailsinfo';
    $result = $wpdb->get_results("SELECT * FROM `$table_name`", ARRAY_A);

    if ($result) {
        foreach ($result as $row) {
            echo "<tr>";
            echo "<td>{$row['id']}</td>";
            echo "<td>{$row['username']}</td>";
            echo "<td>{$row['email']}</td>";
            echo "<td>{$row['password']}</td>";
            echo "<td>{$row['cpassword']}</td>";
            echo "<td>{$row['fee']}</td>";
            echo "<td>{$row['class']}</td>";
            echo "<td>{$row['course']}</td>";
            echo "<td>{$row['address']}</td>";
            echo "<td>{$row['pincode']}</td>";
            echo "<td>{$row['city']}</td>";
            echo "<td>{$row['state']}</td>";
            echo "<td><button class='btn btn-danger' data-id='{$row['id']}'>Delete</button></td>";
            echo "<td><button class='btn  btn-secondary'  data-id='{$row['id']}'>Update</button></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='13'>Data not found</td></tr>";
    }
}

add_action('wp_ajax_delete_data', 'delete_data');
function delete_data()
{
    if (isset($_POST['id'])) {
        $id = intval($_POST['id']);
        global $wpdb;
        $table_name = $wpdb->prefix . 'crudetailsinfo';
        $result = $wpdb->delete($table_name, array(
            'id' => $id
        ));
        if ($result) {
            wp_send_json_success('Data deleted successfully');
        } else {
            wp_send_json_error('Data not deleted successfully');
        }
    }
}


add_action('wp_ajax_update_data', 'update_data');

function update_data()
{
    $id = intval($_POST['id']);
    $username = sanitize_text_field($_POST['username']);
    $email = sanitize_email($_POST['email']);
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
    $table_name = $wpdb->prefix . 'crudetailsinfo';
    $result = $wpdb->update($table_name, array(
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
    ), array(
        'id' => $id
    ));

    if ($result) {
        wp_send_json_success('Data updated successfully');
    } else {
        wp_send_json_error('Data not updated successfully');
    }
}