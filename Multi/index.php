<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wizard Form</title>
    <link rel="stylesheet" href="wizard.css">
   
   
</head>

<body>
<?php if(isset($_GET['error'])) : ?>
                <p> <?php echo $_GET['error']; ?></p>
                <?php endif ?>
    <form action="insert.php" method="post" autocomplete="off" enctype="multipart/form-data">
        <h1 align=center>MultiStep Form</h1>
        <input type="file" name="my_image">
        <div class="tab" id="tab-1">
            <p>Name:</p>
            <input class="inp" type="text" placeholder="First Name" name="firstname" id="fname"><span class="error"
                id="fnameerror"></span>
            <input class="inp" type="text" placeholder="Last Name" name="lastname" id="lname"><span class="error"
                id="lnameerror"></span>
            <div class="index-btn-wrapper">
                <div class="index-btn" onclick="run(1, 2);">Next</div>
            </div>
        </div>
        <div class="tab" id="tab-2">
            <p>Contact Info:</p>
            <input class="inp" type="email" placeholder="Email" name="email" id="email"><span class="error" id="emailerror"></span>
            <input class="inp" type="phone" placeholder="Phone" name="phone" id="phone"><span class="error" id="phoneerror"></span>
            <div class="index-btn-wrapper">
                <div class="index-btn" onclick="run(2, 1);">Previous</div>
                <div class="index-btn" onclick="run(2, 3);">Next</div>
            </div>
        </div>
       
        <div class="tab" id="tab-3">
            <p>Login Info:</p>
            <input class="inp" type="text" placeholder="Username" name="username" id="username"><span class="error"
                id="usernameerror"></span>
            <input class="inp" type="password" placeholder="Password" name="password" id="password"><span class="error"
                id="passerror"></span>
            <div class="index-btn-wrapper">
                <div class="index-btn" onclick="run(3, 2);">Previous</div>
                <div class="index-btn" onclick="run(3, 4);">Next</div>
            </div>
        </div>

        <div class="tab" id="tab-4">
            <div class="index-btn-wrapper">
                <div class="index-btn" onclick="run(4, 3);">Previous</div>
                <button class="index-btn" type="submit" style="background-color: blue;">Submit</button>
            </div>
        </div>


    </form>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="wizard.js"></script>
   

</body>

</html>