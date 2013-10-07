<?php
/* 
 ******************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ******************************************************************
*/

define("THIS_PAGE",'view_channel');
define("PARENT_PAGE",'channels');


require 'includes/config.inc.php';
$pages->page_redir();
$userquery->perm_check('view_channel',true);


$u = $_GET['user'];
$u = $u ? $u : $_GET['userid'];
$u = $u ? $u : $_GET['username'];
$u = $u ? $u : $_GET['uid'];
$u = $u ? $u : $_GET['u'];
$u = mysql_clean($u);

$udetails = $userquery->get_user_details($u);
if($udetails)
{
	
	//Subscribing User
	if($_GET['subscribe'])
	{
		$userquery->subscribe_user($udetails['userid']);
	}
	
	//Adding Comment
	if(isset($_POST['add_comment']))
	{
		$userquery->add_comment($_POST['comment'],$udetails['userid']);
	}
	//Calling view channel functions
	call_view_channel_functions($udetails);
	
	assign("u",$udetails);
	
	//Getting profile details
	$p = $userquery->get_user_profile($udetails['userid']);
	assign('p',$p);
	
	//Checking Profile permissions
	
	$perms = $p['show_profile'];
	if(userid()!=$udetails['userid'])
	{
		if(($perms == 'friends' || $perms == 'members') && !userid())
		{
			e(lang('you_cant_view_profile'));
			
			if(!has_access('admin_access',true))
				$Cbucket->show_page = false;
		}elseif($perms == 'friends' && !$userquery->is_confirmed_friend($udetails['userid'],userid()))
		{
			e(sprintf(lang('only_friends_view_channel'),$udetails['username']));
			
			if(!has_access('admin_access',true))
				$Cbucket->show_page = false;
		}
		
		//Checking if user is not banned by admin
		if(userid())
		{
			if($userquery->is_user_banned(username(),$udetails['userid'],$udetails['banned_users']))
			{
				e(sprintf(lang('you_are_not_allowed_to_view_user_channel'),$udetails['username']));
				assign('isBlocked','yes');
				if(!has_access('admin_access',true))
					$Cbucket->show_page = false;
			}
		}
	}
	
	
	subtitle(sprintf(lang('user_s_channel'),$udetails['username']));
	
	//Setting profilte item
	$profileItem = $userquery->getProfileItem($udetails['userid'],true);
	
	assign('profile_item',$profileItem);
}else{
	
	if($_GET['seo_diret']!='yes')
	{
		e(lang("usr_exist_err"));
		$Cbucket->show_page = false;
	}else{
		header("HTTP/1.0 404 Not Found");
		if(file_exists(LAYOUT."/404.html")) {
			template_files('404.html');
		} else {
			$data = "404_error";
			if(has_access('admin_access'))
				e(sprintf(lang("err_warning"),"404","http://docs.clip-bucket.com/?p=154"),"w");	
			e(lang($data));
		}
		
		display_it();
		exit();
	}
}
add_js(array('jquery_plugs/compressed/jquery.jCarousel.js'=>'view_channel'));

if($Cbucket->show_page)
	Template('view_channel.html');
else
{
	$Cbucket->show_page = true;
	if($udetails)
	template_files('blocks/view_channel/user_block.html');
	display_it();
}
?>