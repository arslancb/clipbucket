<?php

/**
 * @deprecated since 3.0
 * 
 */
function flashPlayer($params) {
    return cb_video_player($params);
}

/**
 * Loads ClipBucket players...
 * 
 * @global type $Cbucket
 * @global type $swfobj
 * @param type $param
 * @return boolean
 * 
 * @todo Write documentation on this function..
 */
function cb_video_player($param) {
    global $Cbucket, $swfobj;

    $param['player_div'] = $param['player_div'] ? $param['player_div'] : 'videoPlayer';

    $key = $param['key'];
    
    $code = $param['code'];
    $flv_url    = $file;
    $embed      = $param['embed'];
    $code       = $param['code'];
    $height     = $param['height'] ? $param['height'] : config('player_height');
    $width      = $param['width'] ? $param['width'] : config('player_width');
    $param['height'] = $height;
    $param['width'] = $width;


    if (!$param['autoplay'])
        $param['autoplay'] = config('autoplay_video');

    if(!$param['files'])
    {
        global $cbvid;
        $files = $cbvid->get_video_files($param['video']);
        $param['files'] = $files;
    }
    assign('player_params', $param);

    $param = apply_filters($param, 'play_video');

    //Calling actions for play_video
    $output = call_actions('play_video', $param);
    if ($output) {
        $player_code = $output;
        return $output;
    }

    if (function_exists('cbplayer') && empty($player_code))
        $player_code = cbplayer($param, true);

    if ($player_code)
        if (!$pak_player && $show_player) {
            assign("player_js_code", $player_code);
            Template(PLAYER_DIR . '/player.html', false);
            return false;
        } else {
            return false;
        }

    return blank_screen($param);
}

/**
 * FUnctiuon used to plya HQ videos
 */
function HQflashPlayer($param) {
    return flashPlayer($param);
}

/**
 * Function used to get player from website settings
 */
function get_player() {
    global $Cbucket;
    return $Cbucket->configs['player_file'];
}

?>