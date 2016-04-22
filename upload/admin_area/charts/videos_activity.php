<?php
include '../../includes/admin_config.php';
//include 'ofc-library/open-flash-chart.php';

$days = 7;
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
	$data = $db->select(tbl("stats"),"*"," date_added LIKE '%$date_pattern%' ",1);
	$data = $data[0];
	$datas[] = $data;
	}
	
	$year[] = date("M d",$last_week+($i*86400));
}

pr($datas,true);
for($i=0;$i<$days;$i++)
{
	$day[$i]['video'] = json_decode($datas[$i]['video_stats'],true);
	$day[$i]['users'] = json_decode($datas[$i]['user_stats'],true);
	$day[$i]['groups'] = json_decode($datas[$i]['group_stats'],true);
	
}
$max = 1;
for($i=0;$i<$days;$i++)
{	
	if($i==$days-1)
	{
		$uploads[] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"today"))+0;
		$active[] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"today","active"=>'yes',"status"=>'Successful'))+0;
		$processing[] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"today","status"=>'Processing'))+0;
	}else{
		$uploads[] =$day[$i]['video']->uploads+0;
		$active[] =$day[$i]['video']->active+0;
		$processing[] =$day[$i]['video']->processing+0;
	}
	$max = max($max,$uploads[$i],$active[$i],$processing[$i]);
}



//pr($uploads,true);

//pr($active,true);

//pr($processing,true);


//$max = $max+(round($max/2,0.49));
//$steps = round($max/5,0.49);


?>