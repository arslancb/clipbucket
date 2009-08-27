<?php
if(!$groups->ExistsURL($url) || $url == 'Array'){
	$msg = $LANG['grp_exist_error'];
	$show_group = 'No';
	Assign('subtitle',@$details['group_name']);
	Assign('msg',$msg);
	Assign('show_group',$show_group);
	Template('header.html');
	Template('message.html');	
	//Template('group_header.html');
	Template('add_group_videos.html');
	Template('footer.html');
	exit();
}