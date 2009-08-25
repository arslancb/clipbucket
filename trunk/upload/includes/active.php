<?php
$scriptName = @$_SERVER['SCRIPT_NAME'];
$arrayScriptName = explode("/",$scriptName);
if (sizeof($arrayScriptName) > 0){
	$scriptName 		= $arrayScriptName[(sizeof($arrayScriptName)-1)];
	$homecurrent 		= "";
	$uploadcurrent 		= "";
	$watchcurrent 		= "";
	$acountactive 		= "";
	$friendcurrent 		= "";
	$channelscurrent	= "";
	$communitycurrent 	= "";

	$listCurrent = array(
	"activation.php"			=> "signupactive",
	"channels.php"				=> "channelactive",
	"index.php"					=> "homeactive",
	"myaccount.php"				=> "accountactive",
	"signup.php"				=> "signupactive",
	"signup_success.php"		=> "signupactive",
	"upload.php"				=> "uploadactive",
	"user_account.php"			=> "accountactive",
	"user_videos.php"			=> "watchactive",
	"user_fav_videos.php"		=> "watchactive",
	"user_contacts.php"			=> "channelactive",
	"videos.php"				=> "watchactive",
	"view_channel.php"			=> "channelactive",
	"watch_video.php"			=> "watchactive",
	"inbox.php"					=> "accountactive",
	"sent.php"					=> "accountactive",
	"groups.php"				=> "groupactive",
	"view_group.php"			=> "groupactive",
	"view_group_videos.php"		=> "groupactive",
	"view_group_members.php"	=> "groupactive",
	"edit_group.php"			=> "groupactive",
	"manage_group_videos.php"	=> "groupactive",
	"manage_group_members.php"	=> "groupactive",
	"invite_group.php"			=> "groupactive",
	"join_group.php"			=> "groupactive",
	"add_group_video.php"		=> "groupactive",
	"view_topic.php"			=> "groupactive",
	"manage_groups.php"			=> "groupactive",
	"create_group.php"			=> "groupactive",
	"view_channel.php"			=> "view_channel_active",
	"login_success.php"			=> "login_success",
	"logout_success.php"		=> "logout_success"
	
	   );
	$resultCurrent = @$listCurrent[$scriptName] ; 
	/*if ($resultCurrent != ""){
		${$resultCurrent} = " id='currentTab' ";
	}
	else{
		$homeactive = " id='currentTab' ";
	} */
    if ($resultCurrent != ""){
		${$resultCurrent} = "current";
		Assign('curActive',$resultCurrent);
	}
	else{
		$global = "current";
	} 
	
	@Assign('accountactive',$accountactive);	
	@Assign('homeactive',$homeactive);
	@Assign('signupactive',$signupactive);
	@Assign('uploadactive',$uploadactive);
	@Assign('watchactive',$watchactive);
	@Assign('channelactive',$channelactive);
	@Assign('groupactive',$groupactive);
	@Assign('view_channel_active',$view_channel_active);
}
?>