<?php
 /**
  * Written by : Arslan Hassan
  * Software : ClipBucket v2
  * License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
  **/
require'../includes/admin_config.php';
$userquery->login_check('admin_access');

if($_POST['update_player_size']=='yes')
{
	$height = mysql_clean($_POST['height']);
	$width = mysql_clean($_POST['width']);
	$config_name = mysql_clean($_POST['width']);
	if(!is_numeric($height) || $height <=100)
		$height = 100;
	if(!is_numeric($width) || $width <=100)
		$width = 100;
	$myquery->Set_Website_Details('player_height',$height);
	$myquery->Set_Website_Details('player_width',$width);
	echo "Player size has been updated";
}
if($_POST['update_channel_player_size']=='yes')
{
	$height = mysql_clean($_POST['height']);
	$width = mysql_clean($_POST['width']);
	$config_name = mysql_clean($_POST['width']);
	if(!is_numeric($height) || $height <=100)
		$height = 100;
	if(!is_numeric($width) || $width <=100)
		$width = 100;
	$myquery->Set_Website_Details('channel_player_height',$height);
	$myquery->Set_Website_Details('channel_player_width',$width);
	echo "Channel Player size has been updated";
}
?>