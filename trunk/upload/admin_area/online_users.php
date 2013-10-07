<?php
/* 
 ***********************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.		
 | @ Author 	: ArslanHassan												
 | @ Software 	: ClipBucket , © PHPBucket.com							
 *************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

if($_GET['kick'])
{
	if($sess->kick(mysql_clean($_GET['kick'])))
	{
		e("User has been kicked out","m");
	}
}

$online_users = $userquery->get_online_users(false);
assign('total',count($online_users));
assign('online_users',$online_users);
assign('queryString',queryString(NULL,'kick'));
subtitle("View online users");
template_files('online_users.html');
display_it();


?>