<?php

error_reporting(E_ALL);
//required_once '../../includes/admin_config.php';
require_once('../../../includes/admin_config.php');
//include 'ofc-library/open-flash-chart.php';


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


$array_user = array('label' => 'Users','data'=>$U);

echo json_encode(array($array_user));
//echo json_encode(array($array2));


?>