<?php

/**
 * Embed player system has been totallay changed in 
 * ClipBucket 2.0.8
 *
 * Each Embedded Code will have this file included
 * this will redirect the file to proper embedable payer file
 * it will take $_GET paramters, do the proper actions and redirect it
 *
 * @Author : Arslan Hassan
 *
 */
 
 
 include("../includes/config.inc.php");
 
 $vid = mysql_clean(get('vid'));
 $vdetails = get_video_details($vid);
 
 if(!$vdetails)
 {
	 e(lang('class_vdo_exist_err'));
	 display_it();
	 exit();
 }
	//Calling Src Functions, if there any...
	$funcs = $cbvid->embed_src_func_list;
	if(is_array($funcs))
	{
		foreach($funcs as $func)
		{
			if(@function_exists($func))
			$embed_code = $func($vdetails);
		}
	}
 	
	//Simply Display Pak Player if there is nothing to do..
	redirect_to(pakplayer_embed_src($vdetails));
 
?>