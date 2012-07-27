<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('member_moderation');
$pages->page_redir();


$mode = $_POST['mode'];

//Delete User
if(isset($_GET['deleteuser'])){
	$deleteuser = mysql_clean($_GET['deleteuser']);
	$userquery->delete_user($deleteuser);
}

//Deleting Multiple Videos
if($mode=='delete'){
	for($id=0;$id<=count($_POST['check_user']);$id++)
		$userquery->delete_user($_POST['check_user'][$id]);
	$eh->flush();
	e("Selected users have been deleted","m");
}

//Activate User
if(isset($_GET['activate'])){
	$user = mysql_clean($_GET['activate']);
	$userquery->action('activate',$user);
}
//Deactivate User
if(isset($_GET['deactivate'])){
	$user = mysql_clean($_GET['deactivate']);
	$userquery->action('deactivate',$user);
}

//Using Multple Action
if($mode=='activate'){
	for($id=0;$id<=count($_POST['check_user']);$id++){
		$userquery->action('activate',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been activated","m");
}
if($mode=='deactivate'){
	for($id=0;$id<=count($_POST['check_user']);$id++){
		$userquery->action('deactivate',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been deactivated","m");
}
			
//Make User Featured
if(isset($_GET['featured'])){
	$user = mysql_clean($_GET['featured']);
	$userquery->action('featured',$user);
}
//Make User UnFeatured
if(isset($_GET['unfeatured'])){
	$user = mysql_clean($_GET['unfeatured']);
	$userquery->action('unfeatured',$user);
}
//Using Multple Action
if($mode=='featured'){
	for($id=0;$id<=count($_POST['check_user']);$id++){
		$userquery->action('featured',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been set as featured","m");
}
if($mode=='unfeature'){
	for($id=0;$id<=count($_POST['check_user']);$id++){
		$userquery->action('unfeatured',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been removed from featured list","m");
}

//Ban User
if(isset($_GET['ban'])){
	$user = mysql_clean($_GET['ban']);
	$userquery->action('ban',$user);
}
//UnBan User
if(isset($_GET['unban'])){
	$user = mysql_clean($_GET['unban']);
	$userquery->action('unban',$user);
}

//Using Multple Action
if($mode=='ban'){
	for($id=0;$id<=count($_POST['check_user']);$id++){
		$userquery->action('ban',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been banned","m");
}

if($mode=='unban'){
	for($id=0;$id<=count($_POST['check_user']);$id++){
		$userquery->action('unban',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been unbanned","m");
}

			
	//Calling Video Manager Functions
	call_functions($userquery->user_manager_functions);

	//Getting Member List
	$page = mysql_clean($_GET['page']);
	$get_limit = create_query_limit($page,RESULTS);
	
	if(isset($_GET['search']))
	{
		
		$array = array
		(
		 'userid' 	=> $_GET['userid'],
		 'username'	=> $_GET['username'],
		 'category'     => $_GET['category'],
		 'featured'     => $_GET['search_featured'],
		 'ban'		=> $_GET['search_ban'],
		 'status'	=> $_GET['status'],
		 'email'	=> $_GET['email'],
		 'gender'	=> $_GET['gender'],
		 'level'	=> $_GET['level']
		 );		
	}
	
	$result_array = $array;
	//Getting Video List
	$result_array['limit'] = $get_limit;
	if(!$array['order'])
		$result_array['order'] = " doj DESC ";
		
	$users = get_users($result_array);

	Assign('users', $users);	

	//Collecting Data for Pagination
	$mcount = $array;
	$mcount['count_only'] = true;
	$total_rows  = get_users($mcount);
	$total_pages = count_pages($total_rows,RESULTS);
	$pages->paginate($total_pages,$page);
	
	//Pagination
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
                                        'sep'   => '<div></div>',
					'id'=> 'category',
                                        'class' => 'checkbox',
					'value'=> array('category',$cats_array),
					'hint_1'=>  lang('vdo_cat_msg'),
					'display_function' => 'convert_to_categories',
					'category_type'=>'user');
	assign('cat_array',$cat_array);


subtitle("Members Manager");
template_files('members.html');
display_it();
?>
