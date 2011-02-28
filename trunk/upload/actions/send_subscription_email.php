<?php

/**
 * this file is used to send subscription email
 * @Author : Arslan Hassan
 * @Date : 2/24/2011
 */
 
 $in_bg_cron = true;

 include(dirname(dirname(__FILE__))."/includes/config.inc.php");

 $videoid = $argv[1];
 
 $video = $cbvid->get_video($videoid);
 
 if($video)
 {
	 
	 if(
	 ($video['broadcast']=='public' || $video['broadcast'] =="logged")
	 && $video['subscription_email']=='pending')
	
	 $userquery->sendSubscriptionEmail($video,true);
 }

?>