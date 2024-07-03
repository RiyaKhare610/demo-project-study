<?php 
// Example function to output content for the submenu page
function my_submenu_page_content() {
    echo '<div class="wrap">
        <h1>Hello, this is my custom submenu page!</h1>
        <p>This is where you can add your custom content.</p>
    </div>';
}

// Adding the submenu page
function my_add_submenu_page() {
    add_menu_page(
        'Parent Menu',          // Page title of parent menu
        'Parent Menu',          // Menu title of parent menu
        'manage_options',       // Capability of parent menu
        'parent-menu',          // Menu slug of parent menu
        'my_menu_page_content', // Callback function of parent menu
        'dashicons-admin-generic' // Icon URL of parent menu
    );

    add_submenu_page(
        'parent-menu',          // Parent menu slug
        'Submenu Page',         // Page title of submenu
        'Submenu Page',         // Menu title of submenu
        'manage_options',       // Capability of submenu
        'submenu-page',         // Menu slug of submenu
        'my_submenu_page_content' // Callback function of submenu
    );
}
add_action('admin_menu', 'my_add_submenu_page');
?>