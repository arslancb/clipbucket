<?php
	/**
	*	Plugin Name: Social Beast
	*	Description: Display links to your social networks anywhere on website
	*	Author: Saqib Razzaq
	*	ClipBucket Version: 2.8.1.x
	*	Plugin Version 1.0
	* 	@since August 9th, 2016
	*	Github: https://github.com/saqirzzq
	*/
	define("SOCIAL_BEAST_BASE",basename(dirname(__FILE__)));
	define("SOCIAL_BEAST_DIR",PLUG_DIR.'/'.SOCIAL_BEAST_BASE);
	define("SOCIAL_BEAST_URL",PLUG_URL.'/'.SOCIAL_BEAST_BASE);
	define("SOCIAL_BEAST_ADMIN_DIR", SOCIAL_BEAST_DIR.'/admin');
	define("SOCIAL_BEAST_ADMIN_URL", SOCIAL_BEAST_URL.'/admin');
	define("SOCIAL_BEAST_INCLUDES", PLUG_DIR.'/'.SOCIAL_BEAST_BASE.'/honey_includes');
	assign("SOCIAL_BEAST_ADMIN_URL",SOCIAL_BEAST_ADMIN_URL);
	define('FONT_AWESOME', SOCIAL_BEAST_URL.'/font_awesome.css');
	define('BEAST_CSS', SOCIAL_BEAST_URL.'/beast.css');
	assign("ajax_file",SOCIAL_BEAST_URL.'/ajax.php');
	assign("font_awesome",FONT_AWESOME);
	require SOCIAL_BEAST_DIR.'/includes/common.php';
	getWild();
?>	