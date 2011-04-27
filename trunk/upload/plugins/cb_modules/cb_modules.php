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
	
	
	function show_social_bookmarks($vdetails)
	{
		
		echo '<div style="margin-bottom:5px" width="100%">';
		
		echo '<span class="boomarker"><script src="http://www.stumbleupon.com/hostedbadge.php?s=1"></script></span>';
		echo '<span class="boomarker"><td align="center"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></span>';
		echo '<span class=\'boomarker\'><script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script><fb:like href="" layout="button_count" show_faces="true" width="10" font=""></fb:like></span>';
		echo '</div>';
	
		
	}	
	register_anchor_function("show_social_bookmarks","video_bookmarks");
	
	add_header(PLUG_DIR.'/'.this_plugin().'/css.html');
}

?>