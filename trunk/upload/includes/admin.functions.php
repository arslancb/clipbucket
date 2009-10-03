<?php

/**
 * All Functions that
 * are used by admin,
 * registered here
 */
 
 
//Registering Admin Options for Watch Video

if(has_access('admin_access',TRUE))
{
	function show_video_admin_link($data)
	{
		echo '<a href="'.ADMIN_BASEURL.'/edit_video.php?video='.$data['videoid'].'">Edit Video</a>';
	}
	
	register_anchor_function('show_video_admin_link','watch_admin_options');
}

?>