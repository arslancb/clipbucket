<?php

/**
 * FIle used to display editor
 * pick video in HTML format
 * that will be passed to JS code
 * and then will be inserted into DIV
 * using JQuery
 */
 
include("../../includes/config.inc.php");


if(isset($_POST['vid']))
{
	$vid = $_POST['vid'];
	$vdetails = get_video_details($vid);
	if($vdetails)
	{
		assign('video',$vdetails);
		$data = Fetch("blocks/editor_pick/video_block.html");
		echo json_encode(array('data'=>$data));
	}else{
		echo json_encode(array('data'=> "<em>No Video</em>"));
	}
}else{
	header("location:".BASEURL);
}

?>