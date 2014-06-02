<?php
/*
	Player Name: CB HTML5 Player 2.7
	Description: New html5 ClipBucket Player with all required features
	Author: Fahad Abbas
	ClipBucket Version: 2.7
	
	
 
 
 * @Author : Arslan Hassan
 * @Script : ClipBucket v2
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @Since : September 15 2009
 
 */

$html5_player = false;

if(!function_exists('html5_player'))
{
	define("HTML5_PLAYER",basename(dirname(__FILE__)));
	define("HTML5_PLAYER_DIR",PLAYER_DIR."/".HTML5_PLAYER);
	define("HTML5_PLAYER_URL",PLAYER_URL."/".HTML5_PLAYER);
	assign('html5_player_dir',HTML5_PLAYER_DIR);
	assign('html5_player_url',HTML5_PLAYER_URL);
	
	function html5_player($in)
	{
		global $html5_player;
		$html5_player = true;
		
		$vdetails = $in['vdetails'];
		$vid_file = get_video_file($vdetails,true,true);
		//Checking for YT Referal

		if(function_exists('get_refer_url_from_embed_code'))
		{
			$ref_details = get_refer_url_from_embed_code(unhtmlentities(stripslashes($vdetails['embed_code'])));
			$ytcode = $ref_details['ytcode'];
		}
		
		if($vid_file || $ytcode)
		{
			$hd = $data['hq'];
			
			if($hd=='yes') $file = get_hq_video_file($vdetails); else $file = get_video_file($vdetails,true,true);
			$hd_file = get_hq_video_file($vdetails);
			
			
			if($ytcode)
			{
				assign('youtube',true);
				assign('ytcode',$ytcode);
			}
			
			if(!strstr($in['width'],"%"))
				$in['width'] = $in['width'].'px';
			if(!strstr($in['height'],"%"))
				$in['height'] = $in['height'].'px';
		
			if($in['autoplay'] =='yes' || $in['autoplay']===true || 
			($_COOKIE['auto_play_playlist'] && ($_GET['play_list'] || $_GET['playlist'])))
			{
				$in['autoplay'] = true;
			}else{
				$in['autoplay'] = false;
			}
		
           // include('../../../../includes/config.inc.php');
            //$related_videos = get_videos(array('title'=>$title,'tags'=>$tags,'exclude'=>$videoid,'show_related'=>'yes','limit'=>8,'order'=>'date_added DESC'));
          
            $v_cat = $vdo['category'];
            if($v_cat[2] =='#') {
            $video_cat = $v_cat[1];
            }else{
            $video_cat = $v_cat[1].$v_cat[2];}
            $vid_cat = str_replace('%#%','',$video_cat);
            assign('vid_cat',$vid_cat);
            $vid_cond['order'] = " date_added DESC ";
            $vlist = $vid_cond;
            $vlist['limit'] = 4;
            $videos = get_videos($vlist);
            Assign('related', $videos);

          
			
			$l_details = BASEURL.'/images/icons/country/hp.png';
			$l_convert = base64_encode(file_get_contents($l_details));
			assign('display',$l_convert);
			

			$ov_details = BASEURL.'/images/icons/country/ov.png';
			$ov_convert = base64_encode(file_get_contents($ov_details));
			assign('ov',$ov_convert);

		  

            assign('about',BASEURL);
            
            $jquery = BASEDIR.'/js/jquery.js';
            assign('jquery',$jquery);
			
			$v_details = extract($vdetails);
		    $v_details;
            assign('v_details',$v_details);


            // logo placement
            $pos = config('logo_placement');
		    switch($pos)
		    {
			case "tl":
			$position = array("top"=>'5px',"left"=>'5px',"bottom"=>'',"right"=>'');
			break;
			
			case "tr":
			$position = array("top"=>'5px',"left"=>'',"bottom"=>'',"right"=>'5px');
			break;
			
			case "br":
			$position = array("top"=>'',"left"=>'',"bottom"=>'5px',"right"=>'5px');
			break;
			
			case "bl":
			$position = array("top"=>'',"left"=>'5px',"bottom"=>'5px',"right"=>'');
			break;
		    }
		
		
            assign('top',$position["top"]);
            assign('left',$position["left"]);
            assign('bottom',$position["bottom"]); 
            assign('right',$position["right"]);  

            assign('username',$username);
            assign('title',$title);
            assign('thumb',$default_thumb);
            assign('key',$videokey);
            assign('has_hq',$has_hq);

			assign('player_data',$in);
			assign('normal_vid_file',$vid_file);
			assign('hq_vid_file',$hd_file);			
			assign('vdata',$vdetails);


			
			Template(HTML5_PLAYER_DIR.'/html5_player.html',false);
			Template(HTML5_PLAYER_DIR.'/html5_player_header.html',false);
			
			return true;
		}
	}


/*
function html5_player_logo_position($pos=false)
	{
		if(!$pos)
			$pos = config('logo_placement');
		switch($pos)
		{
			case "tl":
			$position = array("top"=>'0',"left"=>'0',"bottom"=>'',"right"=>'');
			break;
			
			case "tr":
			$position = array("top"=>'0',"left"=>'',"bottom"=>'',"right"=>'0');
			break;
			
			case "br":
			$position = array("top"=>'',"left"=>'',"bottom"=>'0',"right"=>'0');
			break;
			
			case "bl":
			$position = array("top"=>'',"left"=>'0',"bottom"=>'0',"right"=>'');
			break;
			
		}
		
		return $position;


		  
	}
 $position = array("top"=>'0',"left"=>'0',"bottom"=>'',"right"=>'');
   echo $position["top"];   

   */ 
     
	register_actions_play_video('html5_player');

	
}




 

?>