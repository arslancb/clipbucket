<?php
define("IS_CAPTCHA_LOADING",true);
require '../../includes/common.php';
require "captcha/class.img_validator.php";

$word = substr(md5(rand(100,999999)),6,6);

$img = new img_validator();
$img->generates_image($word,true);
?>