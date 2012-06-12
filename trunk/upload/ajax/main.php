<?php

/** 
 * All AJax requests which does not fall in other categories or files
 * are saved here
 * 
 * @author Arslan Hassan
 * @license AAL
 * @since 3.0 
 */



include("../includes/config.inc.php");


//Getting mode..
$mode = $_POST['mode'];

$mode = mysql_clean($mode);


switch($mode){
    
    //Rating function works with every object in similar manner therefore
    //Using the same code in different files we are using it here...
    case "rating":
    {
        
        $type = mysql_clean(post('type'));
        $id = mysql_clean(post('id'));
        $rating = mysql_clean(post('rating'));
        
        switch($type){
            case "video":
            {
                $result = $cbvid->rate_video($id,$rating);
                echo showRating($result,$type);
            }
            break;

            case "photo":
            {
                $rating = $_POST['rating']*2;
                $id = $_POST['id'];
                $result = $cbphoto->rate_photo($id,$rating);
                $result['is_rating'] = true;
                $cbvid->show_video_rating($result);

                $funcs = cb_get_functions('rate_photo');	
                if($funcs)
                foreach($funcs as $func)
                {
                        $func['func']($id);
                }
            }
            break;
            case "collection":
            {
                $rating = $_POST['rating']*2;
                $id = $_POST['id'];
                $result = $cbcollection->rate_collection($id,$rating);
                $result['is_rating'] = true;
                $cbvid->show_video_rating($result);

                $funcs = cb_get_functions('rate_collection');	
                if($funcs)
                foreach($funcs as $func)
                {
                        $func['func']($id);
                }
            }
            break;

            case "user":
            {
                $rating = $_POST['rating']*2;
                $id = $_POST['id'];
                $result = $userquery->rate_user($id,$rating);
                $result['is_rating'] = true;
                $cbvid->show_video_rating($result);

                $funcs = cb_get_functions('rate_user');	
                if($funcs)
                foreach($funcs as $func)
                {
                        $func['func']($id);
                }
            }
            break;
        }
    }
    break;
    
    
    case "create_playlist":
    {
        $array = array(
            'name',
            'description',
            'tags',
            'playlist_type',
            'privacy',
            'allow_comments',
            'allow_rating',
            'type',
        );
        
        $type = post('type');
        
        $input = array();
        foreach($array as $ar)
        {
            $input[$ar] = mysql_clean(post($ar));
        }
        
        
        if($type=='v')
            $pid = $cbvid->action->create_playlist($input);
        
        if(!$type)
            e(lang("Invalid playlist type"));
        
        if(error())
        {
            echo json_encode(array('err'=>error(),'rel'=>get_rel_list()));
        }else
        {
            $playlist = $cbvid->action->get_playlist($pid);
            assign('playlist',$playlist);
            if(post('oid')) assign('oid',post('oid'));
            assign('type',post('type'));
            
            $template = Fetch('blocks/playlist.html');
            $ul_template = fetch('blocks/playlist-ul.html');
            echo json_encode(array('success'=>'yes','rel'=>get_rel_list(),
            'template'=>$template,'pid'=>$pid,'ul_template'=>$ul_template,
                'msg'=>msg()));
        }
        
    }
    break;
    
    case "delete_playlist":
    {
        $pid = mysql_clean(post('pid'));
        $cbvid->action->delete_playlist($pid);
        
        if(error())
        {
             echo json_encode(array('err'=>error()));
        }else
        {
            echo json_encode(array('msg'=>array(lang('Playlist has been removed'))));
        }
    }
    break;
    
    case "add_playlist_item":
    {
        
        $type = post('v');
        $pid = mysql_clean(post('pid'));
        $id = mysql_clean(post('oid'));
       // $note = mysql_clean(post('note'));
        
        switch($type){
            case 'v':
            default:
            {
                $item_id = $cbvid->action->add_playlist_item($pid,$id );
                
                if(!error())
                {
                    updateObjectStats('plist','video',$id);
                    echo json_encode(array('status'=>'ok',
                        'msg'=>msg(),'item_id'=>$item_id,'updated'=>nicetime(now())));
                }else{
                    echo json_encode(array('err'=>error()));
                }
            }
        }
        
    }
    break;
    
    case "update_playlist_order":
    {
        $pid = mysql_clean(post('playlist_id'));
        $items = post('playlist_item');
        $items = array_map('mysql_clean',$items);
        
        $cbvid->action->update_playlist_order($pid,$items);
        
        if(error())
            echo json_encode(array('err'=>error()));
        else
            echo json_encode(array('success'=>'yes'));
    }
    break;
    
    case "save_playlist_item_note":
    {
        $item_id = mysql_clean(post('item_id'));
        $text = mysql_clean(post('text'));
        
        $cbvid->action->save_playlist_item_note($item_id,$text);
        
        if(error())
        {
            echo json_encode(array('err'=>error()));
        }  else {
            echo json_encode(array('msg'=>msg()));
        }
    }
    break;
    
    
    case "remove_playlist_item":
    {
        $item_id = mysql_clean(post('item_id'));
        $cbvid->action->delete_playlist_item($item_id);
        if(error())
            echo json_encode(array('err'=>error()));
        else
            echo json_encode(array('success'=>'ok'));
    }
    break;
    
    case 'add_comment';
    {
        $type = $_POST['type'];
        switch($type)
        {
            case 'v':
            case 'video':
            default:
            {
                $id = mysql_clean($_POST['obj_id']);
                $comment = $_POST['comment'];
                if($comment=='undefined')
                        $comment = '';
                $reply_to = $_POST['reply_to'];

                $cid = $cbvid->add_comment($comment,$id,$reply_to);
            }
            break;
            case 'u':
            case 'c':
            {

                $id = mysql_clean($_POST['obj_id']);
                $comment = $_POST['comment'];
                if($comment=='undefined')
                        $comment = '';
                $reply_to = $_POST['reply_to'];

                $cid = $userquery->add_comment($comment,$id,$reply_to);
            }
            break;
            case 't':
            case 'topic':
            {

                $id = mysql_clean($_POST['obj_id']);
                $comment = $_POST['comment'];
                if($comment=='undefined')
                        $comment = '';
                $reply_to = $_POST['reply_to'];

                $cid = $cbgroup->add_comment($comment,$id,$reply_to);
            }
            break;

            case 'cl':
            case 'collection':
            {
                $id = mysql_clean($_POST['obj_id']);
                $comment = $_POST['comment'];
                if($comment=='undefined')
                        $comment = '';
                $reply_to = $_POST['reply_to'];

                $cid = $cbcollection->add_comment($comment,$id,$reply_to);	
            }
            break;

            case "p":
            case "photo":
            {
                $id = mysql_clean($_POST['obj_id']);
                $comment = $_POST['comment'];
                if($comment=='undefined')
                        $comment = '';
                $reply_to = $_POST['reply_to'];
                $cid = $cbphoto->add_comment($comment,$id,$reply_to);	
            }
            break;

        }
        
        
        if(error())
        {
            exit(json_encode(array('err'=>error())));
        }

            $comment = $myquery->get_comment($cid);
            assign('comment',$comment);
            $template = get_template('single_comment');
            $array = array(
                'msg' => msg(),
                'comment' => $template,
                'success' => 'ok',
                'cid' => $cid
            );
        
        echo json_encode($array);

    }
    break;
    
    case "get_comments":
    {
        $params = array();
        $limit = config('comments_per_page');
        $page = $_POST['page'];
        $params['type'] = mysql_clean($_POST['type']);
        $params['type_id'] = mysql_clean($_POST['type_id']);
        $params['last_update'] = mysql_clean($_POST['last_update']);
        $params['limit'] = create_query_limit($page,$limit);	

        $admin = "";
        if($_POST['admin']=='yes' && has_access('admin_access',true))
        {
                $params['cache'] ='no';
                $admin = "yes";
        }
        $comments = $myquery->getComments($params);
        //Adding Pagination
        $total_pages = count_pages($_POST['total_comments'],$limit);
        assign('object_type',mysql_clean($_POST['object_type']));		
        //Pagination
        $pages->paginate($total_pages,$page,NULL,NULL,'<a href="javascript:void(0)"
        onClick="getComments(\''.$params['type'].'\',\''.$params['type_id'].'\',\''.$params['last_update'].'\',
        \'#page#\',\''.$_POST['total_comments'].'\',\''.mysql_clean($_POST['object_type']).'\',\''.$admin.'\')">#page#</a>');

        assign('comments',$comments);
        assign('type',$params['type']);
        assign('type_id',$params['type_id']);
        assign('last_update',$params['last_update']);
        assign('total',$_POST['total_comments']);
        assign('total_pages',$total_pages);
        assign('comments_voting',$_POST['comments_voting']);

        if($_POST['admin']=='yes' && has_access('admin_access',true))
        {
            Template(BASEDIR.'/'.ADMINDIR.'/'.TEMPLATEFOLDER.'/cbv3/layout/blocks/comments.html',false);
            exit();
        }else
        {
            $template = get_template('comments');
        }

        assign('commentPagination','yes');

        $template .= get_template('pagination');

        echo json_encode(array('success'=>'yes','output'=>$template));

    }
    break;


    default:
        exit(json_encode(array('err'=>array(lang('Invalid request')))));
}
?>