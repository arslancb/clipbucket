<?php
/**
 * @author : Arslan Hassan
 * @software : ClipBucket
 * @since : June 20 2009
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @URI : http://clip-bucket.com
 */

#Including Maing file and checking user level
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
$userquery->login_check('admin_access');

$mode = $_GET['mode'];
$lid = mysql_clean($_GET['lid']);
$action = mysql_clean($_GET['action']);

//Deleteing Level
if($action=='delete')
	$userquery->delete_user_level($lid);
	
switch($mode)
{
	case 'view':
	default:
	{
		Assign('view','view');
	}
	break;
	
	case 'edit':
	{
		//Updating Level permissions
		if(isset($_POST['update_level_perms']))
		{
			$perm_array = $_POST;
			$userquery->update_user_level($lid,$perm_array);
		}
		
		
		//Getting Details of $level
		$levelDetails = $userquery->get_level_details($lid);
		Assign('level_details',$levelDetails);
		
		//GettinG Level Permission
		$level_perms = $userquery->get_level_permissions($lid);
		
		$plugin_perms = $level_perms['plugins_perms'];
		$plugin_perms = json_decode($plugin_perms,true);
		
		assign('plugin_perms',$plugin_perms);
		
		Assign('level_perms',$level_perms);
		
		Assign('view','edit');
	}	
	break;
	
	case 'add':
	{
		if(isset($_POST['add_new_level']))
		{
			$array = $_POST;
			if($userquery->add_user_level($array))
				redirect_to('user_levels.php?added=true');
		}
		Assign('view','add');
	}
}

subtitle("User levels");
template_files('user_levels.html');
display_it();
?>