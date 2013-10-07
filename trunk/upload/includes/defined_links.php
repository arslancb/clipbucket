<?php
/**
 * ALL LINKS ARE DEFINED HERE
 * YOU CAN CHANGE THEM IF REQUIRED
 * ARRAY( [name]=> Array([Non SEO Link], [SEO Link])) - Without BASEURL
 */


$cbLinks = array
(
'channels'		=>array('channels.php','channels/'),
'compose_new'	=>array('private_message.php?mode=new_msg','private_message.php?mode=new_msg'),
'contact_us'	=>array('contact.php','contact'),
'create_group'	=>array('create_group.php','create_group'),
'groups'		=>array('groups.php','groups/'),
'inbox'			=>array('private_message.php?mode=inbox','private_message.php?mode=inbox'),
'login'			=>array('signup.php','signup.php'),
'login_success'	=>array('login_success.php','login_success.php'),
'logout'		=>array('logout.php','logout.php'),
'logout_success'=>array('logout_success.php','logout_success.php'),
'my_account'	=>array('myaccount.php','my_account'),
'my_videos'		=>array('manage_videos.php','manage_videos.php'),
'my_favorites'	=>array('manage_videos.php?mode=favorites','manage_videos.php?mode=favorites'),
'my_playlists'	=>array('manage_playlists.php','manage_playlists.php'),
'my_contacts'	=>array('manage_contacts.php','manage_contacts.php'),
'notifications'	=>array('private_message.php?mode=notification','private_message.php?mode=notification'),
'rss'			=>array("rss.php?mode=","rss/"),
'search_result'	=>array('search_result.php','search_result.php'),
'signup'		=>array('signup.php','signup'),
'upload'		=>array('upload.php','upload'),
'user_contacts' =>array('user_contacts.php?user=','user_contacts.php?user='),
'user_subscriptions' =>array('user_contacts.php?mode=subscriptions&user=','user_contacts.php?mode=subscriptions&user='),
'user_subscribers' =>array('user_contacts.php?mode=subscribers&user=','user_contacts.php?mode=subscribers&user='),
'user_favorites'=>array('user_videos.php?mode=favorites&user=','user_videos.php?mode=favorites&user='),
'user_videos' 	=>array('user_videos.php?user=','user_videos.php?user='),
'videos'		=>array('videos.php','videos/'),

);

if(is_array($Cbucket->links))
	$Cbucket->links  =  array_merge($Cbucket->links,$cbLinks);

/**
 * Sortings
 */
function sorting_links()
{
	if(!isset($_GET['sort']))
		$_GET['sort'] = 'most_recent';
	if(!isset($_GET['time']))
		$_GET['time'] = 'all_time';

	$array = array
	('most_recent' 	=> lang('recent'),
	 'most_viewed'	=> lang('viewed'),
	 'featured'		=> lang('featured'),
	 'top_rated'	=> lang('top_rated'),
	 'most_commented'	=> lang('commented'),
	 );
	return $array;
}

function time_links()
{
	$array = array
	('all_time' 	=> lang('alltime'),
	 'today'		=> lang('today'),
	 'yesterday'	=> lang('yesterday'),
	 'this_week'	=> lang('thisweek'),
	 'last_week'	=> lang('lastweek'),
	 'this_month'	=> lang('thismonth'),
	 'last_month'	=> lang('lastmonth'),
	 'this_year'	=> lang('thisyear'),
	 'last_year'	=> lang('lastyear'),
	 );
	return $array;
}
?>