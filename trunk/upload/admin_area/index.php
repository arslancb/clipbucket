<?php
/* 
 *****************************************************************
 | Copyright (c) 2007-2012 Clip-Bucket.com. All rights reserved.	
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , � PHPBucket.com														|
 *****************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();


//	$latest = get_latest_cb_info();
	$Cbucket->cbinfo['latest'] = $latest;
	if($Cbucket->cbinfo['version'] < $Cbucket->cbinfo['latest']['version'])
		$Cbucket->cbinfo['new_available'] = true;
		



template_files('index.html');
display_it();
?>