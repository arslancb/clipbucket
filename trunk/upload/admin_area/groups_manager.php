<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

	// Deactivate Group
	if(isset($_GET['deactivate'])) {
		$cbgroup->unactive_gp(mysql_clean($_GET['deactivate']));
	}
	
	// Activate Group
	if(isset($_GET['activate'])) {
		$cbgroup->active_gp(mysql_clean($_GET['activate']));	
	}

	//Multiple Activate
	if(isset($_POST['activate_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->active_gp($_POST['check_group'][$i],true);
		}
		e(lang('Selected Groups are activated.','m'));
	}

	//Multiple Deactivate
	if(isset($_POST['deactivate_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->unactive_gp($_POST['check_group'][$i],true);
		}
		e(lang('Selected Groups are deactivated.','m'));
	}

	// Delete group
	if(isset($_GET['delete_group'])) {
		$cbgroup->delete_group(mysql_clean($_GET['delete_group']));	
	}
	
	//Multiple Delete
	if(isset($_POST['delete_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->delete_group($_POST['check_group'][$i],true);
		}
		e(lang('Selected Groups are Deleted.','m'));
	}
	
	//Collecting Data for Pagination
	$total_rows  = $db->count('users','*',$cond);
	$total_pages = count_pages($total_rows,RESULTS);
	
	//Pagination
	$pages->paginate($total_pages,$page);

/*	// Assign varibles
	assign('category',$cbgroup->get_categories());
	assign('gps',$cbgroup->get_groups());
*/
template_files('under_development.html');
display_it();

?>