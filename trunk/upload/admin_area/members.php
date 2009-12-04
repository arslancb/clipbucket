<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2009 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->login_check('member_moderation');
$pages->page_redir();


//Delete User
if(isset($_GET['deleteuser'])){
	$deleteuser = mysql_clean($_GET['deleteuser']);
	$userquery->delete_user($deleteuser);
}

//Deleting Multiple Videos
if(isset($_POST['delete_selected'])){
	for($id=0;$id<=RESULTS;$id++)
		$userquery->delete_user($deleteuser);
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
if(isset($_POST['activate_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$userquery->action('activate',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been activated","m");
}
if(isset($_POST['deactivate_selected'])){
	for($id=0;$id<=RESULTS;$id++){
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
if(isset($_POST['make_featured_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$userquery->action('featured',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been set as featured","m");
}
if(isset($_POST['make_unfeatured_selected'])){
	for($id=0;$id<=RESULTS;$id++){
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
if(isset($_POST['ban_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$userquery->action('ban',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been banned","m");
}

if(isset($_POST['unban_selected'])){
	for($id=0;$id<=RESULTS;$id++){
		$userquery->action('unban',$_POST['check_user'][$id]);
	}
	$eh->flush();
	e("Selected users have been unbanned","m");
}

			
//-------TIME END TO DO SOME ACTION-------//


//Getting Member List
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);
$users = $db->select("users",'*',$cond,$get_limit,"doj DESC");
Assign('users', $users);	

//Collecting Data for Pagination
$total_rows  = $db->count('users','*',$cond);
$total_pages = count_pages($total_rows,VLISTPP);

//Pagination
$pages->paginate($total_pages,$page);
	
template_files('members.html');
display_it();
?>
