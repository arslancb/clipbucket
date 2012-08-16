<?php

/**
 * Api Put method to add/upload/insert stuff
 * on ClipBucket website
 */

$request = $_REQUEST;
$mod = $request['mode'];

switch($mode)
{
    case "upload_video":
    {
        echo json_encode(array('response'=>'ok',$request));
    }
}


?>
