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
	assign('p',$userquery->get_user_profile($udetails['userid']));
	
	subtitle(sprintf(lang('user_s_channel'),$udetails['username']));
	
}else{
	e(lang("usr_exist_err"));
	$Cbucket->show_page = false;
}

if($Cbucket->show_page)
Template('view_channel.html');
else
display_it();
?>