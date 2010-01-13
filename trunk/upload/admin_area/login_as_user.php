<?php
/**
 * View User Details
 * @author:Arslan
 * @Since : Oct 16 09
 */
require'../includes/admin_config.php';
$pages->page_redir();

if($_GET['revert'])
{
	$userquery->revert_from_user();
	redirect_to(BASEURL.'/admin_area');
}
$userquery->login_check('admin_access');

$uid = $_GET['uid'];

if($userquery->login_as_user($uid))
	redirect_to(BASEURL);
display_it();
?>