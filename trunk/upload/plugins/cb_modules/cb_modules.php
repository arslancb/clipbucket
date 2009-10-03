<?php

/*
Plugin Name: ClipBucket Modules
Description: Social Bookmarks and Recently Viewed Videos - Classic ClipBucet Modules
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
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
		$social_boookmarks = array
		(
		'digg'		=> 'http://digg.com/submit?phase=2&url={URL}&title={TITLE}',
		'delicious' => 'http://del.icio.us/post?url={URL}&title={TITLE}',
		'stumbleupon'=> 'http://www.stumbleupon.com/submit?url={URL}&title={TITLE}',
		'reddit'	=> 'http://reddit.com/submit?url={URL}&title={TITLE}',
		'technorati'=> 'http://technorati.com/cosmos/search.html?url={URL}',
		'facebook'	=> 'http://www.facebook.com/share.php?u={URL}'
		);
		echo '<div style="margin:5px;">';
		foreach($social_boookmarks as $bookmark=>$link)
		{
			$link = preg_replace(array("/{URL}/","/{TITLE}/"),array(videolink($vdetails),$vdetails['title']),$link);
			
			echo '<a href="'.$link.'"><img src="'.PLUG_URL.'/cb_modules/images/social_icons/'.$bookmark.'.png" border="0"></a> ';
		}
		echo '</div>';
	}
	
	
	register_anchor_function("show_social_bookmarks","video_bookmarks");
}

?>