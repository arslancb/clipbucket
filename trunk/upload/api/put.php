<?php

/**
 * Api Put method to add/upload/insert stuff
 * on ClipBucket website
 */
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
                exit(json_encode(array('err' => error())));
            }

            $comment = $myquery->get_comment($cid);
            assign('comment', $comment);
            $template = get_template('single_comment');
            $array = array(
                'msg' => msg(),
                'comment' => $template,
                'success' => 'ok',
                'cid' => $cid
            );

            echo json_encode($array);
        }
}
?>
