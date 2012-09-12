<?php

/**
 * file_name : functions_videos.php
 * This function file contains all functions that are related to video section
 * @Author : Arslan
 * @Script : ClipBucket
 * @Since : 2.7
 */

/**
 * Get old time format
 *
 * ClipBucket uses different functions to convert time
 * This function simply converts seconds in MM:SS format
 * its old because it does not support Hours
 *
 * @since 1.6
 *
 * @param int $temps Duration of video in seconds
 * @return STRING Duration of video in mm:ss format
 */
function old_set_time($temps) {
    round($temps);
    $heures = floor($temps / 3600);
    $minutes = round(floor(($temps - ($heures * 3600)) / 60));
    if ($minutes < 10)
        $minutes = "0" . round($minutes);
    $secondes = round($temps - ($heures * 3600) - ($minutes * 60));
    if ($secondes < 10)
        $secondes = "0" . round($secondes);
    return $minutes . ':' . $secondes;
}

/**
 * Get video duration in H:M:S format
 *
 * This function works the same as old_set_time() in addition 
 * of Hours format. It also converts the video duration in 
 * H:M:S format
 *
 * @since : 2.x
 * 
 * @param $sec INT video duration in seconds
 * @param $padHours BOLLEAN weather to display hours or not
 * @return STRING video duration in H:M:S format
 */
function SetTime($sec, $padHours = true) {

    if ($sec < 3600)
        return old_set_time($sec);

    $hms = "";

    // there are 3600 seconds in an hour, so if we
    // divide total seconds by 3600 and throw away
    // the remainder, we've got the number of hours
    $hours = intval(intval($sec) / 3600);

    // add to $hms, with a leading 0 if asked for
    $hms .= ($padHours) ? str_pad($hours, 2, "0", STR_PAD_LEFT) . ':' : $hours . ':';

    // dividing the total seconds by 60 will give us
    // the number of minutes, but we're interested in
    // minutes past the hour: to get that, we need to
    // divide by 60 again and keep the remainder
    $minutes = intval(($sec / 60) % 60);

    // then add to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT) . ':';

    // seconds are simple - just divide the total
    // seconds by 60 and keep the remainder
    $seconds = intval($sec % 60);

    // add to $hms, again with a leading 0 if needed
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    return $hms;
}

function duration($time, $pad = true) {
    return SetTime($time, $pad);
}

/**
 * Get thumbnails of a video
 * 
 * This function will get all video thumbnails
 * and list them in array, you can either get single
 * thumb or number or size. This function has few limitations
 * that we will try to cover in upcoming updates.
 *
 * @since 2.x
 * @uses default_thumb();
 * @uses MyQuery->get_video_details();
 *
 * @param $vdetails ARRAY video details, array('videod','title'...) or it can be just STRING videoid
 * @param $num STRING number of thumb , if you want to get thumb-2 , you will set 2, default value is 'default' which return 1
 * @param $multi BOOLEAN weather to return ALL thumbnails in array or just single thumb
 * @param $count BOOLEAN just count thumbs or not, if set to true, function will return number of thumb INT only
 * @param $return_full_path BOOLEAN if set to true, thumb will be return along with THUMBS_URL e.g http://cb/thumb/file-1.jpg
 * if set to false, it will return file-1.jpg
 * @param $return_big BOOLEAN weather to return BIG thumbnail or not, if set true, it will return file-big.jpg
 *
 * @since 2.6
 * @param $size STRING dimension of thumb, it can be 120x90, 320x240, it was introduced in 2.6 to get more thumbs
 * using the same funcion.
 * @return STRING video thumbnail with/without path or ARRAY list of video thumbs or INT just number of thumbs
 * 
 */
function get_thumb($vdetails, $num = 'default', $multi = false, $count = false, $return_full_path = true, $return_big = true, $size = NULL) 
{
    global $db, $Cbucket, $myquery;

        
    if(!is_array($vdetails))
        $vdetails = $myquery->get_video_details($vdetails);
    if($vdetails['thumbs'])
    {
        $thumbs = json_decode($vdetails['thumbs'],true);
        
        $thumb_size = get_size_by_name($size);
        if(!$thumb_size)
            $thumb_size = $size;
        
        if(!is_numeric($num) || $num < 1)
            $num = 1;
        $img = $thumbs[$thumb_size][$num-1];
        
        if(!$img)
            $img = $thumbs[get_size_by_name('default')][0];
        if(!$img)
        {
            if(is_array($thumbs))
            foreach($thumbs as $thumb)
            {
                $img = $thumb[0];
                break;
            }
        }
        
        
        if($img)
        {
            if($count)
                return count($thumbs[$thumb_size]);
            
            if($multi)
                if($multi=='all')
                    return $thumbs;
                else
                    return $thumbs[$thumb_size];
            
            $folder = '';
            $folder = $vdetails['file_directory'];
            if($folder)
                $folder .= '/';
            
            $path = THUMBS_URL.'/'.$folder;  
            return $path.$img;
        }
    }
    
    $num = $num ? $num : 'default';
    //checking what kind of input we have
    if (is_array($vdetails)) {
        if (empty($vdetails['title'])) {
            //check for videoid
            if (empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey'])) {
                if ($multi)
                    return $dthumb[0] = default_thumb();
                return default_thumb();
            }else {
                if (!empty($vdetails['videoid']))
                    $vid = $vdetails['videoid'];
                elseif (!empty($vdetails['vid']))
                    $vid = $vdetails['vid'];
                elseif (!empty($vdetails['videokey']))
                    $vid = $vdetails['videokey'];
                else {
                    if ($multi)
                        return $dthumb[0] = default_thumb();
                    return default_thumb();
                }
            }
        }
    }else {
        if (is_numeric($vdetails))
            $vid = $vdetails;
        else {
            if ($multi)
                return $dthumb[0] = default_thumb();
            return default_thumb();
        }
    }


    //checking if we have vid , so fetch the details
    if (!empty($vid))
        $vdetails = $myquery->get_video_details($vid);

    if (empty($vdetails['title'])) {
        if ($multi)
            return default_thumb();
        return default_thumb();
    }

    //Checking if there is any custom function for
    if (count($Cbucket->custom_get_thumb_funcs) > 0) {

        foreach ($Cbucket->custom_get_thumb_funcs as $funcs) {

            //Merging inputs
            $in_array = array(
                'num' => $num,
                'multi' => $multi,
                'count' => $count,
                'return_full_path' => $return_full_path,
                'return_big' => $return_big
            );
            if (function_exists($funcs)) {
                $func_returned = $funcs($vdetails, $in_array);
                if ($func_returned)
                    return $func_returned;
            }
        }
    }

    #get all possible thumbs of video
    if ($vdetails['file_name'])
        $vid_thumbs = glob(THUMBS_DIR . "/" . $vdetails['file_name'] . "*");
    #replace Dir with URL
    if (is_array($vid_thumbs))
        foreach ($vid_thumbs as $thumb) {
            if (file_exists($thumb) && filesize($thumb) > 0) {
                $thumb_parts = explode('/', $thumb);
                $thumb_file = $thumb_parts[count($thumb_parts) - 1];

                if (!is_big($thumb_file) || $return_big) {
                    if ($return_full_path)
                        $thumbs[] = THUMBS_URL . '/' . $thumb_file;
                    else
                        $thumbs[] = $thumb_file;
                }
            }elseif (file_exists($thumb))
                unlink($thumb);
        }

    if (count($thumbs) == 0) {
        if ($count)
            return count($thumbs);
        if ($multi)
            return $dthumb[0] = default_thumb();
        return default_thumb();
    }
    else {
        if ($multi)
            return $thumbs;
        if ($count)
            return count($thumbs);

        //Now checking for thumb
        if ($num == 'default') {
            $num = $vdetails['default_thumb'];
        }
        if ($num == 'big' || $size == 'big') {

            $num = 'big-' . $vdetails['default_thumb'];
            if (!file_exists(THUMBS_DIR . '/' . $vdetails['file_name'] . '-' . $num . '.jpg'))
                $num = 'big';
        }

        $default_thumb = array_find($vdetails['file_name'] . '-' . $num, $thumbs);

        if (!empty($default_thumb))
            return $default_thumb;
        return $thumbs[0];
    }
}



/**
 * Check input file is a big thumb or not
 *
 * @param STRING thumb_file name
 * @return BOOLEAN true|false
 */
function is_big($thumb_file) {
    if (strstr($thumb_file, 'big'))
        return true;
    else
        return false;
}

/**
 * function used to get default thumb of ClipBucket 
 *
 * When there is no video thumb, clipbucket will display a processing thumb
 * which can either be located in images folder of ClipBucket selected template
 * or in files/thumbs folder, default image name is always 'processing.jpg' or 'processing.png'
 *
 * @return STRING default thumb with URL
 */
function default_thumb() {
    //Checking file .png exists in template  or not
    if (file_exists(TEMPLATEDIR . '/images/processing.png')) {
        return TEMPLATEURL . '/images/processing.png';

        //else try .jpg file
    } elseif (file_exists(TEMPLATEDIR . '/images/processing.jpg')) {
        return TEMPLATEURL . '/images/processing.jpg';
    }else
    //else return file from files/thumbs folder
        return BASEURL . '/files/thumbs/processing.jpg';
}

/**
 * check weather input thumb is 'default' 
 * 
 * @param STRING thumbFile $i
 * @return BOOLEAN 
 */
function is_default_thumb($i) {
    if (getname($i) == 'processing.jpg')
        return true;
    else
        return false;
}

/**
 * Gets link of video
 *
 * Get video link depending how you have configured clipbucket
 * SEO or Non-Seo or change patterns.
 *
 * @param ARRAY video details or it can be INT videoid
 * @param STRING type , {link|download}
 */
function video_link($vdetails, $type = NULL) {
    global $myquery, $db;
    #checking what kind of input we have
    if (is_array($vdetails)) {
        if (empty($vdetails['title'])) {
            #check for videoid
            if (empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey'])) {
                return BASEURL;
            } else {
                if (!empty($vdetails['videoid']))
                    $vid = $vdetails['videoid'];
                elseif (!empty($vdetails['vid']))
                    $vid = $vdetails['vid'];
                elseif (!empty($vdetails['videokey']))
                    $vid = $vdetails['videokey'];
                else
                    return BASEURL;
            }
        }
    }else {
        if (is_numeric($vdetails))
            $vid = $vdetails;
        else
            return BASEURL;
    }
    #checking if we have vid , so fetch the details
    if (!empty($vid))
        $vdetails = $myquery->get_video_details($vid);

    //calling for custom video link functions
    $functions = cb_get_functions('video_link');
    if ($functions) {
        foreach ($functions as $func) {
            $array = array('vdetails' => $vdetails, 'type' => $type);
            if (function_exists($func['func'])) {
                $returned = $func['func']($array);
                if ($returned) {
                    $link = $returned;
                    return $link;
                    break;
                }
            }
        }
    }

    $plist = "";
    if (SEO == 'yes') {

        if ($vdetails['playlist_id'])
            $plist = '?play_list=' . $vdetails['playlist_id'];

        switch (config('seo_vido_url')) {
            default:
                $link = BASEURL . '/video/' . $vdetails['videokey'] . '/' . SEO(clean(str_replace(' ', '-', $vdetails['title']))) . $plist;
                break;

            case 1: {
                    $link = BASEURL . '/' . SEO(clean(str_replace(' ', '-', $vdetails['title']))) . '_v' . $vdetails['videoid'] . $plist;
                }
                break;

            case 2: {
                    $link = BASEURL . '/video/' . $vdetails['videoid'] . '/' . SEO(clean(str_replace(' ', '-', $vdetails['title']))) . $plist;
                }
                break;

            case 3: {
                    $link = BASEURL . '/video/' . $vdetails['videoid'] . '_' . SEO(clean(str_replace(' ', '-', $vdetails['title']))) . $plist;
                }

            case 4: {
                    if ($vdetails['slug']) {
                        $link = BASEURL . '/video/'
                                . $vdetails['slug']
                                . $plist;
                    } else {
                        //check if slug was recently added...
                        $slug_arr = get_slug($vdetails['videoid'], 'v');

                        if (!$slug_arr) {
                            $slug_arr = add_slug(slug($vdetails['title']), $vdetails['videoid'], 'v');
                            $db->update(tbl('video'), array('slug_id'), array($slug_arr['id'])
                                    , "videoid='" . $vdetails['videoid'] . "'");
                        }

                        $link = BASEURL . '/video/'
                                . $slug_arr['slug']
                                . $plist;
                    }
                }
                break;
        }
    } else {
        if ($vdetails['playlist_id'])
            $plist = '&play_list=' . $vdetails['playlist_id'];
        $link = BASEURL . '/watch_video.php?v=' . $vdetails['videokey'] . $plist;
    }
    if (!$type || $type == 'link')
        return $link;
    elseif ($type == 'download')
        return BASEURL . '/download.php?v=' . $vdetails['videokey'];
}

/**
 * get video thumb in smart template
 * 
 * This is an alias of get_thumb() function to get thumb in templates
 * please read our documentation about template functions for more information
 * about {getSmartyThumb|getThumb}
 */
function getSmartyThumb($params) {
    return get_thumb($params['vdetails'], $params['num'], $params['multi'], $params['count_only'], true, true, $params['size']);
}

/**
 * Function used to check weather video has Mp4 file or not
 */
function has_hq($vdetails, $is_file = false) {
    $custom_funcs = cb_get_functions('has_hq');
    if ($custom_funcs && !$is_file)
        foreach ($custom_funcs as $func) {
            if (function_exists($func)) {
                return $func($vdetails);
            }
        }

    if (!$is_file)
        $file = get_hq_video_file($vdetails);
    else
        $file = $vdetails;

    if (getext($file) == 'mp4' && !strstr($file, '-m'))
        return $file;
    else
        return false;
}

/**
 * Gets video link , used in Smarty 
 * {VideoLink vdetails=$vdata type=link,playlist assign=somevar}
 * @param type $params
 * @return type 
 */
function videoSmartyLink($params) {
    $link = VideoLink($params['vdetails'], $params['type']);
    if (!$params['assign'])
        return $link;
    else
        assign($params['assign'], $link);
}

/**
 * function used to validate Video Category
 * 
 * @global type $myquery
 * @global type $LANG
 * @global type $cbvid
 * @param type $array
 * @return type 
 */
function validate_vid_category($array = NULL) {
    global $myquery, $LANG, $cbvid;
    if ($array == NULL)
        $array = $_POST['category'];
    if (count($array) == 0)
        return false;
    else {

        foreach ($array as $arr) {
            if ($cbvid->category_exists($arr))
                $new_array[] = $arr;
        }
    }
    if (count($new_array) == 0) {
        e(lang('vdo_cat_err3'));
        return false;
    } elseif (count($new_array) > ALLOWED_VDO_CATS) {
        e(sprintf(lang('vdo_cat_err2'), ALLOWED_VDO_CATS));
        return false;
    }

    return true;
}

/**
 * Function used to check videokey exists or not
 * key_exists
 */
function vkey_exists($key) {
    global $db;
    $db->select(tbl("video"), "videokey", " videokey='$key'");
    if ($db->num_rows > 0)
        return true;
    else
        return false;
}

/**
 * Function used to check file_name exists or not
 * as its a unique name so it will not let repost the data
 */
function file_name_exists($name) {
    global $db;
    $results = $db->select(tbl("video"), "videoid,file_name", " file_name='$name'");

    if ($db->num_rows > 0)
        return $results[0]['videoid'];
    else
        return false;
}

/**
 * Function used to get video from conversion queue
 */
function get_queued_video($update = TRUE) {
    global $db;
    $max_conversion = config('max_conversion');
    $max_conversion = $max_conversion ? $max_conversion : 2;
    $max_time_wait = config('max_time_wait'); //Maximum Time Wait to make PRocessing Video Automatcially OK
    $max_time_wait = $max_time_wait ? $max_time_wait : 7200;

    //First Check How Many Videos Are In Queu Already
    $processing = $db->count(tbl("conversion_queue"), "cqueue_id", " cqueue_conversion='p' ");
    if (true) {
        if ($fileName) {
            $queueName = getName($fileName);
            $ext = getExt($fileName);
            $fileNameQuery = " AND cqueue_name ='$queueName' AND cqueue_ext ='$ext' ";
        }
        $results = $db->select(tbl("conversion_queue"), "*", "cqueue_conversion='no' $fileNameQuery", 1);
        $result = $results[0];
        if ($update)
            $db->update(tbl("conversion_queue"), array("cqueue_conversion", "time_started"), array("p", time()), " cqueue_id = '" . $result['cqueue_id'] . "'");
        return $result;
    }else {
        //Checking if video is taking more than $max_time_wait to convert so we can change its time to 
        //OK Automatically
        //Getting All Videos That are being processed
        $results = $db->select(tbl("conversion_queue"), "*", " cqueue_conversion='p' ");
        foreach ($results as $vid) {
            if ($vid['time_started']) {
                if ($vid['time_started'])
                    $time_started = $vid['time_started'];
                else
                    $time_started = strtotime($vid['date_added']);

                $elapsed_time = time() - $time_started;

                if ($elapsed_time > $max_time_wait) {
                    //CHanging Status TO OK
                    $db->update(tbl("conversion_queue"), array("cqueue_conversion"), array("yes"), " cqueue_id = '" . $result['cqueue_id'] . "'");
                }
            }
        }
        return false;
    }
}

/**
 * Function used to get video being processed
 */
function get_video_being_processed($fileName = NULL) {
    global $db;

    if ($fileName) {
        $queueName = getName($fileName);
        $ext = getExt($fileName);
        $fileNameQuery = " AND cqueue_name ='$queueName' AND cqueue_ext ='$ext' ";
    }

    $results = $db->select(tbl("conversion_queue"), "*", "cqueue_conversion='p' $fileNameQuery");
    return $results;
}

function get_video_details($vid = NULL) {
    global $myquery;
    if (!$vid)
        global $vid;
    return $myquery->get_video_details($vid);
}

/**
 * Function used to get all video files
 * @param Vdetails
 * @param $count_only
 * @param $with_path
 */
function get_all_video_files($vdetails, $count_only = false, $with_path = false) {
    $details = get_video_file($vdetails, true, $with_path, true, $count_only);
    if ($count_only)
        return count($details);
    return $details;
}

function get_all_video_files_smarty($params) {
    $vdetails = $params['vdetails'];
    $count_only = $params['count_only'];
    $with_path = $params['with_path'];
    return get_all_video_files($vdetails, $count_only, $with_path);
}

/**
 * Function use to get video files
 */
function get_video_file($vdetails, $return_default = true, $with_path = true, $multi = false, $count_only = false, $hq = false) {
    global $Cbucket;
    # checking if there is any other functions
    # available
    if (is_array($Cbucket->custom_video_file_funcs))
        foreach ($Cbucket->custom_video_file_funcs as $func)
            if (function_exists($func)) {
                $func_returned = $func($vdetails, $hq);
                if ($func_returned)
                    return $func_returned;
            }

    #Now there is no function so lets continue as (WITH .files)
    if ($vdetails['file_name'])
        $vid_files = glob(VIDEOS_DIR . "/" . $vdetails['file_name'] . ".*");

    #Now there is no function so lets continue as (WITH - files)
    if ($vdetails['file_name'])
        $vid_files_more = glob(VIDEOS_DIR . "/" . $vdetails['file_name'] . "-*");

    if ($vid_files && $vid_files_more)
        $vid_files = array_merge($vid_files, $vid_files_more);


    #replace Dir with URL
    if (is_array($vid_files))
        foreach ($vid_files as $file) {
            $files_part = explode('/', $file);
            $video_file = $files_part[count($files_part) - 1];

            if ($with_path)
                $files[] = VIDEOS_URL . '/' . $video_file;
            else
                $files[] = $video_file;
        }

    if (count($files) == 0 && !$multi && !$count_only) {
        if ($return_default) {
            if ($with_path)
                return VIDEOS_URL . '/no_video.flv';
            else
                return 'no_video.flv';
        }else {
            return false;
        }
    } else {
        if ($multi)
            return $files;
        if ($count_only)
            return count($files);

        foreach ($files as $file) {
            if ($hq) {
                if (getext($file) == 'mp4' && !strstr($file, '-m')) {
                    return $file;
                    break;
                }
            } else {
                return $file;
                break;
            }
        }
        return $files[0];
    }
}

/**
 * FUnction used to get HQ ie mp4 video
 */
function get_hq_video_file($vdetails, $return_default = true) {
    return get_video_file($vdetails, $return_default, true, false, false, true);
}

/**
 * Function used to update processed video
 * @param Files details
 */
function update_processed_video($file_array, $status = 'Successful', $ingore_file_status = false, $failed_status = '') {
    global $db;
    $file = $file_array['cqueue_name'];
    $array = explode('-', $file);

    if (!empty($array[0]))
        $file_name = $array[0];
    $file_name = $file;

    $file_path = VIDEOS_DIR . '/' . $file_array['cqueue_name'] . '.flv';
    $file_size = @filesize($file_path);

    if (file_exists($file_path) && $file_size > 0 && !$ingore_file_status) {
        $stats = get_file_details($file_name);

        //$duration = $stats['output_duration'];
        //if(!$duration)
        //	$duration = $stats['duration'];

        $duration = parse_duration(LOGS_DIR . '/' . $file_array['cqueue_name'] . '.log');

        $db->update(tbl("video"), array("status", "duration", "failed_reason"), array($status, $duration, $failed_status), " file_name='" . $file_name . "'");
    } else {
        $stats = get_file_details($file_name);

        //$duration = $stats['output_duration'];
        //if(!$duration)
        //	$duration = $stats['duration'];

        $duration = parse_duration(LOGS_DIR . '/' . $file_array['cqueue_name'] . '.log');

        $db->update(tbl("video"), array("status", "duration", "failed_reason"), array('Failed', $duration, $failed_status), " file_name='" . $file_name . "'");
    }
}

/**
 * This function will activate the video if file exists
 */
function activate_video_with_file($vid) {
    global $db;
    $vdetails = get_video_details($vid);
    $file_name = $vdetails['file_name'];
    $results = $db->select(tbl("conversion_queue"), "*", " cqueue_name='$file_name' AND cqueue_conversion='yes'");
    $result = $results[0];
    update_processed_video($result);
}

/**
 * Function Used to get video file stats from database
 * @param FILE_NAME
 */
function get_file_details($file_name) {
    global $db;
    //$result = $db->select(tbl("video_files"),"*"," id ='$file_name' OR src_name = '$file_name' ");
    //Reading Log File
    $file = LOGS_DIR . '/' . $file_name . '.log';
    if (!file_exists($file))
        $file = $file_name;
    if (file_exists($file)) {
        $data = file_get_contents($file);
        //$file = file_get_contents('1260270267.log');

        preg_match_all('/(.*) : (.*)/', trim($data), $matches);

        $matches_1 = ($matches[1]);
        $matches_2 = ($matches[2]);

        for ($i = 0; $i < count($matches_1); $i++) {
            $statistics[trim($matches_1[$i])] = trim($matches_2[$i]);
        }
        if (count($matches_1) == 0) {
            return false;
        }
        $statistics['conversion_log'] = $data;
        return $statistics;
    }else
        return false;
}

function parse_duration($log) {
    $duration = false;
    $log_details = get_file_details($log);
    $duration = $log['output_duration'];
    if (!$duration || !is_numeric($duration))
        $duration = $log['duration'];

    if (!$duration || !is_numeric($duration)) {
        if (file_exists($log))
            $log_content = file_get_contents($log);

        //Parse duration..
        preg_match_all('/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9.]{1,5})/i', $log_content, $matches);

        unset($log_content);

        //Now we will multiple hours, minutes accordingly and then add up with seconds to 
        //make a single variable of duration

        $hours = $matches[1][0];
        $minutes = $matches[2][0];
        $seconds = $matches[3][0];

        $hours = $hours * 60 * 60;
        $minutes = $minutes * 60;
        $duration = $hours + $minutes + $seconds;

        $duration;
    }
    return $duration;
}

/**
 * Function used to get thumbnail number from its name
 * Updated: If we provide full path for some reason and 
 * web-address has '-' in it, this means our result is messed.
 * But we know our number will always be in last index
 * So wrap it with end() and problem solved.
 */
function get_thumb_num($name) {
    $list = end(explode('-', $name));
    $list = explode('.', $list);
    return $list[0];
}

/**
 * Function used to remove thumb
 */
function delete_video_thumb($file) {
    global $LANG;
    $path = THUMBS_DIR . '/' . $file;
    if (file_exists($path)) {
        unlink($path);
        e(lang('video_thumb_delete_msg'), 'm');
    } else {
        e(lang('video_thumb_delete_err'));
    }
}

/**
 * function used to remove video thumbs
 */
function remove_video_thumbs($vdetails) {
    global $cbvid;
    return $cbvid->remove_thumbs($vdetails);
}

/**
 * function used to remove video log
 */
function remove_video_log($vdetails) {
    global $cbvid;
    return $cbvid->remove_log($vdetails);
}

/**
 * function used to remove video files
 */
function remove_video_files($vdetails) {
    global $cbvid;
    return $cbvid->remove_files($vdetails);
}

/**
 * Function used to check video is playlable or not
 * @param vkey,vid
 */
function video_playable($id) {
    global $cbvideo, $userquery;

    if (isset($_POST['watch_protected_video']))
        $video_password = mysql_clean(post('video_password'));
    else
        $video_password = '';

    if (!is_array($id))
        $vdo = $cbvideo->get_video($id);
    else
        $vdo = $id;
    $uid = userid();
    if (!$vdo) {
        e(lang("class_vdo_del_err"));
        return false;
    } elseif ($vdo['status'] != 'Successful') {
        e(lang("this_vdo_not_working"));
        if (!has_access('admin_access', TRUE))
            return false;
        else
            return true;
    }elseif ($vdo['broadcast'] == 'private'
            && !$userquery->is_confirmed_friend($vdo['userid'], userid())
            && !is_video_user($vdo)
            && !has_access('video_moderation', true)
            && $vdo['userid'] != $uid) {
        e(lang('private_video_error'));
        return false;
    } elseif ($vdo['active'] == 'pen') {
        e(lang("video_in_pending_list"));
        if (has_access('admin_access', TRUE) || $vdo['userid'] == userid())
            return true;
        else
            return false;
    }elseif ($vdo['broadcast'] == 'logged'
            && !userid()
            && !has_access('video_moderation', true)
            && $vdo['userid'] != $uid) {
        e(lang('not_logged_video_error'));
        return false;
    } elseif ($vdo['active'] == 'no') {
        e(lang("vdo_iac_msg"));
        if (!has_access('admin_access', TRUE))
            return false;
        else
            return true;
    }
    //No Checking for video password
    elseif ($vdo['video_password']
            && $vdo['broadcast'] == 'unlisted'
            && $vdo['video_password'] != $video_password
            && !has_access('video_moderation', true)
            && $vdo['userid'] != $uid) {
        if (!$video_password)
            e(lang("video_pass_protected"));
        else
            e(lang("invalid_video_password"));
        template_files("blocks/watch_video/video_password.html", false, false);
    }
    else {
        $funcs = cb_get_functions('watch_video');

        if ($funcs)
            foreach ($funcs as $func) {
                $data = $func['func']($vdo);
                if ($data)
                    return $data;
            }
        return true;
    }
}

/**
 * function used to get vidos
 */
function get_videos($param) {
    global $cbvideo;
    return $cbvideo->get_videos($param);
}

/**
 * function used to get allowed extension as in array
 */
function get_vid_extensions() {
    $exts = config('allowed_types');
    $exts = preg_replace("/ /", "", $exts);
    $exts = explode(",", $exts);
    return $exts;
}

function check_cbvideo() {
    /**
     * dont ever forget its name
     * its a damn ClipBucket
     */
}

/**
 * Gives coversion process output
 */
function conv_status($in) {
    switch ($in) {
        case "p":
            return "Processing";
            break;
        case "no":
            return "Pending";
            break;
        case "yes":
            return "Done";
            break;
    }
}

/**
 * Function used to check 
 * input users are valid or not
 * so that only registere usernames can be set
 */
function video_users($users) {
    global $userquery;
    $users_array = explode(',', $users);
    $new_users = array();

    if ($users_array)
        foreach ($users_array as $user) {
            if ($user != username() && !is_numeric($user) && $userquery->user_exists($user)
                    && $user) {
                $new_users[] = $user;
            }
        }

    $new_users = array_unique($new_users);

    if (count($new_users) > 0) {
        return implode(',', $new_users);
    } else {
        return " ";
    }
}

/**
 * function used to check weather logged in user is
 * is in video users or not
 */
function is_video_user($vdo, $user = NULL) {

    if (!$user)
        $user = username();
    if (is_array($vdo))
        $video_users = $vdo['video_users'];
    else
        $video_users = $vdo;


    $users_array = explode(',', $video_users);

    if (in_array($user, $users_array))
        return true;
    else
        return false;
}

/**
 * function used to delete vidoe from collections
 */
function delete_video_from_collection($vdetails) {
    global $cbvid;
    $cbvid->collection->deleteItemFromCollections($vdetails['videoid']);
}

/**
 *
 * @param type $vdetails
 * @param type $num
 * @param type $multi
 * @param type $count
 * @return type 
 */
function GetThumb($vdetails, $num = 'default', $multi = false, $count = false) {
    return get_thumb($vdetails, $num, $multi, $count);
}

/**
 *
 * @param type $vdetails
 * @param type $type
 * @return type 
 */
function VideoLink($vdetails, $type = NULL) {
    return video_link($vdetails, $type);
}

/**
 * @method get video seo name
 * @name video_slug
 * @author Arslan
 * @param ARRAY video details
 */
function video_slug($video) {
    if (!$video['title'])
        return false;

    $slug = slug($video['title']);
    $theslug = $slug;
    $count = 0;
    while (1) {
        if (!video_slug_exists($theslug))
            break;
        $count++;
        $theslug = $slug . '-' . $count;
    }


    return $theslug;
}

/**
 * @todo check video slug exists or not
 * @name video_slug_exists
 * @author Arslan
 * @param STRNG slug
 */
function video_slug_exists($slug) {
    return slug_exists($slug, 'v');
}


/**
 * checks weather a video has thumb or not
 * this function is pretty basic for now, will improve it later
 * it will check weather /path/to/thumbs ... default.jpg exists or not
 * 
 * @author Arslan 
 * @param ARRAY video details
 * @return Boolean/Array
 */
function hasThumbs($video)
{
    $file = $video['file_name'];
    if(!$file)
        $file = $video['queue_name'];
    
    //Now lets do this,.,....
    $filepath = $video['file_directory'].'/'.$file;
    $thumbsDir = THUMBS_DIR.'/'.$filepath;
    
    $files = glob($thumbsDir.'*.jpg');
    
    if($files)
        return $files;
    else
        return false;
}
/**
 * Alias of hasThumbs
 */
function has_thumbs($video){ return hasThumbs($video); }

/**
 * add thumb size for custom thumb sizes
 * 
 * @param STRING $size size dimension wxh
 * @param STRING $name DEFAULT => same as size
 * 
 */

function add_thumb_size($size,$name=NULL)
{
    
    global $Cbucket;
    if(!$name)
        $name = $size;
    
    $Cbucket->thumb_sizes[$name] = $size;
    
    return true;    
}

/**
 * Get thumb sizes registered using add_thumb_size function
 * 
 */
function get_thumb_sizes()
{
    global $Cbucket;
    $sizes = $Cbucket->thumb_sizes;
    $sizes = apply_filters($sizes, 'thumb_sizes');
    
    return $sizes;
}


/**
 * Get thumb size from name
 */
function get_size_by_name($name)
{
    global $Cbucket;
    $sizes = $Cbucket->thumb_sizes;
    
    
    return $sizes[$name];
}