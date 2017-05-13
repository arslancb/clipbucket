<?php
/*
Plugin Name: Timecode
Description: Allow timecodes in a youtube like fashion embedding startup time in the fragment, timecodes in video (01:15) description will be automatically transformed into links
Author: Sylvain Tognola
Author Website: http://corbac.fr
ClipBucket Version: 2
Version: 1.0
Plugin Type: global

Works with most HTML5 players 
you must call the anchor "after_watch_video"
*/

if(!function_exists('timecode_link'))
{
	function timecode_link($comment)
	{
		$timecodepattern = '/([0-9]*)[:]?([0-9]{2}):([0-9]{2})/';
    return preg_replace_callback($timecodepattern, function($match){
		  $hours    = intval($match[1]);
		  $minutes  = intval($match[2]);
		  $seconds  = intval($match[3]);
		  $timeinseconds = $hours * 60 * 60 + $minutes * 60 + $seconds; 
		  return "<a onclick=\"javascript:video_seek($timeinseconds);\" href=\"#time=$timeinseconds\">$match[0]</a>";
    
    }, $comment);
	}
}


ob_start(); ?>
<script>
  $(window).on('hashchange',function(){
    //video_seek(parseInt(location.hash.match('[0-9]+')[0]));
    document.getElementsByTagName("video")[0].currentTime = parseInt(location.hash.match('[0-9]+')[0]);
    document.getElementsByTagName("video")[0].play();
  }).trigger('hashchange');
</script>
<?php 
$jssnip = ob_get_clean();

/* if (config( 'player_dir' ) == "CB_html5_player"){
  video-seek...
  behavior...
}
*/


//Registering Action that will be applied while displaying comment and or description
register_action(array('timecode_link'=>array('comment','description','pm_compose_box','before_topic_post_box','private_message')));

$hints = "<div style='font-family:tahoma; margin:0px 0x 5px 0px'><strong>*You can use timecodes like this 01:01 or even 1:30:24</strong><br />";
//Registerin Anchors , that will be displayed before compose boxes
register_anchor($hints,'after_compose_box');
register_anchor($hints,'after_reply_compose_box');
register_anchor($hints,'after_desc_compose_box');
register_anchor($hints,'after_pm_compose_box');
register_anchor($hints,'after_topic_post_box');

register_anchor($jssnip,'after_watch_video');

?>
