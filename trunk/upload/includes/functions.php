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
	
	function cb_clean($string,$array=array('no_html'=>true,
            'mysql_clean'=>false))
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
	
	//This Fucntion is for Securing Password,
        // you may change its combination for security reason but make 
        // sure dont not rechange once you made your script run
	
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
			return strtolower(substr($file, strrpos($file,'.') + 1));
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
        
        
        
        /**
         * this_page()
         * 
         * get current page name as defined in THIS_PAGE static variable
         * 
         * @param STRING $page_name
         * @return STRING current_page
         */
         function this_page($page)
         {
             if(defined('THIS_PAGE'))
                 return THIS_PAGE;
             else
                 return 'index';
         }

         /**
          * isValidToken()
          * 
          * validate input token given in $_POST request when submitting data in 
          * ClipBucket.
          * 
          * @param STRING $token 
          * @param STRING $method
          * 
          * return BOOLEAN
          */
         function isValidToken($token,$method=NULL)
         {
             $fullToken = getToken($method);
             if($fullToken!=$token)
                 return false;
             else {
                 return true;
             }
         }

         /**
          * getToken()
          * 
          * Function used to get current token
          * 
          * @param STRING $method 
          * @return STRING $token
          */
         function getToken($method=NULL)
         {
             $sess = session_id();
             $ip = $_SERVER['REMOTE_ADDR'];
             $webkey = "";

             if(defined('CB_WEBSITE_KEY'))
             {
                 $webkey = CB_WEBSITE_KEY;
             }

             if($webkey)
             $fullToken = md5($sess.$method.$ip.$webkey);
             else
             $fullToken = md5($sess.$method.$ip);

             return $fullToken;
         }

         /**
          * createDataFolders()
          * 
          * create date folders with respect to date. so that no folder gets overloaded
          * with number of files.
          * 
          * @param string FOLDER, if set to null, sub-date-folders will be created in 
          * all data folders
          * @return string 
          */
         function createDataFolders($headFolder=NULL)
        {
                $year = date("Y");
                $month = date("m");
                $day  = date("d");
                $folder = $year.'/'.$month.'/'.$day;
                if(!$headFolder)
                {
                    @mkdir(VIDEOS_DIR.'/'.$folder,0777,true);
                    @mkdir(THUMBS_DIR.'/'.$folder,0777,true);
                    @mkdir(ORIGINAL_DIR.'/'.$folder,0777,true);
                    @mkdir(PHOTOS_DIR.'/'.$folder,0777,true);
                }else
                {
                     @mkdir($headFolder.'/'.$folder,0777,true);
                }
                return $folder;
        }


        /**
         * Gets the list of comments and assign it the given paramter
         * @global type $myquery
         * @param type $params ARGUMENTS , assign=variable to assign comments array
         * in smarty template, read getComments for more information
         * @return ARRAY $comments 
         */
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
         * This wil get an Advertisment from database and display it
         * 
         * @global type $adsObj
         * @param ARRAY (style,class,align,place)
         * style = Css Styling on div wrapping AD
         * class = class of div wrapping AD
         * place = AD placement code e.g ad_300x250
         * @return string 
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
        
        
        function cb_bottom()
	{
		//Woops..its gone
	}
        
        function getSmartyCategoryList($params)
	{
		return getCategoryList($params);
	}
        
        
        
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
	 * Function used to count fields in mysql
	 * @param TABLE NAME
	 * @param Fields
	 * @param condition
	 */
	function dbcount($tbl,$fields='*',$cond=false)
	{
		global $db;
		if($cond)
			$condition = " Where $cond ";
		$query = "Select Count($fields) From $tbl $condition";
		$result = $db->Execute($query);
		$db->total_queries++;
		$db->total_queries_sql[] = $query;
		return $result->fields[0];
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
	 * Function used to return mysql time
	 * @author : Fwhite
	 */
	function NOW()
	{
		return date('Y-m-d H:i:s', time());
	}
	
	
        
        
        
        /**
	 * Function used to display flash player for ClipBucket video
	 */
	function flashPlayer($param)
	{
		global $Cbucket,$swfobj;
		
		$param['player_div'] = $param['player_div'] ? $param['player_div'] : 'videoPlayer';
		
		$key 		= $param['key'];
		$flv 		= $param['flv'].'.flv';
		$code 		= $param['code'];
		$flv_url 	= $file;
		$embed 		= $param['embed'];
		$code 		= $param['code'];
		$height 	= $param['height'] ? $param['height'] : config('player_height');
		$width 		= $param['width'] ? $param['width'] : config('player_width');
		$param['height'] = $height;
		$param['width'] = $width ;
		
		if(!$param['autoplay'])
		$param['autoplay'] = config('autoplay_video');
		
		assign('player_params',$param);
		if(count($Cbucket->actions_play_video)>0)
		{
	 		foreach($Cbucket->actions_play_video as $funcs )
			{
				
				if(function_exists($funcs))
				{
					$func_data = $funcs($param);
				}
				if($func_data)
				{
					$player_code = $func_data;
					break;
				}
			}
		}
		
		if(function_exists('cbplayer') && empty($player_code))
			$player_code = cbplayer($param,true);
		
		global $pak_player;
		
		if($player_code)
		if(!$pak_player && $show_player)
		{
			assign("player_js_code",$player_code);
			Template(PLAYER_DIR.'/player.html',false);
			return false;
		}else
		{
			return false;
		}
		
		return blank_screen($param);
	}
	
	
	/**
	 * FUnctiuon used to plya HQ videos
	 */
	function HQflashPlayer($param)
	{
		return flashPlayer($param);
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
	 * Function used to call display
	 */
	function display_it()
	{
		global $ClipBucket;
		$dir = LAYOUT;
		foreach($ClipBucket->template_files as $file)
		{
			if(file_exists(LAYOUT.'/'.$file) || is_array($file))
			{
				
				if(!$ClipBucket->show_page && $file['follow_show_page'])
				{
					
				}else
				{
					if(!is_array($file))
						$new_list[] = $file;
					else
					{
						if($file['folder'] && file_exists($file['folder'].'/'.$file['file']))
							$new_list[] = $file['folder'].'/'.$file['file'];
						else
							$new_list[] = $file['file'];
					}
				}							
			}
		}
		
		assign('template_files',$new_list);

		Template('body.html');
		
		footer();
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
	 * Function used to return LANG variable
	 */
	function lang($var,$sprintf=false)
	{
		global $LANG,$Cbucket;

		$array_str = array
		( '{title}');
		$array_replace = array
		( $Cbucket->configs['site_title'] );
		
		if($LANG[$var])
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
		if($param['assign']=='')
			return lang($param['code'],$param['sprintf']);
		else
			assign($param['assign'],lang($param['code'],$param['sprintf']));
	}
        
        
        
        /**
	 * Function used to get player logo
	 */
	function website_logo()
	{
		$logo_file = config('player_logo_file');
		if(file_exists(BASEDIR.'/images/'.$logo_file) && $logo_file)
			return BASEURL.'/images/'.$logo_file;
		
		return BASEURL.'/images/logo.png';
	}
	
	/**
	 * Function used to assign link
	 */
	function cblink($params)
	{
		global $ClipBucket;
		$name = $params['name'];
		$ref = $param['ref'];
		
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
		
		if($params['assign'])
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
	 * Function used to display
	 * Blank Screen
	 * if there is nothing to play or to show
	 * then show a blank screen
	 */
	function blank_screen($data)
	{
		global $swfobj;
		$code = '<div class="blank_screen" align="center">No Player or Video File Found - Unable to Play Any Video</div>';
		$swfobj->EmbedCode(unhtmlentities($code),$data['player_div']);
		return $swfobj->code;
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
		$funcs = $Cbucket->$name;
		if(is_array($funcs) && count($funcs)>0)
			return $funcs;
		else
			return false;
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
	 * Function used to get config value
	 * of ClipBucket
	 */
	function config($input)
	{
		global $Cbucket;
		return $Cbucket->configs[$input];
	}
	function get_config($input){ return config($input); }
        
        
        
        
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
					$db->update(tbl("video"),array("views","last_viewed"),array("|f|views+1",NOW())," videoid='$id' OR videokey='$id'");
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
		Template('blocks/share_form.html');
	}
	
	/**
	 * Function used to show flag form
	 */
	function show_flag_form($array)
	{
		assign('params',$array);
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
		
		Template('blocks/playlist_form.html');
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
			
			assign("collections",$collections);
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
	
        
        
        
        function check_install($type)
        {
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
		$destination.'/'.$dest_name;
		$fp = fopen ($destination.'/'.$dest_name, 'w+');
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
	function je($in){ return json_encode($in); }
	/**
	 * JSON_DECODE short
	 */
	function jd($in,$returnClass=false){ if(!$returnClass) return  json_decode($in,true); else return  json_decode($in); }
	
	
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
		
		if (preg_match('/Android/i', $u_agent)) {
			$platform = 'android';
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
                
                $newQuery = array();
                
                //Cleaning values...
                foreach($query as $field => $value)
                    $newQuery[$field] = mysql_clean($value);
                
                $counter = mysql_clean($counter);
                $query = $newQuery;
                
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
				if(file_exists($modfile))
					include($modfile);
		}
	}
	
        
        
//Including videos functions
include("functions_videos.php");
//Including Users Functions
include("functions_users.php");
//Group Functions
include("functions_groups.php");
//Collections Functions
include("functions_collections.php");

include("functions_hooks.php");
include("functions_photos.php");
include("functions_forms.php");

?>