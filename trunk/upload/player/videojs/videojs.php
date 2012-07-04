<?php
/*
   Player Name: Video JS 1.0
   Description: HTML5 Player with Many HOT Features.
   Author: Arslan Hassan
   Min Version : 3.0
   Plugin Version: 1.0
   Website: http://clip-bucket.com/pak-player
 * @Author : Arslan Hassan
 * @Script : ClipBucket v3.0
 * @Since : 7/2/2012
 */


    define('VIDEO_JS_DIR_NAME',basename(dirname(__FILE__)));
    define("VIDEO_JS_DIR",PLAYER_DIR.'/'.VIDEO_JS_DIR_NAME);
    define("VIDEO_JS_URL",PLAYER_URL.'/'.VIDEO_JS_DIR_NAME);
    
    /* CONFIGS */
    define('USE_VIDEOJS_CDN',true);
    
    /**
     * PLayer video in video-js player
     * @global boolean $pak_player
     * @param type $in
     * @return boolean 
     */
    function video_js($in)
    {
        assign('configs',$in);
        
        $player = fetch(VIDEO_JS_DIR.'/player.html',false);
        return $player;
    }
    
    register_action('video_js','play_video');
    
    if(USE_VIDEOJS_CDN)
    {
        add_js('http://vjs.zencdn.net/c/video.js','watch_video');
        add_css('http://vjs.zencdn.net/c/video-js.css','watch_video');
    }else
    {
        add_js(VIDEO_JS_URL.'/video.min.js','watch_video');
        add_css(VIDEO_JS_URL.'/video-js.css','watch_video');
    }
?>