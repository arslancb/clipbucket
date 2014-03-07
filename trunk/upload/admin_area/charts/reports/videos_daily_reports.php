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

//Make arrays for json
$array_video = array('label' => 'Videos','data'=>$V);
$array_user = array('label' => 'Users','data'=>$U);

$array_group = array('label' => 'Groups','data'=>$G);

echo json_encode(array($array_video));
//echo json_encode(array($array2));


?>