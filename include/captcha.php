<?php
session_start();
$_SESSION["captcha1"] = mt_rand(1,20);
$_SESSION["captcha2"] = mt_rand(1,20);

$img = imagecreate(100, 30);
$font = "../data/fonts/OpenSans-Regular.ttf";

$bg_color = imagecolorallocate($img, 0, 0, 0);
$text_color = imagecolorallocate($img, 255, 255, 255);

// Generate random lines :
$line_color = imagecolorallocate($img, 64,64,64);
for($i=0;$i<10;$i++) {
    imageline($img,0,rand()%30,100,rand()%30,$line_color);
}

// Add the text :
$text = $_SESSION["captcha1"] . " + " . $_SESSION["captcha2"];
imagettftext($img, 20, 0, 5, 25, $text_color, $font, $text);

// generate random dark gray dots :
$pixel_color = imagecolorallocate($img, 32,32,32);
for($i=0;$i<1000;$i++) {
    imagesetpixel($img,rand()%100,rand()%30,$pixel_color);
}

// generate (fewer) random white dots :
$pixel_color = imagecolorallocate($img, 128,128,128);
for($i=0;$i<200;$i++) {
    imagesetpixel($img,rand()%100,rand()%30,$pixel_color);
}

header('Content-Type: image/jpeg');
imagejpeg($img);
imagedestroy($img);
