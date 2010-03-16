<?php

/**
 * Manage Playlist Page
 * @author : Arslan hassan
 * @Software: ClipBucket
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 */


require 'includes/config.inc.php';
$userquery->logincheck();
$udetails = $userquery->get_user_details(userid());
assign('user',$udetails);
assign('p',$userquery->get_user_profile($udetails['userid']));


$mode = $_GET['mode'];

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,VLISTPP);


switch($mode)
{
	case 'manage_playlist':
	case 'manage_video_playlist':
	default:
	{
		
		//Deleting Playlist
		if(!empty($_GET['delete_pl']))
		{
			$plid = mysql_clean($_GET['delete_pl']);
			$cbvid->action->delete_playlist($plid);
		}
		
		//Adding New Playlist
		if(isset($_POST['add_playlist']))
		{
			$params = array('name'=>mysql_clean($_POST['name']));
			$cbvid->action->create_playlist($params);
		}
		
		assign('mode','manage_playlist');
		//Getting List of available playlists
		$playlists = $cbvid->action->get_playlists();
		assign('playlists',$playlists);
		
		
	}
	break;
	
	case 'edit_playlist':
	{
		assign('mode','edit_playlist');
		$pid = $_GET['pid'];
		
		if(isset($_POST['edit_playlist']))
		{
			$params = array('name'=>mysql_clean($_POST['name']),'pid'=>mysql_clean($pid));
			$cbvid->action->edit_playlist($params);
		}
		
		$playlist = $cbvid->action->get_playlist($pid,userid());
		
		//Deleting Item
		if(!empty($_GET['delete_item']))
		{
			$delid = mysql_clean($_GET['delete_item']);
			$cbvid->action->delete_playlist_item($delid);
		}
		
		if($playlist)
		{
			assign('playlist',$playlist);
			//Getting Playlist Item
			$items = $cbvid->get_playlist_items($pid);
			assign('items',$items);
			
		}else
			e(lang('playlist_not_exist'));
		
	}
	
	
	break;
}

subtitle(lang("manage_playlist"));
template_files('manage_playlists.html');
display_it();
?>