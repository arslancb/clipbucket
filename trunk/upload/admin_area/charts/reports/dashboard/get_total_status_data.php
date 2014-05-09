<?php

error_reporting(E_ALL);
require_once('../../../../includes/admin_config.php');

$totalVideos['today'] = $cbvid->get_videos(array("count_only"=>true, "data_span" => "today"),TRUE);
$totalVideos['this_week'] = $cbvid->get_videos(array("count_only"=>true, "data_span" => "this_week"),TRUE);
$totalVideos['this_month'] = $cbvid->get_videos(array("count_only"=>true, "data_span" => "this_month"),TRUE);

$totalUsers['today'] = $userquery->get_users(array("count_only"=>true, "data_span" => "today"),TRUE);
$totalUsers['this_week'] = $userquery->get_users(array("count_only"=>true, "data_span" => "this_week"),TRUE);
$totalUsers['this_month'] = $userquery->get_users(array("count_only"=>true, "data_span" => "this_month"),TRUE);

$totalPhotos['today'] = $cbphoto->get_photos(array("count_only"=>true, "data_span" => "today"),TRUE);
$totalPhotos['this_week'] = $cbphoto->get_photos(array("count_only"=>true, "data_span" => "this_week"),TRUE);
$totalPhotos['this_month'] = $cbphoto->get_photos(array("count_only"=>true, "data_span" => "this_month"),TRUE);


$todayStats = array(
	"label" => "Today Stats", 
	"data" => array(
		array('users', $totalUsers['today']),
		array('photos',  $totalPhotos['today']),
		array('videos', $totalVideos['today']),
		)
	);

$weekStats = array(
	"label" => "Week Stats", 
	"data" => array(
		array('users', $totalUsers['this_week']),
		array('photos',  $totalPhotos['this_week']),
		array('videos', $totalVideos['this_week']),
		)
	);


$monthStats = array(
	"label" => "Month Stats", 
	"data" => array(
		array('users', $totalUsers['this_month']),
		array('photos',  $totalPhotos['this_month']),
		array('videos', $totalVideos['this_month']),
		)
	);

$data = array(
	"today" => $todayStats,
	"this_week" => $weekStats,
	"this_month" => $monthStats,
	);

echo json_encode($data);