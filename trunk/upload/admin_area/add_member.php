<?php

/**
 * @Software : ClipBucket
 * @Author : Arslan Hassan
 * @Since : Jan 5 2009
 * @Function : Add Member
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 */
 

require'../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('member_moderation');
$pages->page_redir();

if(isset($_POST['add_member']))
{
	if($userquery->signup_user($_POST))
	{
		e(lang("new_mem_added"),"m");
		$_POST = '';
	}
}

subtitle("Add New Member");
template_files('add_members.html');
display_it();

?>