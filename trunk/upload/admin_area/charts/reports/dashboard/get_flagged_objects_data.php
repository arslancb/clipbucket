<?php
error_reporting(E_ALL);
require_once('../../../../includes/admin_config.php');

$flaggedVideos['today'] = $cbvid->get_videos(array("count_only"=>true, "flagged" => "yes", "data_span" => "today"),TRUE);
$flaggedVideos['this_week'] = $cbvid->get_videos(array("count_only"=>true, "flagged" => "yes", "data_span" => "this_week"),TRUE);
$flaggedVideos['this_month'] = $cbvid->get_videos(array("count_only"=>true, "flagged" => "yes", "data_span" => "this_month"),TRUE);

$flaggedUsers['today'] = $userquery->get_users(array("count_only"=>true, "flagged" => "yes", "data_span" => "today"),TRUE);
$flaggedUsers['this_week'] = $userquery->get_users(array("count_only"=>true, "flagged" => "yes", "data_span" => "this_week"),TRUE);
$flaggedUsers['this_month'] = $userquery->get_users(array("count_only"=>true, "flagged" => "yes", "data_span" => "this_month"),TRUE);

$flaggedPhotos['today'] = $cbphoto->get_photos(array("count_only"=>true, "flagged" => "yes", "data_span" => "today"),TRUE);
$flaggedPhotos['this_week'] = $cbphoto->get_photos(array("count_only"=>true, "flagged" => "yes", "data_span" => "this_week"),TRUE);
$flaggedPhotos['this_month'] = $cbphoto->get_photos(array("count_only"=>true, "flagged" => "yes", "data_span" => "this_month"),TRUE);


$flaggedTodayStats = array(
	"label" => "Today flagged objects", 
	"data" => array(
		array('users', $flaggedUsers['today']),
		array('photos',  $flaggedPhotos['today']),
		array('videos', $flaggedVideos['today']),
		)
	);

$flaggedWeekStats = array(
	"label" => "Week flagged objects", 
	"data" => array(
		array('users', $flaggedUsers['this_week']),
		array('photos',  $flaggedPhotos['this_week']),
		array('videos', $flaggedVideos['this_week']),
		)
	);


$flaggedMonthStats = array(
	"label" => "Month flagged objects", 
	"data" => array(
		array('users', $flaggedUsers['this_month']),
		array('photos',  $flaggedPhotos['this_month']),
		array('videos', $flaggedVideos['this_month']),
		)
	);

$data = array(
	"today" => $flaggedTodayStats,
	"this_week" => $flaggedWeekStats,
	"this_month" => $flaggedMonthStats,
	);

echo json_encode($data);