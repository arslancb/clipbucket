<?php
include '../../includes/admin_config.php';
include 'ofc-library/open-flash-chart.php';

$days = 7;
$last_week = time()-86400*$days + 86400;
$the_last_week = date('M d', $last_week);
$title = new title("Groups Stats ".$the_last_week." - ".date("M d"));
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
	$data = $db->select(tbl("stats"),"*"," date_added LIKE '%$date_pattern%' ",1);
	$data = $data[0];
	$datas[] = $data;
	}
	
	$year[] = date("M d",$last_week+($i*86400));
}


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
		$groups[] = $cbgroup->get_groups(array("count_only"=>true,"date_span"=>"today"))+0;
		$active[] = $cbgroup->get_groups(array("count_only"=>true,"date_span"=>"today","active"=>'yes'))+0;
		$tota_topics = $db->select(tbl("groups"),"SUM(total_topics) as the_topics"," date_added  LIKE '%$date%'");
		$topics_bar_data[] = $tota_topics['the_topics']+0;
	}else{
		$groups[] =$day[$i]['groups']->created+0;
		$active[] =$day[$i]['groups']->active+0;
		$topics_bar_data[] =$day[$i]['groups']->total_topics+0;
	}
	$max = max($max,$groups[$i],$active[$i],$topics_bar_data[$i]);
}



$total_bars = new bar_cylinder();
$total_bars->set_values($groups);
$total_bars->colour( '#0066ff');
$total_bars->key('Total', 14);

$active_bars = new bar_cylinder();
$active_bars->set_values($active);
$active_bars->colour( '#99cc00');
$active_bars->key('Active', 14);

$topics_bar = new bar_cylinder();
$topics_bar->set_values($topics_bar_data);
$topics_bar->colour( '#BF3B69');
$topics_bar->key('Topics', 14);




//$max = $max+(round($max/2,0.49));
$steps = round($max/5,0.49);
$y = new y_axis();
$y->set_range( 0, $max, $steps);


$chart = new open_flash_chart();
$chart->set_title( $title );
$chart->add_element( $total_bars );
$chart->add_element( $active_bars );
$chart->add_element( $topics_bar );

$x_labels = new x_axis_labels();
$x_labels->set_steps( 1 );
$x_labels->set_colour( '#A2ACBA' );
$x_labels->set_labels( $year );

$x = new x_axis();
$x->set_colour( '#A2ACBA' );
$x->set_grid_colour( '#D7E4A3' );
$x->set_offset( true );
$x->set_steps(4);
// Add the X Axis Labels to the X Axis
$x->set_labels( $x_labels );

$chart->set_x_axis( $x );
$chart->set_bg_colour('#ffffff');

$chart->set_y_axis( $y );

echo $chart->toString();

?>