<?php

include("../includes/config.inc.php");

$mode = $_REQUEST['mode'];

header("Content-type: application/json");

switch ($mode) {

    case "friend": 
    case "friends": 
    {
        $friends = $userquery->get_contacts(userid());
        $new_friends = array();
        if($friends)
        {
            foreach($friends as $friend)
            {
                $new_friends[] = array(
                    'value' => $friend['contact_userid'],
                    'name'  => name($friend),
                    'image' => $userquery->avatar($friend,'small'),
                );
            }

            echo json_encode($new_friends);
        }
    }
    break;
    case "groups":
    case "group":
    {
        $groups = $cbgroup->user_joined_groups(userid());
        
        $new_groups = array();
        if($groups)
        {
            foreach($groups as $group)
            {
                $new_groups[] = array(
                    'value' => $group['group_id'],
                    'name'  => $group['group_name'],
                    'image' => $cbgroup->get_group_thumb($group,'small')
                );
            }
            
            echo json_encode($new_groups);
        }
    }
    break;
    case "mentions":
    {
         $friends = $userquery->get_contacts(userid());
        $new_friends = array();
        if($friends)
        {
            foreach($friends as $friend)
            {
                $new_friends[] = array(
                    'id' => $friend['contact_userid'],
                    'name'  => name($friend),
                    'avatar' => $userquery->avatar($friend,'small'),
                    'type'  => 'user'
                );
            }

            echo json_encode($new_friends);
        }
    }
    break;

    default:
        exit(json_encode(array('err' => array(lang('Invalid request')))));
}
?>