<?php

/**
#########################################################################################################
# Copyright (c) 2008 - 2009 ClipBucket / PHPBucket. All Rights Reserved.
# [url]http://clip-bucket.com[/url]
# Function:         Various
# Author:           Arslan Hassan
# Language:         PHP
# License:          CBLA @ [url]http://cbla.cbdev.org/[/url]
# Version:          1.7.x SVN
# Last Modified:    Monday, March 23, 2009 / 01:08 AM GMT+1 (fwhite)
# Notice:           Please maintain this section
#########################################################################################################
*/

 require 'define_php_links.php';
 include_once 'upload_forms.php';
 
    function add_column_if_not_exist($table, $column, $column_attr){
	$exists = false;
	$columns = mysql_query("show columns from $table");
	while($c = mysql_fetch_assoc($columns)){
		if($c['Field'] == $column){
			$exists = true;
			break;
		}
	}
	if(!$exists){
		mysql_query("ALTER TABLE `$table` ADD `$column`  $column_attr");
	}
    }
 
	//This Funtion is use to get CURRENT PAGE DIRECT URL
	function curPageURL() {
 		$pageURL = 'http';
		if (@$_SERVER["HTTPS"] == "on") {
		$pageURL .= "s";
		}
		$pageURL .= "://";
 		$pageURL .= $_SERVER['SERVER_NAME'];
		$pageURL .= $_SERVER['PHP_SELF'];
		$query_string = $_SERVER['QUERY_STRING'];
		if(!empty($query_string)){
		$pageURL .= '?'.$query_string;
		}
 		return $pageURL;
	}
	
	//QuotesReplace
	function Replacer($string)
	{
	//Wp-Magic Quotes
	$string = preg_replace("/'s/", '&#8217;s', $string);
	$string = preg_replace("/'(\d\d(?:&#8217;|')?s)/", "&#8217;$1", $string);
	$string = preg_replace('/(\s|\A|")\'/', '$1&#8216;', $string);
	$string = preg_replace('/(\d+)"/', '$1&#8243;', $string);
	$string = preg_replace("/(\d+)'/", '$1&#8242;', $string);
	$string = preg_replace("/(\S)'([^'\s])/", "$1&#8217;$2", $string);
	$string = preg_replace('/(\s|\A)"(?!\s)/', '$1&#8220;$2', $string);
	$string = preg_replace('/"(\s|\S|\Z)/', '&#8221;$1', $string);
	$string = preg_replace("/'([\s.]|\Z)/", '&#8217;$1', $string);
	$string = preg_replace("/ \(tm\)/i", ' &#8482;', $string);
	$string = str_replace("''", '&#8221;', $string);

	$array = array('/& /');
	$replace = array('&amp; ') ;
	return $string = preg_replace($array,$replace,$string);
	}
	//This Funtion is used to clean a String
	
	function clean($string,$allow_html=false) {
 	 $string = stripslashes($string);
 	 //$string = htmlentities($string);
	 if($allow_html==false){
 	 $string = strip_tags($string);
	 $string =  Replacer($string);
	 }
	 //$string = utf8_encode($string);
 	 return $string;
	}
	
	//This Fucntion is for Securing Password, you may change its combination for security reason but make sure dont not rechange once you made your script run
	
	function pass_code($string) {
 	 $password = md5(md5(sha1(sha1(md5($string)))));
 	 return $password;
	}
	
	//Mysql Clean Queries
	
	function mysql_clean($id){
		$id = clean($id);
		if (get_magic_quotes_gpc())
		{
		$id = stripslashes($id);
		}
	$id = mysql_real_escape_string($id);
	return $id;
	}
	
	//Redirect Using JAVASCRIPT
	
	function redirect_to($url){
		echo '<script type="text/javascript">
		window.location = "'.$url.'"
		</script>';
		}
		
	//Simple Template Displaying Function
	
	function Template($template,$layout=true){
	global $admin_area;
		if($layout)
		DoTemplate::display(LAYOUT.'/'.$template);
		else
		DoTemplate::display($template);
		
		if($template == 'footer.html' && $admin_area !=TRUE){
			DoTemplate::display(BASEDIR.'/includes/templatelib/'.$template);
		}
		if($template == 'header.html'){
			DoTemplate::display(BASEDIR.'/includes/templatelib/'.$template);
		}        	
	}
	
	function Assign($name,$value){
	DoTemplate::assign($name,$value);
	}
	
	//Funtion of Random String
	function RandomString($length)
	{
    // Generate random 32 charecter string
    $string = md5(time());

    // Position Limiting
    $highest_startpoint = 32-$length;

    // Take a random starting point in the randomly
    // Generated String, not going any higher then $highest_startpoint
    $randomString = substr($string,rand(0,$highest_startpoint),$length);

    return $randomString;

}


 //This Function Is Used To Display Tags Cloud
	 function TagClouds($cloudquery)
	{
			$tags = array();
			$cloud = array();
			$query = mysql_query($cloudquery);
			while ($t = mysql_fetch_array($query))
			{
					$db = explode(' ', $t[0]);
					while (list($key, $value) = each($db))
					{
							@$keyword[$value] += 1;
					}
			}
			if (is_array(@$keyword))
			{
					$minFont = 11;
					$maxFont = 22;
					$min = min(array_values($keyword));
					$max = max(array_values($keyword));
					$fix = ($max - $min == 0) ? 1 : $max - $min;
					// Display the tags
					foreach ($keyword as $tag => $count)
					{
							$size = $minFont + ($count - $min) * ($maxFont - $minFont) / $fix;
							$cloud[] = '<a class=cloudtags style="font-size: ' . floor($size) . 'px;" href="' . BASEURL.search_result.'?query=' . $tag . '" title="Tags: ' . ucfirst($tag) . ' was used ' . $count . ' times"><span>' . mysql_clean($tag) . '</span></a>';
					}
					$shown = join("\n", $cloud) . "\n";
					return $shown;
			}
		}
		
 // -------------RATING FUNCTION---------------- //
 
function getRating($id){

	$total = 0;
	$rows = 0;
	
	$sel = mysql_query("SELECT * FROM video WHERE videoid = '$id'");
	if(mysql_num_rows($sel) > 0){
	$data = mysql_fetch_assoc($sel);
		
		//$newPerc = round($perc/5)*5;
		//return $newPerc.'%';
		
		$newPerc = round($data['rating']*10,2);
		return $newPerc.'%';
	
	} else {
	
		return '0%';
	
	}
}

function outOfFive($id){

	$total = 0;
	$rows = 0;
	
	$sel = mysql_query("SELECT * FROM video WHERE videoid = '$id'");
	if(mysql_num_rows($sel) > 0){
		$data = mysql_fetch_assoc($sel);
		
		return round($data['rating']/2,2);
		//return round(($perc*2), 0)/2; // 3.5
	
	} else {
	
		return '0';
	
	}
	
	
}

function getVotes($id){

		$sel = mysql_query("SELECT * FROM video WHERE videoid = '$id'");
		$data = mysql_fetch_assoc($sel);
		return $data['rated_by'];
	
}

function pullRating($id,$show5 = false, $showPerc = false, $showVotes = false, $static = NULL){
	global $row;
	// Check if they have already voted...
	$text = '';
	
	$sel = mysql_query("SELECT * FROM video WHERE videoid = '$id'");
		$data = mysql_fetch_array($sel);
		$voter_id = $data['voter_ids'];
		@$userid = $_SESSION['userid'];
				$niddle = "|";
				$niddle .= $userid;
				$niddle .= "|";
				$flag = strstr($voter_id, $niddle);
	if($row['user_rate_opt1'] !='yes'){
		if($data['username'] == $_SESSION['username']){
			$static = 'novote';
		}
	}
	if(!empty($flag) || $static == 'novote' || !isset($_SESSION['userid']) || isset($_COOKIE['has_voted_'.$id]) ){
	
		
		
		if($show5 || $showPerc || $showVotes){

			$text .= '<div class="rated_text">';
			
		}
			
			if($show5){
				$text .= 'Rated <span id="outOfFive_'.$id.'" class="out5Class">'.outOfFive($id).'</span>/5';
			} 
			if($showPerc){
				$text .= ' (<span id="percentage_'.$id.'" class="percentClass">'.getRating($id).'</span>)';
			}
			if($showVotes){
				$text .= ' (<span id="showvotes_'.$id.'" class="votesClass">'.getVotes($id).'</span>)';
			}
			
		if($show5 || $showPerc || $showVotes){	
			
			$text .= '</div>';
		
		}
		
		
		return $text.'
			<ul class="star-rating2" id="rater_'.$id.'">
				<li class="current-rating" style="width:'.getRating($id).';" id="ul_'.$id.'"></li>
				<li><a onclick="return false;" href="#" title="1 star out of 5" class="one-star" > </a></li>
				<li><a onclick="return false;" href="#" title="2 stars out of 5" class="two-stars"> </a></li>
				<li><a onclick="return false;" href="#" title="3 stars out of 5" class="three-stars"> </a></li>
				<li><a onclick="return false;" href="#" title="4 stars out of 5" class="four-stars"> </a></li>
				<li><a onclick="return false;" href="#" title="5 stars out of 5" class="five-stars"> </a></li>
			</ul>
			<div id="loading_'.$id.'"></div>';

		
	} else {
		
		if($show5 || $showPerc || $showVotes){
			
			$text .= '<div class="rated_text">';
			
		}
			if($show5){
				$show5bool = 'true';
				$text .= 'Rated <span id="outOfFive_'.$id.'" class="out5Class">'.outOfFive($id).'</span>/5';
			} else {
				$show5bool = 'false';
			}
			if($showPerc){
				$showPercbool = 'true';
				$text .= ' (<span id="percentage_'.$id.'" class="percentClass">'.getRating($id).'</span>)';
			} else {
				$showPercbool = 'false';
			}
			if($showVotes){
				$showVotesbool = 'true';
				$text .= ' (<span id="showvotes_'.$id.'" class="votesClass">'.getVotes($id).'</span>)';
			} else {
				$showVotesbool = 'false';	
			}
			
		if($show5 || $showPerc || $showVotes){	
		
			$text .= '</div>';
			
		}
		
		return $text.'
			<ul class="star-rating" id="rater_'.$id.'">
				<li class="current-rating" style="width:'.getRating($id).';" id="ul_'.$id.'"></li>
				<li><a href="javascript:void(0)" onclick="rate(\'1\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.'); return false;" href="rating_process.php?id='.$id.'&rating=1" title="1 star out of 5" class="one-star" > </a></li>
				<li><a href="javascript:void(0)" onclick="rate(\'2\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.'); return false;" href="rating_process.php?id='.$id.'&rating=2" title="2 stars out of 5" class="two-stars"> </a></li>
				<li><a href="javascript:void(0)" onclick="rate(\'3\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.'); return false;" href="rating_process.php?id='.$id.'&rating=3" title="3 stars out of 5" class="three-stars"> </a></li>
				<li><a href="javascript:void(0)" onclick="rate(\'4\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.'); return false;" href="rating_process.php?id='.$id.'&rating=4" title="4 stars out of 5" class="four-stars"> </a></li>
				<li><a href="javascript:void(0)" onclick="rate(\'5\',\''.$id.'\','.$show5bool.','.$showPercbool.','.$showVotesbool.'); return false;" href="rating_process.php?id='.$id.'&rating=5" title="5 stars out of 5" class="five-stars"> </a></li>
			</ul>
			<div id="loading_'.$id.'"></div>';
	
	}
}	

			
	//Function Send Email
		function send_email($from,$to,$subj,$msg){
				$header = "From: ".$from." \r\n";
				$header .= "Content-Type: text/html; charset=utf-8  \r\n";
				$retval = mail ($to,$subj,$msg,$header);
				   if( $retval == true ){
					 return true;
				   }else{
					 return false;
				   }
		}

	/**
	 * Function used to get file name
	 */
	function GetName($file){
		$path = explode('/',$file);
		if(is_array($path))
			$file = $path[count($path)-1];
		$new_name 	 = substr($file, 0, strrpos($file, '.'));
		return $new_name;
	}

        function get_elapsed_time($ts,$datetime=1)
        {
          if($datetime == 1)
          {
          $ts = date('U',strtotime($ts));
          }
          $mins = floor((time() - $ts) / 60);
          $hours = floor($mins / 60);
          $mins -= $hours * 60;
          $days = floor($hours / 24);
          $hours -= $days * 24;
          $weeks = floor($days / 7);
          $days -= $weeks * 7;
          $t = "";
          if ($weeks > 0)
            return "$weeks week" . ($weeks > 1 ? "s" : "");
          if ($days > 0)
            return "$days day" . ($days > 1 ? "s" : "");
          if ($hours > 0)
            return "$hours hour" . ($hours > 1 ? "s" : "");
          if ($mins > 0)
            return "$mins min" . ($mins > 1 ? "s" : "");
          return "< 1 min";
        }

	//Function Used TO Get Extensio Of File
		function GetExt($file){
			return substr($file, strrpos($file,'.') + 1);
			}

function SetTime($sec, $padHours = true) {

    $hms = "";

    // there are 3600 seconds in an hour, so if we
    // divide total seconds by 3600 and throw away
    // the remainder, we've got the number of hours
    $hours = intval(intval($sec) / 3600);

    // add to $hms, with a leading 0 if asked for
    $hms .= ($padHours)
          ? str_pad($hours, 2, "0", STR_PAD_LEFT). ':'
          : $hours. ':';

    // dividing the total seconds by 60 will give us
    // the number of minutes, but we're interested in
    // minutes past the hour: to get that, we need to
    // divide by 60 again and keep the remainder
    $minutes = intval(($sec / 60) % 60);

    // then add to $hms (with a leading 0 if needed)
    $hms .= str_pad($minutes, 2, "0", STR_PAD_LEFT). ':';

    // seconds are simple - just divide the total
    // seconds by 60 and keep the remainder
    $seconds = intval($sec % 60);

    // add to $hms, again with a leading 0 if needed
    $hms .= str_pad($seconds, 2, "0", STR_PAD_LEFT);

    return $hms;
}
	
	//Simple Validation
	function isValidText($text){
      $pattern = "^^[_a-z0-9-]+$";
      if (eregi($pattern, $text)){
         return true;
      	}else {
         return false;
      }   
   }	
   
   //Function Used To Validate Email
	
	function isValidEmail($email){
      $pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
      if (eregi($pattern, $email)){
         return true;
      }
      else {
         return false;
      }   
   }

   
   	// THIS FUNCTION SETS HTMLSPECIALCHARS_DECODE IF FUNCTION DOESN'T EXIST
	// INPUT: $text REPRESENTING THE TEXT TO DECODE
	//	  $ent_quotes (OPTIONAL) REPRESENTING WHETHER TO REPLACE DOUBLE QUOTES, ETC
	// OUTPUT: A STRING WITH HTML CHARACTERS DECODED
	if(!function_exists('htmlspecialchars_decode')) {
		function htmlspecialchars_decode($text, $ent_quotes = "") {
		$text = str_replace("&quot;", "\"", $text);
		$text = str_replace("&#039;", "'", $text);
		$text = str_replace("&lt;", "<", $text);
		$text = str_replace("&gt;", ">", $text);
		$text = str_replace("&amp;", "&", $text);
		return $text;
		}
	} // END htmlspecialchars() FUNCTION
	
	//THIS FUNCTION IS USED TO LIST FILE TYPES IN FLASH UPLOAD
	//INPUT FILE TYPES
	//OUTPUT FILE TYPE IN PROPER FORMAT
	function ListFileTypes($types){
		$types_array = preg_replace('/,/',' ',$types);
		$types_array = explode(' ',$types_array);
		$list = 'Video,';
		for($i=0;$i<=count($types_array);$i++){
		if($types_array[$i]!=''){
		$list .= '*.'.$types_array[$i];
		if($i!=count($types_array))$list .= ';';
		}
		}
	return $list;
	}
	
	
	//FUNCTION USED TO FORMAT FILE SIZE
	//INPUT BYTES
	//OUTPT MB , Kib
	function formatfilesize( $data ) {
        // bytes
        if( $data < 1024 ) {
            return $data . " bytes";
        }
        // kilobytes
        else if( $data < 1024000 ) {
				return round( ( $data / 1024 ), 1 ) . "KB";
        }
        // megabytes
        else if($data < 1024000000){
            return round( ( $data / 1024000 ), 1 ) . " MB";
        }else{
			 return round( ( $data / 1024000000 ), 1 ) . " GB";
		}
    
    }
	
	/**
	 * FUNCTION USED TO GET THUMBNAIL
	 * @param ARRAY video_details, or videoid will also work
	 */
	 
	function get_thumb($vdetails,$num='default',$multi=false,$count=false){
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
				}else{
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
			$vdetails = $myquery->get_video_details($vid);
		
		if(empty($vdetails['title']))
		{
			if($multi)
					return default_thumb();
			return default_thumb();
		}
			
		#Checking if there is any custom function for
		if(count($Cbucket->custom_get_thumb_funcs)>0)
		foreach($Cbucket->custom_get_thumb_funcs as $funcs)
		{
			if(function_exists($funcs))
				return $funcs($vdetails);
		}
		
		#get all possible thumbs of video
		$vid_thumbs = glob(THUMBS_DIR."/".$vdetails['file_name']."*");
		#replace Dir with URL
		foreach($vid_thumbs as $thumb)
		{
			$thumb_parts = explode('/',$thumb);
			$thumb_file = $thumb_parts[count($thumb_parts)-1];
			$thumbs[] = THUMBS_URL.'/'.$thumb_file;
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
			$vdetails['file_name'].'-'.$num;
			$default_thumb = array_find($vdetails['file_name'].'-'.$num,$thumbs);
			
			if(!empty($default_thumb))
				return $default_thumb;
			return $thumbs[0];
		}
		
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
		return BASEURL.'/files/thumbs/processing.jpg';
	 }
	 
	 
	//TEST EXCEC FUNCTION 
	function test_exec( $cmd )
	{
		echo '<div border="1px">';
		echo '<h1>' . htmlentities( $cmd ) . '</h1>';

		if (stristr(PHP_OS, 'WIN')) { 
			$cmd = $cmd;
		}else{
			$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\"";
		}
		$data = shell_exec( $cmd );
		if( $data === false )
			echo "<p>FAILED: $cmd</p></div>";
		echo '<p><pre>' . htmlentities( $data ) . '</pre></p></div>';
	}
	
	
	/**
	 * Function used to get video link
	 * @param ARRAY video details
	 */
	function video_link($vdetails)
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
			$vdetails = $myquery->get_video_details($vid);
		
		if(SEO == 'yes'){
			$link = BASEURL.'/video/'.$vdetails['videokey'].'/'.SEO(clean(str_replace(' ','-',$vdetails['title'])));
		}else{
			$link = BASEURL.'/watch_video.php?v='.$vdetails['videokey'];
		}
		return $link;
	}
	
	
	//Function That will use in creating SEO urls
	function VideoLink($vdetails){
		return video_link($vdetails);
	} 
	
	/**
	* FUNCTION USED TO GET ADVERTISMENT
	* @param : array(Ad Code, LIMIT);
	*/
	function getAd($params,&$Smarty)
	{
		global $adsObj;
		$data = $adsObj->getAd($params['place']);
		return $data;
	}
	
	/**
	* FUNCTION USED TO GET THUMBNAIL, MADE FOR SMARTY
	* @ param : array("FLV");
	*/
	function getSmartyThumb($params,&$Smarty)
	{
		return get_thumb($params['vdetails'],$params['num'],$params['multi'],$params['count_only']);
	}
	
	/**
	* Function Used to format video duration
	* @param : array(videoKey or ID,videok TITLE)
	*/
	
	function videoSmartyLink($params,&$Smarty)
	{
		return	VideoLink($params['vdetails']);
	}
	
	/**
	* FUNCTION USED TO GET VIDEO RATING IN SMARTY
	* @param : array(pullRating($videos[$id]['videoid'],false,false,false,'novote');
	*/
	function pullSmartyRating($param,&$Smarty)
	{
		return pullRating($param['id'],$param['show5'],$param['showPerc'],$aram['showVotes'],$param['static']);	
	}
	
	/**
	* FUNCTION USED TO CLEAN VALUES THAT CAN BE USED IN FORMS
	*/
	function cleanForm($string)
	{
		$string = htmlspecialchars($string);
		return $string;
	}
	function form_val($string){return cleanForm($string); }
	
	/**
	* FUNCTION USED TO MAKE TAGS MORE PERFECT
	* @Author : Arslan Hassan <arslan@clip-bucket.com,arslan@labguru.com>
	* @param tags text unformatted
	* returns tags formatted
	*/
	function genTags($tags,$sep=',')
	{
		//Remove fazool spaces
		$tags = preg_replace(array('/ ,/','/, /'),',',$tags);
		$tags = preg_replace( "`[,]+`" , ",", $tags);
		$tag_array = explode($sep,$tags);
		foreach($tag_array as $tag)
		{
			if(isValidtag($tag))
			{
				$newTags[] = $tag;
			}
			
		}
		//Creating new tag string
		$tagString = implode(',',$newTags);
		return $tagString;
	}
	
	/**
	* FUNCTION USED TO VALIDATE TAG
	* @Author : Arslan Hassan <arslan@clip-bucket.com,arslan@labguru.com>
	* @param tag
	* return true or false
	*/
	function isValidtag($tag)
	{
		$disallow_array = array
		('of','is','no','on','off','a','the','why','how','what','in');
		if(!in_array($tag,$disallow_array))
			return true;
		else
			return false;
	}
	
	
	/**
	* FUNCTION USED TO GET CATEGORY LIST
	*/
	function getCategoryList()
	{
		global $db;
		$sql = "SELECT * FROM category";
		return $db->GetArray($sql);
	}
	
	
	/**
	* FUNCTION USED TO REGISTER ACTIONS THAT ARE TO APPLIED
	* ON COMMENTS , TITLE, DESCRIPTIONS etc
	*/
	function register_action($name,$type=NULL)
	{
		global $Cbucket;
		if(is_array($name))
		{
			foreach($name as $key => $naam)
				if(is_array($naam))
				{
					foreach($naam as $name)
					{
						$Cbucket->actionList[$name][] = $key;
					}
				}else{

					$Cbucket->actionList[$naam][] = $key;
				}
		}elseif($type!=NULL){
			$Cbucket->actionList[$type][] = $name;
		}
	}
	//Function used to register function as multiple modifiers
	
	
	
	/**
	* Function used to insert data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @param : extra params
	*/
	function dbInsert($tbl,$flds,$vls,$ep=NULL)
	{
		global $db ;
		$total_fields = count($flds);
		$count = 0;
		foreach($flds as $field)
		{
			$count++;
			$fields_query .= $field;
			if($total_fields!=$count)
				$fields_query .= ',';
		}
		$total_values = count($vls);
		$count = 0;
		foreach($vls as $value)
		{
			$count++;
			$val = mysql_clean($value);
			$needle = substr($val,0,3);
			
			if($needle != '|f|')
				$values_query .= "'".$val."'";
			else
			{
				$val = substr($val,3,strlen($val));
				$values_query .= "'".$val."'";
			}
			
			
			if($total_values!=$count)
				$values_query .= ',';
		}
		//Complete Query
		$query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";
		//if(!mysql_query($query)) die(mysql_error());
		$db->Execute($query);
		if(mysql_error()) die ($db->db_query.'<br>'.mysql_error());
	}
	
	/**
	* Function used to Update data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @param : Condition params
	* @params : Extra params
	*/
	function dbUpdate($tbl,$flds,$vls,$cond,$ep=NULL)
	{
		global $db ;
		
		$total_fields = count($flds);
		$count = 0;
		for($i=0;$i<$total_fields;$i++)
		{
			$count++;
			$val = mysql_clean($vls[$i]);
			$needle = substr($val,0,3);
			if($needle != '|f|')
				$fields_query .= $flds[$i]."='".$val."'";
			else
			{
				$val = substr($val,3,strlen($val));
				$fields_query .= $flds[$i]."=".$val."";
			}
			if($total_fields!=$count)
				$fields_query .= ',';
		}
		//Complete Query
		$query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";
		//if(!mysql_query($query)) die(mysql_error());
		$db->Execute($query);
		if(mysql_error()) die ($db->db_query.'<br>'.mysql_error());
		return $query;
	}
	
	
	
	/**
	* Function used to Delete data in database
	* @param : table name
	* @param : fields array
	* @param : values array
	* @params : Extra params
	*/
	function dbDelete($tbl,$flds,$vls,$ep=NULL)
	{
		global $db ;
		$total_fields = count($flds);
		$count = 0;
		for($i=0;$i<$total_fields;$i++)
		{
			$count++;
			$fields_query .= $flds[$i].'='.mysql_clean($vls[$i]);
			if($total_fields!=$count)
				$fields_query .= ' AND ';
		}
		//Complete Query
		$query = "DELETE FROM $tbl WHERE $fields_query $ep";
		//if(!mysql_query($query)) die(mysql_error());
		$db->Execute($query);
		if(mysql_error()) die ($db->db_query.'<br>'.mysql_error());
	}
	
	
	/**
	* FUNCTION USED TO CREATE ANCHOR PLACEMENT
	* these are the placement where we can add plugin's or widget's code,
	* e.g if we want to display a new WYSIWYG box before comment text area 
	* we will create anchor before text area as {ANCHOR place='before_compose_box'}
	* code will be written in plugin file and its place will point 'before_compose_box'
	* then our function will get all the code for this placement and will display it
	* @param : array(Ad Code, LIMIT);
	*/
	function ANCHOR($params,&$Smarty)
	{
		global $Cbucket;
		//Getting List of codes to display at this anchor
		$codes = $Cbucket->get_anchor_codes($params['place']);
		if(!empty($codes))
		{
			if(is_array($codes))
			{
				foreach($codes as $code)
				{
					echo $code;
				}
			}else{
				echo $codes;
			}
		}
		
		//Getting list of function that will be performed while calling achor
		$funcs = $Cbucket->get_anchor_function_list($params['place']);

		if(!empty($funcs))
		{
			if(is_array($funcs))
			{
				foreach($funcs as $func)
				{
					if(function_exists($func))
					$func();
				}
			}else{
				$funcs();
			}
		}
	}
	
	/**
	* FUNCTION USED TO REGISTER ANCHORS
	* before_comments etc.. see complete list on http://docs.clip-bucket.com
	*/
	function register_anchor($name,$type=NULL)
	{
		global $Cbucket;
		if(is_array($name))
		{
			foreach($name as $key => $naam)
				if(is_array($naam))
				{
					foreach($naam as $name)
					{
						$Cbucket->anchorList[$name][] = $key;
					}
				}else{

					$Cbucket->anchorList[$naam][] = $key;
				}
		}elseif($type!=NULL){
			$Cbucket->anchorList[$type][] = $name;
		}
	}
	
	
	
	/**
	* FUNCTION USED TO REGISTER FUNCTION
	* If you want to perform some function on 
	* some place, you can simple register function that will be execute where anchor points are
	* placed
	*/
	function register_anchor_function($name,$type=NULL)
	{
		global $Cbucket;
		if(is_array($name))
		{
			foreach($name as $key => $naam)
				if(is_array($naam))
				{
					foreach($naam as $name)
					{
						$Cbucket->anchor_function_list[$name][] = $key;
					}
				}else{

					$Cbucket->anchor_function_list[$naam][] = $key;
				}
		}elseif($type!=NULL){
			$Cbucket->anchor_function_list[$type][] = $name;
		}
	}
	
	/**
	 * Insert Id
	 */
	 function get_id($code)
	 {
		 global $Cbucket;
		 $id = $Cbucket->ids[$code];
		 if(empty($id)) $id = $code;
		 return $id;
	 }
	 
	/**
	 * Set Id
	 */
	 function set_id($code,$id)
	 {
		 global $Cbucket;
		 return $Cbucket->ids[$code]=$id;
	 }
	 
	 
	 /**
	  * Function used to add items in admin menu
	  * This function will insert new item in admin menu
	  * under given header, if the header is not available 
	  * it will create one, ( Header means titles ie 'Plugins' 'Videos' etc)
	  * @param STRING $header - Could be Plugin , Videos, Users , please check 
	  * http://docs.clip-bucket.com. for reference
	  * @param STRING name 
	  * @param STRING link
	  * That will add new item in admin menu
	  */
	 function add_admin_menu($header='Tool Box',$name,$link)
	 {
		 global $Cbucket;
		 //Gett Menu
		 $menu = $Cbucket->AdminMenu;
		 //Add New Meny
		 $menu[$header][$name] = $link;
		 $Cbucket->AdminMenu = $menu;
	 }
	 
	
	/**
	 * Function used to select data from database
	 */
	function dbselect($tbl,$fields='*',$cond=false,$limit=false,$order=false)
	{
		global $db;
		$query_params = '';
		//Making Condition possible
		if($cond)
		$where = " WHERE ";
		else
		$where = false;
		
		$query_params .= $where;
		if($where)
		{
			$query_params .= $cond;
		}
		
		
		
		if($order)
			$query_params .= " ORDER BY $order ";
		if($limit)
			$query_params .= " LIMIT $limit ";
			
		$query = " SELECT $fields FROM $tbl $query_params ";

		//Finally Executing	
		$data = $db->Execute($query);
		$db->num_rows = $data->_numOfRows;

		//Now Get Rows and return that data
		if($db->num_rows > 0)
			return $data->getrows();
		else
			return false;
	}
	
	
	
	/**
	 * An easy function for erorrs and messages (e is basically short form of exception)
	 * I dont want to use the whole Trigger and Exception code, so e pretty works for me :D
	 * @param TEXT $msg
	 * @param TYPE $type (e for Error, m for Message
	 * @param INT $id Any Predefined Message ID
	 */
	
	function e($msg=NULL,$type='e',$id=NULL)
	{
		global $eh;
		return $eh->e($msg,$type,$id);
	}
	
	
	/**
	 * Function used to get subscription template
	 */
	function get_subscription_template()
	{
		global $LANG;
		return $LANG['user_subscribe_message'];
	}
	
	
	/**
	 * Short form of print_r as pr
	 */
	function pr($text)
	{
		print_r($text);
	}
	
	
	/**
	 * This function is used to call function in smarty template
	 * This wont let you pass parameters to the function, but it will only call it
	 */
	function FUNC($params,&$Smarty)
	{
		global $Cbucket;
		//Function used to call functions by
		//{func namefunction_name}
		// in smarty
		$func=$params['name'];
		if(function_exists($func))
			$func();
	}
	
	/**
	 * Function used to get userid anywhere 
	 * if there is no user_id it will return false
	 */
	function user_id()
	{
		global $userquery;
		if($userquery->userid !='') return $userquery->userid; else false;
	}
	//replica
	function userid(){return user_id();}
	
	/**
	 * Function used to get username anywhere 
	 * if there is no usern_name it will return false
	 */
	function user_name()
	{
		global $userquery;
		return $userquery->user_name;
	}
	function username(){return user_name();}
	
	/**
	 * Function used to return mysql time
	 * @author : Fwhite
	 */
	function NOW()
	{
		return date('Y-m-d H:i:s', time());
	}
	
	
	/**
	 * Function used to get Regular Expression from database
	 * @param : code
	 */
	function get_re($code)
	{
		global $db;
		$results = $db->select("validation_re","*"," re_code='$code'");
		if($db->num_rows>0)
		{
			return $results[0]['re_syntax'];
		}else{
			return false;
		}
	}
	function get_regular_expression($code)
	{
		return get_re($code); 
	}
	
	/**
	 * Function used to check weather input is valid or not
	 * based on preg_match
	 */
	function check_re($syntax,$text)
	{
		preg_match('/'.$syntax.'/',$text,$matches);
		if(!empty($matches[0]))
		{
			return true;
		}else{
			return false;
		}
	}
	function check_regular_expression($code,$text)
	{
		return check_re($code,$text); 
	}
	
	/**
	 * Function used to check field directly
	 */
	function validate_field($code,$text)
	{
		$syntax =  get_re($code);
		if(empty($syntax))
			return true;
		return check_regular_expression($syntax,$text);
	}
	
	function is_valid_syntax($code,$text)
	{
		return validate_field($code,$text);
	}
	
	/**
	 * Function used to apply function on a value
	 */
	function is_valid_value($func,$val)
	{
		if(!function_exists($func))
			return true;
		elseif(!$func($val))
			return false;
		else
			return true;
	}
	
	function apply_func($func,$val)
	{
		if(is_array($func))
		{
			foreach($func as $f)
				if(function_exists($f))
					$val = $f($val);
		}else{
			$val = $func($val);
		}
		return $val;
	}
	
	/**
	 * Function used to validate YES or NO input
	 */
	function yes_or_no($input,$return=yes)
	{
		$input = strtolower($input);
		if($input!=yes && $input !=no)
			return $return;
		else
			return $input;
	}
	
	/**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_category($array=NULL)
	{
		global $myquery,$LANG;
		if($array==NULL)
			$array = $_POST['category'];
		if(count($array)==0)
			return false;
		else
		{
			
			foreach($array as $arr)
			{
				if($myquery->CategoryExists($arr))
					$new_array[] = $arr;
			}
		}
		if(count($new_array)==0)
		{
			e($LANG['vdo_cat_err3']);
			return false;
		}elseif(count($new_array)>ALLOWED_CATEGORIES)
		{
			e(sprintf($LANG['vdo_cat_err2'],ALLOWED_CATEGORIES));
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
		$db->select("video","videokey"," videokey='$key'");
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
		$results = $db->select("video","videoid,file_name"," file_name='$name'");
		
		if($db->num_rows >0)
			return $results[0]['videoid'];
		else
			return false;
	}
	
	
	
	/**
	 * Function used to get video from downloading queue
	 */
	function get_queued_video()
	{
		global $db;
		$results = $db->select("conversion_queue","*","cqueue_conversion='no'");
		$result = $results[0];
		$db->update("conversion_queue",array("cqueue_conversion"),array("p")," cqueue_id = '".$result['cqueue_id']."'");
		return $result;
	}

	
	
	function get_video_details($vid=NULL)
	{
		global $myquery;
		if(!$vid)
			global $vid;
		
		return $myquery->get_video_details($vid);
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
	function get_all_video_files_smarty($params,&$Smarty)
	{
		$vdetails = $params['vdetails'];
		$count_only = $params['count_only'];
		$with_path = $params['with_path'];
		return get_all_video_files($vdetails,$count_only,$with_path);
	}
	
	/**
	 * Function use to get video files
	 */
	function get_video_file($vdetails,$return_default=true,$with_path=true,$multi=false,$count_only=false)
	{
		# checking if there is any other functions
		# available
		if(is_array($Cbucket->custom_video_file_funcs))
		foreach($Cbucket->custom_video_file_funcs as $funcs)
			if(function_exists($func))
				return $func($vdetails);
		
		#Now there is no function so lets continue as
		$vid_files = glob(VIDEOS_DIR."/".$vdetails['file_name']."*");

		#replace Dir with URL
		foreach($vid_files as $file)
		{
			$files_part = explode('/',$file);
			$video_file = $files_part[count($files_part)-1];
			
			if($with_path)
				$files[]	= VIDEOS_URL.'/'.$video_file;
			else
				$files[]	= $video_file;
		}
		
		if(count($files)==0 && !$multi && !$count_only)
		{
			if($return_default)
			{
				if($with_path)
					return VIDEOS_URL.'/no_video.flv';
				else
					return 'no_video.flv';
			}
		}else{
			if($multi)
				return $files;
			if($count_only)
				return count($files);
			return $files[0];
		}
	}
	
	
	/**
	 * Function used to display flash player for ClipBucket video
	 */
	function flashPlayer($param,&$Smarty)
	{
		global $Cbucket,$swfobj;
		
		$key 		= $param['key'];
		$flv 		= $param['flv'].'.flv';
		$code 		= $param['code'];
		$flv_url 	= $file;
		$embed 		= $param['embed'];
		$code 		= $param['code'];
		$height 	= $param['height'] = $param['height'] ? $param['height'] : 360;
		$width 		= $param['width'] = $param['width'] ? $param['width'] : 450;
		
		if(count($Cbucket->actions_play_video)>0)
		{
	 		foreach($Cbucket->actions_play_video as $funcs)
			{
				if(function_exists($funcs))
				{
					$func_data = $funcs($param['vdetails']);
				}
				if($func_data)
					return $func_data;
			}
		}
		
		#checking video file
		$file = get_video_file($param['vdetails']);
		
		
		
		//Getting Player
		$player = get_player();
		$swfobj->playerFile = BASEURL.'/player/'.FLVPLAYER;
		$swfobj->FlashObj();
		//Writing Param
		$swfobj->addParam('allowfullscreen','true');
		$swfobj->addParam('allowscriptaccess','always');
		$swfobj->addParam('quality','high');
		$swfobj->addVar('baseurl',BASEURL);
		$swfobj->addVar('video',get_video_file($param['vdetails'],false,true));

		if(!empty($flv_url) && GetExt($flv_url)=='flv'){
			$swfobj->addVar('file_url',urldecode($flv_url));
		}else{
			$swfobj->addVar('file_url',$file );
		}
		$swfobj->CreatePlayer();
		return $swfobj->code;
	}
	
	/**
	 * Function used to get player from website settings
	 */
	function get_player()
	{
		global $Cbucket;
		return $Cbucket->configs['player_file'];
	}
	
	
	/**
	 * Function used to get user avatar
	 * @param ARRAY $userdetail
	 * @param SIZE $int
	 */
	function avatar($param,&$Smarty)
	{
		global $userquery;
		$udetails = $param['details'];
		$size = $param['size'];
		$uid = $param['uid'];
		return $userquery->avatar($udetails,$size,$uid);
	}
	
	
	/**
	 * This funcion used to call function dynamically in smarty
	 */
	function load_form($param,&$Smarty)
	{
		$func = $param['name'];
		if(function_exists($func))
			return $func($param);
	}
	
	
	
	/**
	 * Function used to add custom upload fields
	 * In this you will provide an array that has a complete
	 * details of the field such as 'name',validate_func etc
	 * please check docs.clip-bucket.com for "how to add custom upload field"
	 */
	function register_custom_upload_field($array)
	{
		global $Upload;
		$name = key($array);
		if(is_array($array) && !empty($array[$name]['name']))
		{
			foreach($array as $key => $arr)
				$Upload->custom_upload_fields[$key] = $arr;
		}
	}
	
	/**
	 * Function used to add custom form fields
	 * In this you will provide an array that has a complete
	 * details of the field such as 'name',validate_func etc
	 * please check docs.clip-bucket.com for "how to add custom form field"
	 */
	function register_custom_form_field($array)
	{
		global $Upload;
		$name = key($array);
		if(is_array($array) && !empty($array[$name]['name']))
		{
			foreach($array as $key => $arr)
				$Upload->custom_form_fields[$key] = $arr;
		}
	}
	
	
	/**
	 * Function used to get PHP Path
	 */
	 function php_path()
	 {
		 return PHP_PATH;
	 }
	 
	 
	 
	 /**
	  * Function used to add actions that will be performed
	  * when video is uploaded
	  * @param Function name
	  */
	 function register_after_video_upload_action($func)
	 {
		 global $Upload;
		 $Upload->actions_after_video_upload[] = $func;
	 }
	 
	  /**
	  * Function used to add actions that will be performed
	  * when video is going to play, it will check which player to use
	  * what type to use and what to do
	  * @param Function name
	  */
	 function register_actions_play_video($func)
	 {
		 global $Cbucket;
		 $Cbucket->actions_play_video[] = $func;
	 }
	 
	 
	 
	/**
	 * Function in case htmlspecialchars_decode does not exist
	 */
	function unhtmlentities ($string) {
		$trans_tbl =get_html_translation_table (HTML_ENTITIES );
		$trans_tbl =array_flip ($trans_tbl );
		return strtr ($string ,$trans_tbl );
	}
	
	
	
	/**
	 * Function used to update processed video
	 * @param Files details
	 */
	function update_processed_video($file_array)
	{
		global $db;
		$file = $file_array['cqueue_name'];
		$array = explode('-',$file);
		
		if(!empty($array[0]))
			$file_name = $array[0];
		$file_name = $file;
		
		$file_path = VIDEOS_DIR.'/'.$file_array['cqueue_name'].'.flv';

		if(file_exists($file_path))
		{		
			$file_size = filesize($file_path);
			//Now we will update video where file_name = $file_name
			if($file_size>0)
			{
				//Get Duration 
				$stats = get_file_details($file_name);
				$db->update("video",array("status","duration"),array("Successful",$stats['src_duration'])," file_name='".$file_name."'");
			}
		}	
	}
	
	
	/**
	 * This function will activate the video if file exists
	 */
	function activate_video_with_file($vid)
	{
		global $db;
		$vdetails = get_video_details($vid);
		$file_name = $vdetails['file_name'];
		$results = $db->select("conversion_queue","*"," cqueue_name='$file_name' AND cqueue_conversion='yes'");
		$result = $results[0];
		update_processed_video($result);							   
	}
	
	
	/**
	 * Function Used to get video file stats from database
	 * @param FILE_NAME
	 */
	function get_file_details($file_name)
	{
		global $db;
		$result = $db->select("video_files","*"," id ='$file_name' OR src_name = '$file_name' ");
		return $result[0];
	}
	
	
	/**
	 * Function used to execute command in background
	 */
	function bgexec($cmd) {
		if (substr(php_uname(), 0, 7) == "Windows"){
			//exec($cmd." >> /dev/null &");
			exec($cmd);
			//pclose(popen("start \"bla\" \"" . $exe . "\" " . escapeshellarg($args), "r")); 
		}else{
			exec($cmd . " > /dev/null &");  
		}
	}
	
	
	/**
	 * Function used to get thumbnail number from its name
	 */
	function get_thumb_num($name)
	{
		$list = explode('-',$name);
		$list = explode('.',$list[1]);
		return  $list[0];
	}
	
	
	/**
	 * Function used to remove thumb
	 */
	function delete_video_thumb($file)
	{
		global $LANG;
		$path = THUMBS_DIR.'/'.$file;
		if(file_exists($path))
		{
			unlink($path);
			e($LANG['video_thumb_delete_msg'],m);
		}else{
			e($LANG['video_thumb_delete_err']);
		}
	}
	 
	 
	/**
	 * Function used to get array value
	 * if you know partial value of array and wants to know complete 
	 * value of an array, this function is being used then
	 */
	function array_find($needle, $haystack)
	{
	   foreach ($haystack as $item)
	   {
		  if (strpos($item, $needle) !== FALSE)
		  {
			 return $item;
			 break;
		  }
	   }
	}

	
	
	/**
	 * Function used to give output in proper form 
	 */
	function input_value($params,&$Smarty)
	{
		$input = $params['input'];

		if(function_exists($input['display_function']))
			return $input['display_function']($input['value']);
		else
			return $input['value'];
	}
	
	/**
	 * Function used to convert input to categories
	 * @param input can be an array or #12# like
	 */
	function convert_to_categories($input)
	{
		if(is_array($input))
		{
			foreach($input as $in)
			{		
				if(is_array($in))
				{
					foreach($in as $i)
					{
						if(is_array($i))
						{
							foreach($i as $info)
							{
								$cat_details = get_category($info);
								$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
							}
						}elseif(is_numeric($i)){
							$cat_details = get_category($i);
							$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
						}
					}
				}elseif(is_numeric($in)){
					$cat_details = get_category($in);
					$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
				}
			}
		}else{
			preg_match_all('/#([0-9]+)#/',$default['category'],$m);
			$cat_array = array($m[1]);
			foreach($cat_array as $i)
			{
				$cat_details = get_category($i);
				$cat_array[] = array($cat_details['categoryid'],$cat_details['category_name']);
			}
		}
		
			$count = 1;
			if(is_array($cat_array))
			{
				foreach($cat_array as $cat)
				{
					echo '<a href="'.$cat[0].'">'.$cat[1].'</a>';
					if($count!=count($cat_array))
					echo ', ';
					$count++;
				}
			}
	}
	
	
	
	/**
	 * Function used to get categorie details
	 */
	function get_category($id)
	{
		global $myquery;
		return $myquery->get_category($id);
	}
	
	
	/**
	 * Sharing OPT displaying
	 */
	function display_sharing_opt($input)
	{
		foreach($input as $key => $i)
		{
			return $key;
			break;
		}
	}
	
	/**
	 * Function used to get number of videos uploaded by user
	 * @param INT userid
	 * @param Conditions
	 */
	function get_user_vids($uid,$cond=NULL,$count_only=false)
	{
		global $userquery;
		return $userquery->get_user_vids($uid,$cond,$count_only);
	}
	
	
	
	/**
	 * Function used to get error_list
	 */
	function error_list()
	{
		global $eh;
		return $eh->error_list;
	}
	
	
	/**
	 * Function used to add tempalte in display template list
	 */
	function template_files($file)
	{
		global $ClipBucket;
		$ClipBucket->template_files[] = $file;
	}
	
	/** 
	 * Function used to call display
	 */
	function display_it()
	{
		global $ClipBucket;
		$dir = LAYOUT;
		foreach($ClipBucket->template_files as $file)
		{
			if(file_exists(LAYOUT.'/'.$file))
			{
				$new_list[] = $file;
			}
		}
		
		assign('template_files',$new_list);
		Template('body.html');
	}
	
	
	/**
	 * Function used to display hint
	 */
	function hint($hint)
	{
		
	}
	
	
	
	function showpagination($total,$page,$link,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		global $pages;
		return $pages->pagination($total,$page,$link,$extra_params,$tag);
	}
	
	
	/**
	 * Function used to check username is disallowed or not
	 * @param USERNAME
	 */
	function check_disallowed_user($username)
	{
		global $Cbucket;
		$disallowed_user = $Cbucket->configs['disallowed_usernames'];
		$censor_users = explode(',',$disallowed_user);
		if(in_array($username,$censor_users))
			return false;
		else
			return true;
	}

	 
?>