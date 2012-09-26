<?php

include("../includes/config.inc.php");

$mode = $_POST['mode'];

switch ($mode) {

    case "add_topic": {

            $array = $_POST;
            $tid = $cbgroup->add_topic($array);

            if (error()) {
                echo json_encode(array('err' => error()));
            } else {
                
                $topic = $cbgroup->get_topic_details($tid);
                Assign('topic', $topic);
                $template = get_template('topic');
                echo json_encode(array('success' => 'yes', 'tid' => $tid
                    ,'template'=>$template));
            }
        }
        break;
        
        
        case "join_group":
        case "leave_group":
        {
            $gid = mysql_clean(post('group_id'));
            
            if($mode=='join_group')
                $cbgroup->join_group($gid,userid());
            else 
                $cbgroup->leave_group($gid,userid());
            
            if(error())
            {
                echo json_encode(array('err'=>error()));
            }else
            {
                echo json_encode(array('success'=>'yes'));
            }
        }
        break;
    default:
        exit(json_encode(array('err' => array(lang('Invalid request')))));
}
?>
