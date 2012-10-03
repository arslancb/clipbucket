<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author	   : ArslanHassan									
 | @ Software  : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE","edit_photo");
define("PARENT_PAGE",'photos');

require 'includes/config.inc.php';
$userquery->login_check('edit_video');

$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));

photo_manager_link_callbacks();

$pid = mysql_clean($_GET['photo']);
$photo = $cbphoto->get_photo($pid, true);

if(empty($photo))
	e(lang("photo_not_exists"));
elseif($photo['userid'] != userid())
{
	e(lang("You can not edit this photo."));
	$Cbucket->show_page = false;	
} else {
	if(isset($_POST['update_photo']))
	{
            if ( $photo['is_avatar'] == 'yes' ) {
                echo 'Temporarily you can can not update your avatar details';
                return false;
            }
		$cbphoto->update_photo();
		$photo = $cbphoto->get_photo( $pid, true );	
	}
          
    assign('p',$photo);
}


subtitle(lang("Edit Photo"));
template_files('edit_photo.html');
display_it();
?>