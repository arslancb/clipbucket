<?php

/**
 * This file is used to verify weather video is converted or not
 * if it is converted then activate it and let it go
 */
//Sleeping..
//sometimes video is inserted after video conversion so in this case, video can get lost
//if($argv[2]=='sleep')
//sleep(10);

$in_bg_cron = true;

include(dirname(__FILE__) . "/../includes/config.inc.php");

cb_call_functions('verify_converted_videos_cron');

if ($argv[1])
    $filename = $argv[1];
else if ($_GET['filename'])
    $filename = $_GET['filename'];
else
    $filename = false;

$queues = get_video_being_processed($filename);

foreach ($queues as $video)
{
    $files = $video['files'];
    $videoid = $video['videoid'];
    
    
    //Deactivating the queue..in case its completed and active..
    if($video['queue_status']=='s')
    {
        $cbupload->deactivate_queue($video['file_name']);
    }
    
    //Checking if video was in processing state then change its status
    //to something reliable..

    $video_status = $video['status'];

    if ($video_status == 'Processing')
    {
        if($files)
        foreach ($files as $file)
        {
            if ($file['status'] == 's' && $file['is_original'] == 'no')
            {

                $video_status = 'Successful';
                update_video_status($video['file_name'], $video_status);
                
                if ($video['duration'] < 1)
                {
                    $output_results = json_decode($file['output_results'], true);
                    $duration = $output_results['duration'];

                    //Update video details..
                    update_video_data($videoid,'duration', $duration);
                    $video['duration'] = $duration;
                }
            }
        }

        //Now if video status is still not successful
        //Let us look if it has failed for some reason...
        if($files)
        foreach ($files as $file)
        {
            if ($file['status'] == 'f' && $file['is_original'] == 'no')
            {
                $video_status = 'Failed';
                $failed_reason = 'Video failed in conversion';
                update_video_status($video['file_name'], $video_status, $failed_reason);
            }
        }
    }

    if ($video_status == 'Successful')
    {
        if ($video['subscription_email'] == 'pending')
        {
            //Send emails...even queue then in subscription so we can 
            //Send them later..
            //For now..we will use our old method..
            exec(php_path() . " -q "
                    . BASEDIR . "/actions/send_subscription_email.php $videoid &> /dev/null &");
        }


        //Just incase...video duration is not updated...
        if ($video['duration'] < 1)
            
            if($files)
            foreach ($files as $file)
            {
                if ($file['status'] == 's' && $file['is_original'] == 'no')
                {

                    $video_status = 'Successful';
                    update_video_status($video['file_name'], $video_status);

                    $output_results = json_decode($file['output_results'], true);
                    $duration = $output_results['duration'];

                    //Update video details..
                    update_video_data($videoid,'duration', $duration);

                    if ($duration)
                        break;
                }
            }
    }


    if ($video_status == 'Failed')
    {
        //Send some sad emails to the uploader
        //That we could not do anything, we are sorry :'(
    }


    if (get_functions('after_convert_functions'))
    {
        foreach (get_functions('after_convert_functions') as $func)
        {
            if (@function_exists($func))
                $func($video);
        }
    }
}
?>