<?php
/*
	Player Name: CB HTML5 Player 2.7
	Description: New Official CB html5 ClipBucket Player with all required features
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
		$video_play = get_video_files($vdetails,true,true);
	
	
		
		if(function_exists('get_refer_url_from_embed_code'))
		{
			$ref_details = get_refer_url_from_embed_code(unhtmlentities(stripslashes($vdetails['embed_code'])));
			$ytcode = $ref_details['ytcode'];
		}
		
		if($video_play || $ytcode)
		{
			$hd = $data['hq'];
		
			if($hd=='yes') $file = get_hq_video_file($vdetails); else $file = get_video_files($vdetails,true,true);
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
		
            // Allowing CB defalut player settings to Html5player
			if($in['autoplay'] =='yes')
			assign('autoplay','true');


			assign('vdata',$vdetails);
            assign('height',$in['height']);
            assign('width',$in['width']);


		  
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
            
			if (THIS_PAGE == 'watch_video')
			 assign('enlarge_small','true');
		    else
		     assign('enlarge_small','false');	
			
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
		
            //getting hq test for HD button
            $hq_file = $video_play[1];
            assign('testing',$hq_file);
		
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
             
            // setting flash player fallback for Flashplayer Videos 
            $ext = getExt($video_play[0]);
              
            if ( $ext == 'flv' ){
	            assign('player_data',$in);	
	            assign('cb_skin','glow/glow.xml');
	            assign('player_url',PLAYER_URL);	
	            assign('flashplayer',true); 
            }
          
            if($video_play[0])
            {
	            assign('application_videos',$video_play[0]);

	            if ($video_play[1] == '')
	            {	
		            assign('normal_vid_file',$video_play[0]);
		            assign('hq_vid_file','');
	            }
	            else
	            {
		            assign('normal_vid_file',$video_play[1]);	
		            assign('hq_vid_file',$video_play[0]);
	            }
            }
            else
            {	
            $json_array = json_encode($video_play);
            assign('json_videos',$json_array);
            $video_play = array_reverse($video_play, true);
            assign('ms_videos',$video_play);
            }

		
		
		
			Template(HTML5_PLAYER_DIR.'/html5_player.html',false);
			
			
			return true;
		}
	}



     
	register_actions_play_video('html5_player');
  // $Cbucket->add_header(HTML5_PLAYER_DIR.'/html5_player_header.html');
  
     
	
}


//overlay 

/*$ov_details = BASEURL.'/images/icons/country/ov.png';
  $ov_convert = base64_encode(file_get_contents($ov_details));
  assign('ov',$ov_convert);*/

 

?>