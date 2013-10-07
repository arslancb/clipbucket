<?php
include '../../includes/admin_config.php';
include 'ofc-library/open-flash-chart.php';

$days = 10;
$last_week = time()-86400*$days + 86400;
$the_last_week = date('M d', $last_week);
$title = new title("ClipBucket Daily Activity ".$the_last_week." - ".date("M d"));
$title->set_style("{font-size:14px;font-family:Century Gothic;font-weight:bold}");


$vid_stats = $data['video_stats'];
$vid_stats = json_decode($vid_stats);


$year = array();

//Getting This Weeks Data
for($i=0;$i<$days;$i++)
{
	if($i<$days-1)
	{
		$date_pattern = date("Y-m-d",$last_week+($i*86400));
		
		//echo "date_added LIKE '%$date_pattern%'";
		$data = $db->select(tbl("stats"),"*"," date_added LIKE '%$date_pattern%' ",1);
		$data = $data[0];
		$datas[] = $data;
	}
	
	$year[] = date("M d",$last_week+($i*86400));
}


for($i=0;$i<$days;$i++)
{
	$day[$i]['video'] = json_decode($datas[$i]['video_stats']);
	$day[$i]['users'] = json_decode($datas[$i]['user_stats']);
	$day[$i]['groups'] = json_decode($datas[$i]['group_stats']);
	
}
$max = 1;
for($i=0;$i<$days;$i++)
{	
	if($i==$days-1)
	{
		$vid_uploads[] = $cbvid->get_videos(array("count_only"=>true,"date_span"=>"today"))+0;
		$user_signups[] = $userquery->get_users(array("count_only"=>true,"date_span"=>"today"))+0;
		$groups_added[] = $cbgroup->get_groups(array("count_only"=>true,"date_span"=>"today"))+0;
	}else{
		$vid_uploads[] =$day[$i]['video']->uploads+0;
		$user_signups[] =$day[$i]['users']->signups+0;
		$groups_added[] =$day[$i]['groups']->created+0;
	}
	$max = max($max,$vid_uploads[$i],$user_signups[$i],$groups_added[$i]);
}



$vid_line = new line();
$vid_line->set_values($vid_uploads);
$vid_line->colour( '#336600');
$vid_line->set_key('Videos', 14);


$user_line = new line();
$user_line->set_values($user_signups);
$user_line->colour( '#0099cc');
$user_line->set_key('User', 14);


$grp_line = new line();
$grp_line->set_values($groups_added);
$grp_line->colour( '#990000');
$grp_line->set_key('Groups', 14);


$max = $max;
$steps = round($max/5,0.49);
$y = new y_axis();
$y->set_range( 0, $max, $steps);


$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $vid_line );
$chart->add_element( $user_line );
$chart->add_element( $grp_line );

$x_labels = new x_axis_labels();
$x_labels->set_steps( 1 );
$x_labels->set_vertical();
$x_labels->set_colour( '#A2ACBA' );
$x_labels->set_labels( $year );

$x = new x_axis();
$x->set_colour( '#A2ACBA' );
$x->set_grid_colour( '#D7E4A3' );
$x->set_offset( false );
$x->set_steps(4);
// Add the X Axis Labels to the X Axis
$x->set_labels( $x_labels );

$chart->set_x_axis( $x );


$chart->set_y_axis( $y );

echo $chart->toString();

?>