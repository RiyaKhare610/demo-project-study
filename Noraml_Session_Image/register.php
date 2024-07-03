<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        h2 {
            color: #333;
        }
        .form-container {
            background-color: #fff;
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="submit"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form method="post" action="insert.php" id="register" enctype="multipart/form-data">
        <input type="file" name="my_image" id="my_image" class="inp"><br><br>
        <span id="imageError" class="error"></span><br>
            Name: <input type="text" name="name" id="name" class="inp"><br>
            <span id="nameError" class="error"></span><br>
            Email: <input type="email" name="email" id="email" class="inp"><br>
            <span id="emailError" class="error"></span><br>
            Phone: <input type="text" name="phone" id="phone" class="inp"><br>
            <span id="phoneError" class="error"></span><br>
            Username: <input type="text" name="username" id="username" class="inp"><br>
            <span id="usernameError" class="error"></span><br>
            Password: <input type="password" name="password" id="password" class="inp"><br>
            <span id="passwordError" class="error"></span><br>
            <input type="submit" value="Submit">
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
             $('#register').submit(function(event){
                
                $('.inp').click(function(){
                    $('.error').text('');
                });
                    var my_image = $('input[name="my_image"]').val().trim();
                    var name = $('#name').val().trim();
                    var email = $('#email').val().trim();
                    var phone = $('#phone').val().trim();
                    var username = $('#username').val().trim();
                    var password = $('#password').val().trim();
                    var namePattern = /^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/;
                    var phonePattern = /^\(?\d{3}\)?[-.\s]?\d{3}[-.\s]?\d{4}$/;
                    var usernamePattern = /^[a-zA-Z0-9_]+$/;
                    var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    var passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;

                     if (my_image === '') {
                        $('#imageError').text('*Please choose an image.').css('color', 'red');
                        event.preventDefault();
                        return false;
                     }

                     if (name === '') {
                    $('#nameError').text('*Please enter your  name.').css('color', 'red');
                    event.preventDefault();
                    return false;
                } else if (!namePattern.test(name)) {
                    $('#nameError').text('Name can only contain letters, numbers, and underscores.');
                    event.preventDefault();
                } else {
                    $('#nameError').text('');
                }

                if(email === ''){
                    $('#emailError').text('*Please enter your email').css('color','red');
                }else if(!emailPattern.test(email)){
                    $('#emailError').text('Please enter a valid email address.');
                    event.preventDefault();
                    return false;
                }else{
                    $('#emailError').text('');
                }

                if(phone === ''){
                    $('#phoneError').text('*Please enter your phone number').css('color','red');
                }else if(!phonePattern.test(phone)){
                    $('#phoneError').text('Please enter a valid phone number.');
                    event.preventDefault();
                    return false;
                }else{
                    $('#phoneError').text('');
                }

                if(username === ''){
                    $('#usernameError').text('*Please enter your username').css('color','red');
                }else if(!usernamePattern.test(username)){
                    $('#usernameError').text('Please enter a valid username.');
                    event.preventDefault();
                    return false;
                }else{
                    $('#usernameError').text('');
                }
                if(password === ''){
                    $('#passwordError').text('*Please enter your password').css('color','red');
                }else if(!passwordPattern.test(password)){
                    $('#passwordError').text('Please enter a valid password.');
                    event.preventDefault();
                    return false;
                }else{
                    $('#passwordError').text('');
                }
             });   
        });
    </script>
</body>
</html>
