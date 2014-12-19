<?php

error_reporting(E_ALL);
require_once('../../../../includes/admin_config.php');

$todayUsers['signups'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today"));
$todayUsers['inactive'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today","status"=>'ToActivate'));
$todayUsers['active'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today","status"=>'Ok'));

$userTodayStats = array(
	"label" => "User Today Stats", 
	"data" => array(
		array('signups', $todayUsers['signups']),
		array('inactive',  $todayUsers['inactive']),
		array('active', $todayUsers['active']),
		)
	);


$weekUsers['signups'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_week"));
$weekUsers['inactive'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_week","status"=>'ToActivate'));
$weekUsers['active'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_week","status"=>'Ok'));

$userWeekStats = array(
	"label" => "User month Stats", 
	"data" => array(
		array('signups',$weekUsers['signups']),
		array('inactive',$weekUsers['inactive']),
		array('active',$weekUsers['active'])
	),
);


$monthUsers['signups'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_month"));
$monthUsers['inactive'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_month","status"=>'ToActivate'));
$monthUsers['active'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_month","status"=>'Ok'));

$userMonthStats = array(
	"label" => "User month Stats", 
	"data" => array(
		array('signups',$monthUsers['signups']),
		array('inactive',$monthUsers['inactive']),
		array('active',$monthUsers['active'])
	),
);


$data = array(
	"today" => $userTodayStats,
	"this_week" => $userWeekStats,
	"this_month" => $userMonthStats,
	);

echo json_encode($data);