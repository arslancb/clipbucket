<?php

error_reporting(E_ALL);
//required_once '../../includes/admin_config.php';
require_once('../../../includes/admin_config.php'); 
//include 'ofc-library/open-flash-chart.php';


$days = 10;
$last_week = time()-86400*$days + 86400;
$the_last_week = date('M d', $last_week);

$vid_stats = $data['video_stats'];
$vid_stats = json_decode($vid_stats);

$year = array();

//Getting This Weeks Data
for($i=0;$i<$days;$i++)
{
	if($i<$days)
	{
		$date_pattern = date("Y-m-d",$last_week+($i*86400));
		
		//echo "date_added LIKE '%$date_pattern%'";
		$data = $db->select(tbl("stats"),"*"," date_added LIKE '%$date_pattern%' ",1);
		 $data = $data[0];
		$datas[] = $data;
	}
	
	$year[] = date("M d",$last_week+($i*86400));
}
 //Videos
echo $_post['videos'];
if(isset($_post['videos'])){

}
 $date_pattern = date("Y-m-d");

$videos['uploads'] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"today"),TRUE);
$videos['processing'] = $cbvid->get_videos(array("count_only"=>true,"status"=>"Processing","date_span"=>"today"),TRUE);
$videos['active'] = $cbvid->get_videos(array("count_only"=>true,"active"=>"yes","date_span"=>"today"),TRUE);
$V = array(array('uploads',$videos['uploads']),array('processing',$videos['processing']),array('active',$videos['active'])); 


//Users
$users['signups'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today"));
$users['inactive'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today","status"=>'ToActivate'));
$users['active'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today","status"=>'Ok'));
//Views
$user_views = $db->select(tbl("users"),"SUM(profile_hits) as total_views"," doj LIKE '%$date%'");
//$users['views'] = $user_views[0]['total_views'];
//Total Comments
$user_comments = $db->select(tbl("users"),"SUM(comments_count) as total_comments"," doj LIKE '%$date%'");
//$users['comments'] = $user_comments[0]['total_comments'];

$U = array(array('signups',$users['signups']),array('inactive',$users['inactive']),array('Active User',$users['active']),array('views User',$users['views']),array('comments User',$users['comments'])); 

//Groups
$groups['created'] = $cbgroup->get_groups(array("count_only"=>true,"date_span"=>"today"));
$groups['active'] = $cbgroup->get_groups(array("count_only"=>true,"date_span"=>"today","active"=>"yes"));
//Total Views
$group_views = $db->select(tbl("groups"),"SUM(total_views) as the_views"," date_added LIKE '%$date%'");
//$groups['views'] = $group_views[0]['the_views'];
//Total Discussion
$group_topics = $db->select(tbl("groups"),"SUM(total_topics) as the_topics"," date_added LIKE '%$date%'");
//$groups['total_topics'] = $group_topics[0]['the_topics'];
//TOtal Comments
$group_discussions = $db->select(tbl("group_topics"),"SUM(total_replies) as the_discussions"," date_added LIKE '%$date%'");
//$groups['total_discussions'] = $group_discussions[0]['the_discussions'];


$G = array(array('created',$groups['created']),array('Active',$groups['active']),
	array('total_topics',$groups['total_topics']),array('total_discussions',$groups['total_discussions'])); 
//Make arrays for json
$array_video = array('label' => 'Videos','data'=>$V);

$array_user = array('label' => 'Users','data'=>$U);

$array_group = array('label' => 'Groups','data'=>$G);

echo json_encode(array($array_user,$array_video,$array_group));
//echo json_encode(array($array2));


?>