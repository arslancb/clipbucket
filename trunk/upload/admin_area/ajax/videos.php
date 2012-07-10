<?php

include("../../includes/admin_config.php");
//Ajax categories...

$mode = post('mode');

switch($mode)
{
    case "add_profile":
    {
        $profile_id = $cbvid->add_video_profile($_POST);
        
        //$cid = 18;
        if(error())
        {
            echo json_encode(array('err'=>error(),'rel'=>get_rel_list()));
        }else{
           echo json_encode(array('success'=>'yes','profile_id'=>$profile_id,'rel'=>''));
        }
    }
    break;
    case "update_profile":
    {
        $profile_id = $cbvid->update_video_profile($_POST);
        
        //$cid = 18;
        if(error())
        {
            echo json_encode(array('err'=>error(),'rel'=>get_rel_list()));
        }else{
           echo json_encode(array('success'=>'yes','profile_id'=>$profile_id,'rel'=>''));
        }
    }
    break;
    
    
}
?>