<?php
/**
 * FILE : Download
 * Function : Download video
 * @author : Arslan Hassan
 * @Software : ClipBucket
 * @Since : 1 Feb, 2010
 *
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 */
 
define("THIS_PAGE",'download');
define("PARENT_PAGE",'videos');

require 'includes/config.inc.php';
$userquery->perm_check('download_video',true);
$pages->page_redir();

//Getting Video Key
$vkey = @$_GET['v'];
$vdo = $cbvid->get_video($vkey);

if($vdo && video_playable($vkey))
{
	//Calling Functions When Video Is going to download
	call_download_video_function($vdo);
	
	$file = get_video_file($vdo,false);

	if($file)
		$vdo['download_file'] = $file;
	else
	{
		$Cbucket->show_page = false;
		e(lang("unable_find_download_file"));
	}
	assign('vdo',$vdo);
	subtitle("Download ".$vdo['title']);
	
}


//Displaying The Template
template_files('download.html');
display_it();
?>
