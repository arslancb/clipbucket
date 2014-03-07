<?php

error_reporting(E_ALL);
//required_once '../../includes/admin_config.php';
require_once('../../../includes/admin_config.php');
//include 'ofc-library/open-flash-chart.php';


$days = 10;
$last_week = time()-86400*$days + 86400;
$the_last_week = date('M d', $last_week);

//$title = new title("ClipBucket Daily Activity ".$the_last_week." - ".date("M d"));

//$title->set_style("{font-size:14px;font-family:Century Gothic;font-weight:bold}");

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
        if(!empty($data['video_stats'])){
            //$data = array(array('1999',3.0),array('2000',3.9),array('2001',2.0),array('2002',1.2));
            //echo $data['video_stats'];
            /*	[{"label":"videos","data":{"uploads":"3","processing":"555555","active":"6","views":"1","comments":"10"}}];
                [{"label":"Scores","data":[["1999",3],["2000",3.9],["2001",2],["2002",1.2]]}]*/
            //$data = json_decode($data['video_stats']);
            //$array = array('label' => 'videos','data'=>$data['video_stats']);
            //echo json_encode(array($array));
        }
        //echo $data['video_stats'].'spliter'.$data['user_stats'].'spliter'.$data['group_stats'];

        $datas[] = $data;
    }

    $year[] = date("M d",$last_week+($i*86400));
}


//Users
$users['signups'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_week"));
$users['inactive'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_week","status"=>'ToActivate'));
$users['active'] = $userquery->get_users(array("count_only"=>true,"date_span"=>"this_week","status"=>'Ok'));
//Views
$user_views = $db->select(tbl("users"),"SUM(profile_hits) as total_views"," doj LIKE '%$date_pattern%'");
//$users['views'] = $user_views[0]['total_views'];
//Total Comments
$user_comments = $db->select(tbl("users"),"SUM(comments_count) as total_comments"," doj LIKE '%$date_pattern%'");
//$users['comments'] = $user_comments[0]['total_comments'];

$U = array(array('signups',$users['signups']),array('inactive',$users['inactive']),array('Active User',$users['active']),array('views User',$users['views']),array('comments User',$users['comments']));


//Make arrays for json


$array_user = array('label' => 'Users','data'=>$U);



echo json_encode(array($array_user));
//echo json_encode(array($array2));



//pr($datas,true);

for($i=0;$i<$days;$i++)
{
    $day[$i]['video'] = json_decode($datas[$i]['video_stats']);
    $day[$i]['users'] = json_decode($datas[$i]['user_stats']);
    $day[$i]['groups'] = json_decode($datas[$i]['group_stats']);
}

$max = 1;
for($i=0;$i<$days;$i++)
{
    if($i==$days)
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
//echo $max;
//echo $vid_uploads;
//echo 'uploads='.json_decode($vid_uploads);
//print_r($vid_uploads);

//echo 'signup='.json_decode($user_signups);

//echo 'groups_added'.json_decode($groups_added);
//pr($vid_uploads,true);
$max = $max;
$steps = round($max/5,0.49);

?>