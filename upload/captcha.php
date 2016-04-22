<?php

// You must start sessions in the page you'll use the image validator!
session_start();

require "./includes/classes/captcha/class.img_validator.php";

$word = substr(md5(rand(100,999999)),6,6);

$img = new img_validator();
$img->generates_image($word,true);
?>