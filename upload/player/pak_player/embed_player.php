<?php


define("CLEAN_BASEURL","/\/player\/pak_player/");
include("../../includes/config.inc.php");


$vid = $_GET['vid'];
//gettin video details
$vdetails = get_video_details($vid);

if(!$vdetails)
	exit(json_encode(array("err"=>"no video details found")));
	
if(!function_exists('embed_video_src'))
{
	function embed_video_src($params)
	{
		$pseudo_streaming = false;
		$ppfile = 'pakplayer.swf';
		
		if(config('pak_license'))
		{
			$ppfile = 'pakplayer.unlimited.swf';
		}
		
		$video = $params['video'];
		$array = array();
		
		$plugins['controls']['url'] = BASEURL.'/player/pak_player/pakplayer.controls.swf';
		$plugins['controls']['background'] = 'url('.BASEURL.'/player/pak_player/bg.png) repeat';
		
		
		$canvas['backgroundColor'] = "#000000";
		$canvas['backgroundGradient'] = "none";
		
		/* Checking for youtube */
		$ref = $video['refer_url'];
		//Trying other method
		if(function_exists('get_refer_url_from_embed_code'))
		{
			$ref_details = get_refer_url_from_embed_code(unhtmlentities(stripslashes($video['embed_code'])));
			$ytcode = $ref_details['ytcode'];
		}
						
		
		if($ytcode)
		{
			$plugins['youtube']['url'] = BASEURL.'/player/pak_player/pakplayer.youtube.swf';
			$plugins['youtube']['enableGdata'] = true;
			
			$clip['url'] = 'api:'.$ytcode;
			$clip['provider'] = 'youtube';
			$clip['urlResolvers'] = 'youtube';
			
		}else
		{
			if(config('pseudostreaming')=='yes')
			{
				$plugins['pseudo']['url'] = BASEURL.'/player/pak_player/pakplayer.pseudo.swf';
				$clip['provider'] = 'pseudo';
				$pseudo_streaming = true;
			}
			$clip['url'] = get_video_file($video,true,true);
		}
		
		/* End Checking Youtube */
	
		// Setting AutoPlay
		if($_GET['autoplay'])
		{
			$autoplay = $_GET['autoplay'];
		}else
		{
			$autoplay = $_GET['autoplay_embed'];
		}
		
		if($autoplay=='yes')
			$autoplay = true;
		else
			$autoplay = false;
			
			
		$clip['scaling'] = 'fit';				
		$clip['autoPlay'] = $autoplay;
		$clip['linkUrl'] = videoLink($video);
		$clip['linkWindow'] = '_blank';
		
		$logo['url'] = website_logo();
		$logo['fullscreenOnly'] = false;
		
		$logoPlace = config('logo_placement');
		$padding = config('logo_padding');
		switch($logoPlace)
		{
			case "tr":
			$logo['top'] = $padding; $logo['right'] = $padding;
			break;
			case "tl":
			$logo['top'] = $padding; $logo['left'] = $padding;
			break;
			case "br":
			$logo['bottom'] = $padding; $logo['right'] = $padding;
			break;
			case "bl":
			$logo['bottom'] = $padding; $logo['left'] = $padding;
			break;
		}

		
		$logo['opacity'] = 0.4;
		$logo['linkUrl'] = videoLink($video);	
		
		
		$array['key'] = config("pak_license");
		$array['plugins'] = $plugins;
		$array['canvas'] = $canvas;
		$array['clip'] = $clip;
		$array['contextMenu'][][config("pakplayer_contextmsg")] = "";
		$array['logo']  = $logo;
		
		if(!$params['only_configs'])
			return BASEURL.'/player/pak_player/'.$ppfile.'?config='.json_encode($array);
		else
			return json_encode($array);
	}
	
}

if($_GET['json'])
echo embed_video_src(array("video" => get_video_details($vid),'only_configs'=>true));
else
echo embed_video_src(array("video" => get_video_details($vid),'only_configs'=>false));
?>