<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , � PHPBucket.com					
 ****************************************************************
*/

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
	
	$gpid = mysql_clean($_GET['group_id']);
	
	$gp = $cbgroup->group_exists($gpid);
	
	if($gp)
	{
            $mode = $_GET['mode'];
            if ( $mode ) {
                switch ( $mode ) {
                    case 'feature': {
                        $cbgroup->grp_actions('feature',mysql_clean($_GET['group_id']));
                    }break;
                
                    case 'unfeature': {
                        $cbgroup->grp_actions('unfeature',mysql_clean($_GET['group_id']));
                    }break;
                
                    case 'activate': {
                        $cbgroup->grp_actions('activate',mysql_clean($_GET['group_id']));	
                    }break;
                
                    case 'deactivate': {
                        $cbgroup->grp_actions('deactivate',mysql_clean($_GET['group_id']));
                    }break;
                
                    default: {
                        e(lang('No or unsupported action provided'));
                    }break;
                }
            }
		if(isset($_POST['update_group']))
		{
			$_POST['group_id'] = $gpid;
			$cbgroup->update_group();
			//$group = $cbgroup->get_group_details($gpid);
	
		}
        $group = $cbgroup->get_details($gpid);
        assign('group',$group );
	}else
		e("Group does not exist");


subtitle("Edit Group");
template_files('edit_group.html');
display_it();

?>