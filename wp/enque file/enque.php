<?php 
function enqueue_script_and_style()
{
    wp_enqueue_script('custom-script', plugins_url('ajax-crud.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('custom-script', 'custom_script_params', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_script_and_style');

?>