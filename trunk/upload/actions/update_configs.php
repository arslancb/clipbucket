<?php
 /**
  * Written by : Arslan Hassan
  * Software : ClipBucket v2
  * License : CBLA
  **/
require'../includes/admin_config.php';
$userquery->login_check('admin_access');

if(isset($_POST['update_player_size']))
{
	$height = mysql_clean($_POST['height']);
	$width = mysql_clean($_POST['width']);
	if(!is_numeric($height) || $height <=100)
		$height = 100;
	if(!is_numeric($width) || $width <=100)
		$width = 100;
	$myquery->Set_Website_Details('player_height',$height);
	$myquery->Set_Website_Details('player_width',$width);
	echo "Player size has been updated";
}
?>