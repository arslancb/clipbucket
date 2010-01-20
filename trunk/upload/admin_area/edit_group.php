<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
	
	$gpid = mysql_clean($_GET['group_id']);
	
	$group = $cbgroup->get_details($gpid);
	
	if($group)
	{
		if(isset($_POST['update_group']))
		{
			$_POST['group_id'] = $gpid;
			$cbgroup->update_group();
			$group = $cbgroup->get_group_details($gid);
	
		}
		assign('group',$group );
	}else
		e("Group does not exist");


subtitle("Edit Group");
template_files('edit_group.html');
display_it();

?>