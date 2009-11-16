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
		$groups->unactive_gp(mysql_clean($_GET['deactivate']));
	}
	
	// Activate Group
	if(isset($_GET['activate'])) {
		$groups->active_gp(mysql_clean($_GET['activate']));	
	}

	//Multiple Activate
	if(isset($_POST['activate_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$groups->active_gp($_POST['check_group'][$i],true);
		}
		e(lang('Selected Groups are activated.','m'));
	}

	//Multiple Deactivate
	if(isset($_POST['deactivate_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$groups->unactive_gp($_POST['check_group'][$i],true);
		}
		e(lang('Selected Groups are deactivated.','m'));
	}

	// Delete group
	if(isset($_GET['delete_group'])) {
		$groups->delete_group(mysql_clean($_GET['delete_group']));	
	}
	
	//Multiple Delete
	if(isset($_POST['delete_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$groups->delete_group($_POST['check_group'][$i],true);
		}
		e(lang('Selected Groups are Deleted.','m'));
	}
	
	//Collecting Data for Pagination
	$total_rows  = $db->count('users','*',$cond);
	$total_pages = count_pages($total_rows,VLISTPP);
	
	//Pagination
	$pages->paginate($total_pages,$page);

	// Assign varibles
	assign('category',$groups->get_categories());
	assign('gps',$groups->get_groups());

template_files('groups_manager.html');
display_it();

?>