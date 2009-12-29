<?php
/**
 * ALL LINKS ARE DEFINED HERE
 * YOU CAN CHANGE THEM IF REQUIRED
 * ARRAY( [name]=> Array([Non SEO Link], [SEO Link])) - Without BASEURL
 */

$Cbucket->links  = array
(
'channels'		=>array('channels.php','channels'),
'compose_new'	=>array('private_message.php?mode=new_msg','private_message.php?mode=new_msg'),
'create_group'	=>array('create_group.php','create_group'),
'groups'		=>array('groups.php','groups'),
'inbox'			=>array('private_message.php?mode=inbox','private_message.php?mode=inbox'),
'login'			=>array('signup.php','signup.php'),
'login_success'	=>array('login_success.php','login_success.php'),
'logout'		=>array('logout.php','logout.php'),
'my_account'	=>array('myaccount.php','my_account'),
'my_videos'		=>array('manage_videos.php','manage_videos.php'),
'notifications'	=>array('private_message.php?mode=notification','private_message.php?mode=notification'),
'search_result'	=>array('search_result.php','search_result.php'),
'signup'		=>array('signup.php','signup'),
'upload'		=>array('upload.php','upload'),
'user_contacts' =>array('user_contacts.php?user=','user_contacts.php?user='),
'user_favorites'=>array('user_videos.php?mode=favorites&user=','user_videos.php?mode=favorites&user='),
'user_videos' 	=>array('user_videos.php?user=','user_videos.php?user='),
'videos'		=>array('videos.php','videos'),

);


/**
 * Sortings
 */
function sorting_links()
{
	$array = array
	('most_recent' 	=> 'Recent',
	 'most_viewed'	=> 'Viewed',
	 'featured'		=> 'Featured',
	 'top_rated'	=> 'Top Rated',
	 'most_commented'	=> 'Commented',
	 );
	return $array;
}

function time_links()
{
	$array = array
	('all_time' 	=> 'All Time',
	 'today'		=> 'Today',
	 'yesterday'	=> 'Yesterday',
	 'this_week'	=> 'This Week',
	 'last_week'	=> 'Last Week',
	 'this_month'	=> 'This Month',
	 'last_month'	=> 'Last Month',
	 'this_year'	=> 'This Year',
	 'last_year'	=> 'Last Year',
	 );
	return $array;
}
?>