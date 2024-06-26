<?php
/*
Plugin Name: OWT List Table
Description: This is a simple plugin for WP_LIST_TABLE learning
Author: Custom WP
Version: 3.0
*/

// Add menu page
add_action('admin_menu', 'wpl_owt_list_table_menu');
function wpl_owt_list_table_menu() {
    add_menu_page("OWT List Table", "OWT List Table", "manage_options", "owt-list-table", "wpl_owt_list_table_function");
    add_submenu_page("owt-list-table", "Add New Item", "Add New Item", "manage_options", "owt-add-new", "wpl_owt_add_new_item_page");
}

// Enqueue scripts and styles
add_action('admin_enqueue_scripts', 'wpl_owt_list_table_enqueue_scripts');
function wpl_owt_list_table_enqueue_scripts() {
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_style('wp-jquery-ui-dialog');
    wp_enqueue_style('owt-custom-styles', plugins_url('custom-styles.css', __FILE__));

}

function wpl_owt_add_new_item_page(){
    if (isset($_POST['submit_data'])) {
        wpl_handle_form_submission();
    }
    ?>
    <div class="wrap">
    <h2>OWT List Table</h2>
    <form method="post">
        <h3>Add New Item</h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="name">Name</label></th>
                <td><input type="text" name="name" id="name" required /></td>
            </tr>
            <tr>
                <th scope="row"><label for="description">Description</label></th>
                <td><textarea name="description" id="description" required></textarea></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="submit_data" class="button button-primary" value="Add Item" />
        </p>
    </form>


</div>
<?php
}


// Main function to display the admin page
function wpl_owt_list_table_function() {


    if (isset($_POST['update_data'])) {
        wpl_handle_update_submission();
    }

    $listTable = new OWT_List_Table();
    $listTable->prepare_items();
?>
    <div class="wrap">
        <h2>OWT List Table</h2>


        <h3>List of Items</h3>
        <form method="post">
            <?php
            $listTable->search_box('search', 'search_id');
            $listTable->display();
            ?>
        </form>
    </div>

    <div id="update-modal" title="Update Item" style="display:none;">
        <form method="post" id="update-form">
            <input type="hidden" name="item_id" id="modal-item-id" value="">
            <table class="form-table">
                <tr>
                    <th scope="row"><label for="modal-name">Name</label></th>
                    <td><input type="text" name="name" id="modal-name" required /></td>
                </tr>
                <tr>
                    <th scope="row"><label for="modal-description">Description</label></th>
                    <td><textarea name="description" id="modal-description" required></textarea></td>
                </tr>
            </table>
            <p class="submit">
                <input type="submit" name="update_data" class="button button-primary" value="Update Item" />
            </p>
        </form>
    </div>

    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $(".update-button").on("click", function(e) {
            e.preventDefault(); // Prevent default link behavior

            var itemId = $(this).data("id");
            var itemName = $(this).data("name");
            var itemDescription = $(this).data("description");

            $("#modal-item-id").val(itemId);
            $("#modal-name").val(itemName);
            $("#modal-description").val(itemDescription);

            $("#update-modal").dialog({
                modal: true,
                width: 600,

            });
        });
    });
</script>


<?php
}

// Include WP_List_Table if not already loaded
if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

// Define custom WP_List_Table class
class OWT_List_Table extends WP_List_Table {
    function __construct() {
        parent::__construct(array(
            'singular' => 'item',
            'plural'   => 'items',
            'ajax'     => false
        ));
    }

    function column_default($item, $column_name) {
        switch ($column_name) {
            case 'id':
            case 'name':
            case 'description':
                return $item->$column_name;
            case 'action':
                return sprintf(
                    '<button class="button update-button" data-id="%s" data-name="%s" data-description="%s">Update</button>',
                    $item->id,
                    esc_attr($item->name),
                    esc_attr($item->description)
                );
            default:
                return print_r($item, true);
        }
    }

    function get_columns() {
        $columns = array(
            'cb'          => '<input type="checkbox" />',
            'id'          => 'ID',
            'name'        => 'Name',
            'description' => 'Description',
            'action'      => 'Action'
        );
        return $columns;
    }

    function get_sortable_columns() {
        $sortable_columns = array(
            'id'   => array('id', false),
            'name' => array('name', false)
        );
        return $sortable_columns;
    }

    function prepare_items() {
        global $wpdb;
        $per_page = 3;

        $columns  = $this->get_columns();
        $hidden   = array();
        $sortable = $this->get_sortable_columns();

        $this->_column_headers = array($columns, $hidden, $sortable);

        $data = $this->get_data();

        usort($data, array(&$this, 'usort_reorder'));

        $current_page = $this->get_pagenum();
        $total_items  = count($data);

        $this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));
    }

    function usort_reorder($a, $b) {
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'id';
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        $result = strcmp($a->$orderby, $b->$orderby);
        return ($order === 'asc') ? $result : -$result;
    }

    function get_data() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'custom_table';
        $search = (isset($_POST['s'])) ? $_POST['s'] : '';

        if (!empty($search)) {
            $query = $wpdb->prepare("SELECT * FROM $table_name WHERE name LIKE %s OR description LIKE %s", '%' . $wpdb->esc_like($search) . '%', '%' . $wpdb->esc_like($search) . '%');
        } else {
            $query = "SELECT * FROM $table_name";
        }

        $results = $wpdb->get_results($query);

        if ($wpdb->last_error) {
            error_log("Database Error: " . $wpdb->last_error);
        }

        return $results;
    }

    function column_cb($item) {
        return sprintf(
            '<input type="checkbox" name="item[]" value="%s" />',
            $item->id
        );
    }
}

// Handle form submission for adding an item
function wpl_handle_form_submission() {
    global $wpdb;

    $name = sanitize_text_field($_POST['name']);
    $description = sanitize_textarea_field($_POST['description']);

    $table_name = $wpdb->prefix . 'custom_table';
    $result = $wpdb->insert(
        $table_name,
        array(
            'name'        => $name,
            'description' => $description,
        )
    );

    if ($result) {
        echo '<div class="notice notice-success is-dismissible"><p>Item added successfully!</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>There was an error adding the item.</p></div>';
    }
}

// Handle form submission for updating an item
function wpl_handle_update_submission() {
    global $wpdb;

    $item_id = intval($_POST['item_id']);
    $name = sanitize_text_field($_POST['name']);
    $description = sanitize_textarea_field($_POST['description']);

    $table_name = $wpdb->prefix . 'custom_table';
    $result = $wpdb->update(
        $table_name,
        array(
            'name'        => $name,
            'description' => $description,
        ),
        array('id' => $item_id)
    );

    if ($result !== false) {
        echo '<div class="notice notice-success is-dismissible"><p>Item updated successfully!</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>There was an error updating the item.</p></div>';
    }
}
