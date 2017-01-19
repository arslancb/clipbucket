<?php
/**
 * View User Details
 * @author:Arslan
 * @Since : Oct 16 09
 */
require'../includes/admin_config.php';

if(!$userquery->is_admin_logged_as_user())
{
	$userquery->admin_login_check();
	$userquery->login_check('member_moderation');
}
$pages->page_redir();



if($_GET['revert'])
{
	$userquery->revert_from_user();
	redirect_to(BASEURL.'/admin_area');
}
$uid = $_GET['uid'];

$udetails = $userquery->get_user_details(userid());
$userLevel = $udetails['level'];

$userToLoginAsDetails = $userquery->get_user_details($uid);
$userToLoginAsLevel = $userToLoginAsDetails['level'];

if ($userLevel > 1 && $userToLoginAsLevel == 1) {
	e("You do not have enough permissions to login as Admin user");
}

if($userquery->login_as_user($uid))
	redirect_to(BASEURL);
display_it();

?>