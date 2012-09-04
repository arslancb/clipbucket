<?php

/**
 * Api Put method to add/upload/insert stuff
 * on ClipBucket website
 */
include('../includes/config.inc.php');


$request = $_REQUEST;
$mode = $request['mode'];

switch ($mode) {
    case "upload_video": {
            echo json_encode(array('response' => 'ok', $request));
        }
        break;

    case "addComment": {
            $type = $request['type'];
            switch ($type) {
                case 'v':
                case 'video':
                default: {
                        $id = mysql_clean($request['obj_id']);
                        $comment = $request['comment'];
                        if ($comment == 'undefined')
                            $comment = '';
                        $reply_to = $request['reply_to'];

                        $cid = $cbvid->add_comment($comment, $id, $reply_to);
                    }
                    break;
                case 'u':
                case 'c': {

                        $id = mysql_clean($request['obj_id']);
                        $comment = $request['comment'];
                        if ($comment == 'undefined')
                            $comment = '';
                        $reply_to = $request['reply_to'];

                        $cid = $userquery->add_comment($comment, $id, $reply_to);
                    }
                    break;
                case 't':
                case 'topic': {

                        $id = mysql_clean($request['obj_id']);
                        $comment = $request['comment'];
                        if ($comment == 'undefined')
                            $comment = '';
                        $reply_to = $request['reply_to'];

                        $cid = $cbgroup->add_comment($comment, $id, $reply_to);
                    }
                    break;

                case 'cl':
                case 'collection': {
                        $id = mysql_clean($request['obj_id']);
                        $comment = $request['comment'];
                        if ($comment == 'undefined')
                            $comment = '';
                        $reply_to = $request['reply_to'];

                        $cid = $cbcollection->add_comment($comment, $id, $reply_to);
                    }
                    break;

                case "p":
                case "photo": {
                        $id = mysql_clean($request['obj_id']);
                        $comment = $request['comment'];
                        if ($comment == 'undefined')
                            $comment = '';
                        $reply_to = $request['reply_to'];
                        $cid = $cbphoto->add_comment($comment, $id, $reply_to);
                    }
                    break;
            }


            if (error()) {
                exit(json_encode(array('err' => error(), 'session' => $_COOKIE['PHPSESSID'])));
            }

            $comment = $myquery->get_comment($cid);

            $array = array(
                'msg' => msg(),
                'comment' => $comment,
                'success' => 'ok',
                'cid' => $cid
            );

            echo json_encode($array);
        }
        break;

        case "create_playlist":
        case "addPlaylist":
        case "add_playlist":
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

            $type = $request['type'];

            $input = array();
            foreach ($array as $ar) {
                $input[$ar] = mysql_clean($request[$ar]);
            }


            if ($type == 'v' || !isset($type))
                $pid = $cbvid->action->create_playlist($input);

            if (!$type)
                e(lang("Invalid playlist type"));

            if (error()) {
                echo json_encode(array('err' => error(), 'rel' => get_rel_list()));
            } else {
                $playlist = $cbvid->action->get_playlist($pid);

                echo json_encode(array('success' => 'yes', 'rel' => get_rel_list(),
                'pid' => $pid, 'playlist' => $playlist,
                'msg' => msg()));
            }
        }
        break;

        case "delete_playlist": {
            $pid = mysql_clean($request['playlist_id']);
            $cbvid->action->delete_playlist($pid);

            if (error()) {
                echo json_encode(array('err' => error()));
            } else {
                echo json_encode(array('msg' => array(lang('Playlist has been removed'))));
            }
        }
        break;

        case "add_playlist_item": {

            $type = $request['type'];
            $pid = mysql_clean($request['playlist_id']);
            $id = mysql_clean($request['object_id']);
            // $note = mysql_clean(post('note'));

            switch ($type) {
                case 'v':
                default: {
                        $item_id = $cbvid->action->add_playlist_item($pid, $id);

                        if (!error()) {
                            updateObjectStats('plist', 'video', $id);
                            echo json_encode(array('status' => 'ok',
                                'msg' => msg(), 'item_id' => $item_id, 'updated' => nicetime(now())));
                        } else {
                            echo json_encode(array('err' => error()));
                        }
                    }
            }
        }
        break;
        
        case "remove_playlist_item":
        {
            $item_id = mysql_clean($request['item_id']);
            $cbvid->action->delete_playlist_item($item_id);
            if(error())
                echo json_encode(array('err'=>error()));
            else
                echo json_encode(array('success'=>'ok'));
        }
        break;
}
?>
