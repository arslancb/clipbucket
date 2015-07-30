<?php
/**
###################################################################
# Copyright (c) 2008 - 2010 ClipBucket / PHPBucket
# URL:              [url]http://clip-bucket.com[/url]
# Function:         Various
# Author:           Arslan Hassan
# Language:         PHP
# License:          Attribution Assurance License
# [url]http://www.opensource.org/licenses/attribution.php[/url]
# Version:          $Id: functions.php 1068 2013-05-22 08:26:28Z ahzulfi $
# Last Modified:    $Date: 2013-05-22 13:26:28 +0500 (Wed, 22 May 2013) $
# Notice:           Please maintain this section
####################################################################
**/

 define("SHOW_COUNTRY_FLAG",TRUE);
 require 'define_php_links.php';
 include_once 'upload_forms.php';
 
 	/**
    * Function used to throw
    ** @param  $message pointer
    ** @return message
    */
   function throw_error($message,$pointer="")
    {
        global $Cbucket;
        if($pointer)
        $Cbucket->error_pointer[$pointer] = $message;
          throw new Exception($message);
    }
 
	//This Funtion is use to get CURRENT PAGE DIRECT URL
	function curPageURL()
	{
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
 	 //$string = $string;
 	 //$string = htmlentities($string);
	 if($allow_html==false){
 		 $string = strip_tags($string);
		 $string =  Replacer($string);
	 }
	// $string = utf8_encode($string);
 	 return $string;
	}
	
	function cb_clean($string,$array=array('no_html'=>true,'mysql_clean'=>false))
	{
		if($array['no_html'])
			$string = htmlentities($string);
		if($array['special_html'])
			$string = htmlspecialchars($string);
		if($array['mysql_clean'])
			$string = mysql_real_escape_string($string);
		if($array['nl2br'])
			$string = nl2br($string);
		return $string;
	}
	
	//This Fucntion is for Securing Password, you may change its combination for security reason but make sure dont not rechange once you made your script run
	
	function pass_code($string) {
 	 $password = md5(md5(sha1(sha1(md5($string)))));
 	 return $password;
	}
	
	//Mysql Clean Queries
	function sql_free($id)
	{
		if (!get_magic_quotes_gpc())
		{
			$id = addslashes($id);
		}
		return $id;
	}
	
	
	function mysql_clean($id,$replacer=true){
		//$id = clean($id);
		global $db;
		if (get_magic_quotes_gpc())
		{
			$id = stripslashes($id);
		}
		$id = htmlspecialchars(mysqli_real_escape_string($db->mysqli,$id));
		if($replacer)
			$id = Replacer($id);
		return $id;
	}
	
	function escape_gpc($in)
	{
		if (get_magic_quotes_gpc())
		{
			$in = stripslashes($in);
		}
		return $in;
	}

	//Funtion of Random String
	function RandomString($length)
	{
		$string = md5(microtime());
		$highest_startpoint = 32-$length;
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
		

			
	/**
	 * Function used to send emails
	 * @Author : Arslan Hassan
	 * this is a very basic email function 
	 * you can extend or replace this function easily
	 * read our docs.clip-bucket.com
	 */
	function cbmail($array)
	{
		$func_array = get_functions('email_functions');
		if(is_array($func_array))
		{
			foreach($func_array as $func)
			{
				if(function_exists($func))
				{
					return $func($array);
				}
			}
		}
		
		$content = escape_gpc($array['content']);
		$subject = escape_gpc($array['subject']);
		$to		 = $array['to'];
		$from	 = $array['from'];
		$to_name = $array['to_name'];
		$from_name = $array['from_name'];
		
		if($array['nl2br'])
			$content = nl2br($content);
		
		# CHecking Content
		if(preg_match('/<html>/',$content,$matches))
		{
			if(empty($matches[1]))
			{
				$content = wrap_email_content($content);
			}
		}
		$message .= $content;
		
		//ClipBucket uses PHPMailer for sending emails
		include_once("classes/phpmailer/class.phpmailer.php");
		include_once("classes/phpmailer/class.smtp.php");
		
		$mail  = new PHPMailer(); // defaults to using php "mail()"
		
		$mail_type = config('mail_type');
		
		//---Setting SMTP ---		
		if($mail_type=='smtp')
		{
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->Host       = config('smtp_host'); // SMTP server
			if(config('smtp_auth')=='yes')
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->Port       = config('smtp_port');                    // set the SMTP port for the GMAIL server
			$mail->Username   = config('smtp_user'); // SMTP account username
			$mail->Password   = config('smtp_pass');        // SMTP account password
		}
		//--- Ending Smtp Settings
		
		$mail->SetFrom($from, $from_name);
		
		if(is_array($to))
		{
			foreach($to as $name)
			{		
				$mail->AddAddress(strtolower($name), $to_name);
			}
		} else {
			$mail->AddAddress(strtolower($to), $to_name);
		}
		
		$mail->Subject = $subject;
		$mail->MsgHTML($message);
				
		if(!$mail->Send())
		{
			//apply check here nafisa	
			if(has_access('admin_access',TRUE) )
		  		e("Mailer Error: " . $mail->ErrorInfo);
		  return false;
		}else
			return true;
	}
	function send_email($from,$to,$subj,$message)
	{
		return cbmail(array('from'=>$from,'to'=>$to,'subject'=>$subj,'content'=>$message));
	}
	
	/**
	 * Function used to wrap email content in 
	 * HTML AND BODY TAGS
	 */
	function wrap_email_content($content)
	{
		return '<html><body>'.$content.'</body></html>';
	}
	
	/**
	 * Function used to get file name
	 */
	function GetName($file)
	{
		if(!is_string($file))
			return false;
		
		//for srever thumb files 
		$parts = parse_url($file);
        parse_str($parts['query'], $query);
        $get_file_name = $query['src'];
        $path = explode('.',$get_file_name);
        $server_thumb_name = $path[0];
        if (!empty($server_thumb_name))
        	return $server_thumb_name;
        /*srever thumb files END */

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
			return strtolower(substr($file, strrpos($file,'.') + 1));
			}

	function old_set_time($temps)
	{
			round($temps);
			$heures = floor($temps / 3600);
			$minutes = round(floor(($temps - ($heures * 3600)) / 60));
			if ($minutes < 10)
					$minutes = "0" . round($minutes);
			$secondes = round($temps - ($heures * 3600) - ($minutes * 60));
			if ($secondes < 10)
					$secondes = "0" .  round($secondes);
			return $minutes . ':' . $secondes;
	}
	function SetTime($sec, $padHours = true) {
	
		if($sec < 3600)
			return old_set_time($sec);
			
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
      $pattern = "/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
	  preg_match($pattern, $email,$matches);
      if ($matches[0]!=''){
         return true;
      }
      else {
		/* if(!DEVELOPMENT_MODE)
         	return false;
		 else*/
		 	return true;
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

	/**
	 * Get Directory Size - get_video_file($vdata,$no_video,false);
	 */
	function get_directory_size($path)
	{
		$totalsize = 0;
		$totalcount = 0;
		$dircount = 0;
		if ($handle = opendir ($path))
		{
		while (false !== ($file = readdir($handle)))
		{
		  $nextpath = $path . '/' . $file;
		  if ($file != '.' && $file != '..' && !is_link ($nextpath))
		  {
			if (is_dir ($nextpath))
			{
			  $dircount++;
			  $result = get_directory_size($nextpath);
			  $totalsize += $result['size'];
			  $totalcount += $result['count'];
			  $dircount += $result['dircount'];
			}
			elseif (is_file ($nextpath))
			{
			  $totalsize += filesize ($nextpath);
			  $totalcount++;
			}
		  }
		}
		}
		closedir ($handle);
		$total['size'] = $totalsize;
		$total['count'] = $totalcount;
		$total['dircount'] = $dircount;
		return $total;
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
		
		return $data;
		// if( $data === false )
		// 	echo "<p>FAILED: $cmd</p></div>";
		// echo '<p><pre>' . htmlentities( $data ) . '</pre></p></div>';
	}
	
	/**
	 * Function used to get shell output
	 */
	function shell_output($cmd)
	{
		if (stristr(PHP_OS, 'WIN')) { 
			$cmd = $cmd;
		}else{
			$cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\"  2>&1";
		}
		$data = shell_exec( $cmd );
		return $data;
	}
	

	
	
	/**
	 * Function used to tell ClipBucket that it has closed the script
	 */
	function the_end()
	{
		if(!$isWorthyBuddy) 
		{
				/*
				echo base64_decode("PGgyPklsbGVnYWwgT3BlcmF0aW9uIEZvdW5k");
				echo "- Please VISIT ";
				echo base64_decode("PGEgaHJlZj0iaHR0cDovL2NsaXAtYnVja2V0LmNvbS8iPkNsaXBCdWNrZXQ8L2E+");
				echo " for Details</h2>";
				*/
				
				//Dear user, i have spent too much time of my life on developing
				//This software and if you want to return me in this way, 
				//its ok for me, but for those who told you how to do this...
				
		}
	}

	/**
	 * Group Link
	 */
	function group_link($params)
	{
		$grp = $params['details'];
		$id = $grp['group_id'];
		$name = $grp['group_name'];
		$url = $grp['group_url'];
		
		if($params['type']=='' || $params['type']=='group')
		{
			if(SEO==yes)
				return BASEURL.'/group/'.$url;
			else
				return BASEURL.'/view_group.php?url='.$url;
		}
		
		if($params['type']=='view_members')
		{
			return BASEURL.'/view_group_members.php?url='.$url;
			if(SEO==yes)
				return BASEURL.'/group_members/'.$url;
			else
				return BASEURL.'/view_group_members.php?url='.$url;
		}
		
		if($params['type']=='view_videos')
		{
			return BASEURL.'/view_group_videos.php?url='.$url;
			if(SEO==yes)
				return BASEURL.'/group_videos/'.$url;
			else
				return BASEURL.'/view_group_videos.php?url='.$url;
		}
		
		if($params['type'] == 'view_topics')
		{
			if(SEO == "yes")
				return BASEURL."/group/".$url."?mode=view_topics";
			else
				return BASEURL."/view_group.php?url=".$url."&mode=view_topics";		
		}
		
		if($params['type'] == 'view_report_form')
		{
			if(SEO == "yes")
				return BASEURL."/group/".$url."?mode=view_report_form";
			else
				return BASEURL."/view_group.php?url=".$url."&mode=view_report_form";	
		}
	}
	
	/**
	* FUNCTION USED TO GET COMMENTS
	* @param : array();
	*/
	function getComments($params=NULL)
	{
		global $db;
		$order = $params['order'];
		$limit = $params['limit'];
		$type = $params['type'];
		$cond = '';
		if(empty($type))
			$type = "v";
		$cond .= tbl("comments.type")." = '".$type."'";
		
		if($params['type_id'] && $params['sectionTable'])
		{
			if($cond != "")
				$cond .= " AND ";
			$cond .= tbl("comments.type_id")." = ".tbl($params['sectionTable'].".".$params['type_id']);
		}
				
		if($params['cond'])
		{
			if($cond != "")
				$cond .= " AND ";
			$cond .= $params['cond'];
		}


        $query = "SELECT * FROM ".tbl("comments".($params['sectionTable']?",".$params['sectionTable']:NULL));

        if($cond)
            $query .= " WHERE ".$cond;
        if($order)
            $query .=" ORDER BY ".$order;

        if($limit)
            $query .=" LIMIT ".$limit;

		if(!$params['count_only'])
        {
   
            $result = db_select($query);
            //$result = db_select(tbl("comments".($params['sectionTable']?",".$params['sectionTable']:NULL)),"*",$cond,$limit,$order);
        }


		//echo $db->db_query;	
		if($params['count_only'])
			$result = $db->count(tbl("comments"),"*",$cond);
			
		if($result)
			return $result;
		else
			return false;						
	}
	
	function getSmartyComments($params)
	{
		global $myquery;
		$comments  =  $myquery->getComments($params);
		
		if($params['assign'])
			assign($params['assign'],$comments);
		else
			return $comments;
	}
	
	/**
	* FUNCTION USED TO GET ADVERTISMENT
	* @param : array(Ad Code, LIMIT);
	*/
	function getAd($params)
	{
		global $adsObj;
		$data = '';
		if($params['style'] || $params['class'] || $params['align'])
			$data .= '<div style="'.$params['style'].'" class="'.$params['class'].'" align="'.$params['align'].'">';
		$data .= ad($adsObj->getAd($params['place']));
		if($params['style'] || $params['class'] || $params['align'])
			$data .= '</div>';
		return $data;
	}
	
	/**
	* FUNCTION USED TO GET THUMBNAIL, MADE FOR SMARTY
	* @ param : array("FLV");
	*/
	function getSmartyThumb($params)
	{
		return get_thumb($params['vdetails'],$params['num'],$params['multi'],$params['count_only'],true,true,$params['size']);
	}

	
	/**
	* FUNCTION USED TO GET VIDEO RATING IN SMARTY
	* @param : array(pullRating($videos[$id]['videoid'],false,false,false,'novote');
	*/
	function pullSmartyRating($param)
	{
		return pullRating($param['id'],$param['show5'],$param['showPerc'],$aram['showVotes'],$param['static']);	
	}
	
	/**
	* FUNCTION USED TO CLEAN VALUES THAT CAN BE USED IN FORMS
	*/
	function cleanForm($string)
	{
		if(is_string($string))
			$string = htmlspecialchars($string);
		if(get_magic_quotes_gpc())
			if(!is_array($string))
			$string = stripslashes($string);			
		return $string;
	}
	function form_val($string){return cleanForm($string); }
	
	//Escaping Magic Quotes
	
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
		if(is_array($newTags))
			$tagString = implode(',',$newTags);
		else
			$tagString = 'no-tag';
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
		if(!in_array($tag,$disallow_array) && strlen($tag)>2)
			return true;
		else
			return false;
	}
	
	
	/**
	* FUNCTION USED TO GET CATEGORY LIST
	*/
	function getCategoryList($params=false)
	{
		global $cats;
		$cats = "";
		
		$type = $params['type'];
		switch($type)
		{
			default:
			{
				 cb_call_functions('categoryListing',$params);
			}
			break;
			
			case "video":case "videos":
			case "v": 
			{
				global $cbvid;
				$cats = $cbvid->cbCategories($params);
			}
			break;
				
			case "users":case "user":
			case "u": case "channels": case "channels":
			{
				global $userquery;
				$cats = $userquery->cbCategories($params);
			}
			break;
			
			case "group":case "groups":
			case "g":
			{
				global $cbgroup;
				$cats = $cbgroup->cbCategories($params);
			}
			break;
			
			case "collection":case "collections":
			case "cl":
			{
				global $cbcollection;
				$cats = $cbcollection->cbCategories($params);
			}
			break;		
		}
		
		return $cats;
	}
	/*function getCategoryList($type='video',$with_all=false,$return_html=false)
	{
		$use_subs = config('use_subs');

		switch ($type)
		{
			case "video":
			default:
			{
				global $cbvid;

				if($return_html && $use_subs == "1") {
					$cats = $cbvid->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $cbvid->get_categories();
					
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
			case "user":
			{
				global $userquery;

				
				if($return_html && $use_subs == "1") {
					$cats = $userquery->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $userquery->get_categories();
					
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
			
			case "group":
			case "groups":
			{
				global $cbgroup;
				
				
				if($return_html && $use_subs == "1") {
					$cats = $cbgroup->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $cbgroup->get_categories();
						
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
			
			case "collection":
			case "collections":
			{
				global $cbcollection;
				
				
				if($return_html && $use_subs == "1") {
					$cats = $cbcollection->cb_list_categories($type,$with_all);
				} else {
					if($with_all)
						$all_cat = array(array('category_id'=>'all','category_name'=>'All'));
						
					$cats = $cbcollection->get_categories();
						
					if($all_cat && is_array($cats))
						$cats = array_merge($all_cat,$cats);
				}
				return $cats;
			}
			break;
		}
	}*/
	function cb_bottom()
	{
		//Woops..its gone
	}
	
	
	function getSmartyCategoryList($params)
	{
		return getCategoryList($params);
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
		$db->insert($tbl,$flds,$vls,$ep);
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
		return $db->update($tbl,$flds,$vls,$cond,$ep);		
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
		return $db->delete($tbl,$flds,$vls,$ep);		
	}
	
	
	/**
	 **
	 */
	function cbRocks()
	{
		define("isCBSecured",TRUE); 
		//echo cbSecured(CB_SIGN);
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
	 * Function used to select data from database
	 */
	function dbselect($tbl,$fields='*',$cond=false,$limit=false,$order=false,$p=false)
	{
		global $db;
		return $db->dbselect($tbl,$fields,$cond,$limit,$order,$p);
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
		if(!empty($msg))
			return $eh->e($msg,$type,$id);
	}
	
	
	/**
	 * Function used to get subscription template
	 */
	function get_subscription_template()
	{
		global $LANG;
		return lang('user_subscribe_message');
	}
	
	
	/**
	 * Short form of print_r as pr
	 */
	function pr($text,$wrap_pre=false)
	{
		if(!$wrap_pre)
		print_r($text);
		else
		{
			echo "<pre>";
			print_r($text);
			echo "</pre>";
		}
	}

	/**
	* PR extended into auto exiting
	*/
	function pex($text,$wrap_pre=false)
	{
		if(!$wrap_pre)
		print_r($text);
		else
		{
			echo "<pre>";
			print_r($text);
			echo "</pre>";
			exit("PEX Ran!");
		}
	}
	
	
	/**
	 * This function is used to call function in smarty template
	 * This wont let you pass parameters to the function, but it will only call it
	 */
	function FUNC($params)
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
		if($userquery->userid !='' && $userquery->is_login) return $userquery->userid; else false;
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
		if($userquery->user_name)
			return $userquery->user_name;
		else
			return $userquery->get_logged_username();
	}
	function username(){return user_name();}
	
	/**
	 * Function used to check weather user access or not
	 */
	function has_access($access,$check_only=TRUE,$verify_logged_user=true)
	{
		global $userquery;
		//dump($userquery->login_check($access,$check_only,$verify_logged_user));
		return $userquery->login_check($access,$check_only,$verify_logged_user);
	}
	
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
		$results = $db->select(tbl("validation_re"),"*"," re_code='$code'");
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
		preg_match('/'.$syntax.'/i',$text,$matches);
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
		if(DEV_INGNORE_SYNTAX)
			return true;
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
	function validate_group_category($array=NULL)
	{
		global $cbgroup;
		return $cbgroup->validate_group_category($array);
	}

	/**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_collection_category($array=NULL)
	{
		global $cbcollection;
		return $cbcollection->validate_collection_category($array);
	}
	
	/**
	 * Function used to get user avatar
	 * @param ARRAY $userdetail
	 * @param SIZE $int
	 */
	function avatar($param)
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
	function load_form($param)
	{
		$func = $param['name'];
		if(function_exists($func))
			return $func($param);
	}
	
	
	
	/**
	 * Function used to get PHP Path
	 */
	 function php_path()
	 {
		 if(PHP_PATH !='')
			 return PHP_PATH;
		 else
		 	return "/usr/bin/php";
	 }
	 
	 /**
	  * Functon used to get binary paths
	  */
	 function get_binaries($path)
	 {
		 if(is_array($path))
		 {
			 $type = $path['type'];
			 $path = $path['path'];
		 }
		
		if($type=='' || $type=='user')
		{
			$path = strtolower($path);
			switch($path)
			{
				case "php":
				return php_path();
				break;
				
				case "mp4box":
				return config("mp4boxpath");
				break;
				
				case "flvtool2":
				return config("flvtool2path");
				break;
				
				case "ffmpeg":
				return config("ffmpegpath");
				break;
			}
		}else{
			$path = strtolower($path);
			switch($path)
			{
				case "php":
				$return_path = shell_output("which php");
				if($return_path)
					return $return_path;
				else
					return "Unable to find PHP path";
				break;
				
				case "mp4box":
				$return_path =  shell_output("which MP4Box");
				if($return_path)
					return $return_path;
				else
					return "Unable to find mp4box path";
				break;
				
				case "flvtool2":
				$return_path =  shell_output("which flvtool2");
				if($return_path)
					return $return_path;
				else
					return "Unable to find flvtool2 path";
				break;
				
				case "ffmpeg":
				$return_path =  shell_output("which ffmpeg");
				if($return_path)
					return $return_path;
				else
					return "Unable to find ffmpeg path";
				break;
			}
		}
	 }
	 
	 
	/**
	 * Function in case htmlspecialchars_decode does not exist
	 */
	function unhtmlentities ($string)
	{
		$trans_tbl =get_html_translation_table (HTML_ENTITIES );
		$trans_tbl =array_flip ($trans_tbl );
		return strtr ($string ,$trans_tbl );
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
	function input_value($params)
	{
		$input = $params['input'];
		$value = $input['value'];
		
		if($input['value_field']=='checked')
			$value = $input['checked'];
			
		if($input['return_checked'])
			return $input['checked'];
			
		if(function_exists($input['display_function']))
			return $input['display_function']($value);
		elseif($input['type']=='dropdown')
		{
			if($input['checked'])
				return $value[$input['checked']];
			else
				return $value[0];
		}else
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
	 * Function used to get msg_list
	 */
	function msg_list()
	{
		global $eh;
		return $eh->message_list;
	}
	
	
	/**
	 * Function used to add tempalte in display template list
	 * @param File : file of the template
	 * @param Folder : weather to add template folder or not
	 * if set to true, file will be loaded from inside the template
	 * such that file path will becom $templatefolder/$file
	 * @param follow_show_page : this param tells weather to follow ClipBucket->show_page
	 * variable or not, if show_page is set to false and follow is true, this template will not load
	 * otherwise there it WILL
	 */
	function template_files($file,$folder=false,$follow_show_page=true)
	{
		global $ClipBucket;
		if(!$folder)
			$ClipBucket->template_files[] = array('file' => $file,'follow_show_page'=>$follow_show_page);
		else
			$ClipBucket->template_files[] = array('file'=>$file,
			'folder'=>$folder,'follow_show_page'=>$follow_show_page);
	}
	
	/**
	 * Function used to include file
	 */
	function include_template_file($params)
	{
		$file = $params['file'];
		
		if(file_exists(LAYOUT.'/'.$file))
			Template($file);
		elseif(file_exists($file))
			Template($file,false);
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

	
	/**
	 * Function used to validate username
	 * @input USERNAME
	 */
	function username_check($username)
	{
		global $Cbucket;
		$banned_words = $Cbucket->configs['disallowed_usernames'];
		$banned_words = explode(',',$banned_words);
		foreach($banned_words as $word)
		{
			preg_match("/$word/Ui",$username,$match);
			if(!empty($match[0]))
				return false;
		}
		//Checking if its syntax is valid or not
		$multi = config('allow_unicode_usernames');
		
		//Checking Spaces
		if(!config('allow_username_spaces'))
		preg_match('/ /',$username,$matches);
		if(!is_valid_syntax('username',$username) && $multi!='yes' || $matches)
			e(lang("class_invalid_user"));
		return true;
	}
	
	
	
	
	
	/**
	 * Function used to check weather username already exists or not
	 * @input USERNAME
	 */
	function user_exists($user)
	{
		global $userquery;
		return $userquery->username_exists($user);
	}
	
	/**
	 * Function used to check weather email already exists or not
	 * @input email
	 */
	function email_exists($user)
	{
		global $userquery;
		return $userquery->duplicate_email($user);
	}
	
	/**
	 * function used to check weather group URL exists or not
	 */
	function group_url_exists($url)
	{
		global $cbgroup;
		return $cbgroup->group_url_exists($url);
	}
	
	
	/**
	 * Function used to check weather erro exists or not
	 */
	function error($param='array')
	{
		if(count(error_list())>0)
		{
			if($param!='array')
			{
				if($param=='single')
					$param = 0;
				$msg = error_list();
				return $msg[$param];
			}
			return error_list();
		}else{
			return false;
		}
	}
	
	/**
	 * Function used to check weather msg exists or not
	 */
	function msg($param='array')
	{
		if(count(msg_list())>0)
		{
			if($param!='array')
			{
				if($param=='single')
					$param = 0;
				$msg = msg_list();
				return $msg[$param];
			}
			return msg_list();
		}else{
			return false;
		}
	}
	
	
	
	/**
	 * Function used to load plugin
	 * please check docs.clip-bucket.com
	 */
	function load_plugin()
	{
		global $cbplugin;
		
	}
	
	
	
	/**
	 * Function used to create limit functoin from current page & results
	 */
	function create_query_limit($page,$result)
	{
		$limit  = $result;	
		if(empty($page) || $page == 0 || !is_numeric($page)){
		$page   = 1;

		}
		$from 	= $page-1;
		$from 	= $from*$limit;
		
		return $from.','.$result;
	}
	
	
	/**
	 * Function used to get value from $_GET
	 */
	function get_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_GET[$val]);
		else
			return $_GET[$val];
	}function get($val){ return get_form_val($val); }
	
	/**
	 * Function used to get value form $_POST
	 */
	function post_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_POST[$val]);
		else
			$_POST[$val];
	}
	
	
	/**
	 * Function used to get value from $_REQUEST
	 */
	function request_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_REQUEST[$val]);
		else
			$_REQUEST[$val];
	}
	
	
	/**
	 * Function used to return LANG variable
	 */
	function lang($var,$sprintf=false)
	{
		global $LANG,$Cbucket;

		$array_str = array
		( '{title}');
		$array_replace = array
		( $Cbucket->configs['site_title'] );
		
		if(isset($LANG[$var]))
		{
			$phrase =  str_replace($array_str,$array_replace,$LANG[$var]);
		}else
		{
			$phrase = str_replace($array_str,$array_replace,$var);
		}
		
		if($sprintf)
		{
			$sprints = explode(',',$sprintf);
			if(is_array($sprints))
			{
				foreach($sprints as $sprint)
				{
					$phrase = sprintf($phrase,$sprint);
				}
			}
		}
		
		return $phrase;
		
	}
	function smarty_lang($param)
	{
		if(getArrayValue($param, 'assign')=='')
			return lang($param['code'],getArrayValue($param, 'sprintf'));
		else
			assign($param['assign'],lang($param['code'],$param['sprintf']));
	}


	function getArrayValue($array = array(), $key = false){
		if(!empty($array) && $key){
			if(isset($array[$key])){
				return $array[$key];
			}else{
				return false;
			}
		}
		return false;
	}

	function getConstant($constantName = false){
		if($constantName && defined($constantName))
			return constant($constantName);
		return false;
	}

	/**
	 * Function used to assign link
	 */
	function cblink($params)
	{
		global $ClipBucket;
		$name = getArrayValue($params, 'name');
		$ref = getArrayValue($params, 'ref');
		
		if($name=='category')
		{
			return category_link($params['data'],$params['type']);
		}
		if($name=='sort')
		{
			return sort_link($params['sort'],'sort',$params['type']);
		}
		if($name=='time')
		{
			return sort_link($params['sort'],'time',$params['type']);
		}
		if($name=='tag')
		{
			return BASEURL.'/search_result.php?query='.urlencode($params['tag']).'&type='.$params['type'];
		}
		if($name=='category_search')
		{
			return BASEURL.'/search_result.php?category[]='.$params['category'].'&type='.$params['type'];
		}
		
		
		if(SEO!='yes')
		{
			preg_match('/http:\/\//',$ClipBucket->links[$name][0],$matches);
			if($matches)
				$link = $ClipBucket->links[$name][0];
			else
				$link = BASEURL.'/'.$ClipBucket->links[$name][0];
		}else
		{
			preg_match('/http:\/\//',$ClipBucket->links[$name][1],$matches);
			if($matches)
				$link = $ClipBucket->links[$name][1];
			else
				$link = BASEURL.'/'.$ClipBucket->links[$name][1];
		}
		
		$param_link = "";
		if(!empty($params['extra_params']))
		{
			preg_match('/\?/',$link,$matches);
			if(!empty($matches[0]))
			{
				$param_link = '&'.$params['extra_params'];
			}else{
				$param_link = '?'.$params['extra_params'];
			}
		}
		
		if(isset($params['assign']))
			assign($params['assign'],$link.$param_link);
		else
			return $link.$param_link;
	}

	
	/**
	 * Function used to show rating
	 * @inputs
	 * class : class used to show rating usually rating_stars
	 * rating : rating of video or something
	 * ratings : number of rating
	 * total : total rating or out of
	 */
	function show_rating($params)
	{
		$class 	= $params['class'] ? $params['class'] : 'rating_stars';
		$rating 	= $params['rating'];
		$ratings 	= $params['ratings'];
		$total 		= $params['total'];
		$style		= $params['style'];
		if(empty($style))
			$style = config('rating_style');
		//Checking Percent

		{
			if($total<=10)
				$total = 10;
			$perc = $rating*100/$total;
			$disperc = 100 - $perc;		
			if($ratings <= 0 && $disperc == 100)
				$disperc = 0;
		}
				
		$perc = $perc.'%';
		$disperc = $disperc."%";		
		switch($style)
		{
			case "percentage": case "percent":
			case "perc": default:
			{
				$likeClass = "UserLiked";
				if(str_replace('%','',$perc) < '50')
					$likeClass = 'UserDisliked';
					
				$ratingTemplate = '<div class="'.$class.'">
									<div class="ratingContainer">
										<span class="ratingText">'.$perc.'</span>';
				if($ratings > 0)
					$ratingTemplate .= ' <span class="'.$likeClass.'">&nbsp;</span>';										
				$ratingTemplate .='</div>
								</div>';	
			}
			break;
			
			case "bars": case "Bars": case "bar":
			{
				$ratingTemplate = '<div class="'.$class.'">
					<div class="ratingContainer">
						<div class="LikeBar" style="width:'.$perc.'"></div>
						<div class="DislikeBar" style="width:'.$disperc.'"></div>
					</div>
				</div>';
			}
			break;
			
			case "numerical": case "numbers":
			case "number": case "num":
			{
				$likes = round($ratings*$perc/100);
				$dislikes = $ratings - $likes;
				
				$ratingTemplate = '<div class="'.$class.'">
					<div class="ratingContainer">
						<div class="ratingText">
							<span class="LikeText">'.$likes.' Likes</span>
							<span class="DislikeText">'.$dislikes.' Dislikes</span>
						</div>
					</div>
				</div>';
			}
			break;
			
			case "custom": case "own_style":
			{
				$file = LAYOUT."/".$params['file'];
				if(!empty($params['file']) && file_exists($file))
				{
					// File exists, lets start assign things
					assign("perc",$perc); assign("disperc",$disperc);
					
					// Likes and Dislikes
					$likes = floor($ratings*$perc/100);
					$dislikes = $ratings - $likes;
					assign("likes",$likes);	assign("dislikes",$dislikes);
					Template($file,FALSE);										
				} else {
					$params['style'] = "percent";
					return show_rating($params);	
				}
			}
			break;
		}
		/*$rating = '<div class="'.$class.'">
					<div class="stars_blank">
						<div class="stars_filled" style="width:'.$perc.'">&nbsp;</div>
						<div class="clear"></div>
					</div>
				  </div>';*/
		return $ratingTemplate;
	}

	/**
	 * Function used to display an ad
	 */
	function ad($in)
	{
		return stripslashes(htmlspecialchars_decode($in));
	}
	
	
	/**
	 * Function used to get
	 * available function list
	 * for special place , read docs.clip-bucket.com
	 */
	function get_functions($name)
	{
		global $Cbucket;
		if(isset($Cbucket->$name)){
			$funcs = $Cbucket->$name;
			if(is_array($funcs) && count($funcs)>0)
				return $funcs;
			else
				return false;
		}
	}
	
	
	/**
	 * Function used to add js in ClipBuckets JSArray
	 * see docs.clip-bucket.com
	 */
	function add_js($files)
	{
		global $Cbucket;
		return $Cbucket->addJS($files);
	}
	
	/**
	 * Function add_header()
	 * this will be used to add new files in header array
	 * this is basically for plugins
	 * for specific page array('page'=>'file') 
	 * ie array('uploadactive'=>'datepicker.js')
	 */
	function add_header($files)
	{
		global $Cbucket;
		return $Cbucket->add_header($files);
	}
	function add_admin_header($files)
	{
		global $Cbucket;
		return $Cbucket->add_admin_header($files);
	}

	/**
	 * Funcion used to call functions
	 * when user view channel
	 * ie in view_channel.php
	 */
	function call_view_channel_functions($u)
	{
		$funcs = get_functions('view_channel_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($u);
				}
			}
		}
		
		increment_views($u['userid'],"channel");
	}
	
	
	
	
	/**
	 * Funcion used to call functions
	 * when user view topic
	 * ie in view_topic.php
	 */
	function call_view_topic_functions($tdetails)
	{
		$funcs = get_functions('view_topic_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($tdetails);
				}
			}
		}
		
		increment_views($tdetails['topic_id'],"topic");
	}


	

	/**
	 * Funcion used to call functions
	 * when user view group
	 * ie in view_group.php
	 */
	function call_view_group_functions($gdetails)
	{
		$funcs = get_functions('view_group_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($gdetails);
				}
			}
		}
		increment_views($gdetails['group_id'],"group");
	}
	
	/**
	 * Funcion used to call functions
	 * when user view collection
	 * ie in view_collection.php
	 */
	function call_view_collection_functions($cdetails)
	{
		$funcs = get_functions('view_collection_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($cdetails);
				}
			}
		};

		increment_views($cdetails['collection_id'],"collection");
	}

	
	/**
	 * Function used to incream number of view
	 * in object
	 */
	function increment_views($id,$type=NULL)
	{
		global $db;
		switch($type)
		{
			case 'v':
			case 'video':
			default:
			{
				if(!isset($_COOKIE['video_'.$id])){
					$currentTime = time();
					file_put_contents("/home/sajjad/Desktop/log.txt", json_encode($videoViewsRecord));
					$views = (int)$videoViewsRecord["video_views"] + 1;
					$db->update(tbl("video_views"),array("video_views","last_updated"),array($views,$currentTime)," video_id='$id' OR videokey='$id'");
					$query = "UPDATE " . tbl("video_views") . " SET video_views = video_views + 1 WHERE video_id = {$id}";
					$result = $db->Execute($query);
					setcookie('video_'.$id,'watched',time()+3600);
					
				}
			}
			break;
			case 'u':
			case 'user':
			case 'channel':

			{
				
				if(!isset($_COOKIE['user_'.$id])){
					$db->update(tbl("users"),array("profile_hits"),array("|f|profile_hits+1")," userid='$id'");
					setcookie('user_'.$id,'watched',time()+3600);
				}
			}
			break;
			case 't':
			case 'topic':

			{
				if(!isset($_COOKIE['topic_'.$id])){
					$db->update(tbl("group_topics"),array("total_views"),array("|f|total_views+1")," topic_id='$id'");
					setcookie('topic_'.$id,'watched',time()+3600);
				}
			}
			break;
			break;
			case 'g':
			case 'group':

			{
				if(!isset($_COOKIE['group_'.$id])){
					$db->update(tbl("groups"),array("total_views"),array("|f|total_views+1")," group_id='$id'");
					setcookie('group_'.$id,'watched',time()+3600);
				}
			}
			break;
			case "c":
			case "collect":
			case "collection":
			{
				if(!isset($_COOKIE['collection_'.$id])){
					$db->update(tbl("collections"),array("views"),array("|f|views+1")," collection_id = '$id'");
					setcookie('collection_'.$id,'viewed',time()+3600);
				}
			}
			break;
			
			case "photos":
			case "photo":
			case "p":
			{
				if(!isset($_COOKIE['photo_'.$id]))
				{
					$db->update(tbl('photos'),array("views","last_viewed"),array("|f|views+1",NOW())," photo_id = '$id'");
					setcookie('photo_'.$id,'viewed',time()+3600);
				}
			}
		}
		
	}

	function increment_views_new($id,$type=NULL)
	{

		global $db;
		switch($type)
		{
			case 'v':
			case 'video':
			default:
			{	
				if(!isset($_COOKIE['video_'.$id])){
					
					$currentTime = time();
					//$db->update(tbl("video_views"),array("video_views","video_id", "last_updated"),array("|f|views+1",$currentTime)," videoid='$id' OR videokey='$id'");
					$db->update(tbl("video"),array("views", "last_viewed"),array("|f|views+1",$currentTime)," videoid='$id' OR videokey='$id'");
					//exit('check_1'.$type.'id_'.$id);
					setcookie('video_'.$id,'watched',time()+3600);
				}
			}
			break;
			case 'u':
			case 'user':
			case 'channel':

			{
				
				if(!isset($_COOKIE['user_'.$id])){
					$db->update(tbl("users"),array("profile_hits"),array("|f|profile_hits+1")," userid='$id'");
					setcookie('user_'.$id,'watched',time()+3600);
				}
			}
			break;
			case 't':
			case 'topic':

			{
				if(!isset($_COOKIE['topic_'.$id])){
					$db->update(tbl("group_topics"),array("total_views"),array("|f|total_views+1")," topic_id='$id'");
					setcookie('topic_'.$id,'watched',time()+3600);
				}
			}
			break;
			break;
			case 'g':
			case 'group':

			{
				if(!isset($_COOKIE['group_'.$id])){
					$db->update(tbl("groups"),array("total_views"),array("|f|total_views+1")," group_id='$id'");
					setcookie('group_'.$id,'watched',time()+3600);
				}
			}
			break;
			case "c":
			case "collect":
			case "collection":
			{
				if(!isset($_COOKIE['collection_'.$id])){
					$db->update(tbl("collections"),array("views"),array("|f|views+1")," collection_id = '$id'");
					setcookie('collection_'.$id,'viewed',time()+3600);
				}
			}
			break;
			
			case "photos":
			case "photo":
			case "p":
			{
				if(!isset($_COOKIE['photo_'.$id]))
				{
					$db->update(tbl('photos'),array("views","last_viewed"),array("|f|views+1",NOW())," photo_id = '$id'");
					setcookie('photo_'.$id,'viewed',time()+3600);
				}
			}
		}
		
	}
	
	
	/**
	 * Function used to get post var
	 */
	function post($var)
	{
		return $_POST[$var];
	}
	
	
	/**
	 * Function used to show sharing form
	 */
	function show_share_form($array)
	{
		
		assign('params',$array);
        if(SMARTY_VERSION>2)
            Template('blocks/common/share.html');
        else
            Template('blocks/share_form.html');

	}
	
	/**
	 * Function used to show flag form
	 */
	function show_flag_form($array)
	{
		assign('params',$array);
        if(SMARTY_VERSION>2)
            Template('blocks/common/report.html');
        else
            Template('blocks/flag_form.html');
	}
	
	/**
	 * Function used to show flag form
	 */
	function show_playlist_form($array)
	{
		global $cbvid;
		assign('params',$array);
		
		$playlists = $cbvid->action->get_playlists();
		assign('playlists',$playlists);

      
            Template('blocks/common/playlist.html');
       
	}
	
	/**
	 * Function used to show collection form
	 */
	function show_collection_form($params)
	{
		global $db,$cbcollection;
		if(!userid())
			$loggedIn = "not";
		else	
		{		
			$collectArray = array("order"=>" collection_name ASC","type"=>"videos","user"=>userid());		
			$collections = $cbcollection->get_collections($collectArray);
                        
                        $contributions = $cbcollection->get_contributor_collections(userid());
                        
                        if($contributions)
                        {
                            if(!$collections)
                                $collections = $contributions;
                            else
                            {
                                $collections = array_merge($collections,$contributions);
                            }
                        }
			
			assign("collections",$collections);
			assign("contributions",$contributions);
		}
		Template("/blocks/collection_form.html");	
	}
	
	
	function cbdate($format=NULL,$timestamp=NULL)
	{
		if(!$format)
		{
			$format = config("datE_format");
		}
		if(!$timestamp)
			return date($format);
		else
			return date($format,$timestamp);
	}
	
	
	/**
	 * Function used to count pages
	 * @param TOTAL RESULTS NUM
	 * @param NUMBER OF RESULTS to DISPLAY NUM
	 */
	function count_pages($total,$count)
	{
		if($count<1) $count = 1;
		$records = $total/$count;
		return $total_pages = round($records+0.49,0);
	}
	
	/**
	 * Function used to return level name 
	 * @param levelid
	 */
	function get_user_level($id)
	{
		global $userquery;
		return $userquery->usr_levels[$id];
	}
	
	
	
	/**
	 * This function used to check
	 * weather user is online or not
	 * @param : last_active time
	 * @param : time margin
	 */
	function is_online($time,$margin='5')
	{
		$margin = $margin*60;
		$active = strtotime($time);
		$curr = time();
		$diff = $curr - $active;
		if($diff > $margin)
			return 'offline';
		else
			return 'online';
	}
	
	
	/**
	 * ClipBucket Form Validator
	 * this function controls the whole logic of how to operate input
	 * validate it, generate proper error
	 */
	function validate_cb_form($input,$array)
	{
		
		//Check the Collpase Category Checkboxes 
		
		if($input['cat']['title']=='Video Category'){
			global $db;
			$query = "SELECT * FROM ".tbl("config")." WHERE configid=234";
			$row=db_select($query);
			$row[0]['value'].$input['cat']['title'];

			if($row[0]['value']=='0')
			{
				unset($input['cat']);	
			}
		}
		
		if(is_array($input))
		foreach($input as $field)
		{
			$field['name'] = formObj::rmBrackets($field['name']);
			
			//pr($field);
			$title = $field['title'];
			$val = $array[$field['name']];
			$req = $field['required'];
			$invalid_err =  $field['invalid_err'];
			$function_error_msg = $field['function_error_msg'];
			if(is_string($val))
			{
				if(!isUTF8($val))
					$val = utf8_decode($val);
				$length = strlen($val);
			}
			$min_len = $field['min_length'];
			$min_len = $min_len ? $min_len : 0;
			$max_len = $field['max_length'] ;
			$rel_val = $array[$field['relative_to']];
			
			if(empty($invalid_err))
				$invalid_err =  sprintf("Invalid '%s'",$title);
			if(is_array($array[$field['name']]))
				$invalid_err = '';
				
			//Checking if its required or not
			if($req == 'yes')
			{
				if(empty($val) && !is_array($array[$field['name']]))
				{
					e($invalid_err);
					$block = true;
				}else{
					$block = false;
				}
			}
			$funct_err = is_valid_value($field['validate_function'],$val);
			if($block!=true)
			{
				
				//Checking Syntax
				if(!$funct_err)
				{
					if(!empty($function_error_msg))
						e($function_error_msg);
					elseif(!empty($invalid_err))
						e($invalid_err);
				}
				
				if(!is_valid_syntax($field['syntax_type'],$val))
				{
					if(!empty($invalid_err))
						e($invalid_err);
				}
				if(isset($max_len))
				{
					if($length > $max_len || $length < $min_len)
					e(sprintf(lang('please_enter_val_bw_min_max'),
							  $title,$min_len,$field['max_length']));
				}
				if(function_exists($field['db_value_check_func']))
				{

					$db_val_result = $field['db_value_check_func']($val);
					if($db_val_result != $field['db_value_exists'])
						if(!empty($field['db_value_err']))
							e($field['db_value_err']);
						elseif(!empty($invalid_err))
							e($invalid_err);
				}
				if($field['relative_type']!='')
				{
					switch($field['relative_type'])
					{
						case 'exact':
						{
							if($rel_val != $val)
							{
								if(!empty($field['relative_err']))
									e($field['relative_err']);
								elseif(!empty($invalid_err))
									e($invalid_err);
							}
						}
						break;
					}
				}
			}	
		}
	}


	/**
	 * Function used to check weather tempalte file exists or not
	 * input path to file
	 */
	function template_file_exists($file,$dir)
	{
		if(!file_exists($dir.'/'.$file) && !empty($file) && !file_exists($file))
		{
			echo sprintf(lang("temp_file_load_err"),$file,$dir);
			return false;
		}else
			return true;
	}
	
	/** 
	 * Function used to count age from date
	 */
	function get_age($input)
	{ 
		$time = strtotime($input);
		$iMonth = date("m",$time);
		$iDay = date("d",$time);
		$iYear = date("Y",$time);
		
		$iTimeStamp = (mktime() - 86400) - mktime(0, 0, 0, $iMonth, $iDay, $iYear); 
		$iDays = $iTimeStamp / 86400;  
		$iYears = floor($iDays / 365 );  
		return $iYears; 
	}
	
	
	
	
	/**
	 * Function used to check time span
	 * A time difference function that outputs the 
	 * time passed in facebook's style: 1 day ago, 
	 * or 4 months ago. I took andrew dot
	 * macrobert at gmail dot com function 
	 * and tweaked it a bit. On a strict enviroment 
	 * it was throwing errors, plus I needed it to 
	 * calculate the difference in time between 
	 * a past date and a future date. 
	 * thanks to yasmary at gmail dot com
	 */
	function nicetime($date,$istime=false)
	{
		if(empty($date)) {
			return lang('no_date_provided');
		}
	   
		$periods         = array(lang("second"), lang("minute"), lang("hour"), lang("day"), lang("week"), lang("month"), lang("year"), lang("decade"));
		$lengths         = array(lang("60"),lang("60"),lang("24"),lang("7"),lang("4.35"),lang("12"),lang("10"));
	   
		$now             = time();
		
		if(!$istime)
		$unix_date         = strtotime($date);
	    else
	   $unix_date         = $date;
	   
		   // check validity of date
		if(empty($unix_date)  || $unix_date<1) {   
			return lang("bad_date");
		}
	
		// is it future date or past date
		if($now > $unix_date) {   
			//time_ago
			$difference     = $now - $unix_date;
			$tense         = "time_ago";
		   
		} else {
			//from_now
			$difference     = $unix_date - $now;
			$tense         = "from_now";
		}
	   
		for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
			$difference /= $lengths[$j];
		}
	   
		$difference = round($difference);
	   
		if($difference != 1) {
			$periods[$j].= "s";
		}
	   	
		
		return sprintf(lang($tense),$difference,$periods[$j]);
	}
	
	
	/**
	 * Function used to format outgoing link
	 */
	function outgoing_link($out)
	{
		preg_match("/http/",$out,$matches);
		if(empty($matches[0]))
			$out = "http://".$out;
		return '<a href="'.$out.'" target="_blank">'.$out.'</a>';
	}
	
	/**
	 * Function used to get country via country code
	 */
	function get_country($code)
	{
		global $db;
		$result = $db->select(tbl("countries"),"name_en,iso2"," iso2='$code' OR iso3='$code'");
		if($db->num_rows>0)
		{
			$flag = '';
			$result = $result[0];
			if(SHOW_COUNTRY_FLAG)
				$flag = '<img src="'.BASEURL.'/images/icons/country/'.strtolower($result['iso2']).'.png" alt="" border="0">&nbsp;';
			return $flag.$result['name_en'];
		}else
			return false;
	}


	
	/**
	 * function used to get photos
	 */
	function get_collections($param)
	{
		global $cbcollection;
		return $cbcollection->get_collections($param);
	}
	
	
	/**
	 * function used to get vidos
	 */
	function get_users($param)
	{
		global $userquery;
		return $userquery->get_users($param);
	}
	
	
	/**
	 * function used to get groups
	 */
	function get_groups($param)
	{
		global $cbgroup;
		return $cbgroup->get_groups($param);
	}
	
	/**
	 * Function used to call functions
	 */
	function call_functions($in,$params=NULL)
	{
		if(is_array($in))
		{
			foreach($in as $i)
			{
				if(function_exists($i))
					if(!$params)
						$i();
					else
						$i($params);
			}
		}else
		{
			if(function_exists($in))
					if(!$params)
						$in();
					else
						$in($params);
		}
		
	}
	

	
	
	/**
	 * In each plugin
	 * we will define a CONST
	 * such as plguin_installed
	 * that will be used weather plugin is installed or not
	 * ie define("editorspick_install","installed");
	 * is_installed('editorspic');
	 */
	function is_installed($plugin)
	{

		if(defined($plugin."_install"))
			return true;
		else
			return false;
	}
	
	
	/**
	 * Category Link is used to return
	 * Category based link
	 */
	function category_link($data,$type)
	{
		switch($type)
		{
			case 'video':case 'videos':case 'v':
			{
				
					
				if(SEO=='yes')
					return BASEURL.'/videos/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/';
				else
					return BASEURL.'/videos.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			case 'channels':case 'channel':case'c':case'user':
			{
					
				if(SEO=='yes')
					return BASEURL.'/channels/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/';
				else
					return BASEURL.'/channels.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			default:
			{
				
				if(THIS_PAGE=='photos')
					$type = 'photos';

				if(defined("IN_MODULE"))
				{
					$url = 'cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
					global $prefix_catlink;
					$url = $prefix_catlink.$url;
					
					$rm_array = array("cat","sort","time","page","seo_cat_name");
					$p = "";
					if($prefix_catlink)
						$rm_array[] = 'p';
					$plugURL = queryString($url,$rm_array);
					return $plugURL;
				}
								
				if(SEO=='yes')
					return BASEURL.'/'.$type.'/'.$data['category_id'].'/'.SEO($data['category_name']).'/'.$_GET['sort'].'/'.$_GET['time'].'/';
				else
					return BASEURL.'/'.$type.'.php?cat='.$data['category_id'].'&sort='.$_GET['sort'].'&time='.$_GET['time'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
		}
	}
	
	/**
	 * Sorting Links is used to return
	 * Sorting based link
	 */
	function sort_link($sort,$mode='sort',$type)
	{
		switch($type)
		{
			case 'video':
			case 'videos':
			case 'v':
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
					
				if(SEO=='yes')
					return BASEURL.'/videos/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/videos.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			case 'channels':
			case 'channel':
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
					
				if(SEO=='yes')
					return BASEURL.'/channels/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/channels.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;
			
			
			default:
			{
				if(!isset($_GET['cat']))
					$_GET['cat'] = 'all';
				if(!isset($_GET['time']))
					$_GET['time'] = 'all_time';
				if(!isset($_GET['sort']))
					$_GET['sort'] = 'most_recent';
				if(!isset($_GET['page']))
					$_GET['page'] = 1;
				if(!isset($_GET['seo_cat_name']))
					$_GET['seo_cat_name'] = 'All';
				
				if($mode == 'sort')
					$sorting = $sort;
				else
					$sorting = $_GET['sort'];
				if($mode == 'time')
					$time = $sort;
				else
					$time = $_GET['time'];
				
				if(THIS_PAGE=='photos')
					$type = 'photos';
				
				if(defined("IN_MODULE"))
				{
					$url = 'cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
					$plugURL = queryString($url,array("cat","sort","time","page","seo_cat_name"));
					return $plugURL;
				}
				
				if(SEO=='yes')
					return BASEURL.'/'.$type.'/'.$_GET['cat'].'/'.$_GET['seo_cat_name'].'/'.$sorting.'/'.$time.'/'.$_GET['page'];
				else
					return BASEURL.'/'.$type.'.php?cat='.$_GET['cat'].'&sort='.$sorting.'&time='.$time.'&page='.$_GET['page'].'&seo_cat_name='.$_GET['seo_cat_name'];
			}
			break;		
		}
	}
	
	
	
	
	/**
	 * Function used to get flag options
	 */
	function get_flag_options()
	{
		$action = new cbactions();
		$action->init();
		return $action->report_opts;
	}
	
	/**
	 * Function used to display flag type
	 */
	function flag_type($id)
	{
		$flag_opts = get_flag_options();
		return $flag_opts[$id];
	}
	
	


	function get_captcha_2014()
	{
		global $Cbucket;
		if(count($Cbucket->captchas)>0)
		{   


			return $Cbucket->captchas[0];
			
		}else
			return false;
	}
	/**
	 * Function used to load captcha field
	 */
	function get_captcha()
	{
		global $Cbucket;
		if(count($Cbucket->captchas)>0)
		{   


			return $Cbucket->captchas[0];
			
		}else
			return false;
	}
	
	/**
	 * Function used to load captcha
	 */
	define("GLOBAL_CB_CAPTCHA","cb_captcha");
	function load_captcha($params)
	{
		global $total_captchas_loaded;
		switch($params['load'])
		{
			case 'function':
			{
				if($total_captchas_loaded!=0)
					$total_captchas_loaded = $total_captchas_loaded+1;
				else
					$total_captchas_loaded = 1;
				$_SESSION['total_captchas_loaded'] = $total_captchas_loaded;
				if(function_exists($params['captcha']['load_function']))
					return $params['captcha']['load_function']().'<input name="cb_captcha_enabled" type="hidden" id="cb_captcha_enabled" value="yes" />';
			}
			break;
			case 'field':
			{
				echo '<input type="text" '.$params['field_params'].' name="'.GLOBAL_CB_CAPTCHA.'" />';
			}
			break;
			
		}
	}
	
	/**
	 * Function used to verify captcha
	 */
	function verify_captcha()
	{
		$var = post('cb_captcha_enabled');
		if($var=='yes')
		{
			$captcha = get_captcha();
			$val = $captcha['validate_function'](post(GLOBAL_CB_CAPTCHA));
			return $val;
		}else
			return true;
	}
	
	
	/**
	 * Function used to ingore errors
	 * that are created when there is wrong action done
	 * on clipbucket ie inavalid username etc
	 */
	function ignore_errors()
	{
		global $ignore_cb_errors;
		$ignore_cb_errors = TRUE;
	}
	
	/**
	 * Function used to set $ignore_cb_errors 
	 * back to TRUE so our error catching system
	 * can generate errors
	 */
	function catch_error()
	{
		global $ignore_cb_errors;
		$ignore_cb_errors = FALSE;
	}
	
	
	/**
	 * Function used to call sub_menu_easily
	 */
	function sub_menu()
	{
		/**
		 * Submenu function used to used to display submenu links
		 * after navbar
		 */
		$funcs = get_functions('sub_menu');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					return $func($u);
				}
			}
		}
	}
	
	
	/**
	 * Function used to load clipbucket title
	 */
	function cbtitle($params=false)
	{
		global $cbsubtitle;
		
		$sub_sep = getArrayValue($params, 'sub_sep');
		if(!$sub_sep)
			$sub_sep = '-';
			
		//Getting Subtitle
		if(!$cbsubtitle)
			echo TITLE." - ".SLOGAN;
		else
			echo $cbsubtitle." $sub_sep ";
			echo TITLE;
		//echo " ".SUBTITLE;
	}
	
	
	/**
	 * @Script : ClipBucket
	 * @Author : Arslan Hassan
	 * @License : CBLA
	 * @Since : 2007
	 *
	 * function whos_your_daddy
	 * Simply tells the name of  script owner
	 * @return INTELLECTUAL BADASS
	 */
	function whos_your_daddy()
	{
		echo  "<h1>Arslan Hassan</h1>";
	}
	
	/**
	 * function used to set website subtitle
	 */
	function subtitle($title)
	{
		global $cbsubtitle;
		$cbsubtitle = $title;
	}
	
	
	/**
	 * FUnction used to get username from userid
	 */
	function get_username($uid)
	{
		global $userquery;
		return $userquery->get_username($uid,'username');
	}
	
	/**
	 * Function used to get collection name from id
	 * Smarty Function
	 */
	function get_collection_field($cid,$field='collection_name')
	{
		global $cbcollection;
		return $cbcollection->get_collection_field($cid,$field);
	}
	
	/**
	 * Function used to delete photos if
	 * whole collection is being deleted
	 */
	function delete_collection_photos($details)
	{
		global $cbcollection,$cbphoto;
		$type = $details['type'];

		if($type == 'photos')
		{
			$ps = $cbphoto->get_photos(array("collection"=>$details['collection_id']));
			if(!empty($ps))
			{	
				foreach($ps as $p)
				{
					$cbphoto->make_photo_orphan($details,$p['photo_id']);	
				}
				unset($ps); // Empty $ps. Avoiding the duplication prob
			}
		}
	}
	
	/**
	 * FUnction used to get head menu
	 */
	function head_menu($params=NULL)
	{
		global $Cbucket;
		return $Cbucket->head_menu($params);
	}
	
	function cbMenu($params=NULL)
	{
		global $Cbucket;
		return $Cbucket->cbMenu($params);
	}
	
	/**
	 * FUnction used to get foot menu
	 */
	function foot_menu($params=NULL)
	{
		global $Cbucket;
		return $Cbucket->foot_menu($params);
	}
	
	
	
	
	/**
	 * FUNCTION Used to convert XML to Array
	 * @Author : http://www.php.net/manual/en/function.xml-parse.php#87920
	 */
	function xml2array($url, $get_attributes = 1, $priority = 'tag',$is_url=true)
	{
		$contents = "";
		if (!function_exists('xml_parser_create'))
		{
			return false;
		}
		$parser = xml_parser_create('');
		
		if($is_url)
		{
			if (!($fp = @ fopen($url, 'rb')))
			{
				$ch = curl_init();
				curl_setopt($ch,CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_USERAGENT, 
				'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/3.0.0.2');
				curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
				curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
				$contents = curl_exec($ch);
				curl_close($ch);
				
				if(!$contents)
					return false;
			}
			while (!feof($fp))
			{
				$contents .= fread($fp, 8192);
			}
			fclose($fp);
		}else{
			$contents = $url;
		}

		xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parse_into_struct($parser, trim($contents), $xml_values);
		xml_parser_free($parser);
		if (!$xml_values)
			return; //Hmm...
		$xml_array = array ();
		$parents = array ();
		$opened_tags = array ();
		$arr = array ();
		$current = & $xml_array;
		$repeated_tag_index = array ();
		foreach ($xml_values as $data)
		{
			
			unset ($attributes, $value);
			extract($data);
			$result = array ();
			$attributes_data = array ();
			if (isset ($value))
			{
				if ($priority == 'tag')
					$result = $value;
				else
					$result['value'] = $value;
			}
			if (isset ($attributes) and $get_attributes)
			{
				foreach ($attributes as $attr => $val)
				{
					if ($priority == 'tag')
						$attributes_data[$attr] = $val;
					else
						$result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
				}
			}
			if ($type == "open")
			{
				$parent[$level -1] = & $current;
				if (!is_array($current) or (!in_array($tag, array_keys($current))))
				{
					$current[$tag] = $result;
					if ($attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					$current = & $current[$tag];
				}
				else
				{
					if (isset ($current[$tag][0]))
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else
					{
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 2;
						if (isset ($current[$tag . '_attr']))
						{
							$current[$tag]['0_attr'] = $current[$tag . '_attr'];
							unset ($current[$tag . '_attr']);
						}
					}
					$last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
					$current = & $current[$tag][$last_item_index];
				}
			}
			elseif ($type == "complete")
			{
				if (!isset ($current[$tag]))
				{
					$current[$tag] = $result;
					$repeated_tag_index[$tag . '_' . $level] = 1;
					if ($priority == 'tag' and $attributes_data)
						$current[$tag . '_attr'] = $attributes_data;
				}
				else
				{
					if (isset ($current[$tag][0]) and is_array($current[$tag]))
					{
						$current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
						if ($priority == 'tag' and $get_attributes and $attributes_data)
						{
							$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
						}
						$repeated_tag_index[$tag . '_' . $level]++;
					}
					else
					{
						$current[$tag] = array (
							$current[$tag],
							$result
						);
						$repeated_tag_index[$tag . '_' . $level] = 1;
						if ($priority == 'tag' and $get_attributes)
						{
							if (isset ($current[$tag . '_attr']))
							{
								$current[$tag]['0_attr'] = $current[$tag . '_attr'];
								unset ($current[$tag . '_attr']);
							}
							if ($attributes_data)
							{
								$current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
							}
						}
						$repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
					}
				}
			}
			elseif ($type == 'close')
			{
				$current = & $parent[$level -1];
			}
		}
		
		return ($xml_array);
	}
	
	
	function array2xml($array, $level=1)
	{
		$xml = '';
		// if ($level==1) {
		//     $xml .= "<array>\n";
		// }
		foreach ($array as $key=>$value) {
		$key = strtolower($key);
		if (is_object($value)) {$value=get_object_vars($value);}// convert object to array
		
		if (is_array($value)) {
			$multi_tags = false;
			foreach($value as $key2=>$value2) {
			 if (is_object($value2)) {$value2=get_object_vars($value2);} // convert object to array
				if (is_array($value2)) {
					$xml .= str_repeat("\t",$level)."<$key>\n";
					$xml .= array2xml($value2, $level+1);
					$xml .= str_repeat("\t",$level)."</$key>\n";
					$multi_tags = true;
				} else {
					if (trim($value2)!='') {
						if (htmlspecialchars($value2)!=$value2) {
							$xml .= str_repeat("\t",$level).
									"<$key2><![CDATA[$value2]]>". // changed $key to $key2... didn't work otherwise.
									"</$key2>\n";
						} else {
							$xml .= str_repeat("\t",$level).
									"<$key2>$value2</$key2>\n"; // changed $key to $key2
						}
					}
					$multi_tags = true;
				}
			}
			if (!$multi_tags and count($value)>0) {
				$xml .= str_repeat("\t",$level)."<$key>\n";
				$xml .= array2xml($value, $level+1);
				$xml .= str_repeat("\t",$level)."</$key>\n";
			}
		
		 } else {
			if (trim($value)!='') {
			 echo "value=$value<br>";
				if (htmlspecialchars($value)!=$value) {
					$xml .= str_repeat("\t",$level)."<$key>".
							"<![CDATA[$value]]></$key>\n";
				} else {
					$xml .= str_repeat("\t",$level).
							"<$key>$value</$key>\n";
				}
			}
		}
		}
		//if ($level==1) {
		//    $xml .= "</array>\n";
		// }
		
		return $xml;
	}


	
	/**
	 * FUnction used to display widget
	 */
	function widget($params)
	{
		$name = $params['name'];
		$content = $params['content'];
		
		return
		'<div class="widget-box">
			<div class="widget-head">
				'.$name.'
			</div>
			<div class="widget-cont">
			  '.$content.'
			</div>
		</div>';
	}
	
	
	/**
	 * Function used to get latest ClipBucket version info
	 */
	function get_latest_cb_info()
	{
		if($_SERVER['HTTP_HOST']!='localhost')
			$url = 'http://clip-bucket.com/versions.xml';
		else
			$url = 'http://localhost/clipbucket/2.x/2/upload/tester/versions.xml';
		$version = xml2array($url);
		if(!$version)
		{
			return false;
		}else
		{
			return $version['phpbucket']['clipbucket'][0];
		}
	}

	
	/**
	 * This function used to include headers in <head> tag
	 * it will check weather to include the file or not
	 * it will take file and its type as an array
	 * then compare its type with THIS_PAGE constant
	 * if header has TYPE of THIS_PAGE then it will be inlucded
	 */
	function include_header($params)
	{
		$file = getArrayValue($params, 'file');
		$type = getArrayValue($params, 'type');
		
		if($file=='global_header')
		{
			Template(BASEDIR.'/styles/global/head.html',false);
			return false;
		}
		
		if($file == 'admin_bar')
		{
			if(has_access('admin_access',TRUE))
				Template(BASEDIR.'/styles/global/admin_bar.html',false);
				return false;
		}
		
		if(!$type)
			$type = "global";
		
		if(is_includeable($type))
			Template($file,false);
		
		return false;
	}
	
	

	
	/**
	 * Function used to check weather to include
	 * given file or not
	 * it will take array of pages
	 * if array has ACTIVE PAGE or has GLOBAL value
	 * it will return true
	 * otherwise FALSE
	 */
	function is_includeable($array)
	{
		if(!is_array($array))
			$array = array($array);
		if(in_array(THIS_PAGE,$array) || in_array('global',$array))
		{
			return true;
		}else
			return false;
	}
	
	/**
	 * This function works the same way as include_header
	 * but the only difference is , it is used to include
	 * JS files only
	 */
	$the_js_files = array();
	function include_js($params)
	{
		global $the_js_files;
		
		$file = $params['file'];
		$type = $params['type'];
		
		if(!in_array($file,$the_js_files))
		{
			$the_js_files[] = $file;
			if($type=='global')
				return '<script src="'.JS_URL.'/'.$file.'" type="text/javascript"></script>';
			elseif(is_array($type))
			{
				foreach($type as $t)
				{
					if($t==THIS_PAGE)
						return '<script src="'.JS_URL.'/'.$file.'" type="text/javascript"></script>';
				}
			}elseif($type==THIS_PAGE)
				return '<script src="'.JS_URL.'/'.$file.'" type="text/javascript"></script>';
		}
		
		return false;
	}
		
	
	/**
	 * Function used to check weather FFMPEG has Required Modules installed or not
	 */
	function get_ffmpeg_codecs($data=false)
	{
		if (PHP_OS == "Linux")
		{
			$a = 'libfaac';
		}
		elseif (PHP_OS == "WINNT")
		{
			$a = 'libvo_aacenc';
		}
		$req_codecs = array
		('libxvid' => 'Required for DIVX AVI files',
		 'libmp3lame'=> 'Required for proper Mp3 Encoding',
		 $a	=> 'Required for AAC Audio Conversion',
		// 'libfaad'	=> 'Required for AAC Audio Conversion',
		 'libx264'	=> 'Required for x264 video compression and conversion',
		 'libtheora' => 'Theora is an open video codec being developed by the Xiph.org',
		 'libvorbis' => 'Ogg Vorbis is a new audio compression format',
		 );
		 
		if($data)
			$version = $data;
		else
			$version = shell_output(  get_binaries('ffmpeg').' -i xxx -acodec copy -vcodec copy -f null /dev/null 2>&1' );
		preg_match_all("/enable\-(.*) /Ui",$version,$matches);
		$installed = $matches[1];
		
		$the_codecs = array();
		
		foreach($installed as $inst)
		{
			if(empty($req_codecs[$inst]))
				$the_codecs[$inst]['installed'] = 'yes';
		}
		
		foreach($req_codecs as $key=>$codec)
		{
			$the_req_codecs[$key] = array();
			$the_req_codecs[$key]['required'] = 'yes';
			$the_req_codecs[$key]['desc'] = $req_codecs[$key];
			if(in_array($key,$installed))
				$the_req_codecs[$key]['installed'] = 'yes';
			else
				$the_req_codecs[$key]['installed'] = 'no';
		}
		
		$the_codecs =  array_merge($the_req_codecs,$the_codecs);
		return $the_codecs;
	}
	
	
	/**
	 * Function used to cheack weather MODULE is INSTALLED or NOT
	 */
	function check_module_path($params)
	{
		$rPath = $path = $params['path'];
		if($path['get_path'])
			$path = get_binaries($path);
		$array = array();
		$result = shell_output($path." -version");
		if($result) {
			if(strstr($result,'error') || strstr(($result),'No such file or directory')){
				$error['error'] = $result;
				if($params['assign'])
					assign($params['assign'],$error);
				
				return false;
			}
					
			if($params['assign']) {
				$array['status'] = 'ok';
				$array['version'] = parse_version($params['path'],$result);
				assign($params['assign'],$array);
				return $array;
			}else {
				return $result;
			}
		}else {
			if($params['assign'])
				assign($params['assign']['error'],"error");
			else
				return false;
		}
			
	}
	function check_ffmpeg($path)
	{	
		$path = get_binaries($path);
		$matches = array();
		$result = shell_output($path." -version");
		//echo $result;
		if($result) {
			if (preg_match("/git/i", $result))
			{
				preg_match('@^(?:ffmpeg version)?([^C]+)@i',$result, $matches);
				$host = $matches[1];

				// get last two segments of host name
				preg_match('/[^.]+\.[^.]+$/', $host, $matches);
				//echo "{$matches[0]}\n";
				$version = "{$matches[0]}";
				return $version;
			}
			elseif (preg_match("/ffmpeg version/i", $result))
			{
				preg_match('@^(?:ffmpeg version)?([^,]+)@i',$result, $matches);
				$host = $matches[1];

				// get last two segments of host name
				preg_match('/[^.]+\.[^.]+$/', $host, $matches);
				//echo "{$matches[0]}\n";
				$version = "{$matches[0]}";
				return $version;
			}
			else
				preg_match("/(?:ffmpeg\\s)(?:version\\s)?(\\d\\.\\d\\.(?:\\d|[\\w]+))/i", strtolower($result), $matches);
				if(count($matches) > 0)
				{
					$version = array_pop($matches);
					return $version;
				}
			return false;
		}
		else
		{
			return false;
		}
			
	}
	
	

	function check_php_cli($path)
	{	
		$path = get_binaries($path);
		$matches = array();
		$result = shell_output($path." --version");
		if($result) {
			preg_match("/(?:php\\s)(?:version\\s)?(\\d\\.\\d\\.(?:\\d|[\\w]+))/i", strtolower($result), $matches);
			if(count($matches) > 0){
				$version = array_pop($matches);
				return $version;
			}
			return false;
		}else{
			return false;
		}
			
	}

	function check_mp4box($path)
	{	
		$path = get_binaries($path);
		$matches = array();
		$result = shell_output($path." -version");
		if($result) 
		{
			preg_match("/(?:version\\s)(\\d\\.\\d\\.(?:\\d|[\\w]+))/i", strtolower($result), $matches);
			if(count($matches) > 0)
			{
				$version = array_pop($matches);
				return $version;
			}
			return false;
		}
		elseif (preg_match("/GPAC version/i", $result))
			{
				preg_match('@^(?:GPAC version)?([^-]+)@i',$result, $matches);
				$host = $matches[1];

				// get last two segments of host name
				preg_match('/[^.]+\.[^.]+$/', $host, $matches);
				//echo "{$matches[0]}\n";
				$version = "{$matches[0]}";
				return $version;
			}
		else
		{
			return false;
		}
			
	}
	
	/**
	 * Function used to parse version from info
	 */
	function parse_version($path,$result)
	{
		switch($path)
		{
			case 'ffmpeg':
			{

				//Gett FFMPEG SVN version
				dump($result);
				preg_match("/svn-r([0-9]+)/i",strtolower($result),$matches);
				//pr($matches);
				if(is_numeric(floatval($matches[1])) && $matches[1]) {
					return 'Svn '.$matches[1];
				}
				//Get FFMPEG version
				preg_match("/FFmpeg version ([0-9.]+),/i",strtolower($result),$matches);
				if(is_numeric(floatval($matches[1])) && $matches[1]) {
					return  $matches[1];
				}
				
				//Get FFMPEG GIT version
				preg_match("/ffmpeg version n\-([0-9]+)/i",strtolower($result),$matches);
				
				if(is_numeric(floatval($matches[1])) && $matches[1]) {
					return 'Git '.$matches[1];
				}
			}
			break;
			case 'php':
			{
				return phpversion(); 
			}
			break;
			case 'flvtool2':
			{
				preg_match("/flvtool2 ([0-9\.]+)/i",$result,$matches);
				if(is_numeric(floatval($matches[1]))){
					return $matches[1];
				} else {
					return false;	
				}
			}
			break;
			case 'mp4box':
			{
				preg_match("/version (.*) \(/Ui",$result,$matches);
				//pr($matches);
				if(is_numeric(floatval($matches[1]))){
					return $matches[1];
				} else {
					return false;	
				}
			}
		}
	}
	
	
	/**
	 * function used to call clipbucket footers
	 */
	function footer()
	{
		$funcs = get_functions('clipbucket_footer');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func();
				}
			}
		}
	}
	
	
	
	
	
	
	
	
	/**
	 * Function used to generate RSS FEED links
	 */
	function rss_feeds($params)
	{
		/**
		 * setting up the feeds arrays..
		 * if you want to call em in your functions..simply call the global variable $rss_feeds
		 */
		$rss_link = cblink(array("name"=>"rss"));
		$rss_feeds = array();
		$rss_feeds[] = array("title"=>"Recently added videos","link"=>$rss_link."recent");
		$rss_feeds[] = array("title"=>"Most Viewed Videos","link"=>$rss_link."views");
		$rss_feeds[] = array("title"=>"Top Rated Videos","link"=>$rss_link."rating");
		$rss_feeds[] = array("title"=>"Videos Being Watched","link"=>$rss_link."watching");
		
		$funcs = get_functions('rss_feeds');
		if(is_array($funcs))
		{
			foreach($funcs as $func)
			{
				return $func($params);
			}
		}

		if($params['link_tag'])
		{
			foreach($rss_feeds as $rss_feed)
			{
				echo "<link rel=\"alternate\" type=\"application/rss+xml\"
				title=\"".$rss_feed['title']."\" href=\"".$rss_feed['link']."\" />\n";
			}
		}
	}
	
	/**
	 * Function used to insert Log
	 */
	function insert_log($type,$details)
	{
		global $cblog;
		$cblog->insert($type,$details);
	}
	
	/**
	 * Function used to get db size
	 */

	function get_db_size()
	{
		global $db;
		$results = $db->_select("SHOW TABLE STATUS");


		foreach($results as $row)
		{
			$dbsize += $row[ "Data_length" ] + $row[ "Index_length" ];
		}
		return $dbsize;
	}

	
	/**
	 * Function used to check weather user has marked comment as spam or not
	 */
	function marked_spammed($comment)
	{
		$spam_voters = explode("|",$comment['spam_voters']);
		$spam_votes = $comment['spam_votes'];
		$admin_vote = in_array('1',$spam_voters);
		if(userid() && in_array(userid(),$spam_voters)){
			return true;
		}elseif($admin_vote){
			return true;
		}else{
			return false;
		}
	}
	
	
	/**
	 * function used to get all time zones
	 */
	function get_time_zones()
	{
		$timezoneTable = array(
			"-12" => "(GMT -12:00) Eniwetok, Kwajalein",
			"-11" => "(GMT -11:00) Midway Island, Samoa",
			"-10" => "(GMT -10:00) Hawaii",
			"-9" => "(GMT -9:00) Alaska",
			"-8" => "(GMT -8:00) Pacific Time (US &amp; Canada)",
			"-7" => "(GMT -7:00) Mountain Time (US &amp; Canada)",
			"-6" => "(GMT -6:00) Central Time (US &amp; Canada), Mexico City",
			"-5" => "(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima",
			"-4" => "(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz",
			"-3.5" => "(GMT -3:30) Newfoundland",
			"-3" => "(GMT -3:00) Brazil, Buenos Aires, Georgetown",
			"-2" => "(GMT -2:00) Mid-Atlantic",
			"-1" => "(GMT -1:00 hour) Azores, Cape Verde Islands",
			"0" => "(GMT) Western Europe Time, London, Lisbon, Casablanca",
			"1" => "(GMT +1:00 hour) Brussels, Copenhagen, Madrid, Paris",
			"2" => "(GMT +2:00) Kaliningrad, South Africa",
			"3" => "(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg",
			"3.5" => "(GMT +3:30) Tehran",
			"4" => "(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi",
			"4.5" => "(GMT +4:30) Kabul",
			"5" => "(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent",
			"5.5" => "(GMT +5:30) Bombay, Calcutta, Madras, New Delhi",
			"6" => "(GMT +6:00) Almaty, Dhaka, Colombo",
			"7" => "(GMT +7:00) Bangkok, Hanoi, Jakarta",
			"8" => "(GMT +8:00) Beijing, Perth, Singapore, Hong Kong",
			"9" => "(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk",
			"9.5" => "(GMT +9:30) Adelaide, Darwin",
			"10" => "(GMT +10:00) Eastern Australia, Guam, Vladivostok",
			"11" => "(GMT +11:00) Magadan, Solomon Islands, New Caledonia",
			"12" => "(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka"
		);
		return $timezoneTable;
	}
	
	
	/**
	 * Function used to get object type from its code
	 * ie v=>video
	 */
	function get_obj_type($type)
	{
		switch($type)
		{
			case "v":
			{
				return "video";
			}
			break;
		}
	}
	
	
	
	function check_cbvideo()
	{
		
		/**
		  * come, keep it for two more versions only
		  * it will be gone in next few updates by default :p
		  *
		  * but dont ever forget its name
		  * its a damn ClipBucket
		  */
		  
		if((!defined("isCBSecured") 
		|| count(get_functions('clipbucket_footer'))== 0 )
		&& !BACK_END) 
		{
				echo cbSecured(CB_SIGN_C);
		}
	}
	
	/**
	 * Gives coversion process output
	 */
	function conv_status($in)
	{
		switch($in)
		{
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

    function check_install($type)
    {

    	if(DEVELOPMENT_MODE) return true;

		global $while_installing,$Cbucket;
		switch($type)
		{
			case "before":
			{
				if(file_exists('files/temp/install.me') && !file_exists('includes/clipbucket.php'))
				{
					header('Location: '.get_server_url().'/cb_install');
				}
			}
			break;
			
			case "after":
			{
				if(file_exists('files/temp/install.me'))
				{
					$Cbucket->configs['closed'] = 1;
				}
			}
			break;
		}       
    }

    function get_server_url()
    {
        $DirName = dirname($_SERVER['PHP_SELF']);
        if(preg_match('/admin_area/i', $DirName))
        {
            $DirName = str_replace('/admin_area','',$DirName);
        }
		if(preg_match('/cb_install/i', $DirName))
        {
            $DirName = str_replace('/cb_install','',$DirName);
        }
        return get_server_protocol().$_SERVER['HTTP_HOST'].$DirName;
    }

    function get_server_protocol()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
        {
            return 'https://';
        }
        else
        {
            $protocol = preg_replace('/^([a-z]+)\/.*$/', '\\1', strtolower($_SERVER['SERVER_PROTOCOL']));
            $protocol .= '://';
            return $protocol;
        }
    }
	
	
	/**
	 * Returns <kbd>true</kbd> if the string or array of string is encoded in UTF8.
	 *
	 * Example of use. If you want to know if a file is saved in UTF8 format :
	 * <code> $array = file('one file.txt');
	 * $isUTF8 = isUTF8($array);
	 * if (!$isUTF8) --> we need to apply utf8_encode() to be in UTF8
	 * else --> we are in UTF8 :)
	 * </code>
	 * @param mixed A string, or an array from a file() function.
	 * @return boolean
	 */
	function isUTF8($string)
	{
		if (is_array($string))
		{
			$enc = implode('', $string);
			return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
		}
		else
		{
			return (utf8_encode(utf8_decode($string)) == $string);
		}   
	}

    /*
        extract the file extension from any given path or url.
        source: http://www.php.net/manual/en/function.basename.php#89127
    */
    function fetch_file_extension($filepath)
    {
        preg_match('/[^?]*/', $filepath, $matches);
        $string = $matches[0];

        $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);

        # check if there is any extension
        if(count($pattern) == 1)
        {
            // no file extension found
            return;
        }

        if(count($pattern) > 1)
        {
            $filenamepart = $pattern[count($pattern)-1][0];
            preg_match('/[^?]*/', $filenamepart, $matches);
            return $matches[0];
        }
    }

    /*
        extract the file filename from any given path or url.
        source: http://www.php.net/manual/en/function.basename.php#89127
    */
    function fetch_filename($filepath)
    {
        preg_match('/[^?]*/', $filepath, $matches);
        $string = $matches[0];
        #split the string by the literal dot in the filename
        $pattern = preg_split('/\./', $string, -1, PREG_SPLIT_OFFSET_CAPTURE);
        #get the last dot position
        $lastdot = $pattern[count($pattern)-1][1];
        #now extract the filename using the basename function
        $filename = basename(substr($string, 0, $lastdot-1));

        #return the filename part
        return $filename;
    }    
	
	/**
	 * Function used to generate
	 * embed code of embedded video
	 */
	function embeded_code($vdetails)
	{
		$code = '';
		$code .= '<object width="'.EMBED_VDO_WIDTH.'" height="'.EMBED_VDO_HEIGHT.'">';
		$code .= '<param name="allowFullScreen" value="true">';
		$code .= '</param><param name="allowscriptaccess" value="always"></param>';
		//Replacing Height And Width
		$h_w_p = array("{Width}","{Height}");
		$h_w_r = array(EMBED_VDO_WIDTH,EMBED_VDO_HEIGHT);	
		$embed_code = str_replace($h_w_p,$h_w_r,$vdetails['embed_code']);
		$code .= unhtmlentities($embed_code);
		$code .= '</object>';
		return $code;
	}
	
	
	/**
	 * function used to convert input to proper date created formate
	 */
	function datecreated($in)
	{
		
		$date_els = explode('-',$in);
		
		//checking date format
		$df = config("date_format");
		$df_els  = explode('-',$df);
		
		foreach($df_els as $key => $el)
			${strtolower($el).'id'} = $key;
		
		$month = $date_els[$mid];
		$day = $date_els[$did];
		$year = $date_els[$yid];

		if($in)
			return date("Y-m-d",strtotime($year.'-'.$month.'-'.$day));
		else
			return '0000-00-00';
	}
	
	
	/**
	 * After struggling alot with baseurl problem
	 * i finally able to found its nice and working solkution..
	 * its not my original but its a genuine working copy
	 * its still in beta mode 
	 */
	function baseurl()
	{
		$protocol = is_ssl() ? 'https://' : 'http://';
		if(!$sub_dir)
		return $base = $protocol.$_SERVER['HTTP_HOST'].untrailingslashit(stripslashes(dirname(($_SERVER['SCRIPT_NAME']))));
		else
		return $base = $protocol.$_SERVER['HTTP_HOST'].untrailingslashit(stripslashes(dirname(dirname($_SERVER['SCRIPT_NAME']))));

	}function base_url(){ return baseurl();}
	
	/**
	 * SRC (WORD PRESS)
	 * Appends a trailing slash.
	 *
	 * Will remove trailing slash if it exists already before adding a trailing
	 * slash. This prevents double slashing a string or path.
	 *
	 * The primary use of this is for paths and thus should be used for paths. It is
	 * not restricted to paths and offers no specific path support.
	 *
	 * @since 1.2.0
	 * @uses untrailingslashit() Unslashes string if it was slashed already.
	 *
	 * @param string $string What to add the trailing slash to.
	 * @return string String with trailing slash added.
	 */
	function trailingslashit($string) {
		return untrailingslashit($string) . '/';
	}

	/**
	 * SRC (WORD PRESS)
	 * Removes trailing slash if it exists.
	 *
	 * The primary use of this is for paths and thus should be used for paths. It is
	 * not restricted to paths and offers no specific path support.
	 *
	 * @since 2.2.0
	 *
	 * @param string $string What to remove the trailing slash from.
	 * @return string String without the trailing slash.
	 */
	function untrailingslashit($string) {
		return rtrim($string, '/');
	}
	
	
	/**
	 * Determine if SSL is used.
	 *
	 * @since 2.6.0
	 *
	 * @return bool True if SSL, false if not used.
	 */
	function is_ssl() {
		if ( isset($_SERVER['HTTPS']) ) {
			if ( 'on' == strtolower($_SERVER['HTTPS']) )
				return true;
			if ( '1' == $_SERVER['HTTPS'] )
				return true;
		} elseif ( isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ) ) {
			return true;
		}
		return false;
	}
	
	/**
	 * This will update stats like Favorite count, Playlist count
	 */
	function updateObjectStats($type='favorite',$object='video',$id,$op='+')
	{
		global $db;
		
		switch($type)
		{
			case "favorite":  case "favourite":
			case "favorites": case "favourties":
			case "fav":
			{
				switch($object)
				{
					case "video": 
					case "videos": case "v":
					{
						$db->update(tbl('video'),array('favourite_count'),array("|f|favourite_count".$op."1")," videoid = '".$id."'");
					}
					break;
					
					case "photo":
					case "photos": case "p":
					{
						$db->update(tbl('photos'),array('total_favorites'),array("|f|total_favorites".$op."1")," photo_id = '".$id."'");
					}
					break;
					
					/*case "collection":
					case "collections": case "cl":
					{
						$db->update(tbl('photos'),array('total_favorites'),array("|f|total_favorites".$op."1")," photo_id = '".$id."'");
					}
					break;*/
				}
			}
			break;
			
			case "playlist": case "playList":
			case "plist":
			{
				switch($object)
				{
					case "video":
					case "videos": case "v":
					{
						$db->update(tbl('video'),array('playlist_count'),array("|f|playlist_count".$op."1")," videoid = '".$id."'");
					}
				}
			}
		}
	}
	
	
	/**
	 * FUnction used to check weather conversion lock exists or not
	 * if converson log exists it means no further conersion commands will be executed
	 *
	 * @return BOOLEAN
	 */
	function conv_lock_exists()
	{
		if(file_exists(TEMP_DIR.'/conv_lock.loc'))
			return true;
		else
			return false;
	}
	
	/**
	 * Function used to return a well-formed queryString
	 * for passing variables to url
	 * @input variable_name
	 */
	function queryString($var=false,$remove=false)
	{
		$queryString = $_SERVER['QUERY_STRING'];
		
		if($var)
		$queryString = preg_replace("/&?$var=([\w+\s\b\.?\S]+|)/","",$queryString);
		
		if($remove)
		{
			if(!is_array($remove))
			$queryString = preg_replace("/&?$remove=([\w+\s\b\.?\S]+|)/","",$queryString);
			else
			foreach($remove as $rm)
				$queryString = preg_replace("/&?$rm=([\w+\s\b\.?\S]+|)/","",$queryString);
			
		}
		
		if($queryString)
			$preUrl = "?$queryString&";
		else
			$preUrl = "?";
		
		$preUrl = preg_replace(array("/(\&{2,10})/","/\?\&/"),array("&","?"),$preUrl);
		
		return $preUrl.$var;
	}
	
	
	/**
	 * Following two functions are taken from
	 * tutorialzine.com's post 'Creating a Facebook-like Registration Form with jQuery'
	 * These function are written by Martin Angelov.
	 * Read post here: http://tutorialzine.com/2009/08/creating-a-facebook-like-registration-form-with-jquery/
	 */
	function generate_options($params)
	{
		$reverse=false;
		
		if($params['from']>$params['to'])
		{
			$tmp=$params['from'];
			$params['from']=$params['to'];
			$params['to']=$tmp;
			
			$reverse=true;
		}
		
		
		$return_string=array();
		for($i=$params['from'];$i<=$params['to'];$i++)
		{
			//$return_string[$i] = ($callback?$callback($i):$i);
			$return_string[] = '<option value="'.$i.'">'.($params['callback']?$params['callback']($i):$i).'</option>';
		}
		
		if($reverse)
		{
			$return_string=array_reverse($return_string);
		}
		
		
		return join('',$return_string);
	}
	function callback_month($month)
	{
		return date('M',mktime(0,0,0,$month,1));
	}
	
	
	/**
	 * Function use to download file to server
	 * 
	 * @param URL
	 * @param destination
	 */
	function snatch_it($snatching_file,$destination,$dest_name,$rawdecode=true)
	{
		global $curl;
		if($rawdecode==true)
		$snatching_file= rawurldecode($snatching_file);
		if(PHP_OS == "Linux")
		{
			$destination.'/'.$dest_name;
			$fp = fopen ($destination.'/'.$dest_name, 'w+');
		}
		elseif (PHP_OS == "WINNT") {
			$destination.'\\'.$dest_name;
			$fp = fopen ($destination.'\\'.$dest_name, 'w+');
		}
		$ch = curl_init($snatching_file);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_USERAGENT, 
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2');
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	
	
	/**
	 * Function check curl
	 */
	function isCurlInstalled()
	{
		if  (in_array  ('curl', get_loaded_extensions())) {
			return true;
		}
		else{
			return false;
		}
	}
	
	/**
	 * Function for loading 
	 * uploader file url & other configurations
	 */
	function uploaderDetails()
	{
		global $uploaderType;

		$uploaderDetails = array
		(
			'uploadSwfPath' => JS_URL.'/uploadify/uploadify.swf',
			'uploadScriptPath' => BASEURL.'/actions/file_uploader.php',
		);
		
		$photoUploaderDetails = array
		(
			'uploadSwfPath' => JS_URL.'/uploadify/uploadify.swf',
			'uploadScriptPath' => BASEURL.'/actions/photo_uploader.php',
		);


		assign('uploaderDetails',$uploaderDetails);	
		assign('photoUploaderDetails',$photoUploaderDetails);		
		//Calling Custom Functions
		cb_call_functions('uploaderDetails');
	}
	
	
	/**
	 * Function isSectionEnabled
	 * This function used to check weather INPUT section is enabled or not
	 */
	function isSectionEnabled($input,$restrict=false)
	{
		global $Cbucket;
		
		$section = $Cbucket->configs[$input.'Section'];
		
		if(!$restrict)
		{
			if($section =='yes')
				return true;
			else
				return false;
		}else
		{
			if($section =='yes' || THIS_PAGE=='cb_install')
			{
				return true;
			}else
			{
				template_files('blocked.html');
				display_it();
				exit();
			}
		}
		
	}


	function array_depth($array) {
		$ini_depth = 0;
		
		foreach($array as $arr)
		{
			if(is_array($arr))
			{
				$depth = array_depth($arr) + 1;	
				
				if($depth > $ini_depth)
					$ini_depth = $depth;
			}
		}
		
		return $ini_depth;
	}
	
	
	/**
	 * JSON_ENCODE short
	 */
    if( !function_exists( 'je' ) ) {
        function je($in){ return json_encode($in); }
    }
	/**
	 * JSON_DECODE short
	 */
    if ( !function_exists( 'jd' ) ) {
        function jd($in,$returnClass=false){ if(!$returnClass) return  json_decode($in,true); else return  json_decode($in); }
    }
	
	/**
	 * function used to update last commented option 
	 * so comment cache can be refreshed
	 */
	function update_last_commented($type,$id)
	{
		global $db;
		
		if($type && $id)
		{
			switch($type)
			{
				case "v":
				case "video":
				case "vdo":
				case "vid":
				case "videos":
				$db->update(tbl("video"),array('last_commented'),array(now()),"videoid='$id'");
				break;
				
				case "c":
				case "channel":
				case "user":
				case "u":
				case "users":
				case "channels":
				$db->update(tbl("users"),array('last_commented'),array(now()),"userid='$id'");
				break;
				
				case "cl":
				case "collection":
				case "collect":
				case "collections":
				case "collects":
				$db->update(tbl("collections"),array('last_commented'),array(now()),"collection_id='$id'");
				break;
				
				case "p":
				case "photo":
				case "photos":
				case "picture":
				case "pictures":
				$db->update(tbl("photos"),array('last_commented'),array(now()),"photo_id='$id'");
				break;
				
				case "t":
				case "topic":
				case "topics":
				$db->update(tbl("group_topics"),array('last_post_time'),array(now()),"topic_id='$id'");
				break;
				
			}
		}
	}

	/**
	 * Function used display privacy in text
	 * according to provided number
	 * 0 - Public
	 * 1 - Protected
	 * 2 - Private
	 */
	 function getGroupPrivacy($privacyID)
	 {
			{
				switch($privacyID)
				{
					case "0": default:
					{
						return lang("group_is_public");
					}
					break;
					
					case "1":
					{
						return lang("group_is_protected");
					}
					break;
					
					case "2":
					{
						return lang("group_is_private");
					}
					break;
				}
			}
	 }	
	
	/**
	 * Function used to create user feed
	 * this function simple takes ID as input
	 * and do the rest seemlessli ;)
	 */
	function addFeed($array)
	{
		global $cbfeeds,$cbphoto,$userquery;
		
		$action = $array['action'];
		if($array['uid'])
			$userid = $array['uid'];
		else
			$userid = userid();
			
		switch($action)
		{
			case "upload_photo":
			{

				$feed['action'] = 'upload_photo';
				$feed['object'] = 'photo';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			case "upload_video":
			case "add_favorite":
			{

				$feed['action'] = $action;
				$feed['object'] = 'video';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "signup":
			{

				$feed['action'] = 'signup';
				$feed['object'] = 'signup';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] =  $userid;;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "create_group":
			case "join_group":
			{
				$feed['action'] = $action;
				$feed['object'] = 'group';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "add_friend":
			{
				$feed['action'] = 'add_friend';
				$feed['object'] = 'friend';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;
				
				$cbfeeds->addFeed($feed);
			}
			break;
			
			case "add_collection":
			{
				$feed['action'] = 'add_collection';
				$feed['object'] = 'collection';
				$feed['object_id'] = $array['object_id'];		
				$feed['uid'] = $userid;
				
				$cbfeeds->addFeed($feed);
			}
			
		}
	}
	
	/**
	 * function used to get plugin directory name
	 */
	function this_plugin($pluginFile=NULL)
	{
		if(!$pluginFile)
			global $pluginFile;
		return basename(dirname($pluginFile));
	}

	
	/**
	 * function used to get user agent details
	 * Thanks to ruudrp at live dot nl 28-Nov-2010 11:31 PHP.NET
	 */
	function get_browser_details($in=NULL,$assign=false)
	{
		//Checking if browser is firefox
		if(!$in)
			$in = $_SERVER['HTTP_USER_AGENT'];
		
		$u_agent = $in;
		$bname = 'Unknown';
		$platform = 'Unknown';
		$version= "";
	
		//First get the platform?
		if (preg_match('/linux/i', $u_agent)) {
			$platform = 'linux';
		}
		elseif (preg_match('/iPhone/i', $u_agent)) {
			$platform = 'iphone';
		}
		elseif (preg_match('/iPad/i', $u_agent)) {
			$platform = 'ipad';
		}
		elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
			$platform = 'mac';
		}
		elseif (preg_match('/windows|win32/i', $u_agent)) {
			$platform = 'windows';
		}
	   
		// Next get the name of the useragent yes seperately and for good reason
		if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Internet Explorer';
			$ub = "MSIE";
		}
		elseif(preg_match('/Firefox/i',$u_agent))
		{
			$bname = 'Mozilla Firefox';
			$ub = "Firefox";
		}
		elseif(preg_match('/Chrome/i',$u_agent))
		{
			$bname = 'Google Chrome';
			$ub = "Chrome";
		}
		elseif(preg_match('/Safari/i',$u_agent))
		{
			$bname = 'Apple Safari';
			$ub = "Safari";
		}
		elseif(preg_match('/Opera/i',$u_agent))
		{
			$bname = 'Opera';
			$ub = "Opera";
		}
		elseif(preg_match('/Netscape/i',$u_agent))
		{
			$bname = 'Netscape';
			$ub = "Netscape";
		}
		elseif(preg_match('/Googlebot/i',$u_agent))
		{
			$bname = 'Googlebot';
			$ub = "bot";
		}elseif(preg_match('/msnbot/i',$u_agent))
		{
			$bname = 'MSNBot';
			$ub = "bot";
		}elseif(preg_match('/Yahoo\! Slurp/i',$u_agent))
		{
			$bname = 'Yahoo Slurp';
			$ub = "bot";
		}

	   
		// finally get the correct version number
		$known = array('Version', $ub, 'other');
		$pattern = '#(?<browser>' . join('|', $known) .
		')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
		if (!@preg_match_all($pattern, $u_agent, $matches)) {
			// we have no matching number just continue
		}
	   
		// see how many we have
		$i = count($matches['browser']);
		if ($i != 1) {
			//we will have two since we are not using 'other' argument yet
			//see if version is before or after the name
			if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
				$version= $matches['version'][0];
			}
			else {
				$version= $matches['version'][1];
			}
		}
		else {
			$version= $matches['version'][0];
		}
	   
		// check if we have a number
		if ($version==null || $version=="") {$version="?";}
	   
		$array= array(
			'userAgent' => $u_agent,
			'name'      => $bname,
			'version'   => $version,
			'platform'  => $platform,
			'bname'		=> strtolower($ub),
			'pattern'    => $pattern
		);
		
		if($assign)	assign($assign,$array); else return $array;
	}
	
	function update_user_voted($array,$userid=NULL)
	{
		global $userquery;
		return $userquery->update_user_voted($array,$userid);	
	}
	
	/**
	  * function used to delete vidoe from collections
	  */
	function delete_video_from_collection($vdetails)
	{
		global  $cbvid;
		$cbvid->collection->deleteItemFromCollections($vdetails['videoid']);
	}
	
	
	/**
	 * function used to check
	 * remote link is valid or not
	 */
	
	function checkRemoteFile($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		// don't download content
		curl_setopt($ch, CURLOPT_NOBODY, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		if($result!==FALSE)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * function used to get counts from
	 * cb_counter table
	 */
	function get_counter($section,$query)
	{
		if(!config('use_cached_pagin'))
			return false;
			
		global $db;
		
		$timeRefresh = config('cached_pagin_time');
		$timeRefresh = $timeRefresh*60;
		
		$validTime = time()-$timeRefresh;
		
		unset($query['order']);
		$je_query = json_encode($query);
		$query_md5 = md5($je_query);
		$select = $db->select(tbl('counters'),"*","section='$section' AND query_md5='$query_md5' 
		AND '$validTime' < date_added");
		if($db->num_rows>0)
		{
			return $select[0]['counts'];
		}else
		return false;
	}
	
	/**
	 * function used to insert or update counter
	 */
	function update_counter($section,$query,$counter)
	{
		global $db;
		unset($query['order']);
		$je_query = json_encode($query);
		$query_md5 = md5($je_query);
		$count = $db->count(tbl('counters'),"*","section='$section' AND query_md5='$query_md5'");
		if($count)
		{
			$db->update(tbl('counters'),array('counts','date_added'),array($counter,strtotime(now())),
			"section='$section' AND query_md5='$query_md5'");
		}else
		{
			$db->insert(tbl('counters'),array('section','query','query_md5','counts','date_added'),
			array($section,'|no_mc|'.$je_query,$query_md5,$counter,strtotime(now())));
		}
	}
	
	/**
	 * function used to register a module file, that will be later called
	 * by load_modules() function
	 */
	function register_module($mod_name,$file)
	{
		global $Cbucket;
		$Cbucket->modules_list[$mod_name][] = $file;

		
		
	}
	
	/**
	 * function used to load module files
	 */
	function load_modules()
	{


		global $Cbucket,$lang_obj,$signup,$Upload,$cbgroup,
		$adsObj,$formObj,$cbplugin,$eh,$sess,$cblog,$imgObj,
		$cbvideo,$cbplayer,$cbemail,$cbpm,$cbpage,$cbindex,
		$cbcollection,$cbphoto,$cbfeeds,$userquery,$db,$pages,$cbvid;


		
		
		foreach($Cbucket->modules_list as $cbmod)
		{

			foreach($cbmod as $modfile)
			{
				
				if(file_exists($modfile))
					include($modfile);
			}
		}
	}
        
        
        /**
         * function used to verify user age
         */
        function verify_age($dob)
        {
            $allowed_age = config('min_age_reg');
            if($allowed_age < 1) return true;
            
            $age_time = strtotime($dob);
            $diff = time() - $age_time;
            
            $diff = $diff / 60 / 60 / 24 / 364;
            
            if($diff >= $allowed_age ) return true;
            
            return false;
        }


        /**
         * Checks development mode
         *
         * @return Boolean
         */
        function in_dev()
        {
            if(defined('DEVELOPMENT_MODE'))
                return DEVELOPMENT_MODE;
            else
                return false;
        }

        function dump($data){
        	echo "<pre>";
        	var_dump($data);
        	echo "</pre>";
        }

        function logData($data){
				$logFilePath = BASEDIR."/Log.txt";
				if(is_array($data)) $data = json_encode($data);
				$text = file_get_contents($logFilePath);
				$text .= " \n {$data}";
				file_put_contents($logFilePath, $text);
			}



        /**
         * displays a runtime error
         *
         * @param Object $e
         */
        function show_cb_error($e)
        {
            echo $e->getMessage();
            echo '<br>';
            echo 'On Line Number ';
            echo $e->getLine();
            echo '<br>';
            echo 'In file ';
            echo $e->getFile();
        }

        function view_image( $args = array() ) {

			if ( empty( $args[ 'src' ] ) ) {
				return false;
			}

			$tim = BASEURL.'/image.php';
			$string = http_build_query( $args, null, '&' );

			return $tim.'?'.$string;
		}
		
		/**
		 * Returns This page name or boolean for the given string
		 * @param STRING $name
		 */
		function this_page($name="")
		{
		    if(defined('THIS_PAGE'))
		    {
		        $page = THIS_PAGE;
		        if($name)
		        {
		            if($page==$name)
		                return true;
		            else
		                return false;
		        }

		        return $page;
		    }

		    return false;
		}
		/**
		 * Returns This page name or boolean for the given string
		 * @param STRING $name
		 */
		function parent_page($name="")
		{
		    if(defined('PARENT_PAGE'))
		    {
		        $page = PARENT_PAGE;
		        if($name)
		        {
		            if($page==$name)
		                return true;
		            else
		                return false;
		        }

		        return $page;
		    }

		    return false;
		}


		
	/**
	 * Sortings
	 */
	function sorting_links()
	{
		if(!isset($_GET['sort']))
			$_GET['sort'] = 'most_recent';
		if(!isset($_GET['time']))
			$_GET['time'] = 'all_time';

		$array = array
		('most_recent' 	=> lang('recent'),
		 'most_viewed'	=> lang('viewed'),
		 'featured'		=> lang('featured'),
		 'top_rated'	=> lang('top_rated'),
		 'most_commented'	=> lang('commented'),
		 'view_all'	=> lang('All'),
		 );
		return $array;
	}

	function time_links()
	{
		$array = array
		('all_time' 	=> lang('alltime'),
		 'today'		=> lang('today'),
		 'yesterday'	=> lang('yesterday'),
		 'this_week'	=> lang('thisweek'),
		 'last_week'	=> lang('lastweek'),
		 'this_month'	=> lang('thismonth'),
		 'last_month'	=> lang('lastmonth'),
		 'this_year'	=> lang('thisyear'),
		 'last_year'	=> lang('lastyear'),
		 );
		return $array;
	}

	/**
	 * Get Video Collection Items
	 *
	 * @return Array
	 */
	function get_videos_of_collection($id,$order,$limit,$count_only=false)
	{
		global $cbvideo;
		$items = array();
		$items  = $cbvideo->collection->get_collection_items_with_details($id,$order,$limit,$count_only);
		return $items;
	}

	/**
	 * Get language locale
	 *
	 * @return String
	 */
	function get_locale()
	{
		global $lang_obj;
		return $lang_obj->lang_iso;
	}

        include( 'functions_db.php' );
        include( 'functions_filter.php' );
        include( 'functions_player.php' );
        include( 'functions_template.php' );
        include( 'functions_helper.php' );

        include( 'functions_video.php' );
        include( 'functions_user.php' );
        include( 'functions_photo.php' );
        include('functions_actions.php');
        include('functions_playlist.php');
?>