<?php

/*
Plugin Name: Custom Fields Beta for ClipBucket
Description: Add custom fields in your video upload form :)
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/


define('CUSTOM_FIELDS_MOD',TRUE);
define("CUSTOM_FIELDS_BASE",basename(dirname(__FILE__)));
define("CUSTOM_FIELDS_DIR",PLUG_DIR.'/'.CUSTOM_FIELDS_BASE);
define("CUSTOM_FIELDS_URL",PLUG_URL.'/'.CUSTOM_FIELDS_BASE);
define("CUSTOM_FIELDS_ADMIN_DIR", CUSTOM_FIELDS_DIR.'/admin');
define("CUSTOM_FIELDS_ADMIN_URL", CUSTOM_FIELDS_URL.'/admin');
define("CUSTOM_FIELDS_INCLUDES", PLUG_DIR.'/'.CUSTOM_FIELDS_BASE.'/cb_beats_includes');
define("CUSTOM_FIELDS_HTML", PLUG_DIR.'/'.CUSTOM_FIELDS_BASE.'/templates');
define("CUSTOM_FIELDS_HTML_URL", PLUG_URL.'/'.CUSTOM_FIELDS_BASE.'/templates');
define("CUSTOM_FIELDS_ADMIN_HTML", CUSTOM_FIELDS_ADMIN_DIR.'/styles');
define("CUSTOM_FIELDS_ADMIN_HTML_URL", CUSTOM_FIELDS_ADMIN_URL.'/styles');
assign("custom_field_edit_page","admin_area/plugin.php?folder=custom_fields/admin&file=add_custom_field.php");
require CUSTOM_FIELDS_DIR.'/custom_includes/functions.php';
include 'customfield.php';

if(!function_exists('add_custom_field'))
{
	//Adding Admin Menu
	add_admin_menu('Custom Fields','Add New Custom Field','add_custom_field.php',CUSTOM_FIELDS_BASE.'/admin');
	add_admin_menu('Custom Fields','Manage Custom Fields','manage_custom_fields.php');
	
	if(load_form_fields())
		register_custom_form_field(load_form_fields());
	
}


?>