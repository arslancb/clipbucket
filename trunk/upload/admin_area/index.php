<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.									|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , � PHPBucket.com														|
 ****************************************************************************************************
*/

require'../includes/admin_config.php';
$userquery->admin_login_check();
/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
	define('MAIN_PAGE', 'Dashboard');
}

//	$latest = get_latest_cb_info();
	$Cbucket->cbinfo['latest'] = $latest;
	if($Cbucket->cbinfo['version'] < $Cbucket->cbinfo['latest']['version'])
		$Cbucket->cbinfo['new_available'] = true;


$result_array = $array;
//Getting Video List
$result_array['limit'] = $get_limit;
if(!$array['order'])
    $result_array['order'] = " doj DESC LIMIT 5  ";

$users = get_users($result_array);

Assign('users', $users);

//////////////////getting todolist/////////////

$mode = $_POST['mode'];
if(!isset($mode)) $mode = $_GET['mode'];
switch($mode)
{
    case 'add_todo':
    {
        $response = array();
        $value = $_POST['val'];
        if(!empty($value)){
            $myquery->insert_todo($value);
            $response['todo'] = nl2br($value);
            $response['id'] = $db->insert_id();
            echo json_encode($response);
        }
        die();
    }
    break;
    case 'update_todo':
    {
       
        $id = $_POST["pk"];
        $value = trim($_POST["value"]);
        $myquery->update_todo($value, $id);
        echo json_encode(array(
            "msg" => "success",
            ));
        die();
    }
    break;
    case 'update_pharse':
    {
       
        $id = $_POST["pk"];
        $value = trim($_POST["value"]);
        $myquery->update_pharse($value, $id);
        echo json_encode(array(
            "msg" => "success",
            ));
        die();
    }
    break;
    case 'delete_todo':
    {
        $id = mysql_clean($_POST['id']);
        $myquery->delete_todo($id);
        die();
    }
}
///////////////////ends here/////////////



////////////////getting notes

$mode = $_POST['mode'];
switch($mode)
{
    case 'add_note':
    {
        $response = array();
        $value = $_POST['note'];
        $myquery->insert_note($value);
        $response['note'] = nl2br($value);
        $response['id'] = $db->insert_id();

        echo json_encode($response);
        die();
    }
        break;
    case 'delete_note':
    {
        $id = mysql_clean($_POST['id']);
        $myquery->delete_note($id);
        die();
    }
        break;

    case 'delete_comment':
    {
        $type = $_POST['type'];
        switch($type)
        {
            case 'v':
            case 'video':
            default:
                {
                $cid = mysql_clean($_POST['cid']);
                $type_id = $myquery->delete_comment($cid);
                $cbvid->update_comments_count($type_id);
                }
                break;
            case 'u':
            case 'c':
            {
                $cid = mysql_clean($_POST['cid']);
                $type_id = $myquery->delete_comment($cid);
                $userquery->update_comments_count($type_id);
            }
                break;
            case 't':
            case 'topic':
            {
                $cid = mysql_clean($_POST['cid']);
                $type_id = $myquery->delete_comment($cid);
                $cbgroup->update_comments_count($type_id);
            }
                break;

        }
        if(msg())
        {
            $msg = msg_list();
            $msg = $msg[0];
        }
        if(error())
        {
            $err = error_list();
            $err = $err[0];
        }

        $ajax['msg'] = $msg;
        $ajax['err'] = $err;

        echo json_encode($ajax);
    }
        break;

    case 'spam_comment':
    {
        $cid = mysql_clean($_POST['cid']);


        $rating = $myquery->spam_comment($cid);
        if(msg())
        {
            $msg = msg_list();
            $msg = $msg[0];
        }
        if(error())
        {
            $err = error_list();
            $err = $err[0];
        }

        $ajax['msg'] = $msg;
        $ajax['err'] = $err;

        echo json_encode($ajax);
    }
        break;

    case 'remove_spam':
    {
        $cid = mysql_clean($_POST['cid']);


        $rating = $myquery->remove_spam($cid);
        if(msg())
        {
            $msg = msg_list();
            $msg = $msg[0];
        }
        if(error())
        {
            $err = error_list();
            $err = $err[0];
        }

        $ajax['msg'] = $msg;
        $ajax['err'] = $err;

        echo json_encode($ajax);
    }
        break;
}

/////////////////////////ending notes




if(!$array['order'])
    $result_array['order'] = " views DESC LIMIT 8 ";
$videos = get_videos($result_array);

Assign('videos', $videos);


$comments = getComments($comment_cond);
assign("comments",$comments);

$get_limit = create_query_limit($page,5);
$videos = $cbvid->action->get_flagged_objects($get_limit);
Assign('flaggedVideos', $videos);


$get_limit = create_query_limit($page,5);
$users = $userquery->action->get_flagged_objects($get_limit);
Assign('flaggedUsers', $users);


$get_limit = create_query_limit($page,5);
$photos = $cbphoto->action->get_flagged_objects($get_limit);
assign('flaggedPhotos', $photos);

$numbers = array(100,1000,15141,3421);
function format_number($number) {
    if($number >= 1000) {
        return $number/1000 . "k";   // NB: you will want to round this
    }
    else {
        return $number;
    }
}

Assign(BASEURL,'baseurl');

//subtitle(lang('video_manager'));
template_files('index.html');
display_it();
?>





