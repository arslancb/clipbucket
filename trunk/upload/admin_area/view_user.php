<?php
/**
 * View User Details
 * @author:Arslan
 * @Since : Oct 16 09
 */
require'../includes/admin_config.php';
$pages->page_redir();
$userquery->login_check('member_moderation');

$uid = $_GET['uid'];
$udetails = $userquery->get_user_details($uid);

if($udetails)
{
	if(isset($_POST['update_user']))
	{
		$userquery->update_user($_POST);
		if(!error())
		$udetails = $userquery->get_user_details($uid);
	}
	
	assign('u',$udetails);
	assign('p',$userquery->get_user_profile($udetails['userid']));
}else{
	e("No User Found");
	$CBucket->show_page = false;
}

subtitle("View User");
template_files("view_user.html");
display_it();
?>