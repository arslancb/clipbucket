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
# Version:          $Id$
# Last Modified:    $Date$
# Notice:           Please maintain this section
####################################################################
*/

 define("SHOW_COUNTRY_FLAG",TRUE);
 require 'define_php_links.php';
 include_once 'upload_forms.php';
 

 
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
		
		if (get_magic_quotes_gpc())
		{
			$id = stripslashes($id);
		}
		$id = htmlspecialchars(mysql_real_escape_string($id));
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
	
	
	//Redirect Using JAVASCRIPT
	
	function redirect_to($url){
		echo '<script type="text/javascript">
		window.location = "'.$url.'"
		</script>';
		exit("Javascript is turned off, <a href='$url'>click here to go to requested page</a>");
		}
	
	//Test function to return template file
	function Fetch($name,$inside=FALSE)
	{
		if($inside)
			$file = CBTemplate::fetch($inside.$name);
		else
			$file = CBTemplate::fetch(LAYOUT.'/'.$name);
			
		return $file;			
	}
	
	//Simple Template Displaying Function
	
	function Template($template,$layout=true){
	global $admin_area;
		if($layout)
		CBTemplate::display(LAYOUT.'/'.$template);
		else
		CBTemplate::display($template);
		
		if($template == 'footer.html' && $admin_area !=TRUE){
			CBTemplate::display(BASEDIR.'/includes/templatelib/'.$template);
		}
		if($template == 'header.html'){
			CBTemplate::display(BASEDIR.'/includes/templatelib/'.$template);
		}        	
	}
	
	function Assign($name,$value)
	{
		CBTemplate::assign($name,$value);
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

	
	
	//Simple Validation
	function isValidText($text){
      $pattern = "^^[_a-z0-9-]+$";
      if (eregi($pattern, $text)){
         return true;
      	}else {
         return false;
      }   
   }
   
   //Simple Width Fetcher
   function getWidth($file)
   {
		$sizes = getimagesize($file);
		if($sizes)
			return $sizes[0];   
   }
   
   //Simple Height Fetcher
   function getHeight($file)
   {
		$sizes = getimagesize($file);
		if($sizes)
			return $sizes[1];   
   }
   
   //Load Photo Upload Form
   function loadPhotoUploadForm($params)
   {
		global $cbphoto;
		return $cbphoto->loadUploadForm($params);   
   }
   //Photo File Fetcher
   function get_photo($params)
   {
	   global $cbphoto;
	   return $cbphoto->getFileSmarty($params);
   }
   
   //Photo Upload BUtton
   function upload_photo_button($params)
   {
	   global $cbphoto;
	   return $cbphoto->upload_photo_button($params);
   }
   
   //Photo Embed Cides
   function photo_embed_codes($params)
   {
		global $cbphoto;
		return $cbphoto->photo_embed_codes($params);   
   }
   
   //Create download button

   function photo_download_button($params)
   {
		global $cbphoto;
		return $cbphoto->download_button($params);   
   }
   
   //Function Used To Validate Email
	
	function isValidEmail($email){
      $pattern = "/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
	  preg_match($pattern, $email,$matches);
      if ($matches[0]!=''){
         return true;
      }
      else {
		 if(!DEVELOPMENT_MODE)
         	return false;
		 else
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
	
	
	
	function GetThumb($vdetails,$num='default',$multi=false,$count=false)
	{

		return get_thumb($vdetails,$num,$multi,$count);
	}
	
	//Function That will use in creating SEO urls
	function VideoLink($vdetails,$type=NULL)
	{
		return video_link($vdetails,$type);
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
			echo 'Nothing to do here anymore';
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
				
		if(!$params['count_only'])
			$result = $db->select(tbl("comments".($params['sectionTable']?",".$params['sectionTable']:NULL)),"*",$cond,$limit,$order);
			
		//echo $db->db_query;	
		if($params['count_only'])
			$result = $db->count(tbl("comments"),"*",$cond);
			
		if($result)
			return $result;
		else
			return false;						
	}
	
	
	function out($link)
	{
		return BASEURL.'/out.php?l='.urlencode($link);
	}
	

//Including videos functions
include("functions_videos.php");

include("functions1.php");
include("functions2.php");
include("functions3.php");
?>