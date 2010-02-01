<?php
/*
Plugin Name: Comment BBCode
Description: SAMPLE PLUGIN FOR COMMENT AND DESCRIPTION MODIFICATION
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 1.8
Version: 1.0
Website: http://labguru.com/
Plugin Type: global
*/


if(!function_exists('bb_to_html'))
{
	function bb_to_html($comment)
	{
		
	 //Replaceing Image Code
	 $img_patter = '/\[img\](.*)\[\/img\]/';
	 $img_replace = '<img src="$1" />';
	 $coded_comment = preg_replace($img_patter,$img_replace,$comment);
	 
	 $bbcodes = array
	 (
	  '/\[b\]/','/\[i\]/','/\[u\]/','/\[quote\]/','/\[url\](.*)\[\/url\]/',
	  '/\[\/b\]/','/\[\/i\]/','/\[\/u\]/','/\[\/quote\]/','/\[url=(.*)\](.*)\[\/url\]/'
	  );
	 $HTMLcodes = array
	 (
	  '<strong>','<em>','<u>','<blockquote>','<a href="$1">$1</a>',
	  '</strong>','</em>','</u>','</blockquote>','<a href="$1">$2</a>',
	  );
	 
	 $coded_comment = preg_replace($bbcodes,$HTMLcodes,$coded_comment);
	 
	 return $coded_comment;
	}
}

//Registering Action that will be applied while displaying comment and or description
register_action(array('bb_to_html'=>array('comment','description','pm_compose_box','before_topic_post_box','private_message')));

//Registerin Anchors , that will be displayed before compose boxes
register_anchor("<script>edToolbar('comment_box'); </script>",'before_compose_box');
register_anchor("<script>edToolbar('comment_box-reply'); </script>",'before_reply_compose_box');
register_anchor("<script>edToolbar('desc'); </script>",'before_desc_compose_box');
register_anchor("<script>edToolbar('pm_content'); </script>",'before_pm_compose_box');
register_anchor("<script>edToolbar('topic_post'); </script>",'before_topic_post_box');

//Adding JS Code
$Cbucket->addJS(array('bbcode_js/ed.js'=>'global'));
//Creating Menu In Admin Panel
//add_admin_menu('ClipBucket BBCode','Manage BBCodes','admin_bbcoder.php');

?>