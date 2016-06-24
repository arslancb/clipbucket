<?php
/* 
 ***********************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.		
 | @ Author 	: ArslanHassan												
 | @ Software 	: ClipBucket , © PHPBucket.com							
 *************************************************************************
*/

	require'../includes/admin_config.php';
	$userquery->admin_login_check();
	$pages->page_redir();

	/* Assigning page and subpage */
	if(!defined('MAIN_PAGE')){
		define('MAIN_PAGE', 'Tool Box');
	}
	if(!defined('SUB_PAGE')){
		define('SUB_PAGE', 'Development Mode');
	}

	define('DEVFILE', BASEDIR.'/includes/development.dev');
	if (isset($_GET)) {
		$action = $_GET['enable'];
		$data = $_GET['devpower'];
		if ($action == 'yes') {
			if (is_writable(BASEDIR.'/includes')) {
				file_put_contents(DEVFILE, $data);
				if (file_exists(DEVFILE)) {
				assign("devmsg","Development has been enabled successfuly");
			}
			} else {
				assign("deverror","\"includes\" directory is not writeable");
			}
		} elseif ($action == 'no') {
			unlink(DEVFILE);
			if (!file_exists(DEVFILE)) {
				assign("devmsg","Development has been disabled successfuly");
			}
		}
	}

	if (DEVELOPMENT_MODE) {
		$devpower = file_get_contents(DEVFILE);
		assign("devpower",$devpower);
		assign("devmode","yes");
	} else {
		assign("devmode","no");
	}

	subtitle("Development Mode");
	template_files('dev_mode.html');
	display_it();

?>