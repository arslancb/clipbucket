<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/28/13
 * Time: 10:08 AM
 * To change this template use File | Settings | File Templates.
 */

/**
 * Function used to display flash player for ClipBucket video
 */
function flashPlayer($param)
{
    global $Cbucket,$swfobj;
    $param['player_div'] = $param['player_div'] ? $param['player_div'] : 'videoPlayer';

    $key 		= $param['key'];
    $flv 		= $param['flv'].'.flv';
    $code 		= $param['code'];
    $flv_url 	= $file;
    $embed 		= $param['embed'];
    $code 		= $param['code'];
    $height 	= $param['height'] ? $param['height'] : config('player_height');
    $width 		= $param['width'] ? $param['width'] : config('player_width');
    $param['height'] = $height;
    $param['width'] = $width ;
    $param['enlarge_button'] = config('enlarge_button');
    $param['player_logo_url'] = config('player_logo_url');
    //dump($code);

    if(!$param['autoplay'])
        $param['autoplay'] = config('autoplay_video');

    assign('player_params',$param);



    if(count($Cbucket->actions_play_video)>0)
    {
        foreach($Cbucket->actions_play_video as $funcs )
        {
            if(function_exists($funcs))
            {
                $func_data = $funcs($param);
            }
            if($func_data)
            {
                $player_code = $func_data;
                $show_player  = true;
                break;
            }
        }
    }

    if(function_exists('cbplayer') && empty($player_code))
        $player_code = cbplayer($param,true);
    elseif(function_exists('cbplayer'))
        return $player_code;



    global $pak_player;

    if($player_code)
        if(!$pak_player && $show_player && !is_bool($player_code))
        {
            assign("player_js_code",$player_code);
            Template(PLAYER_DIR.'/player.html',false);
            return false;
        }else
        {

            return false;
        }

    return blank_screen($param);
}


/**
 * FUnctiuon used to plya HQ videos
 */
function HQflashPlayer($param)
{
    return flashPlayer($param);
}


/**
 * Function used to get player from website settings
 */
function get_player()
{
    global $Cbucket;
    return $Cbucket->configs['player_file'];
}

/**
 * Function used to display
 * Blank Screen
 * if there is nothing to play or to show
 * then show a blank screen
 */
function blank_screen($data)
{
    global $swfobj;
    $code = '<div class="blank_screen" align="center">No Player or Video File Found - Unable to Play Any Video</div>';
    $swfobj->EmbedCode(unhtmlentities($code),$data['player_div']);
    return $swfobj->code;
}

function get_current_player() {
    global $cbplayer;
    return $cbplayer->get_player_details( config( 'player_file' ), config( 'player_dir' ) );
}