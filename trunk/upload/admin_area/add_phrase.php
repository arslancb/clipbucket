<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');
$pages->page_redir();

/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Stats And Configurations');
}
if(!defined('SUB_PAGE')){
	define('SUB_PAGE', 'Add New Phrases');
}

if(isset($_POST['add_phrase']))
{
	$name = mysql_clean($_POST['name']);
	$text = mysql_real_escape_string($_POST['text']);
	$lang_code = mysql_clean($_POST['lang_code']);
	$lang_obj->add_phrase($name,$text,$lang_code);
}


subtitle("Add Language Phrase");
template_files('add_phrase.html');
display_it();
?>