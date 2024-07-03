$(document).ready(function() {
    $(".tab").css("display", "none");
    $("#tab-1").css("display", "block");

    function run(hideTab, showTab) {
        // Your existing run function code
        if (hideTab < showTab) {
            if (hideTab == 1) {
                $('.inp').click(function(){
                    $('.error').text('');
                });  
                var muskan = validatename();
                if (muskan == false) {
                    return false;
                } else {
                    $('.error').text('');
                }
            }
            if (hideTab == 2) {
                var a = tab2();
                if (a == false) {
                    return false;
                } else {
                    $('.error').text('');
                }
            }
            
            if (hideTab == 3) {
                var ank = login();
                if (ank == false) {
                    return false;
                } else {
                    $('.error').text('');
                }
            }
        }

        for (i = 1; i < showTab; i++) {
            $("#step-" + i).css("opacity", "1");
        }

        $("#tab-" + hideTab).css("display", "none");
        $("#tab-" + showTab).css("display", "block");
    }

    function validatename() {
        // Your existing validatename function code
        var fname = $('#fname').val();
        var lname = $('#lname').val();

        if (fname == "") {
            $("#fnameerror").text("*plese enter first name.");
            return false;
        } else if (fname.length < 4) {
            $("#fnameerror").text("*Length of name is short.");
            return false;
        } else if (!/^[a-zA-Z]+$/.test(fname)) {
            $("#fnameerror").text("*Enter valid name");
            return false;
        }

        if (lname == "") {
            $("#lnameerror").text("*plese enter last name.");
            return false;
        } else if (lname.length < 4) {
            $("#lnameerror").text("*lenth is short.");
            return false;
        } else if (!/^[a-zA-Z]+$/.test(lname)) {
            $("#lnameerror").text("*enter valid last name.");
            return false;
        } else {
            return true;
        }
    }

    function tab2() {
        // Your existing tab2 function code
        var email = $('#email').val();
        var phone = $('#phone').val();

        if (email == "") {
            $("#emailerror").text("*plese enter email.");
            return false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            $("#emailerror").text("*plese enter valid email.");
            return false;
        }
        if (phone == "") {
            $("#phoneerror").text("*plese enter contact no.");
            return false;
        } else if (phone.length != 10) {
            $("#phoneerror").text("*plese enter a valid number.");
            return false;
        } else {
            return true;
        }
    }

    function login() {
        // Your existing login function code
        var user = $('#username').val();
        var pass = $('#password').val();

        if (user == "") {
            $("#usernameerror").text("*plese enter username.");
            return false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(user)) {
            $("#usernameerror").text("*plese enter valid username.");
            return false;
        }
        if (pass == "") {
            $("#passerror").text("*plese enter password.");
            return false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(pass)) {
            $("#passerror").text("*plese enter valid password.");
            return false;
        } else {
            return true;
        }
    }

    // Event binding for buttons
    $('.index-btn').click(function() {
        var hideTab = parseInt($(this).parent().parent().attr('id').split('-')[1]);
        var showTab = hideTab + ($(this).hasClass('next') ? 1 : -1);
        run(hideTab, showTab);
    });

    // Event binding for input fields
    $('.inp').click(function(){
        $('.error').text('');
    });
});
