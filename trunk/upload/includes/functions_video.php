<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/26/13
 * Time: 3:50 PM
 * To change this template use File | Settings | File Templates.
 */

function get_video_fields( $extra = null ) {
    global $cb_columns;
    return $cb_columns->set_object( 'videos' )->get_columns( $extra );
}

/**
 * Function used to check video is playlable or not
 * @param vkey,vid
 */

function video_playable($id)
{
    global $cbvideo,$userquery;

    if(isset($_POST['watch_protected_video']))
        $video_password = mysql_clean(post('video_password'));
    else
        $video_password = '';

    if(!is_array($id))
        $vdo = $cbvideo->get_video($id);
    else
        $vdo = $id;
    $uid = userid();
    if(!$vdo)
    {
        e(lang("class_vdo_del_err"));
        return false;
    }elseif($vdo['status']!='Successful')
    {
        e(lang("this_vdo_not_working"));
        if(!has_access('admin_access',TRUE))
            return false;
        else
            return true;
    }elseif($vdo['broadcast']=='private'
        && !$userquery->is_confirmed_friend($vdo['userid'],userid())
        && !is_video_user($vdo)
        && !has_access('video_moderation',true)
        && $vdo['userid']!=$uid){
        e(lang('private_video_error'));
        return false;
    }elseif($vdo['active'] == 'pen'){
        e(lang("video_in_pending_list"));
        if(has_access('admin_access',TRUE) || $vdo['userid'] == userid())
            return true;
        else
            return false;
    }elseif($vdo['broadcast']=='logged'
        && !userid()
        && !has_access('video_moderation',true)
        && $vdo['userid']!=$uid){
        e(lang('not_logged_video_error'));
        return false;
    }elseif($vdo['active']=='no' )
    {
        e(lang("vdo_iac_msg"));
        if(!has_access('admin_access',TRUE))
            return false;
        else
            return true;
    }
    //No Checking for video password
    elseif($vdo['video_password']
        && $vdo['broadcast']=='unlisted'
        && $vdo['video_password']!=$video_password
        && !has_access('video_moderation',true)
        && $vdo['userid']!=$uid)
    {
        if(!$video_password)
            e(lang("video_pass_protected"));
        else
            e(lang("invalid_video_password"));
        template_files("blocks/watch_video/video_password.html",false,false);
    }
    else
    {
        $funcs = cb_get_functions('watch_video');

        if($funcs)
            foreach($funcs as $func)
            {
                $data = $func['func']($vdo);
                if($data)
                    return $data;
            }
        return true;
    }
}

/**
 * FUNCTION USED TO GET THUMBNAIL
 * @param ARRAY video_details, or videoid will also work
 */

function get_thumb($vdetails,$num='default',$multi=false,$count=false,$return_full_path=true,$return_big=true,$size=false){
    
    //echo $size;
    global $db,$Cbucket,$myquery;
    $num = $num ? $num : 'default';
    #checking what kind of input we have
    if(is_array($vdetails))
    {
        if(empty($vdetails['title']))
        {
            #check for videoid
            if(empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey']))
            {
                if($multi)
                    return $dthumb[0] = default_thumb();
                return default_thumb();
            }
            else
            {
                if(!empty($vdetails['videoid']))
                    $vid = $vdetails['videoid'];
                elseif(!empty($vdetails['vid']))
                    $vid = $vdetails['vid'];
                elseif(!empty($vdetails['videokey']))
                    $vid = $vdetails['videokey'];
                else
                {
                    if($multi)
                        return $dthumb[0] = default_thumb();
                    return default_thumb();
                }
            }
        }
    }else{
        if(is_numeric($vdetails))
            $vid = $vdetails;
        else
        {
            if($multi)
                return $dthumb[0] = default_thumb();
            return default_thumb();
        }
    }


    #checking if we have vid , so fetch the details
    if(!empty($vid))
        $vdetails = get_video_details($vid);


    if(empty($vdetails['title']))
    {
        if($multi)
            return default_thumb();
        return default_thumb();
    }

    
    #Checking if there is any custom function for
    if(count($Cbucket->custom_get_thumb_funcs) > 0)
    {
        foreach($Cbucket->custom_get_thumb_funcs as $funcs)
        {

            //Merging inputs
            $in_array = array(
                'num' => $num,
                'multi' => $multi,
                'count' => $count,
                'return_full_path' => $return_full_path,
                'return_big' => $return_big,
                'size' => $size
            );
            if(function_exists($funcs))
            {
                $func_returned = $funcs($vdetails,$in_array);
                if($func_returned)
                    return $func_returned;
            }
        }
    }
    // echo "hooooo";
    #get all possible thumbs of video
    $thumbDir = (isset($vdetails['file_directory']) && $vdetails['file_directory']) ? $vdetails['file_directory'] : "";
    if(!isset($vdetails['file_directory'])){
        $justDate = explode(" ", $vdetails['date_added']);
        $thumbDir = implode("/", explode("-", array_shift($justDate)));
    }
    if(substr($thumbDir, (strlen($thumbDir) - 1)) !== "/"){
        $thumbDir .= "/";
    }

    //$justDate = explode(" ", $vdetails['date_added']);
    //$dateAdded = implode("/", explode("-", array_shift($justDate)));
    
    $file_dir ="";
    if(isset($vdetails['file_name']) && $thumbDir)
    {
       $file_dir =  "/" . $thumbDir;
    }
    $vid_thumbs = glob(THUMBS_DIR."/" .$file_dir.$vdetails['file_name']."*");
    
   
    #replace Dir with URL
    if(is_array($vid_thumbs))
        foreach($vid_thumbs as $thumb)
        {
            if(file_exists($thumb) && filesize($thumb)>0)
            {
                $thumb_parts = explode('/',$thumb);
                $thumb_file = $thumb_parts[count($thumb_parts)-1];

                if(!is_big($thumb_file) || $return_big)
                {
                    if($return_full_path)
                        $thumbs[] = THUMBS_URL.'/'. $thumbDir . $thumb_file;
                    else
                        $thumbs[] = $thumb_file;
                }
            }elseif(file_exists($thumb))
                unlink($thumb);
        }

    if(count($thumbs)==0)
    {
        if($count)
            return count($thumbs);
        if($multi)
            return $dthumb[0] = default_thumb();
        return default_thumb();
    }
    else
    {
        if($multi)
            return $thumbs;
        if($count)
            return count($thumbs);

        //Now checking for thumb
        if($num=='default')
        {
            $num = $vdetails['default_thumb'];
        }
        if($num=='big' || $size=='big')
        {

            $num = 'big-'.$vdetails['default_thumb'];
            if(!file_exists(THUMBS_DIR.'/'.$vdetails['file_name'].'-'.$num.'.jpg'))
                $num = 'big';
        }

        $default_thumb = array_find($vdetails['file_name'].'-'.$num,$thumbs);

        if(!empty($default_thumb))
            return $default_thumb;
        return $thumbs[0];
    }

}


/**
 * Function used to check weaether given thumb is big or not
 */
function is_big($thumb_file)
{
    if(strstr($thumb_file,'big'))
        return true;
    else
        return false;
}
function GetThumb($vdetails,$num='default',$multi=false,$count=false)
{

    return get_thumb($vdetails,$num,$multi,$count);
}

/**
 * function used to get detaulf thumb of ClipBucket
 */
function default_thumb()
{
    if(file_exists(TEMPLATEDIR.'/images/thumbs/processing.png'))
    {
        return TEMPLATEURL.'/images/thumbs/processing.png';
    }elseif(file_exists(TEMPLATEDIR.'/images/thumbs/processing.jpg'))
    {
        return TEMPLATEURL.'/images/thumbs/processing.jpg';
    }else
        return BASEURL.'/files/thumbs/processing.jpg';
}

/**
 * Function used to check weather give thumb is deafult or not
 */
function is_default_thumb($i)
{
    if(getname($i)=='processing.jpg')
        return true;
    else
        return false;
}

/**
 * Function used to get video link
 * @param ARRAY video details
 */
function video_link($vdetails,$type=NULL)
{
    global $myquery;
    #checking what kind of input we have
    if(is_array($vdetails))
    {
        if(empty($vdetails['title']))
        {
            #check for videoid
            if(empty($vdetails['videoid']) && empty($vdetails['vid']) && empty($vdetails['videokey']))
            {
                return BASEURL;
            }else{
                if(!empty($vdetails['videoid']))
                    $vid = $vdetails['videoid'];
                elseif(!empty($vdetails['vid']))
                    $vid = $vdetails['vid'];
                elseif(!empty($vdetails['videokey']))
                    $vid = $vdetails['videokey'];
                else
                    return BASEURL;
            }
        }
    }else{
        if(is_numeric($vdetails))
            $vid = $vdetails;
        else
            return BASEURL;
    }
    #checking if we have vid , so fetch the details
    if(!empty($vid))
        $vdetails = get_video_details($vid);

    //calling for custom video link functions
    $functions = cb_get_functions('video_link');
    if($functions)
    {
        foreach($functions as $func)
        {
            $array = array('vdetails'=>$vdetails,'type'=>$type);
            if(function_exists($func['func']))
            {
                $returned = $func['func']($array);
                if($returned)
                {
                    $link = $returned;
                    return $link;
                    break;
                }
            }
        }
    }

    $plist = "";
    if(SEO == 'yes'){

        if($vdetails['playlist_id'])
            $plist = '?&play_list='.$vdetails['playlist_id'];

        switch(config('seo_vido_url'))
        {
            default:
                $link = BASEURL.'/video/'.$vdetails['videokey'].'/'.SEO(clean(str_replace(' ','-',$vdetails['title']))).$plist;
                break;

            case 1:
            {
                $link = BASEURL.'/'.SEO(clean(str_replace(' ','-',$vdetails['title']))).'_v'.$vdetails['videoid'].$plist;
            }
                break;

            case 2:
            {
                $link = BASEURL.'/video/'.$vdetails['videoid'].'/'.SEO(clean(str_replace(' ','-',$vdetails['title']))).$plist;
            }
                break;

            case 3:
            {
                $link = BASEURL.'/video/'.$vdetails['videoid'].'_'.SEO(clean(str_replace(' ','-',$vdetails['title']))).$plist;
            }
                break;
        }


    }else{
        if($vdetails['playlist_id'])
            $plist = '&play_list='.$vdetails['playlist_id'];
        $link = BASEURL.'/watch_video.php?v='.$vdetails['videokey'].$plist;
    }
    if(!$type || $type=='link')
        return $link;
    elseif($type=='download')
        return BASEURL.'/download.php?v='.$vdetails['videokey'];
}

//Function That will use in creating SEO urls
function VideoLink($vdetails,$type=NULL)
{
    return video_link($vdetails,$type);
}


/**
 * Function Used to format video duration
 * @param : array(videoKey or ID,videok TITLE)
 */

function videoSmartyLink($params)
{
    $link  =    VideoLink($params['vdetails'],$params['type']);
    if(!$params['assign'])
        return $link;
    else
        assign($params['assign'],$link);
}

/**
 * Function used to validate category
 * INPUT $cat array
 */
function validate_vid_category($array=NULL)
{
    global $myquery,$LANG,$cbvid;
    if($array==NULL)
        $array = $_POST['category'];
    if(count($array)==0)
        return false;
    else
    {

        foreach($array as $arr)
        {
            if($cbvid->category_exists($arr))
                $new_array[] = $arr;
        }
    }
    if(count($new_array)==0)
    {
        e(lang('vdo_cat_err3'));
        return false;
    }elseif(count($new_array)>ALLOWED_VDO_CATS)
    {
        e(sprintf(lang('vdo_cat_err2'),ALLOWED_VDO_CATS));
        return false;
    }

    return true;
}

/**
 * Function used to check videokey exists or not
 * key_exists
 */
function vkey_exists($key)
{
    global $db;
    $db->select(tbl("video"),"videokey"," videokey='$key'");
    if($db->num_rows>0)
        return true;
    else
        return false;
}

/**
 * Function used to check file_name exists or not
 * as its a unique name so it will not let repost the data
 */
function file_name_exists($name)
{
    global $db;
    $results = $db->select(tbl("video"),"videoid,file_name"," file_name='$name'");

    if($db->num_rows >0)
        return $results[0]['videoid'];
    else
        return false;
}



/**
 * Function used to get video from conversion queue
 */
function get_queued_video($update=TRUE,$fileName=NULL)
{
    global $db;
    $max_conversion = config('max_conversion');
    $max_conversion = $max_conversion ? $max_conversion : 2;
    $max_time_wait = config('max_time_wait'); //Maximum Time Wait to make PRocessing Video Automatcially OK
    $max_time_wait = $max_time_wait ? $max_time_wait  : 7200;

    //First Check How Many Videos Are In Queu Already
    $processing = $db->count(tbl("conversion_queue"),"cqueue_id"," cqueue_conversion='p' ");
    if(true)
    {
        if($fileName)
        {
            $queueName = getName($fileName);
            $ext = getExt($fileName);
            $fileNameQuery = " AND cqueue_name ='$queueName' AND cqueue_ext ='$ext' ";
        }
        $results = $db->select(tbl("conversion_queue"),"*","cqueue_conversion='no' $fileNameQuery",1);
        $result = $results[0];
        if($update)
            $db->update(tbl("conversion_queue"),array("cqueue_conversion","time_started"),array("p",time())," cqueue_id = '".$result['cqueue_id']."'");
        return $result;
    }else
    {
        //Checking if video is taking more than $max_time_wait to convert so we can change its time to
        //OK Automatically
        //Getting All Videos That are being processed
        $results = $db->select(tbl("conversion_queue"),"*"," cqueue_conversion='p' ");
        foreach($results as $vid)
        {
            if($vid['time_started'])
            {
                if($vid['time_started'])
                    $time_started = $vid['time_started'];
                else
                    $time_started = strtotime($vid['date_added']);

                $elapsed_time = time()-$time_started;

                if($elapsed_time>$max_time_wait)
                {
                    //CHanging Status TO OK
                    $db->update(tbl("conversion_queue"),array("cqueue_conversion"),
                        array("yes")," cqueue_id = '".$result['cqueue_id']."'");
                }
            }
        }
        return false;
    }
}



/**
 * Function used to get video being processed
 */
function get_video_being_processed($fileName=NULL)
{
    global $db;

    if($fileName)
    {
        $queueName = getName($fileName);
        $ext = getExt($fileName);
        $fileNameQuery = " AND cqueue_name ='$queueName' AND cqueue_ext ='$ext' ";
    }

    //$results = $db->select(tbl("conversion_queue"),"*","cqueue_conversion='p' $fileNameQuery");
    $query = " SELECT * FROM ".tbl("conversion_queue");
    $query .= " WHERE cqueue_conversion='p' ";

    if(isset($fileNameQuery))
        $query .= $fileNameQuery;

    

    $results = db_select($query);

    if($results)
        return $results;
}

function get_video_details( $vid = null, $basic = false ) {
    global $cbvid;

    if( $vid === null ) {
        return false;
    }

    return $cbvid->get_video( $vid, false, $basic );
}

function get_video_basic_details( $vid ) {
    global $cbvid;
    return $cbvid->get_video( $vid, false, true );
}

function get_video_details_from_filename( $filename, $basic = false ) {
    global $cbvid;
    return $cbvid->get_video( $filename, true, $basic );
}

function get_basic_video_details_from_filename( $filename ) {
    global $cbvid;
    return $cbvid->get_video( $filename, true, true );
}

/**
 * Function used to get all video files
 * @param Vdetails
 * @param $count_only
 * @param $with_path
 */
function get_all_video_files($vdetails,$count_only=false,$with_path=false)
{
    $details = get_video_file($vdetails,true,$with_path,true,$count_only);
    if($count_only)
        return count($details);
    return $details;
}
function get_all_video_files_smarty($params)
{
    $vdetails = $params['vdetails'];
    $count_only = $params['count_only'];
    $with_path = $params['with_path'];
    return get_all_video_files($vdetails,$count_only,$with_path);
}

/**
 * Function use to get video files
 */
function get_video_file($vdetails,$return_default=true,$with_path=true,$multi=false,$count_only=false,$hq=false)
{
    global $Cbucket;
    # checking if there is any other functions
    # available
    if(is_array($Cbucket->custom_video_file_funcs))
        foreach($Cbucket->custom_video_file_funcs as $func)
            if(function_exists($func))
            {
                $func_returned = $func($vdetails, $hq);
                if($func_returned)
                    return $func_returned;
            }


            $fileDirectory = "";
            if(isset($vdetails['file_directory']) && !empty($vdetails['file_directory'])){
                $fileDirectory = "{$vdetails['file_directory']}/";
            }
            //dump($vdetails['file_name']);

    #Now there is no function so lets continue as
    if(isset($vdetails['file_name']))
        $vid_files = glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*");
    // if($hq){
    //     var_dump(glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*"));
    // }

    #replace Dir with URL
    if(is_array($vid_files))
        foreach($vid_files as $file)
        {
            // if($hq){
            //     echo "filesize = " . filesize($file);   
            // }
            if(filesize($file) < 100) continue;
            $files_part = explode('/',$file);
            $video_file = $files_part[count($files_part)-1];

            if($with_path)
                $files[]    = VIDEOS_URL.'/' . $fileDirectory . $video_file;
            else
                $files[]    = $video_file;
        }


    if(count($files)==0 && !$multi && !$count_only)
    {
        if($return_default)
        {

            if($with_path)
                return VIDEOS_URL.'/no_video.flv';
            else
                return 'no_video.flv';
        }else{
            return false;
        }
    }else{
        if($multi)
            return $files;
        if($count_only)
            return count($files);


        foreach($files as $file)
        {
            if($hq)
            {
                if(getext($file)=='mp4')
                {
                    return $file;
                    break;
                }
            }else{
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
function get_hq_video_file($vdetails,$return_default=true)
{
    return get_video_file($vdetails,$return_default,true,false,false,true);
}

/**
 * Function used to update processed video
 * @param Files details
 */
function update_processed_video($file_array,$status='Successful',$ingore_file_status=false,$failed_status='')
{
    global $db;
    $file = $file_array['cqueue_name'];
    $array = explode('-',$file);

    if(!empty($array[0]))
        $file_name = $array[0];
    $file_name = $file;

    $file_path = VIDEOS_DIR.'/'.$file_array['cqueue_name'].'.flv';
    $file_size = @filesize($file_path);

    if(file_exists($file_path) && $file_size>0 && !$ingore_file_status)
    {
        $stats = get_file_details($file_name);

        //$duration = $stats['output_duration'];
        //if(!$duration)
        //  $duration = $stats['duration'];

        $duration = parse_duration(LOGS_DIR.'/'.$file_array['cqueue_name'].'.log');

        $db->update(tbl("video"),array("status","duration","failed_reason"),
            array($status,$duration,$failed_status)," file_name='".$file_name."'");
    }else
    {
        //$duration = $stats['output_duration'];
        //if(!$duration)
        //  $duration = $stats['duration'];
        $result = db_select("SELECT * FROM ".tbl("video")." WHERE file_name = '$file_name'");
        if($result)
        {
            foreach($result as $result1)
            {
                $str = '/'.$result1['file_directory'].'/';
                $duration = parse_duration(LOGS_DIR.$str.$file_array['cqueue_name'].'.log');
            }
        }
        

        $db->update(tbl("video"),array("status","duration","failed_reason"),
            array($status,$duration,$failed_status)," file_name='".$file_name."'");

     
    }
}


/**
 * This function will activate the video if file exists
 */
function activate_video_with_file($vid)
{
    global $db;
    $vdetails = get_video_basic_details( $vid );
    $file_name = $vdetails['file_name'];
    $results = $db->select(tbl("conversion_queue"),"*"," cqueue_name='$file_name' AND cqueue_conversion='yes'");
    $result = $results[0];

    update_processed_video($result);
}


/**
 * Function Used to get video file stats from database
 * @param FILE_NAME
 */
function get_file_details($file_name,$get_jsoned=false)
{

    global $db;
    //$result = $db->select(tbl("video_files"),"*"," id ='$file_name' OR src_name = '$file_name' ");
    //Reading Log File
    $result = db_select("SELECT * FROM ".tbl("video")." WHERE file_name = '$file_name'");
    if($result)
    {
        foreach($result as $result1)
        {
            $str = '/'.$result1['file_directory'].'/';
            $file = LOGS_DIR.$str.$file_name.'.log';
        }
    }
    if(!file_exists($file))
        $file = $file_name;
    if(file_exists($file))
    {

        $data = file_get_contents($file);

        if(!$get_jsoned)
            return $data;
        
        //$file = file_get_contents('1260270267.log');
        preg_match_all('/(.*) : (.*)/',trim($data),$matches);

        $matches_1 = ($matches[1]);
        $matches_2 = ($matches[2]);

        for($i=0;$i<count($matches_1);$i++)
        {
            $statistics[trim($matches_1[$i])] = trim($matches_2[$i]);
        }
        if(count($matches_1)==0)
        {
            return false;
        }
        $statistics['conversion_log'] = $data;
        return $statistics;
    }else
        return false;
}

function parse_duration($log)
{
    $duration = false;
    $log_details = get_file_details($log);

    if(isset($log['output_duration']))
    $duration = $log['output_duration'];

    if((!$duration || !is_numeric($duration)) && isset($log['duration']))
        $duration = $log['duration'];

    if(!$duration || !is_numeric($duration))
    {
        if(file_exists($log))
            $log_content = file_get_contents($log);

        //Parse duration..
        preg_match_all('/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9.]{1,5})/i',$log_content,$matches);

        unset($log_content);

        //Now we will multiple hours, minutes accordingly and then add up with seconds to
        //make a single variable of duration

        $hours = $matches[1][0];
        $minutes = $matches[2][0];
        $seconds = $matches[3][0];

        $hours = $hours * 60 * 60;
        $minutes = $minutes * 60;
        $duration = $hours+$minutes+$seconds;

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
function get_thumb_num($name)
{
    $list = explode( '-', $name);
    $list = end( $list );
    $list = explode('.',$list);
    return  $list[0];
}


/**
 * Function used to remove thumb
 */
function delete_video_thumb($file_dir,$file)
{
    global $LANG;
    if($file_dir!=NULL){
    $path = THUMBS_DIR.'/'.$file_dir.'/'.$file;
     }
     else{
       $path = THUMBS_DIR.'/'.$file; 
     }
    
    if(file_exists($path))
    {
        unlink($path);
        e(lang('video_thumb_delete_msg'),'m');
    }else{
        e(lang('video_thumb_delete_err'));
    }
}

/**
 * function used to remove video thumbs
 */
function remove_video_thumbs($vdetails)
{
    global $cbvid;
    return $cbvid->remove_thumbs($vdetails);
}

/**
 * function used to remove video log
 */
function remove_video_log($vdetails)
{
    global $cbvid;
    return $cbvid->remove_log($vdetails);
}

/**
 * function used to remove video files
 */
function remove_video_files($vdetails)
{
    global $cbvid;
    return $cbvid->remove_files($vdetails);
}


/**
 * Function used to check weather video has Mp4 file or not
 */
function has_hq($vdetails,$is_file=false)
{
    if(!$is_file)
        $file = get_hq_video_file($vdetails);
    else
        $file = $vdetails;

    if(getext($file)=='mp4')
        return $file;
    else
        return false;
}

/**
 * Funcion used to call functions
 * when video is going to watched
 * ie in watch_video.php
 */
function call_watch_video_function($vdo)
{
    global $userquery;

    $funcs = get_functions('watch_video_functions');

    if(is_array($funcs) && count($funcs)>0)
    {
        foreach($funcs as $func)
        {

            if(function_exists($func))
            {
                $func($vdo);
            }
        }
    }

    increment_views_new($vdo['videoid'],'video');

    if(userid())
        $userquery->increment_watched_vides(userid());

}

/**
 * Funcion used to call functions
 * when video is going
 * on CBvideo::remove_files
 */
function call_delete_video_function($vdo)
{
    $funcs = get_functions('on_delete_video');
    if(is_array($funcs) && count($funcs) > 0)
    {
        foreach($funcs as $func)
        {
            if(function_exists($func))
            {
                $func($vdo);
            }
        }
    }
}


/**
 * Funcion used to call functions
 * when video is going to dwnload
 * ie in download.php
 */
function call_download_video_function($vdo)
{
    global $db;
    $funcs = get_functions('download_video_functions');
    if(is_array($funcs) && count($funcs)>0)
    {
        foreach($funcs as $func)
        {
            if(function_exists($func))
            {
                $func($vdo);
            }
        }
    }

    //Updating Video Downloads
    $db->update(tbl("video"),array("downloads"),array("|f|downloads+1"),"videoid = '".$vdo['videoid']."'");
    //Updating User Download
    if(userid())
        $db->update(tbl("users"),array("total_downloads"),array("|f|total_downloads+1"),"userid = '".userid()."'");
}

/**
 * function used to get vidos
 */
function get_videos($param)
{
    global $cbvideo;
    return $cbvideo->get_videos($param);
}

/**
 * Function used to check
 * input users are valid or not
 * so that only registere usernames can be set
 */
function video_users($users)
{
    global $userquery;
    $users_array = explode(',',$users);
    $new_users = array();
    foreach($users_array as $user)
    {
        if($user!=username() && !is_numeric($user) && $userquery->user_exists($user))
        {
            $new_users[] = $user;
        }
    }

    $new_users = array_unique($new_users);

    if(count($new_users)>0)
        return implode(',',$new_users);
    else
        return " ";
}

/**
 * function used to check weather logged in user is
 * is in video users or not
 */
function is_video_user($vdo,$user=NULL)
{

    if(!$user)
        $user = username();
    if(is_array($vdo))
        $video_users = $vdo['video_users'];
    else
        $video_users = $vdo;


    $users_array = explode(',',$video_users);

    if(in_array($user,$users_array))
        return true;
    else
        return false;
}

/**
 * function used to get allowed extension as in array
 */
function get_vid_extensions()
{
    $exts = config('allowed_types');
    $exts = preg_replace("/ /","",$exts);
    $exts = explode(",",$exts);
    return $exts;
}

//this function is written for temporary purposes for html5 player 
function get_normal_vid($vdetails,$return_default=true,$with_path=true,$multi=false,$count_only=false,$hq=false){

   global $Cbucket;
    # checking if there is any other functions
    # available
    if(is_array($Cbucket->custom_video_file_funcs))
        foreach($Cbucket->custom_video_file_funcs as $func)
            if(function_exists($func))
            {
                $func_returned = $func($vdetails, $hq);
                if($func_returned)
                    return $func_returned;
            }


            $fileDirectory = "";
            if(isset($vdetails['file_directory']) && !empty($vdetails['file_directory'])){
                $fileDirectory = "{$vdetails['file_directory']}/";
            }
            //dump($vdetails['file_name']);

    #Now there is no function so lets continue as
    if(isset($vdetails['file_name']))
        $vid_files = glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*");
    // if($hq){
    //     var_dump(glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*"));
    // }

    #replace Dir with URL
    if(is_array($vid_files))
        foreach($vid_files as $file)
        {
            // if($hq){
            //     echo "filesize = " . filesize($file);   
            // }
            if(filesize($file) < 100) continue;
            $files_part = explode('/',$file);
            $video_file = $files_part[count($files_part)-1];

            if($with_path)
                $files[]    = VIDEOS_URL.'/' . $fileDirectory. $vdetails['file_name'] ;
            else
                $files[]    = $video_file;
        }
            //pr($files,true);
          
           return $files[0];
    


}


//this function is written for temporary purposes for html5 player 
function get_hq_vid($vdetails,$return_default=true,$with_path=true,$multi=false,$count_only=false,$hq=false){

   global $Cbucket;
    # checking if there is any other functions
    # available
    if(is_array($Cbucket->custom_video_file_funcs))
        foreach($Cbucket->custom_video_file_funcs as $func)
            if(function_exists($func))
            {
                $func_returned = $func($vdetails, $hq);
                if($func_returned)
                    return $func_returned;
            }


            $fileDirectory = "";
            if(isset($vdetails['file_directory']) && !empty($vdetails['file_directory'])){
                $fileDirectory = "{$vdetails['file_directory']}/";
            }
            //dump($vdetails['file_name']);

    #Now there is no function so lets continue as
    if(isset($vdetails['file_name']))
        $vid_files = glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*");
    // if($hq){
    //     var_dump(glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*"));
    // }

    #replace Dir with URL
    if(is_array($vid_files))
        foreach($vid_files as $file)
        {
            // if($hq){
            //     echo "filesize = " . filesize($file);   
            // }
            if(filesize($file) < 100) continue;
            $files_part = explode('/',$file);
            $video_file = $files_part[count($files_part)-1];

            if($with_path)
                $files[]    = VIDEOS_URL.'/' . $fileDirectory. $vdetails['file_name'] ;
            else
                $files[]    = $video_file;
        }
            //pr($files,true);
           
           //echo $files;
           return $files[1];
    


}

 
/**
 * Function used to get list of videos files
 * ..
 * ..
 * @since 2.7*/

function get_video_files($vdetails,$return_default=true,$with_path=true,$multi=false,$count_only=false,$hq=false){

   global $Cbucket;
    # checking if there is any other functions
    # available
    define('VIDEO_VERSION',$vdetails['video_version']);

    if(is_array($Cbucket->custom_video_file_funcs))
        foreach($Cbucket->custom_video_file_funcs as $func)
            if(function_exists($func))
            {
                $func_returned = $func($vdetails, $hq);
                if($func_returned)
                    return $func_returned;
            }
       
           
            $fileDirectory = "";
            if(isset($vdetails['file_directory']) && !empty($vdetails['file_directory'])){
                $fileDirectory = "{$vdetails['file_directory']}/";
            }
            //dump($vdetails['file_name']);

   
   
     #Now there is no function so lets continue as

    if(isset($vdetails['file_name'])){
        if(VIDEO_VERSION == '2.7'){
            $vid_files = glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*");
        }
        else{
            $vid_files = glob(VIDEOS_DIR."/".$vdetails['file_name']."*");    
        }
   }
    // if($hq){
    //     var_dump(glob(VIDEOS_DIR."/".$fileDirectory . $vdetails['file_name']."*"));
    // }

    #replace Dir with URL
    if(is_array($vid_files))
        foreach($vid_files as $file)
        {
            // if($hq){
            //     echo "filesize = " . filesize($file);   
            // }
            if(filesize($file) < 100) continue;
            $files_part = explode('/',$file);
            $video_file = $files_part[count($files_part)-1];

            if($with_path){
                if(VIDEO_VERSION == '2.7')
                    $files[]    = VIDEOS_URL.'/' . $fileDirectory. $video_file ;
                else if(VIDEO_VERSION == '2.6')
                    $files[]    = VIDEOS_URL.'/' . $video_file ;
            }
            else
                $files[]    = $video_file;
        }

    if(count($files)==0 && !$multi && !$count_only)
    {
        if($return_default)
        {

            if($with_path)
                return VIDEOS_URL.'/no_video.mp4';
            else
                return 'no_video.mp4';
        }
        else
        {
            return false;
        }
    }
    else
    {
     return $files;
    }


}


function upload_thumb($array)
{

    global $file_name,$LANG;
    
    //Get File Name
    $file       = $array['name'];
    $ext        = getExt($file);
    $image = new ResizeImage();
    
    if(!empty($file) && file_exists($array['tmp_name']) && !error())
    {

        $file_directory = "";
        if(isset($_REQUEST['time_stamp']))
        {
            $file_directory = create_dated_folder(NULL,$_REQUEST['time_stamp']);
            $file_directory .='/';
            //exit($file_directory);
        }
        if($image->ValidateImage($array['tmp_name'],$ext)){
            $file = BASEDIR.'/files/thumbs/'.$file_directory.$_POST['file_name'].'.'.$ext;
            $bfile = BASEDIR.'/files/thumbs/'.$file_directory.$_POST['file_name'].'.-big.'.$ext;
            if(!file_exists($file))
            {
                move_uploaded_file($array['tmp_name'],$file);
                $image->CreateThumb($file,$bfile,config('big_thumb_width'),$ext,config('big_thumb_height'),false);
                $image->CreateThumb($file,$file,THUMB_WIDTH,$ext,THUMB_HEIGHT,false);
            }
        }else{
            e(lang('vdo_thumb_up_err'));
        }
    }else{
        return true;
    }
}