<?php

/**
 * @Author : Arslan Hassan
 * @Since : 2012
 * @version : 3.0 
 */
$in_bg_cron = true;


//Including new conversion kit, called cb kit.
include("../includes/config.inc.php");
include("../includes/classes/conversion/conversion.class.php");

//Initializing new conversio kit
$cb_converter = new CBConverter();

$max_processes = 5;

//Get Vido
$queued_files = $cbupload->get_queued_files();

//Total Running proccesses...
$process_running = $cbupload->conversion_count();

if($process_running<=$max_processes && $queued_files)
{
    foreach($queued_files as $queue)
    {
        //Creating dated folders
        $folder = create_dated_folder(NULL,$queue['date_added']);
        
        $original_source = ORIGINAL_DIR.'/'.$folder.'/'.$queue['queue_name'].'.'
        .$queue['queue_ext'];
        
        $temp_source =  TEMP_DIR.'/'.$queue['queue_name'].'.'.$queue['queue_tmp_ext'];
        
        if(!file_exists($original_source))
        {
            if(!@rename($temp_source,$original_source))
            {
                echo "Cannot make use of original file...(Err 1)";
            }else
            {
                //Get source information using ffmpeg and save it in our 
                //video file database..
                $video_info = $cb_converter->getInfo($original_source);
                
                if($video_info['has_video']=='no')
                {
                    $cbupload->update_queue_status($queue,'f','Invalid video file');
                }else
                {
                    //Add video info 
                    $cbupload->add_video_file($queue,$video_info,'s');
                }
            }            
        }
        
        if(file_exists($original_source))
        {
            
            if(!$cbvid->hasThumbs($video_info)){
                
                //Generate thumbnails first...then move on..
                 
            }
            
            $video_profiles = $cbvid->get_video_profiles();
            $convert = false;
            foreach($video_profiles as $vid_profile)
            {
                if(!$cbupload->video_file_exists($queue['queue_name'],$queue['queue_id'],$vid_profile['profile_id']))
                {
                    $convert = true;
                                     
                    $output_name = $queue['queue_name'].$vid_profile['suffix'].'.'.$vid_profile['ext'];   
                    $output_file = VIDEOS_DIR.'/'.$folder.'/'.$output_name;           
                    
                    $log_file = $folder.'/'.$queue['queue_name'].$vid_profile['suffix'].'-'.$vid_profile['ext'].'.log';

                    $fid = $cbupload->add_video_file($queue,array('noinfo'),'p',$vid_profile['profile_id'],$log_file);
                    $cbupload->update_queue_status($queue,'u','Started conversion using Profile # '.$vid_profile['profile_id'],true);
                    
                    $log_file = LOGS_DIR.'/'.$log_file;

                    /** All of our new conversion code is written here **/
                    /** Lets us Prepare the ship, Lets Sail again.. **/

                    $converter = new CBConverter($original_source); 
                    $converter->set_log($log_file);
                    
                    /**
                     * @todo : Add Filters for this params 
                     */
                    
                    $twoPass = false;
                    if($vid_profile['2pass']=='yes')
                        $twoPass = true;
                    
                    
                    $params = array(
                        'format'        => $vid_profile['format'],
                        'output_file'   => $output_file,
                        'preset'        => $vid_profile['preset'],
                        'height'        => $vid_profile['height'],
                        'width'         => $vid_profile['width'],
                        'resize'        => $vid_profile['resize'],
                        'bitrate'       => $vid_profile['video_bitrate'],
                        'aspect_ratio'  => $vid_profile['aspect_ratio'],
                        'arate'         => $vid_profile['audio_rate'],
                        'fps'           => $vid_profile['video_rate'],
                        'abitrate'      => $vid_profile['audio_bitrate'],
                        '2pass'         => $twoPass,
                    );
                    
                   
                    $converter->convert($params);
                    $output_details = $converter->getInfo($output_file);
                    $time_finished = time();
                    $log = $converter->log();
                    
                    $fields = array(
                        'log' => '|no_mc|'.json_encode($log),
                        'status' => 's',
                        'output_results' => '|no_mc|'.json_encode($output_details),
                        'date_completed' => $time_finished,
                    );
                    
                    $cbupload->update_video_file($fid,$fields);
                    
                    unset($converter);
                    break;
                }
            }
            
            if(!$convert)
            {
                $cbupload->update_queue_status($queue,'s','File removed from queue');
            }
        }

        break;
    }
}

?>