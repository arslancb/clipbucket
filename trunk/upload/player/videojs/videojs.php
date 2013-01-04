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
    define('USE_VIDEOJS_CDN',false);
    
    /**
     * PLayer video in video-js player
     * @global boolean $pak_player
     * @param type $in
     * @return boolean 
     */
    function video_js($in)
    {
        //Assigning configs so we can easily use them in template..
        assign('configs',$in);
        
        //get the file for video...
        $files = $in['files'];
        
        if($files)
        foreach($files as $key => $file)
        {
            if($key)
            if($key=='flv' || $key=='mp4' || $key=='mobile')
            {
                $video_file = $file;
                break;
            }
            
            if($file['status']=='s' && $file['is_original']!='yes')
            {
                $video_file = VIDEOS_URL.'/'.$file['file_directory'].'/';
                $video_file .= $file['file_name'].$file['suffix'].'.'.$file['ext'];
            
                break;
            } 
            
        }
        
        assign('file',$video_file);
        $player = fetch(VIDEO_JS_DIR.'/player.html',false);
        return $player;
    }
    
    register_action('video_js','play_video');
    
    if(USE_VIDEOJS_CDN)
    {
        add_js('http://vjs.zencdn.net/c/video.js','global');
        add_css('http://vjs.zencdn.net/c/video-js.css','global');
    }else
    {
        add_js(VIDEO_JS_URL.'/video.min.js','global');
        add_css(VIDEO_JS_URL.'/video-js.css','global');
    }
?>