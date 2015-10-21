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
	
	function show_view_channel_link($data)
	{
		echo '<a href="'.ADMIN_BASEURL.'/view_user.php?uid='.$data['userid'].'">Edit User</a>';
	}

	function check_server_confs() 
	{
		define('POST_MAX_SIZE', ini_get('post_max_size'));
	    define('MEMORY_LIMIT', ini_get('memory_limit'));
	    define('UPLOAD_MAX_FILESIZE', ini_get('upload_max_filesize'));
	    define('MAX_EXECUTION_TIME', ini_get('max_execution_time'));

		if ( POST_MAX_SIZE > 50 && MEMORY_LIMIT > 128 && UPLOAD_MAX_FILESIZE > 50 && MAX_EXECUTION_TIME > 7200 ) 
		{
			define("SERVER_CONFS", true);
		} 
		elseif ( POST_MAX_SIZE < 50 || MEMORY_LIMIT < 128 || UPLOAD_MAX_FILESIZE || 50 && MAX_EXECUTION_TIME || 7200 )
		{
			e('You must update <strong>"Server Configurations"</strong>. Click here <a href='.BASEURL.'/admin_area/cb_server_conf_info.php>for details</a>',w);
			define("SERVER_CONFS", false);
		}
		else 
		{
			define("SERVER_CONFS", false);
		}
	}

	register_anchor_function('show_video_admin_link','watch_admin_options');
	register_anchor_function('show_view_channel_link','view_channel_admin_options');
}

?>