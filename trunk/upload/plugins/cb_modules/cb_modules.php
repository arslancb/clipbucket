<?php

/*
Plugin Name: ClipBucket Modules
Description: Social Bookmarks and Recently Viewed Videos - Classic ClipBucet Modules
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2.1
Version: 2
Website: http://labguru.com/
Plugin Type: global
*/
													   
		

if(!function_exists('cb_modules'))
{
	function cb_modules()
	{
	}
	
	
	function show_social_bookmarks($vdetails=NULL)
	{
		
		echo "<span  class='st_stumbleupon_vcount' displayText='Stumble' style='font-size:10px'></span><span  class='st_twitter_vcount' displayText='Tweet'></span><span  class='st_facebook_vcount' displayText='Facebook'></span><span  class='st_plusone_vcount' ></span>";
		echo '<div style="height:10px"></div>';
	
		
	}	
	register_anchor_function("show_social_bookmarks","video_bookmarks");
	
	add_header(PLUG_DIR.'/'.this_plugin().'/css.html');
}

?>