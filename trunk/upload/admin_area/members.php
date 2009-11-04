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
