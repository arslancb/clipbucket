<?php
/**
 * ALL LINKS ARE DEFINED HERE
 * YOU CAN CHANGE THEM IF REQUIRED
 * ARRAY( [name]=> Array([Non SEO Link] [SEO Link])) - Without BASEURL
 */

$Cbucket->links  = array
(
'signup'	=>array('signup.php','signup'),
'login'		=>array('signup.php','login'),
'logout'	=>array('logout.php','logout'),
'videos'	=>array('videos.php','videos'),
'channels'	=>array('channels.php','channels'),
'my_account'=>array('myaccount.php','my_account'),
'groups'	=>array('groups.php','groups'),
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