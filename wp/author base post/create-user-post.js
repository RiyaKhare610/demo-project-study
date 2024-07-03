jQuery(document).ready(function () {
    jQuery('#submit').on('click', function (e) {
        e.preventDefault();


        var image = jQuery('#fimageid')[0].files;

        var formData = new FormData();
        formData.append('action', 'add_user_post');
        formData.append('title', jQuery('#titleid').val());
        formData.append('content', jQuery('#contentid').val());
        formData.append('category', jQuery('#categoryid').val());
        formData.append('username', jQuery('#usernameid').val());
        formData.append('email', jQuery('#emailid').val());
        formData.append('password', jQuery('#passwordid').val());
        formData.append('number', jQuery('#numberid').val());
        formData.append('image', image[0]);


        jQuery.ajax({
            url: custom_script_params.ajax_url,
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.success) {
                    

                    alert('post created');
                    jQuery('#user_post_form')[0].reset();
                window.location = 'http://localhost/newProject/blog/';

                } else {
                    alert('post not created: ' + response.error);
                }
            },
        });


    });
});



//delete post
jQuery(document).ready(function($) {
    $('.del').on('click', function() {
        var postId = $(this).data('post-id');
        var row = $(this).closest('tr');

        if (confirm('Are you sure you want to delete this post?')) {
            $.ajax({
                type: 'POST',
                url: custom_script_params.ajax_url,
                data: {
                    action: 'delete_post',
                    post_id: postId,
                    // nonce: custom_script_params.nonce
                },
                success: function(response) {
                    if (response.success) {
                        row.remove();
                        alert(response.data);
                    } else {
                        alert(response.data);
                    }
                },
                error: function(xhr, status, error) {
                    alert('An error occurred: ' + error);
                }
            });
        }
    });
});

// edit post
jQuery(document).ready(function($) {
    $('.edit').on('click', function() {
        var postId = $(this).data('post-id');
        var postData = $(this).closest('tr');
        var title = postData.find('td:eq(2)').text();
        var content = postData.find('td:eq(3)').text();
        var category = postData.find('td:eq(4)').text();
        var fimage = postData.find('td:eq(1)').find('img').attr('src');
        var userName = postData.find('td:eq(6)').text();
        var userEmail = postData.find('td:eq(7)').text();
        var userPassword = postData.find('td:eq(8)').text();
        var userNumber = postData.find('td:eq(9)').text();

        console.log(category); // Debugging line

        $('#mpost_id').val(postId);
        $('#mtitleid').val(title);
        $('#mcontentid').val(content);
        $('#categoryoption').val(category);
        $('#mfimageid').attr('src', fimage);
        $('#musernameid').val(userName);
        $('#memailid').val(userEmail);
        $('#mpasswordid').val(userPassword);
        $('#mnumberid').val(userNumber);

        $('#myModal').modal('show'); // Assuming you're using Bootstrap modal
    });
});

