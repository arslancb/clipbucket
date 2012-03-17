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
		if(DEVELOPMENT_MODE && DEV_INGNORE_SYNTAX)
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
        
//Including videos functions
include("functions_videos.php");
//Including Users Functions
include("functions_users.php");
//Group Functions
include("functions_groups.php");
//Collections Functions
include("functions_collections.php");

include("functions2.php");
include("functions3.php");
?>