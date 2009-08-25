<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author 	: ArslanHassan																		|
 | @ Software 	: ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

$page = $pages->show_admin_page(clean(@$_GET['settings']));
if(!empty($page)){
$pages->redirect($page);
}
if(@$_GET['msg']){
$msg = clean(@$_GET['msg']);
}
//Show Requested Options
$property_values = array(	'showall' 		=> 'Show All',
							'inactive'		=> 'Inactive Only',
							'active'		=> 'Active Only',
							'addmember'		=> 'Add Member',
							'search'		=> 'Search Members'
						);
				  
$view = clean(@$_GET['view']);

if(empty($view)){ $view = 'showall'; }
while(list($property['value'],$property['name']) = each($property_values)){
	if($property['value'] == $view){
	DoTemplate::assign("property",$property);
	}
}

//-------TIME TO DO SOME ACTION-------//

//Delete User
if(isset($_GET['deleteuser'])){
	$deleteuser = mysql_clean($_GET['deleteuser']);
	if($userquery->Check_User_Exists($deleteuser)){
	if($userquery->DeleteUser($deleteuser)){
	$msg[] = 'User Has Been Deleted Successfully';
	}else{
	$msg[] = 'Error Deleting User';
	}
	}
}

//Deleting Multiple Videos
if(isset($_POST['delete_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					if(@$userquery->Check_User_Exists($_POST['check_user'][$id])){
						$userquery->DeleteUser($_POST['check_user'][$id]);
					}
				}
			$msg = "Selected Users Have Been Deleted";
}

//Activate User
if(isset($_GET['activate'])){
	$user = mysql_clean($_GET['activate']);
	if($userquery->Check_User_Exists($user)){
		$userquery->Activate($user);
		$msg[] = 'User Has Been Activated';
		}
}
//Deactivate User
if(isset($_GET['deactivate'])){
	$user = mysql_clean($_GET['deactivate']);
	if($userquery->Check_User_Exists($user)){
		$userquery->DeActivate($user);
		$msg[] = 'User Has Been Deactivated';
		}
}

//Using Multple Action
			if(isset($_POST['activate_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$userquery->Activate($_POST['check_user'][$id]);
				}
			$msg = "Selected Members Have Been Activated";
			}
			if(isset($_POST['deactivate_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$userquery->DeActivate($_POST['check_user'][$id]);
				}
			$msg = "Selected Members Have Been Dectivated";
			}
			
//Make User Featured
if(isset($_GET['featured'])){
	$user = mysql_clean($_GET['featured']);
	if($userquery->Check_User_Exists($user)){
		$userquery->MakeFeatured($user);
		$msg[] = 'User Has Been Made Featured Member';
		}
}
//Make User UnFeatured
if(isset($_GET['unfeatured'])){
	$user = mysql_clean($_GET['unfeatured']);
	if($userquery->Check_User_Exists($user)){
		$userquery->MakeUnFeatured($user);
		$msg[] = 'User Has Been Unfeatured';
		}
}

//Using Multple Action
			if(isset($_POST['make_featured_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$userquery->MakeFeatured($_POST['check_user'][$id]);
				}
			$msg = "Selected Users Have Been Set As Featured";
			}
			if(isset($_POST['make_unfeatured_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$userquery->MakeUnFeatured($_POST['check_user'][$id]);
				}
			$msg = "Selected Users Have Been Removed From The Featured List";
			}

//Ban User
if(isset($_GET['ban'])){
	$user = mysql_clean($_GET['ban']);
	if($userquery->Check_User_Exists($user)){
		$userquery->ban($user);
		$msg[] = 'User Has Been Banned';
		}
}
//UnBan User
if(isset($_GET['unban'])){
	$user = mysql_clean($_GET['unban']);
	if($userquery->Check_User_Exists($user)){
		$userquery->unban($user);
		$msg[] = 'User Has Been Unbanned';
		}
}

//Using Multple Action
			if(isset($_POST['ban_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$userquery->ban($_POST['check_user'][$id]);
				}
			$msg = "Selected Members Have Been Banned";
			}
			if(isset($_POST['unban_selected'])){
				for($id=0;$id<=RESULTS;$id++){
					$userquery->unban($_POST['check_user'][$id]);
				}
			$msg = "Selected Members Have Been Unbanned";
			}
			
//-------TIME END TO DO SOME ACTION-------//

//Form Processing And Validation

	//User Registration Form Processing and Validation
		if(isset($_POST['button'])){
			$msg = $signup->Admin_Add_User();
			}
			
//Assigning Default Values in Form
@$values= array(
	'default_uname' 	=> mysql_clean($_POST['username']),
	'default_email'		=> mysql_clean($_POST['email']),
	'default_pass' 		=> pass_code(mysql_clean($_POST['password'])),
	'default_fname'		=> mysql_clean($_POST['fname']),
	'default_lname'		=> mysql_clean($_POST['lname']),
	'default_gender'	=> mysql_clean($_POST['gender']),
	'default_level'		=> mysql_clean($_POST['level']),
	'default_m'			=> $_POST['month'],
	'default_d'			=> $_POST['day'],
	'default_y'			=> $_POST['year'],
	'default_ht'		=> mysql_clean($_POST['hometown']),
	'default_city'		=> mysql_clean($_POST['city']),
	'default_country' 	=> $_POST['country'],
	'default_zip'		=> mysql_clean($_POST['zip'])
	);
	while(list($name,$value) = each($values)){
	DoTemplate::assign($name,$value);
	}
	
	@$values_search= array(
	'search_uname' 		=> mysql_clean($_GET['username']),
	'search_email'		=> mysql_clean($_GET['email']),
	'search_fname'		=> mysql_clean($_GET['fname']),
	'search_lname'		=> mysql_clean($_GET['lname']),
	'search_country' 	=> mysql_clean($_GET['country']),
	'search_status'		=> mysql_clean($_GET['status']),
	'search_sort'		=> mysql_clean($_GET['sort']),
	'search_order'		=> mysql_clean($_GET['order'])
	);
	while(list($name,$value) = each($values_search)){
	DoTemplate::assign($name,$value);
	}

//Jump To The page
if(isset($_POST['display_page'])){
	redirect_to($_POST['page_number']);
}

//Users Array
	$limit  = RESULTS;	
	Assign('limit',$limit);
	$page   = clean(@$_GET['page']);
	if(empty($page) || $page == 0){
	$page   = 1;
	}
	$from 	= $page-1;
	$from 	= $from*$limit;

	$query_limit  = "limit $from,$limit";
	$order 	= "ORDER BY doj DESC";

		$sql 	= "SELECT * from users $order $query_limit";
		$sql_p	= "SELECT * from users";
		if(empty($view) || $view == 'showall'){
		$sql = "SELECT * from users $order $query_limit";
		}
		if($view == 'inactive'){
		$sql = "SELECT * from users WHERE usr_status='ToActivate' $order $query_limit";
		$sql_p	= "SELECT * from users WHERE usr_status='ToActivate'";
		}
		if($view == 'active'){
		$sql = "SELECT * from users WHERE usr_status='OK' $order $query_limit";
		$sql_p	= "SELECT * from users WHERE usr_status='OK'";
		}
		
//Search
if(isset($_GET['search'])){
	$username	= mysql_clean($_GET['username']);
	$email		= mysql_clean($_GET['email']);
	$fname		= mysql_clean($_GET['fname']);
	$lname		= mysql_clean($_GET['lname']);
	$country	= mysql_clean($_GET['country']);
	$status		= mysql_clean($_GET['status']);
	$sort		= mysql_clean($_GET['sort']);
	$order		= mysql_clean($_GET['order']);	
	
	if($order == 'ASC'){
		if($sort == 'username'){$orderby 	= 'ORDER BY username ASC';}
		if($sort == 'doj'){		$orderby 	= 'ORDER BY doj ASC';}
		if($sort == 'country'){	$orderby 	= 'ORDER BY country ASC';}
		if($sort == 'lname'){	$orderby 	= 'ORDER BY last_name ASC';}
		if($sort == 'fname'){	$orderby 	= 'ORDER BY first_name ASC';}
	}else{
		if($sort == 'username'){$orderby 	= 'ORDER BY username DESC';}
		if($sort == 'doj'){		$orderby 	= 'ORDER BY doj DESC';}
		if($sort == 'country'){	$orderby 	= 'ORDER BY country DESC';}
		if($sort == 'lname'){	$orderby 	= 'ORDER BY last_name DESC';}
		if($sort == 'fname'){	$orderby 	= 'ORDER BY first_name DESC';
}
	}
	
	
	$sql	 = "SELECT * from users ";
	$sql	.= "WHERE username 	like '%$username%' AND 
	email 		like '%$email%' AND
	first_name  like '%$fname%' AND
	last_name   like '%$lname%' AND
	country 	like '%$country%' AND
	usr_status  like '%$status%' 
	$orderby $query_limit
	";
	$sql_p = "SELECT * from users WHERE username 	like '%$username%' AND 
	email 		like '%$email%' AND
	first_name  like '%$fname%' AND
	last_name   like '%$lname%' AND
	country 	like '%$country%' AND
	usr_status  like '%$status%' 
	$orderby ";
	}

//Assing User Data Values

		$rs = $db->Execute($sql);
		$total = $rs->recordcount() + 0;
		$users = $rs->getrows();
		
		for($id=0;$id<$total;$id++){
		$users[$id]['age'] = $calcdate->age($users[$id]['dob']);
		$users[$id]['total_videos'] = $userquery->TotalVideos($users[$id]['username']);
		$users[$id]['total_friends'] = $userquery->TotalFriends($users[$id]['username']);
		$users[$id]['total_groups'] = $userquery->TotalGroups($users[$id]['username']);
		}
		Assign('total', $total + 0);
		Assign('user', $users);
	
//Pagination #A Tough Job#
if($view == 'search'){
@$link = '&amp;username=' .mysql_clean($_GET['username']). '&amp;email=' .mysql_clean($_GET['email']).'&amp;fname=' .mysql_clean($_GET['fname']).'&amp;lname=' .mysql_clean($_GET['lname']).'&amp;country='.mysql_clean($_GET['country']).'&amp;status='.mysql_clean($_GET['status']).'&amp;sort='.mysql_clean($_GET['sort']).'&amp;order='.mysql_clean($_GET['order']).'&amp;search='.mysql_clean($_GET['search']);
Assign('link',$link);
}	
	
Assign('msg',@$msg);
Template('header.html');
Template('leftmenu.html');
Template('message.html');
Template('members.html');
Template('footer.html');

?>
