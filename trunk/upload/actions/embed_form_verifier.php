<?php

/**
 * This file is used to verify embed form
 * @author : Arslan Hassan
 */

include("../includes/config.inc.php");

$mode = $_POST['mode'];

switch($mode)
{
	case "remote_play":
	{
		$duration = $_POST['duration'];
		$thumb_file = $_POST['thumb_file'];
		$url = $_POST['remote_play_url'];
		$ext = strtolower(getExt($thumb_file));
		
		validate_duration($duration);
		check_remote_play_link($url);
		
		if(empty($thumb_file) || ( $ext != 'jpg' && $ext != 'png' && $ext !='gif'))
			e(lang("pelase_select_img_file_for_vdo"));
		if(count($eh->error_list>0))
			$array['err'] = $eh->error_list[0];
		
		echo json_encode($array);
	}
	break;
	
	case "embed":
	default:
	{
		$embed_code = $_POST['embed_code'];
		$duration = $_POST['duration'];
		$thumb_file = $_POST['thumb_file'];
		$ext = strtolower(getExt($thumb_file));
		
		validate_embed_code($embed_code);
		validate_duration($duration);
		
		if(empty($thumb_file) || ( $ext != 'jpg' && $ext != 'png' && $ext !='gif'))
			e(lang("pelase_select_img_file_for_vdo"));
		if(count($eh->error_list>0))
			$array['err'] = $eh->error_list[0];
		
		echo json_encode($array);
	}
}

?>