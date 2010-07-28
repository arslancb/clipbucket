<?php
/*
Plugin Name: Date Picker
Description: This plugin will allow us to pick dates 
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 1.8
Version: 1.0
Website: http://labguru.com/
Plugin Type: global
*/

register_action('date_picker','date_picker');
//Adding header
$file = PLUG_DIR.'/date_picker/header.html';
$Cbucket->add_header($file,array('upload','signup','edit_video','edit_account'));
?>