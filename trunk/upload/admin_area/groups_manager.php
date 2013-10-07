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
$userquery->perm_check('group_moderation',true);

	// Deactivate Group
	if(isset($_GET['deactivate'])) {
		$cbgroup->grp_actions('deactivate',mysql_clean($_GET['deactivate']));
	}
	
	// Activate Group
	if(isset($_GET['activate'])) {
		$cbgroup->grp_actions('activate',mysql_clean($_GET['activate']));	
	}
	
	// Feature Group
	if(isset($_GET['feature'])) {
		$cbgroup->grp_actions('feature',mysql_clean($_GET['feature']));	
	}
	
	// unFeature Group
	if(isset($_GET['unfeature'])) {
		$cbgroup->grp_actions('unfeature',mysql_clean($_GET['unfeature']));	
	}

	//Multiple Activate
	if(isset($_POST['activate_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->grp_actions('activate',$_POST['check_group'][$i],true);
		}
		$eh->flush();
		e(lang('Selected Groups are activated.'),'m');
	}

	//Multiple Deactivate
	if(isset($_POST['deactivate_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->grp_actions('decativate',$_POST['check_group'][$i],true);
		}
		$eh->flush();
		e(lang('Selected Groups are deactivated.'),'m');
	}
	
	//Multiple Feature
	if(isset($_POST['make_featured_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->grp_actions('feature',$_POST['check_group'][$i],true);
		}
		$eh->flush();
		e(lang('Selected Groups have been set as featured.'),'m');
	}
	
	//Multiple UnFeature
	if(isset($_POST['make_unfeatured_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->grp_actions('feature',$_POST['check_group'][$i],true);
		}
		$eh->flush();
		e(lang('Selected Groups have been set as Unfeatured.'),'m');
	}

	// Delete group
	if(isset($_GET['delete_group'])) {
		$cbgroup->grp_actions("delete",mysql_clean($_GET['delete_group']));	
	}
	
	//Multiple Delete
	if(isset($_POST['delete_selected'])) {
		for($i=0; $i<count($_POST['check_group']); $i++) {
			$cbgroup->grp_actions("delete",$_POST['check_group'][$i],true);
		}
		$eh->flush();
		e(lang('Selected Groups are Deleted.'),'m');
	}
	
	
	//Calling Group Manager Functions
	call_functions($cbgroup->group_manager_funcs);
		
	$page = mysql_clean($_GET['page']);
	$get_limit = create_query_limit($page,RESULTS);
	
	if(isset($_GET['search']))
	{
		
		$array = array
		(
		 'group_id' => $_GET['group_id'],
		 'user' => $_GET['userid'],
		 'title'	=> $_GET['title'],
		 'tags'	=> $_GET['tags'],
		 'category' => $_GET['category'],
		 'featured' => $_GET['featured'],
		 'active'	=> $_GET['active']
		 );		
	}
	
	$result_array = $array;
	//Getting Video List
	$result_array['limit'] = $get_limit;
	if(!$array['order'])
		$result_array['order'] = " date_added DESC ";
	$groups = $cbgroup->get_groups($result_array);
	
	Assign('groups', $groups);	

	//Collecting Data for Pagination
	$gcount = $array;
	$gcount['count_only'] = true;
	$total_rows  = $cbgroup->get_groups($gcount);
	$total_pages = count_pages($total_rows,RESULTS);
	$pages->paginate($total_pages,$page);

	
	//Category Array
	if(is_array($_GET['category']))
		$cats_array = array($_GET['category']);		
	else
	{
		preg_match_all('/#([0-9]+)#/',$_GET['category'],$m);
		$cats_array = array($m[1]);
	}
	$cat_array =	array(lang('vdo_cat'),
					'type'=> 'checkbox',
					'name'=> 'category[]',
					'id'=> 'category',
					'value'=> array('category',$cats_array),
					'hint_1'=>  lang('vdo_cat_msg'),
					'display_function' => 'convert_to_categories',
					'category_type'=>'groups');
	assign('cat_array',$cat_array);


subtitle("Group Manager");
template_files('groups_manager.html');
display_it();

?>