jQuery(document).ready(function ($) {
    // Click event listener for elements with the class 'edit-btn'
    $('.edit-btn').on('click', function () {
        // 'this' refers to the clicked button element
        // Retrieve the data attributes from the clicked button
        var post_id = $(this).data('post-id'); // Get the post ID
        var post_title = $(this).data('post-title');
        var post_content = $(this).data('post-content');
        var post_category = $(this).data('post-category');
        var user_name = $(this).data('user-name');
        var user_email = $(this).data('user-email');

        // Populate the modal input fields with the retrieved data
        $('#exampleModal #post_id').val(post_id); // Set the hidden input field for post ID
        $('#exampleModal #post').val(post_title);
        $('#exampleModal #postcontent').val(post_content);
        $('#exampleModal #modal_category').val(post_category); // Ensure this ID matches the select element in the modal form
        $('#exampleModal #username').val(user_name);
        $('#exampleModal #email').val(user_email);
    });
});
