<?php
    
    /**
    * File: Video Functions
    * Description: Various functions to perform operations on VIDEOS section
    * @license: Attribution Assurance License
    * @since: ClipBucket 1.0
    * @author[s]: Arslan Hassan, Fawaz Tahir, Fahad Abbass, Saqib Razzaq
    * @copyright: (c) 2008 - 2016 ClipBucket / PHPBucket
    * @notice: Please maintain this section
    * @modified: March 11th, 2016 ClipBucket 2.8.1
    */


    function get_video_fields( $extra = null ) {
        global $cb_columns;
        return $cb_columns->set_object( 'videos' )->get_columns( $extra );
    }

    /**
     * Function used to check video is playlable or not
     * @param : { string / id } { $id } { id of key of video }
     * @return : { boolean } { true if playable, else false }
     */

    function video_playable($id) {
        global $cbvideo,$userquery;

        if(isset($_POST['watch_protected_video'])) {
            $video_password = mysql_clean(post('video_password'));
        } else {
            $video_password = '';
        }

        if(!is_array($id)) {
            $vdo = $cbvideo->get_video($id);
        } else {
            $vdo = $id;
        }
        $uid = userid();
        if (!$vdo) {
            e(lang("class_vdo_del_err"));
            return false;
        } elseif ($vdo['status']!='Successful') {
            e(lang("this_vdo_not_working"));
            if(!has_access('admin_access',TRUE)) {
                return false;
            } else {
                return true;
            }
        } elseif ($vdo['broadcast']=='private'
            && !$userquery->is_confirmed_friend($vdo['userid'],userid())
            && !is_video_user($vdo)
            && !has_access('video_moderation',true)
            && $vdo['userid']!=$uid){
            e(lang('private_video_error'));
            return false;
        } elseif ($vdo['active'] == 'pen') {
            e(lang("video_in_pending_list"));
            if (has_access('admin_access',TRUE) || $vdo['userid'] == userid()) {
                return true;
            } else {
                return false;
            }
        } elseif ($vdo['broadcast']=='logged'
            && !userid()
            && !has_access('video_moderation',true)
            && $vdo['userid']!=$uid) {
            e(lang('not_logged_video_error'));
            return false;
        } elseif ($vdo['active']=='no' && $vdo['userid'] != userid() ) {
            e(lang("vdo_iac_msg"));
            if(!has_access('admin_access',TRUE)) {
                return false;
            } else {
                return true;
            }
        } elseif ($vdo['video_password']
            && $vdo['broadcast']=='unlisted'
            && $vdo['video_password']!=$video_password
            && !has_access('video_moderation',true)
            && $vdo['userid']!=$uid) {
            if(!$video_password) {
                e(lang("video_pass_protected"));
            } else {
                e(lang("invalid_video_password"));
            }
            template_files("blocks/watch_video/video_password.html",false,false);
        } else {
            $funcs = cb_get_functions('watch_video');

            if ($funcs) {
                foreach($funcs as $func) {
                    $data = $func['func']($vdo);
                    if ($data) {
                        return $data;
                    }
                }
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

                if(!empty($vdetails['files_thumbs_path'])&&$funcs=='server_thumb')
                {
                    $funcs = "ms_server_thumb";
                }
                
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

                    //Saving All Thumbs
                    if(!is_big($thumb_file) || $return_big){
                        if($return_full_path)
                            $thumbs[] = THUMBS_URL.'/'. $thumbDir . $thumb_file;
                        else
                            $thumbs[] = $thumb_file;
                    }
                    //Saving Original Thumbs
                    if (is_original($thumb_file)){
                        if($return_full_path)
                            $original_thumbs[] = THUMBS_URL.'/'. $thumbDir . $thumb_file;
                        else
                            $original_thumbs[] = $thumb_file;
                    }

                }elseif(file_exists($thumb))
                    unlink($thumb);
            }
        #pr($thumbs,true);
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
            
            //Initializing thumbs settings
            $thumbs_res_settings = thumbs_res_settings_28();

            if($multi){
                if (!empty($original_thumbs) && $size == 'original'){
                    return $original_thumbs;    
                }else{
                    return $thumbs;
                }
            }

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
                $num_big_28 = implode('x', $thumbs_res_settings['320']).'-'.$vdetails['default_thumb'];
                
                $big_thumb_cb26 = THUMBS_DIR.'/'.$vdetails['file_name'].'-'.$num.'.jpg';
                $big_thumb_cb27 = THUMBS_DIR.'/'.$thumbDir.$vdetails['file_name'].'-'.$num.'.jpg';
                $big_thumb_cb28 = THUMBS_DIR.'/'.$thumbDir.$vdetails['file_name'].'-'.$num_big_28.'.jpg';

                if(file_exists($big_thumb_cb26)){
                    return THUMBS_URL.'/'.$vdetails['file_name'].'-'.$num.'.jpg';
                }elseif (file_exists($big_thumb_cb27)){
                    return THUMBS_URL.'/'.$thumbDir.$vdetails['file_name'].'-'.$num.'.jpg';
                }elseif (file_exists($big_thumb_cb28)){
                    return THUMBS_URL.'/'.$thumbDir.$vdetails['file_name'].'-'.$num_big_28.'.jpg';
                }
            }

           $default_thumb = array_find($vdetails['file_name'].'-'.$size.'-'.$num,$thumbs);
            
            if(!empty($default_thumb)){
                return $default_thumb;
            }
            elseif(empty($default_thumb)){
                $default_thumb = array_find($vdetails['file_name'].'-'.$num,$thumbs);
                if (!empty($default_thumb)){
                    return $default_thumb;
                }else{
                    return $thumbs[0];    
                }
            }
           
        }

    }



    /**
    * Function used to check weaether given thumb is big or not
    * @param : { string } { $thumb_file } { the file to be checked for size }
    * @return : { boolean } { true if thumb is big, false }
    */

    function is_big($thumb_file) {
        if(strstr($thumb_file,'big')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function used to check weaether given thumb is original or not
     */
    function is_original($thumb_file)
    {
        if(strstr($thumb_file,'original'))
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
                $plist = '?play_list='.$vdetails['playlist_id'];

            $vdetails['title'] = strtolower($vdetails['title']);

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
            $video = $result[0];
            if ($video['file_server_path']){
                $file = $video['file_server_path'].'/logs/'.$video['file_directory'].$file_name.'.log';
            }
            else{
                $str = '/'.$video['file_directory'].'/';
                $file = LOGS_DIR.$str.$file_name.'.log';
            }
        }
        //saving log in a variable 
        $data = file_get_contents($file);

        if(empty($data))
            $file = $file_name;
        if(!empty($data))
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
     * Function used to remove specific thumbs number
     */
    function delete_video_thumb($file_dir,$file_name,$num)
    {
        global $LANG;
        if(!empty($file_dir)){
            $files = glob(THUMBS_DIR.'/'.$file_dir.'/'.$file_name.'*'.$num.'.*');
        }
        else{
            $files = glob(THUMBS_DIR.'/'.$file_name.'*'.$num.'.*');
        }
        //pr($files,true);

        if ($files){
            foreach ($files as $key => $file){
                if (file_exists($file)){
                    unlink($file);
                }
            }
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
        $users_array = array_filter(array_map('trim', $users_array));
        if(in_array($user,$users_array)){
            return true;
        }
        else{
            return false;
        }
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

        global $file_name,$LANG,$Upload;
        
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

                $imageDetails = getimagesize($array['tmp_name']);
                $file_num = $Upload->get_available_file_num($_POST['file_name']);
                $temp_file = THUMBS_DIR.'/'.$file_directory.'/'.$_POST['file_name'].'-'.$file_num.'.'.$ext;
                
                move_uploaded_file($array['tmp_name'],$temp_file);

                $thumbs_settings_28 = thumbs_res_settings_28();

                foreach ($thumbs_settings_28 as $key => $thumbs_size) {
                    
                    $height_setting = $thumbs_size[1];
                    $width_setting = $thumbs_size[0];
                    if ( $key != 'original' ){
                        $dimensions = implode('x',$thumbs_size);
                    }else{
                        $dimensions = 'original';
                        $width_setting  = $imageDetails[0];
                        $height_setting = $imageDetails[1];
                    }

                    $outputFilePath = THUMBS_DIR.'/'.$file_directory.'/'.$_POST['file_name'].'-'.$dimensions.'-'.$file_num.'.'.$ext;  
                    //echo $outputFilePath.'<br>';
                    $image->CreateThumb($temp_file,$outputFilePath,$width_setting,$ext,$height_setting,false);
                }
                unlink($temp_file);
            }else{
                e(lang('vdo_thumb_up_err'));
            }
        }else{
            return true;
        }
    }

    /**
    * Assigns videos array to video player's HTML in VideoJS and HTML5 Player
    * @param : { array } { $array } { array of all sources for video }
    * @param : { boolean } { $test_msg } { show all notifications during testing }
    * @author : Saqib Razzaq
    */

    function vids_assign( $array, $test_msg = false )
    {
        if (!is_array($array)){
            assign('video_files',array($array));
            return false;
        }

        $data = get_browser_details();
        $vids_array = array();

        foreach ($array as $key => $value) 
        {
            if ( is_mob_vid($value) )
            {
                if (is_phone_user($data)) 
                {
                    if ($test_msg)
                    {
                        echo 'Mobile video on mobile ';
                    }

                    $new_array = array();
                    $new_array[] = $value;
                    assign("mobile_vid", "true");
                    assign("video_files", $new_array);
                    break;
                }
            }
            elseif ( is_extension($value, 'flv') && !is_phone_user($data) )
            {
                if ( $test_msg )
                {
                    echo 'FLV video on PC';
                }

                $new_array = array();
                $new_array[] = $value;
                assign("flv_vid", "true");
                assign("video_files", $new_array);
                break;
            }
            else
            {
                if ( $test_msg )
                {
                    echo 'Regular vids array';
                }

                assign("video_files", $array);
                break;
            }
        }
    }

    /**
    * Checks if a provided file matches provided extension
    * @param : { string } { $filepath } { path or link to file }
    * @param : { string } { $ext } { file extension to match against }
    * @return : { boolean } { true or false }
    * @author : Saqib Razzaq
    */

    function is_extension( $filepath, $ext, $err_msg = false ) 
    {
        # extract file extension
        $file_format = pathinfo($filepath, PATHINFO_EXTENSION);
        # check if extesnon is empty
        if ( empty($filepath) ) 
        {
            if ( $err_msg )
            {
                echo "Invalid URL given for checking";
            }

            # extension is empty so return false
            return false;
          # check extension == extension given by user
        } 
        elseif ( strtolower($file_format) == strtolower($ext) ) 
        {
            # extensions match so return true
            return $ext;
        } 
        else
        {
            if ( $err_msg )
            {
                echo "Invalid extension ".$ext;
            }

            # extesnions don't match or something else went wrong so return false
            return false;
        }
    }

    /**
    * Checks if video was converted using CB Mobile Mod (for 2.6 users)
    * @param : { string } { $url } { url of the video to check }
    * @param : { boolean } { $err_msg } { show or hide error messages }
    * @return : { boolean } { true of false }
    * @author : Saqib Razzaq
    */

    function is_mob_vid( $url, $err_msg = false )
    {
        if (!empty($url))
        {
            if ( is_extension( $url, 'mp4' ) )
            {
                $check = substr($url, -6);

                if ( $check == '-m.mp4' )
                {
                    return true;
                }
                else
                {
                    if ( $err_msg )
                    {
                        echo 'Not a mobile mod video';
                    }

                    return false;
                }
            }
        }
        else
        {
            if ( $err_msg )
            {
                echo "Invalid URL given for checking";
            }

            return false;
        }
    }

    /**
    * Checks if current website user is using mobile / smart phone 
    * @param : { array } { array of data ( useragent etc ) }
    * @return : { string } { platform of user }
    * @author : Saqib Razzaq
    */

    function is_phone_user( $data )
    {
        $platform = $data['platform'];
        $useragent = $data['userAgent'];

        $mob_array = array('Unknown','iphone','ipad','ipod','android');

        if ( in_array($platform, $mob_array) )
        {
            return $platform;
        }
        else
        {
            $data = explode(" ", $useragent);
            
            foreach ($data as $key) {

                $key = strtolower($key);

                if ( in_array($key, $mob_array) )
                {
                    return $key;
                }
                else
                {
                    #echo "na kar";
                }
            }
        }
    }
    /**
    * @author : Fahad Abbas
    * @param : { Null }
    * @return : { Array } { Clipbucket version 2.8 thumbs default settings }
    * @since : 02-03-2016
    */
    function thumbs_res_settings_28(){
        
        $thumbs_res_settings = array(
            "original" => "original",
            '105' => array('168','105'),
            '260' => array('416','260'),
            '320' => array('632','395'),
            '480' => array('768','432')
            );

        return $thumbs_res_settings;
    }

    /**
    * @author : Fahad Abbas
    * @param : { Array } { Video Details }
    * @return : { Variable or boolean } { Max resolution file }
    * @since : 03-03-2016
    */
    function get_high_res_file($vdetails,$dir=false){
        //Getting video Files array
        $video_files = $vdetails['video_files'];
        $video_files = json_decode($video_files,true);
        //Getting video actual files source
        $v_files = get_video_files($vdetails,true,true);

        if (empty($v_files)){
             e(lang('Video file doesn\'t exists'),'e');
        }
        //Checking if video_files field is not empty (greater versions than CB 2.8)
        if (!empty($video_files)){

            $pre_check_file = $video_files[0];
            if (is_int($pre_check_file)){
                $max_file_res = max($video_files);
            }else{
                if (in_array("hd", $video_files)) {
                    $max_file_res = "hd";
                }else{
                    $max_file_res = "sd";
                }

            }
        }else{
            //Checking if video_files field is empty (lower versions than CB 2.8.1)
            foreach ($v_files as $key => $file) {
                $video_files[] = get_video_file_quality($file);
            }
            $pre_check_file = $video_files[0];
            if (is_numeric($pre_check_file)){
                $max_file_res = max($video_files);
            }else{
                if (in_array("hd", $video_files)) {
                    $max_file_res = "hd";
                }else{
                    $max_file_res = "sd";
                }
            }

        }
        // now saving the max resolution file in a variable

        if ($dir){
            $Ext = GetExt($v_files[0]);
            $max_res_file = VIDEOS_DIR.'/'.$vdetails['file_directory'].'/'.$vdetails['file_name'].'-'.$max_file_res.'.'.$Ext;

        }else{
            
            foreach ($v_files as $key => $file) {
                $video_quality =  get_video_file_quality($file);
                if ($max_file_res == $video_quality){
                    $max_res_file = $file;
                }
            }
        }
       
        if (!empty($max_res_file)){
            return $max_res_file;
        }else{
            return false;
        }
       
    }

    /**
    * @author : Fahad Abbas
    * @param : { Var } { quality of input file }
    * @return : { Variable } { resolution of a file }
    * @since : 03-03-2016
    */
    function get_video_file_quality($file){
        
        $quality = explode('-',$file);
        $quality = end($quality);
        $quality = explode('.',$quality);
        $quality = $quality[0];
        return $quality;
    }

    /**
    * Checks ram of a Linux server e.g Ubutnu, CentOS
    * @param : { none }
    * @since : 10th March, 2016 ClipBucket 2.8.1
    * @author : Saqib Razzaq
    * @return : { integer } { $total_ram } { total RAM in GB's }
    */

    function check_server_ram() {
        $fh = fopen('/proc/meminfo','r');
        $mem = 0;
        while ($line = fgets($fh)) {
            $pieces = array();
            if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
              $mem = $pieces[1];
              break;
            }
        }
        fclose($fh);
        $total_ram = $mem / 1024 / 1024;
        return $total_ram;
    }

    /**
    * Checks different sections to make sure video uploading is good to go
    *
    * @param : { none } { everything handled inside function }
    * @checks : { server ram, directory permissions, PHP path, module installations, versions }
    * @action : { string } { error messages depending on situation }
    * @author : Saqib Razzaq
    * @since : 10th March, 2016 ClipBucket 2.8.1
    */

    function pre_upload() {
        if (isset($_GET['alliswell'])) {
            if (has_access("admin_access")) {
                $alliswell = $_GET['alliswell'];
            }
        }
        if (PHP_OS == 'Linux') {
            $ramsize = check_server_ram();
            if ($ramsize < 5) {
                if ($ramsize < 1) {
                    $mode = 'MB';
                } else {
                    $mode = 'GB';
                }
                if (has_access('admin_access')) {
                    e("Current Memory Size (RAM) of server is <strong>".round($ramsize, 2)." ".$mode."</strong> but recomended RAM is atleast 5 GB");
                }
            }
        }
        $directories = array('files','cache','includes');
        foreach ($directories as $dir) {
            $full_path = BASEDIR.'/'.$dir;
            if (!is_writable($full_path)) {
                e("<strong>".$full_path."</strong> is not writeable, video upload might not work");
            }
        }

        $ffmpeg = check_ffmpeg("ffmpeg");
        $phpVersion = check_php_cli("php");
        $MP4BoxVersion = check_mp4box("MP4Box");
        $imagick_version = check_imagick("i_magick");
        $media_info = check_media_info('media_info');
        $ffprobe_path = check_ffprobe_path('ffprobe_path');
        $errs = array();
        $alltools = array(
            "ffmpeg" => $ffmpeg, 
            "php" => $phpVersion, 
            "mp4box" => $MP4BoxVersion, 
            "imagick" => $imagick_version, 
            "mediainfo" => $media_info, 
            "ffprobe" =>$ffprobe_path
            );

        foreach ($alltools as $name => $tool) {
            if (!$tool) {
                $errs[$name] = "not found";
            } else {
                if (has_access("admin_access")) {
                    if ($name == 'php') {
                        if ($phpVersion > "5.4.45" || $phpVersion < "5.4") {
                            e("[Admin only message] Installed PHP Version is <strong>".$phpVersion."</strong> but recomended version is 5.4.x","w");
                        }
                    }
                }
            }

            if ($alliswell) {
                if ($alliswell == 'all') {
                    e($name."[Admin only message] seems good to go","m");
                } else {
                    if (strtolower($name) == $alliswell) {
                        e(strtoupper($name)."[Admin only message] seems good to go","m");
                    }
                }
            }
        }
        
        if (!empty($errs)) {
            if (has_access("admin_access")) {
                foreach ($errs as $name => $issue) {
                    e(strtoupper("[Admin only message] <strong>".$name."</strong>")." couldn't be found or isn't installed properly hence video might not work, check <a href=".BASEURL."/admin_area/cb_mod_check.php>Server Modules</a> page to know more");
                }
            } else {
                e("Video upload might not work properly, kindly contact website admin");
            }
        } else {
            return true;
        }

       
    }


    /**
    * This Function is used to log the input video File procession 
    *
    * @param : { Array } { filename } { file directory } { content }
    * @return : { boolean } { Null } 
    * @since : 8th March, 2016 ClipBucket 2.8.1
    * @author : Fahad Abbas 
    * @email: <fahad.dev@iu.com.pk>
    */

    function log_file_procession($input){
        
        $File_name = $input['file_name'];
        $File_dir  = $input['file_dir'];
        $data      = $input['data'];

        $PlogFilePath = FILES_DIR."/logs/".$File_dir."/".$File_name.".plog";


        if(file_exists($PlogFilePath)) {
            $text = file_get_contents($PlogFilePath);
        }
         logData($PlogFilePath,'plogs_tester');
        $text .= " \n \n  <br><br> > {$data}";
        file_put_contents($PlogFilePath, $text);
    }


    /**
    * This Function is used to get video file procession log  
    *
    * @param : { Array } { video Details } 
    * @return : { File } {  } 
    * @since : 9th March, 2016 ClipBucket 2.8.1
    * @author : Fahad Abbas 
    * @email: <fahad.dev@iu.com.pk>
    */

    function get_file_procession_log($vdetails){

        $file_dir = $vdetails['file_directory'];
        $file_name = $vdetails['file_name'];
        $multiserver_file = $vdetails['file_server_path'];
        if (empty($multiserver_file)){
            $plog_file  =  LOGS_DIR.'/'.$file_dir.'/'.$file_name.'.plog';
        }else{
            $plog_file  =  $multiserver_file.'/logs/'.$file_dir.'/'.$filename.'.plog';
        }
        
       

        if (file_exists($plog_file)){
            $data = file_get_contents($plog_file);
            return $data;
        }else{
            return false;
        }
        
    }

    /**
    * Fetches quicklists stored in cookies
    * @param : { string } { $cookie_name } { false by default, read from certain cookie }
    * @return : { array } { $vid_dets } { an array with all details of videos in quicklists }
    * @since : 18th March, 2016 ClipBucket 2.8.1
    * @author : Saqib Razzaq <saqi.cb@gmail.com>
    */

    function get_fast_qlist($cookie_name = false) {
        global $cbvid;
        if ($cookie_name) {
            $cookie = $cookie_name;
        } else {
            $cookie = 'fast_qlist';
        }

        $raw_cookies = $_COOKIE[$cookie];
        $clean_cookies = str_replace(array("[","]"), "", $raw_cookies);
        $vids = explode(",", $clean_cookies);
        assign("qlist_vids", $vids);
        $vid_dets = array();
        foreach ($vids as $key => $vid) {
            $vid_dets[] = $cbvid->get_video_details($vid);
        }

        return array_filter($vid_dets);
    }

    /**
    * Fetches the oldest video from still-waiting-to-convert list of videos when user by cron is active
    * @param : { none }
    * @author : { Saqib Razzaq }
    * @return : { array } { $returnData } { an array with required parameters for video convert }
    */

    function convertWithCron() {
        global $db;
        $toConvert = $db->select(tbl("conversion_queue"),"*","cqueue_conversion ='no' ORDER BY cqueue_id ASC LIMIT 0,1");
        $filedata = $toConvert[0];
        if (empty($filedata)) {
            return false;
        }
        $dateDir = str_replace('-', '/', $filedata['date_added']);
        $dateDir = substr($dateDir, 0, strpos($dateDir, ' '));
        $returnData = array();
        $returnData[1] = $filedata['cqueue_name'].'.'.$filedata['cqueue_ext'];
        $returnData[2] = $filedata['cqueue_name'];
        $returnData[3] = $dateDir;
        $returnData[4] = FILES_DIR.'/logs/'.$dateDir.'/'.$filedata['cqueue_name'].'.log';
        return $returnData;
    }
    
    function dateNow() {
        return date("Y-m-d H:i:s");
    }
