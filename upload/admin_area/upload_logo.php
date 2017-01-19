<?php
	/*
	* File is used for uploading logo in ClipBucket
	*/

	require_once '../includes/admin_config.php';
	$userquery->admin_login_check();
	$pages->page_redir();
	/* Assigning page and subpage */
	if(!defined('MAIN_PAGE')){
		define('MAIN_PAGE', 'Stats And Configurations');
	}
	if(!defined('SUB_PAGE')){
		define('SUB_PAGE', 'Update Logo');
	}

	$source = BASEURL.'/styles/cb_28/theme/images/logo.png';

	// Upload and Rename File

	if (isset($_POST['submit'])) {
		// function used to upload site logo.
		upload_logo() ;
	}

	assign('source',$source);
	subtitle("Update Logo");
	template_files('upload_logo.html');
	display_it();

?>