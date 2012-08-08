<?php

/**
 * All ajax related stuff for Admin Area
 * @Author Arslan Hassan
 * @package ClipBucket Admin
 */

include("../../includes/admin_config.php");

$mode = post('mode');

switch($mode)
{
    case "add-note":
    case "add_note":
    {
        $array = array();
        $note = mysql_clean($_POST['note']);
        if(!$note) e(lang("Please enter something for note"));
        
        if(error()){
            exit(json_encode(array('err'=>error())));
        }
        $myquery->insert_note($note);
        $array['note'] = nl2br($note);
        $array['id'] = $db->insert_id();

        echo json_encode($array);
    }
    break;

    case "delete-note":
    case "delete_note":
    {
        $id = mysql_clean($_POST['id']);
        $myquery->delete_note($id);
        
        echo json_encode(array('success'=>'ok'));
    }
    break;

    case "update-home-block-order":
    {
        $blocks = $Cbucket->admin_blocks;
        
        $config = array();
        
        $order = 1;
        $orders = array();
        rsort($_POST['block_order']);
        
        foreach($_POST['block_order'] as $block_order)
        {
            $orders[$block_order] = $order;
            $order++;
        }
        
        foreach($blocks as $id => $block)
        {
            $config[$id] = array('order'=>$orders[$id],'container'=> post($id.'-container'));
        }
   
        config('admin-block-orders',  json_encode($config));
        
        echo json_encode(array('success'=>'ok'));
    }
}
?>
