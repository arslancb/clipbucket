<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.						
 | @ Author : ArslanHassan													
 | @ Software : ClipBucket , © PHPBucket.com								
 ***************************************************************
*/

define("THIS_PAGE","invite_group");
define("PARENT_PAGE","groups");

require 'includes/config.inc.php';
$pages->page_redir();

$url = mysql_clean($_GET['url']);


$details = $cbgroup->group_details_url($url);
assign('group',$details);

if(!$cbgroup->is_owner($details))
	e(lang("you_cant_invite_mems"));
else
{
	if($details)
	{
		assign('friends',$userquery->get_contacts(userid(),0));
	}
	
		
	//Inviting Friends
	if(isset($_POST['invite_friends']))
	{
		$cbgroup->invite_members($_POST['friend'],$details['group_id']);
	}
	
	
	assign('mode',"invite_group");
	template_files('view_group.html');
	subtitle(lang("grp_invite_msg"));
	display_it();
}
?>