<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

if(isset($_POST['add_phrase']))
{
	$name = mysql_clean($_POST['name']);
	$text = mysql_real_escape_string($_POST['text']);
	$lang_code = mysql_clean($_POST['lang_code']);
	$lang_obj->add_phrase($name,$text);
}

/*Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('language_settings.html');
Template('footer.html');*/

template_files('add_phrase.html');
display_it();
?>