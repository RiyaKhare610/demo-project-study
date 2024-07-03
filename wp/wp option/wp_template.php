<?php
/*
Template Name: Wp Template
*/





?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>wp-option-setting-page</title>
</head>

<body>

    <?php 
        $font_size = get_option('form_data1_option');
        $color = get_option('form_data2_option');
        $image = get_option('form_data3_option');
    ?>

    <div class="d-flex justify-content-center align-items-center">
        <div class="w-100" style="max-width: 500px;">
            <div class="container border" style="background-color: <?php echo esc_attr(get_option('form_data2_option')); ?>;">
                <h1 style="font-size: <?php echo $font_size ?>px;">Wp Option Setting Page</h1>
                <div>
                    <?php
                        $attachment_id = get_option('form_data3_option');
                        $image_url = wp_get_attachment_url($attachment_id); ?>
                
                    <img src="<?php echo $image_url; ?>" alt="" style="height:250px; widht: 300px;">
               
                
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
</body>

</html>
