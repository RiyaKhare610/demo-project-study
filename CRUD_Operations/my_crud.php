<?php
/*
Plugin Name: Custom CRUD Operations
Version: 3.0
Author: Mrs. Riya
*/

add_action('admin_menu', 'addAdminPageContent');

function addAdminPageContent() {
    add_menu_page('CRUD', 'CRUD', 'manage_options', 'crud', 'crudAdminPage', 'dashicons-wordpress');
    add_submenu_page('crud', 'View Records', 'View Records', 'manage_options', 'crud-view', 'crudAdminPage');
    add_submenu_page('crud', 'Add New Record', 'Add New Record', 'manage_options', 'crud-add', 'crudAddPage');
}

function crudAdminPage() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Sorry, you are not allowed to access this page.'));
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'userstable';

    // Delete record
    if (isset($_GET['del'])) {
        check_admin_referer('crud_nonce_action', 'crud_nonce_field');
        $del_id = intval($_GET['del']);
        $wpdb->delete($table_name, array('user_id' => $del_id));
        echo "<script>location.replace('admin.php?page=crud-view');</script>";
    }

    ?>
    <div class="wrap">
        <h2>View Records</h2>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th width="25%">User ID</th>
                    <th width="25%">Name</th>
                    <th width="25%">Email Address</th>
                    <th width="25%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $result = $wpdb->get_results("SELECT * FROM $table_name");
                foreach ($result as $print) {
                    echo "
                    <tr>
                        <td width='25%'>$print->user_id</td>
                        <td width='25%'>$print->name</td>
                        <td width='25%'>$print->email</td>
                        <td width='25%'>
                            <a href='admin.php?page=crud-add&upt=$print->user_id'><button type='button'>UPDATE</button></a> 
                            <a href='admin.php?page=crud-view&del=$print->user_id&crud_nonce_field=".wp_create_nonce('crud_nonce_action')."'><button type='button'>DELETE</button></a>
                        </td>
                    </tr>
                    ";
                }
                ?>
            </tbody>
        </table>
    </div>
    <?php
}

function crudAddPage() {
    if (!current_user_can('manage_options')) {
        wp_die(__('Sorry, you are not allowed to access this page.'));
    }
    
    global $wpdb;
    $table_name = $wpdb->prefix . 'userstable';

    // Insert new record
    if (isset($_POST['newsubmit'])) {
        check_admin_referer('crud_nonce_action', 'crud_nonce_field');
        $name = sanitize_text_field($_POST['newname']);
        $email = sanitize_email($_POST['newemail']);
        $wpdb->insert($table_name, array('name' => $name, 'email' => $email));
        echo "<script>location.replace('admin.php?page=crud-view');</script>";
    }

    // Update existing record
    if (isset($_POST['uptsubmit'])) {
        check_admin_referer('crud_nonce_action', 'crud_nonce_field');
        $id = intval($_POST['uptid']);
        $name = sanitize_text_field($_POST['uptname']);
        $email = sanitize_email($_POST['uptemail']);
        $wpdb->update($table_name, array('name' => $name, 'email' => $email), array('user_id' => $id));
        echo "<script>location.replace('admin.php?page=crud-view');</script>";
    }

    ?>
    <div class="wrap">
        <h2><?php echo isset($_GET['upt']) ? 'Update Record' : 'Add New Record'; ?></h2>
        <table class="wp-list-table widefat striped">
            <thead>
                <tr>
                    <th width="25%">User ID</th>
                    <th width="25%">Name</th>
                    <th width="25%">Email Address</th>
                    <th width="25%">Actions</th>
                </tr>
            </thead>
            <tbody>
                <form action="" method="post">
                    <?php wp_nonce_field('crud_nonce_action', 'crud_nonce_field'); ?>
                    <?php
                    if (isset($_GET['upt'])) {
                        $upt_id = intval($_GET['upt']);
                        $result = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$upt_id'");
                        foreach ($result as $print) {
                            $name = esc_attr($print->name);
                            $email = esc_attr($print->email);
                        }
                        echo "
                        <tr>
                            <td width='25%'>$print->user_id <input type='hidden' id='uptid' name='uptid' value='$print->user_id'></td>
                            <td width='25%'><input type='text' id='uptname' name='uptname' value='$name'></td>
                            <td width='25%'><input type='text' id='uptemail' name='uptemail' value='$email'></td>
                            <td width='25%'><button id='uptsubmit' name='uptsubmit' type='submit'>UPDATE</button> <a href='admin.php?page=crud-view'><button type='button'>CANCEL</button></a></td>
                        </tr>";
                    } else {
                        echo "
                        <tr>
                            <td><input type='text' value='AUTO_GENERATED' disabled></td>
                            <td><input type='text' id='newname' name='newname'></td>
                            <td><input type='text' id='newemail' name='newemail'></td>
                            <td><button id='newsubmit' name='newsubmit' type='submit'>INSERT</button></td>
                        </tr>";
                    }
                    ?>
                </form>
            </tbody>
        </table>
    </div>
    <?php
}
?>
