<?php
/** 
 * CLipBucket v2 Player Manage
 * Author : Arslan
 *
 * Licensed Under CBLA
 * ClipBucket 2007-2009
 */
 
require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
$userquery->login_check('admin_access');

//Set Mode
assign('mode',$_GET['mode']);

if(isset($_POST['update'])){
	$configs = $Cbucket->configs;
	
	
	$rows = array(
				  	'autoplay_video',
					'buffer_time',
					'logo_placement',
					'use_playlist',
					'youtube_enabled',
					'embed_player_height',
					'embed_player_width','autoplay_embed','pseudostreaming','pak_license','pakplayer_contextmsg'
					);
	
	//Checking for logo
	if(isset($_FILES['logo_file']['name']))
	{
		$logo_file = $Upload->upload_website_logo($_FILES['logo_file']);
		if($logo_file)
			$myquery->Set_Website_Details('player_logo_file',$logo_file);
	}
	
	
	if($_POST['pak_license'] && !file_exists(BASEDIR.'/player/pak_player/pakplayer.unlimited.swf'))
	$_POST['pak_license'] = "";	
	
	foreach($rows as $field)
	{
		$value = mysql_clean($_POST[$field]);
		$myquery->Set_Website_Details($field,$value);
	}
	e("Player Settings Have Been Updated",'m');
	
}


if($_GET['set'])
{
	$cbplayer->set_player($_GET);
}

$row = $myquery->Get_Website_Details();
Assign('row',$row);

subtitle("Manage Players");
template_files('manage_players.html');
display_it();
?>