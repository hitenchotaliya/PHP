<?php
$_SESSION['code'] = rand(1, 50000);

$im = @imagecreate(250, 30) or die("Cannot create image");

$background_color = imagecolorallocate($im, 255, 255, 255);
$text_color = imagecolorallocate($im, 233, 14, 91);
imagestring($im, 5, 5, 5, $_SESSION['code'], $text_color);

header("Content-type: image/png");
imagepng($im);
imagedestroy($im);
?>
    <?php
    if (extension_loaded('gd') && function_exists('gd_info')) {
        echo "GD is enabled";
    } else {
        echo "GD is not enabled";
    }
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');

    ?>
