<?php
/*
	Plugin Name: Pakplayer Manager
	Description: Control Pakplayer more easily
	Author: Arslan Hassan
	ClipBucket Version: 2
	Plugin Version: 1.0
	Website: http://clip-bucket.com/
*/

define('PAKPLAYER_PLUG_DIR',PLUG_DIR.'/pakplayer');
add_admin_menu('Templates And Players','Pakplayer','pakplayer.php','pakplayer/admin');
?>