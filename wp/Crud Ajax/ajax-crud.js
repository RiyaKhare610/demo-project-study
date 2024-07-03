//INSERT

jQuery(document).ready(function ($) {
    loadstudata();
    $('#add').click(function (e) {
        e.preventDefault();

        var userdata = {
            username: $('#unameid').val(),
            password: $('#passid').val(),
            email: $('#emailid').val(),
            addfee: $('#addfeeid').val(),
            class: $('#classid').val(),
            stream: $('#streamid').val(),
            phone: $('#phoneid').val(),
            address: $('#addressid').val(),
            city: $('#cityid').val(),
            state: $('#stateid').val(),
        };

        $.ajax({
            url: custom_script_params.ajax_url,
            type: 'post',
            data: {
                action: 'add_student_ajax',
                user_data: userdata,
            },
            success: function (response) {
                if (response.success) {
                    alert(response.data);
                    loadstudata();
                    $('#myform')[0].reset();
                } else {
                    alert(response.data);
                }
            },
            error: function (xhr, status, error) {
                alert('Error: ' + error);
            }
        });
    });



    //Retrive student data
    function loadstudata() {
        jQuery.ajax({
            url: custom_script_params.ajax_url,
            type: 'post',
            data: {
                action: 'get_student_data_ajax',
            },
            success: function (response) {
                var userdata = response;

                jQuery('#student-table tbody').empty();


                jQuery.each(userdata, function (index, student) {
                    var row = '<tr>' +
                        '<td>' + student.user_id + '</td>' +
                        '<td>' + student.user_name + '</td>' +
                        '<td>' + student.password + '</td>' +
                        '<td>' + student.email + '</td>' + 
                        '<td>' + student.add_fee + '</td>' +
                        '<td>' + student.class + '</td>' +
                        '<td>' + student.stream + '</td>' +
                        '<td>' + student.phone + '</td>' +
                        '<td>' + student.address + '</td>' +
                        '<td>' + student.city + '</td>' +
                        '<td>' + student.state + '</td>' +
                        '<td>' +
                        '<button class="edit-user" data-user-id="' + student.user_id + '" data-user-name="' + student.user_name + '" data-password="' + student.password + '" data-email="' + student.email + '" data-add-fee="' + student.add_fee + '" data-class="' + student.class + '" data-stream="' + student.stream + '" data-phone="' + student.phone + '" data-address="' + student.address + '" data-city="' + student.city + '" data-state="' + student.state + '">Edit</button>' +
                        '<button class="delete-user" data-user-id="' + student.user_id + '" >Delete</button>' +
                        '</td>' +
                        '</tr>';

                    jQuery('#student-table tbody').append(row);
                });
            },

        });
    }


    //UPDATE STUDENT DATA
    jQuery(document).on('click', '.edit-user', function () {

        var userId = jQuery(this).data('user-id');
        var userName = jQuery(this).data('user-name');
        var password = jQuery(this).data('password');
        var email = jQuery(this).data('email');
        var addFee = jQuery(this).data('add-fee');
        var Class = jQuery(this).data('class');
        var stream = jQuery(this).data('stream');
        var phone = jQuery(this).data('phone');
        var address = jQuery(this).data('address');
        var city = jQuery(this).data('city');
        var state = jQuery(this).data('state');



        jQuery('#user-id').val(userId);
        jQuery('#unameid').val(userName);
        jQuery('#passid').val(password);
        jQuery('#emailid').val(email);
        jQuery('#addfeeid').val(addFee);
        jQuery('#classid').val(Class);
        jQuery('#streamid').val(stream);
        jQuery('#phoneid').val(phone);
        jQuery('#addressid').val(address);
        jQuery('#cityid').val(city);
        jQuery('#stateid').val(state);

        jQuery('#add').hide();
        jQuery('#update').show();

    });

    jQuery('#update').click(function(e) {
        e.preventDefault();
    
        var updateData = {
            userId: jQuery('#user-id').val(),
            username: jQuery('#unameid').val(),
            password: jQuery('#passid').val(),
            email: jQuery('#emailid').val(),
            addFee: jQuery('#addfeeid').val(),
            Class: jQuery('#classid').val(), // Changed from 'Class' to 'studentClass'
            stream: jQuery('#streamid').val(),
            phone: jQuery('#phoneid').val(),
            address: jQuery('#addressid').val(),
            city: jQuery('#cityid').val(),
            state: jQuery('#stateid').val()
        };
    
        jQuery.ajax({
            url: custom_script_params.ajax_url,
            type: 'post',
            data: {
                action: 'update_stu_data',
                stu_data: updateData
            },
            success: function(response) {
                if (response.success) {
                    alert('User updated successfully');
                    loadstudata();
                    jQuery('#myform')[0].reset();
                    jQuery('#add').show();
                    jQuery('#update').hide();
                } else {
                    alert('Error updating user: ' + response.error);
                }
            }
        });
    });


    // DELETE STUDENT DATA
    jQuery(document).on('click', '.delete-user', function(){
    
        var confirmDelete = confirm('Are you sure you want to delete this student?');
        if(confirmDelete){
            var userId = jQuery(this).data('user-id');

            jQuery.ajax({
                url: custom_script_params.ajax_url,
                type: 'post',
                data:{
                    action: 'delete_stu_data',
                    user_id: userId,
                },
                success: function(response){
                    if(response.success){
                        alert('Student data deleted successfully.');
                        loadstudata();
                    }else{
                        alert('Error deleting student data:' + response.error );
                    }
                },
            });
        }
    });
    






});


