<?php

if (!function_exists('mob_description'))
{

    function mob_description($description)
    {
        global $Cbucket;

        $description = str_replace('ÿþ', '', $description);
        $description = str_replace('?', 'MY_QUE_MARK', $description);
        $description = utf8_decode($description);
        $description = str_replace('?', '', $description);
        $description = str_replace('MY_QUE_MARK', '?', $description);

        return $description;
    }

}


if (!function_exists('get_mob_video'))
{

    function get_mob_video($params)
    {

        $vdo = $params['video'];

        $assign = $params['assign'];
        $vid_file = get_video_file($vdo, true, true);
        $vidfile = substr($vid_file, 0, strlen($vid_file) - 4) . '-m.mp4';
        assign($assign, $vidfile);

        if ($vidfile)
            return $vidfile;
    }

}


$blacklist_fields = array(
    'password', 'video_password', 'avcode', 'session'
);
?>