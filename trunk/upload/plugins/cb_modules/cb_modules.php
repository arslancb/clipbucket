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
		
		echo '<table style="margin-bottom:5px" width="100%"><tr><td align="center" height="20">';
		echo '<a name="fb_share" type="button-count" ></a> 
<script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" 
        type="text/javascript">
</script></td>';
	echo "<td align=\"center\"><script type=\"text/javascript\">
		(function() {
		var s = document.createElement('SCRIPT'), s1 = document.getElementsByTagName('SCRIPT')[0];
		s.type = 'text/javascript';
		s.async = true;
		s.src = 'http://widgets.digg.com/buttons.js';
		s1.parentNode.insertBefore(s, s1);
		})();
		</script>
		<a class=\"DiggThisButton DiggCompact\"></a></td>";
		echo '<td align="center"><a title="Post to Google Buzz" class="google-buzz-button" href="http://www.google.com/buzz/post" data-button-style="small-count"></a>
<script type="text/javascript" src="http://www.google.com/buzz/api/button.js"></script></td>';
		echo '<td align="center"><script src="http://www.stumbleupon.com/hostedbadge.php?s=1"></script></td>';
		echo '<td align="center"><a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></td>';
		echo '</tr></table>';
	
		
	}
	
	
	register_anchor_function("show_social_bookmarks","video_bookmarks");
	
}

?>