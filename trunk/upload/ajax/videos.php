<?php

/**
 * All ajax requests that are related videos or its object will be listed here
 *  
 * @author Arslan Hassan
 * @license AAL
 * @since 3.0 
 */
include('../includes/config.inc.php');

//Getting mode..
$mode = post('mode');
if (!$mode)
    $mode - get('mode');

$mode = mysql_clean($mode);


switch ($mode) {
    case "delete_videos": {

            try {

                $vids = post('vids');
                $_vids = array();

                if (!$vids)
                    cb_error(lang('No video was selected'));


                //Now lets remove all these videos
                foreach ($vids as $vid) {
                    if ($vid)
                        $cbvideo->delete_video($vid);
                }

                if (error())
                    cb_error(error('single'));

                exit(json_encode(array('success' => 'yes')));
            } catch (Exception $e) {

                exit(json_encode(array('err' => array($e->getMessage()))));
            }
        }
        break;

    case "update_broadcast": {

            try {
                
                $vids = post('vids');
                $type = post('type');
                
                $_vids = array();

                if (!$vids)
                    cb_error(lang('No video was selected'));


                //Now lets remove all these videos
                foreach ($vids as $vid) {
                    if ($vid)
                        $cbvideo->update_broadcast($vid,$type);
                }

                if (error())
                    cb_error(error('single'));

                exit(json_encode(array('success' => 'yes','label'=>ucfirst($type))));
                
            } catch (Exception $e) {

                exit(json_encode(array('err' => array($e->getMessage()))));
            }
        }
        break;

    default:
        exit(json_encode(array('err' => lang('Invalid request'))));
}
?>