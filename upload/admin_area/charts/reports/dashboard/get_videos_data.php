<?php

error_reporting(E_ALL);
require_once('../../../../includes/admin_config.php');

$todayVideos['uploads'] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"today"),TRUE);
$todayVideos['processing'] = $cbvid->get_videos(array("count_only"=>true,"status"=>"Processing","date_span"=>"today"),TRUE);
$todayVideos['active'] = $cbvid->get_videos(array("count_only"=>true,"active"=>"yes","date_span"=>"today"),TRUE);

$videoTodayStats = array(
	"label" => "User Today Stats",
	"data" => array(
		array('uploads', $todayVideos['uploads']),
		array('processing',  $todayVideos['processing']),
		array('active', $todayVideos['active']),
		)
	);


$weekVideos['uploads'] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"this_week"),TRUE);
$weekVideos['processing'] = $cbvid->get_videos(array("count_only"=>true,"status"=>"Processing","date_span"=>"this_week"),TRUE);
$weekVideos['active'] = $cbvid->get_videos(array("count_only"=>true,"active"=>"yes","date_span"=>"this_week"),TRUE);

$videoWeekStats = array(
	"label" => "User month Stats", 
	"data" => array(
		array('uploads',$weekVideos['uploads']),
		array('processing',$weekVideos['processing']),
		array('active',$weekVideos['active'])
	),
);


$monthVideos['uploads'] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"this_month"),TRUE);
$monthVideos['processing'] = $cbvid->get_videos(array("count_only"=>true,"status"=>"Processing","date_span"=>"this_month"),TRUE);
$monthVideos['active'] = $cbvid->get_videos(array("count_only"=>true,"active"=>"yes","date_span"=>"this_month"),TRUE);

$videoMonthStats = array(
	"label" => "User month Stats", 
	"data" => array(
		array('uploads',$monthVideos['uploads']),
		array('processing',$monthVideos['processing']),
		array('active',$monthVideos['active'])
	),
);


$data = array(
	"today" => $videoTodayStats,
	"this_week" => $videoWeekStats,
	"this_month" => $videoMonthStats,
	);

echo json_encode($data);