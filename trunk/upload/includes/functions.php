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
define("SHOW_COUNTRY_FLAG", TRUE);
require 'define_php_links.php';
include_once 'upload_forms.php';

//This Funtion is use to get CURRENT PAGE DIRECT URL
function curPageURL()
{
    $pageURL = 'http';
    if (@$_SERVER["HTTPS"] == "on")
    {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    $pageURL .= $_SERVER['SERVER_NAME'];
    $pageURL .= $_SERVER['PHP_SELF'];
    $query_string = $_SERVER['QUERY_STRING'];
    if (!empty($query_string))
    {
        $pageURL .= '?' . $query_string;
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
    $replace = array('&amp; ');
    return $string = preg_replace($array, $replace, $string);
}

//This Funtion is used to clean a String

function clean($string, $allow_html = false)
{
    //$string = $string;
    //$string = htmlentities($string);
    if ($allow_html == false)
    {
        $string = strip_tags($string);
        $string = Replacer($string);
    }
    // $string = utf8_encode($string);
    return $string;
}

function cb_clean($string, $array = array('no_html' => true,
    'mysql_clean' => false))
{
    if ($array['no_html'])
        $string = htmlentities($string);
    if ($array['special_html'])
        $string = htmlspecialchars($string);
    if ($array['mysql_clean'])
        $string = mysql_real_escape_string($string);
    if ($array['nl2br'])
        $string = nl2br($string);
    return $string;
}

//This Fucntion is for Securing Password,
// you may change its combination for security reason but make 
// sure dont not rechange once you made your script run

function pass_code($string)
{
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

function mysql_clean($id, $replacer = true)
{
    //$id = clean($id);

    if (get_magic_quotes_gpc())
    {
        $id = stripslashes($id);
    }
    $id = htmlspecialchars(mysql_real_escape_string($id));
    if ($replacer)
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

function filter_sql($data)
{
    $data = mysql_real_escape_string($data);
    return $data;
}

//Redirect Using JAVASCRIPT

function redirect_to($url)
{
    echo '<script type="text/javascript">
            window.location = "' . $url . '"
            </script>';
    exit("Javascript is turned off, <a href='$url'>click here to go to requested page</a>");
}

//Funtion of Random String
function RandomString($length)
{
    $string = md5(microtime());
    $highest_startpoint = 32 - $length;
    $randomString = substr($string, rand(0, $highest_startpoint), $length);
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
            $cloud[] = '<a class=cloudtags style="font-size: ' . floor($size) . 'px;" href="' . BASEURL . search_result . '?query=' . $tag . '" title="Tags: ' . ucfirst($tag) . ' was used ' . $count . ' times"><span>' . mysql_clean($tag) . '</span></a>';
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
    if (is_array($func_array))
    {
        foreach ($func_array as $func)
        {
            if (function_exists($func))
            {
                return $func($array);
            }
        }
    }

    $content = escape_gpc($array['content']);
    $subject = escape_gpc($array['subject']);
    $to = $array['to'];
    $from = $array['from'];
    $to_name = $array['to_name'];
    $from_name = $array['from_name'];

    if ($array['nl2br'])
        $content = nl2br($content);

    # CHecking Content
    if (preg_match('/<html>/', $content, $matches))
    {
        if (empty($matches[1]))
        {
            $content = wrap_email_content($content);
        }
    }
    $message .= $content;

    //ClipBucket uses PHPMailer for sending emails
    include_once("classes/phpmailer/class.phpmailer.php");
    include_once("classes/phpmailer/class.smtp.php");

    $mail = new PHPMailer(); // defaults to using php "mail()"

    $mail_type = config('mail_type');

    //---Setting SMTP ---		
    if ($mail_type == 'smtp')
    {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = config('smtp_host'); // SMTP server
        if (config('smtp_auth') == 'yes')
            $mail->SMTPAuth = true;                  // enable SMTP authentication
        $mail->Port = config('smtp_port');                    // set the SMTP port for the GMAIL server
        $mail->Username = config('smtp_user'); // SMTP account username
        $mail->Password = config('smtp_pass');        // SMTP account password
    }
    //--- Ending Smtp Settings

    $mail->SetFrom($from, $from_name);

    if (is_array($to))
    {
        foreach ($to as $name)
        {
            $mail->AddAddress(strtolower($name), $to_name);
        }
    }
    else
    {
        $mail->AddAddress(strtolower($to), $to_name);
    }

    $mail->Subject = $subject;
    $mail->MsgHTML($message);

    if (!$mail->Send())
    {
        if (!DEVELOPMENT_MODE)
            e("Mailer Error: " . $mail->ErrorInfo);
        return false;
    }else
        return true;
}

function send_email($from, $to, $subj, $message)
{
    return cbmail(array('from' => $from, 'to' => $to, 'subject' => $subj, 'content' => $message));
}

/**
 * Function used to wrap email content in 
 * HTML AND BODY TAGS
 */
function wrap_email_content($content)
{
    return '<html><body>' . $content . '</body></html>';
}

/**
 * Function used to get file name
 */
function GetName($file)
{
    if (!is_string($file))
        return false;
    $path = explode('/', $file);
    if (is_array($path))
        $file = $path[count($path) - 1];
    $new_name = substr($file, 0, strrpos($file, '.'));
    return $new_name;
}

function get_elapsed_time($ts, $datetime = 1)
{
    if ($datetime == 1)
    {
        $ts = date('U', strtotime($ts));
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
function GetExt($file)
{
    return strtolower(substr($file, strrpos($file, '.') + 1));
}

//Simple Validation
function isValidText($text)
{
    $pattern = "^^[_a-z0-9-]+$";
    if (eregi($pattern, $text))
    {
        return true;
    }
    else
    {
        return false;
    }
}

//Function Used To Validate Email

function isValidEmail($email)
{
    $pattern = "/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i";
    preg_match($pattern, $email, $matches);
    if ($matches[0] != '')
    {
        return true;
    }
    else
    {
        if (!DEVELOPMENT_MODE)
            return false;
        else
            return true;
    }
}

// THIS FUNCTION SETS HTMLSPECIALCHARS_DECODE IF FUNCTION DOESN'T EXIST
// INPUT: $text REPRESENTING THE TEXT TO DECODE
//	  $ent_quotes (OPTIONAL) REPRESENTING WHETHER TO REPLACE DOUBLE QUOTES, ETC
// OUTPUT: A STRING WITH HTML CHARACTERS DECODED
if (!function_exists('htmlspecialchars_decode'))
{

    function htmlspecialchars_decode($text, $ent_quotes = "")
    {
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

function ListFileTypes($types)
{
    $types_array = preg_replace('/,/', ' ', $types);
    $types_array = explode(' ', $types_array);
    $list = 'Video,';
    for ($i = 0; $i <= count($types_array); $i++)
    {
        if ($types_array[$i] != '')
        {
            $list .= '*.' . $types_array[$i];
            if ($i != count($types_array))
                $list .= ';';
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
    if ($handle = opendir($path))
    {
        while (false !== ($file = readdir($handle)))
        {
            $nextpath = $path . '/' . $file;
            if ($file != '.' && $file != '..' && !is_link($nextpath))
            {
                if (is_dir($nextpath))
                {
                    $dircount++;
                    $result = get_directory_size($nextpath);
                    $totalsize += $result['size'];
                    $totalcount += $result['count'];
                    $dircount += $result['dircount'];
                }
                elseif (is_file($nextpath))
                {
                    $totalsize += filesize($nextpath);
                    $totalcount++;
                }
            }
        }
    }
    closedir($handle);
    $total['size'] = $totalsize;
    $total['count'] = $totalcount;
    $total['dircount'] = $dircount;
    return $total;
}

//FUNCTION USED TO FORMAT FILE SIZE
//INPUT BYTES
//OUTPT MB , Kib
function formatfilesize($data)
{
    // bytes
    if ($data < 1024)
    {
        return $data . " bytes";
    }
    // kilobytes
    else if ($data < 1024000)
    {
        return round(( $data / 1024), 1) . "KB";
    }
    // megabytes
    else if ($data < 1024000000)
    {
        return round(( $data / 1024000), 1) . " MB";
    }
    else
    {
        return round(( $data / 1024000000), 1) . " GB";
    }
}

//TEST EXCEC FUNCTION 
function test_exec($cmd)
{
    echo '<div border="1px">';
    echo '<h1>' . htmlentities($cmd) . '</h1>';

    if (stristr(PHP_OS, 'WIN'))
    {
        $cmd = $cmd;
    }
    else
    {
        $cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\"";
    }
    $data = shell_exec($cmd);
    if ($data === false)
        echo "<p>FAILED: $cmd</p></div>";
    echo '<p><pre>' . htmlentities($data) . '</pre></p></div>';
}

/**
 * Function used to get shell output
 */
function shell_output($cmd)
{
    if (stristr(PHP_OS, 'WIN'))
    {
        $cmd = $cmd;
    }
    else
    {
        $cmd = "PATH=\$PATH:/bin:/usr/bin:/usr/local/bin bash -c \"$cmd\"  2>&1";
    }
    $data = shell_exec($cmd);
    return $data;
}

/**
 * Function used to tell ClipBucket that it has closed the script
 */
function the_end()
{
    if (!$isWorthyBuddy)
    {
        echo 'Nothing to do here anymore';
    }
}

/**
 * FUNCTION USED TO GET COMMENTS
 * @param : array();
 */
function getComments($params = NULL)
{
    global $db;
    $order = $params['order'];
    $limit = $params['limit'];
    $type = $params['type'];
    $cond = '';
    if (empty($type))
        $type = "v";
    $cond .= tbl("comments.type") . " = '" . $type . "'";

    if ($params['type_id'] && $params['sectionTable'])
    {
        if ($cond != "")
            $cond .= " AND ";
        $cond .= tbl("comments.type_id") . " = " . tbl($params['sectionTable'] . "." . $params['type_id']);
    }

    if ($params['cond'])
    {
        if ($cond != "")
            $cond .= " AND ";
        $cond .= $params['cond'];
    }

    if (!$params['count_only'])
        $result = $db->select(tbl("comments" . ($params['sectionTable'] ? "," . $params['sectionTable'] : NULL)), "*", $cond, $limit, $order);

    //echo $db->db_query;	
    if ($params['count_only'])
        $result = $db->count(tbl("comments"), "*", $cond);

    if ($result)
        return $result;
    else
        return false;
}

function out($link)
{
    return BASEURL . '/out.php?l=' . urlencode($link);
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
    if (defined('THIS_PAGE'))
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
function isValidToken($token, $method = NULL)
{
    $fullToken = getToken($method);
    if ($fullToken != $token)
        return false;
    else
    {
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
function getToken($method = NULL)
{
    $sess = session_id();
    $ip = $_SERVER['REMOTE_ADDR'];
    $webkey = "";

    if (defined('CB_WEBSITE_KEY'))
    {
        $webkey = CB_WEBSITE_KEY;
    }

    if ($webkey)
        $fullToken = md5($sess . $method . $ip . $webkey);
    else
        $fullToken = md5($sess . $method . $ip);

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
function createDataFolders($headFolder = NULL, $custom_date = NULL)
{

    $time = time();

    if ($custom_date)
        $time = strtotime($custom_date);

    $year = date("Y", $time);
    $month = date("m", $time);
    $day = date("d", $time);
    $folder = $year . '/' . $month . '/' . $day;

    $data = cb_call_functions('dated_folder');
    if ($data)
        return $data;

    if (!$headFolder)
    {
        @mkdir(VIDEOS_DIR . '/' . $folder, 0777, true);
        @mkdir(THUMBS_DIR . '/' . $folder, 0777, true);
        @mkdir(ORIGINAL_DIR . '/' . $folder, 0777, true);
        @mkdir(PHOTOS_DIR . '/' . $folder, 0777, true);
        @mkdir(LOGS_DIR . '/' . $folder, 0777, true);
    }
    else
    {
        if (!file_exists($headFolder . '/' . $folder))
        {
            @mkdir($headFolder . '/' . $folder, 0777, true);
        }
    }

    $folder = apply_filters($folder, 'dated_folder');
    return $folder;
}

function create_dated_folder($headFolder = NULL, $custom_date = NULL)
{
    return createDataFolders($headFolder, $custom_date);
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
    $comments = $myquery->getComments($params);

    if ($params['assign'])
        assign($params['assign'], $comments);
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
    if ($params['style'] || $params['class'] || $params['align'])
        $data .= '<div style="' . $params['style'] . '" class="' . $params['class'] . '" align="' . $params['align'] . '">';
    $data .= ad($adsObj->getAd($params['place']));
    if ($params['style'] || $params['class'] || $params['align'])
        $data .= '</div>';
    return $data;
}

/**
 * FUNCTION USED TO GET VIDEO RATING IN SMARTY
 * @param : array(pullRating($videos[$id]['videoid'],false,false,false,'novote');
 */
function pullSmartyRating($param)
{
    return pullRating($param['id'], $param['show5'], $param['showPerc'], $aram['showVotes'], $param['static']);
}

//Escaping Magic Quotes

/**
 * FUNCTION USED TO MAKE TAGS MORE PERFECT
 * @Author : Arslan Hassan <arslan@clip-bucket.com,arslan@labguru.com>
 * @param tags text unformatted
 * returns tags formatted
 */
function genTags($tags, $sep = ',')
{
    //Remove fazool spaces
    $tags = preg_replace(array('/ ,/', '/, /'), ',', $tags);
    $tags = preg_replace("`[,]+`", ",", $tags);
    $tag_array = explode($sep, $tags);
    foreach ($tag_array as $tag)
    {
        if (isValidtag($tag))
        {
            $newTags[] = $tag;
        }
    }
    //Creating new tag string
    if (is_array($newTags))
        $tagString = implode(',', $newTags);
    else
        $tagString = 'no-tag';
    return $tagString;
}

/**
 * FUNCTION USED TO GET CATEGORY LIST
 */
function getCategoryList($params = false)
{
    global $cats;
    $cats = "";

    $type = $params['type'];
    switch ($type)
    {
        default:
            {

                cb_call_functions('categoryListing', $params);
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
function dbInsert($tbl, $flds, $vls, $ep = NULL)
{
    global $db;
    $db->insert($tbl, $flds, $vls, $ep);
}

/**
 * Function used to Update data in database
 * @param : table name
 * @param : fields array
 * @param : values array
 * @param : Condition params
 * @params : Extra params
 */
function dbUpdate($tbl, $flds, $vls, $cond, $ep = NULL)
{
    global $db;
    return $db->update($tbl, $flds, $vls, $cond, $ep);
}

/**
 * Function used to Delete data in database
 * @param : table name
 * @param : fields array
 * @param : values array
 * @params : Extra params
 */
function dbDelete($tbl, $flds, $vls, $ep = NULL)
{
    global $db;
    return $db->delete($tbl, $flds, $vls, $ep);
}

/**
 * *
 */
function cbRocks()
{
    define("isCBSecured", TRUE);
    //echo cbSecured(CB_SIGN);
}

/**
 * Insert Id
 */
function get_id($code)
{
    global $Cbucket;
    $id = $Cbucket->ids[$code];
    if (empty($id))
        $id = $code;
    return $id;
}

/**
 * Set Id
 */
function set_id($code, $id)
{
    global $Cbucket;
    return $Cbucket->ids[$code] = $id;
}

/**
 * Function used to select data from database
 */
function dbselect($tbl, $fields = '*', $cond = false, $limit = false, $order = false, $p = false)
{
    global $db;
    return $db->dbselect($tbl, $fields, $cond, $limit, $order, $p);
}

function db_select($query)
{
    global $db;
    return $db->_select($query);
}

function db_update($tbl, $fields, $cond)
{
    global $db;

    $count = 0;
    foreach ($fields as $field => $val)
    {

        if ($count > 0)
            $fields_query .= ',';
        
        
        $needle = substr($val, 0, 1);

        if ($needle != '{{')
            $value = "'" . filter_sql($val) . "'";
        else
        {
            $val = substr($val, 1, strlen($val) - 4);
            $value = filter_sql($val);
        }
        
        $fields_query .= $field."=$value ";
        $count++;
    }
    
    //Complete Query
    $query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";
    //if(!mysql_query($query)) die($query.'<br>'.mysql_error());
    $db->total_queries++;
    $db->total_queries_sql[] = $query;
    $db->Execute($query);
    
    if (mysql_error())
        die($db->db_query . '<br>' . mysql_error());
    
    return true;
}

function db_insert($tbl, $fields)
{
    global $db;

    $count = 0;

    $query_fields = array();
    $query_values = array();


    foreach ($fields as $field => $val)
    {

        $query_fields[] = $field;

        $needle = substr($val, 0, 2);

        if ($needle != '{{')
            $query_values[] = "'" . filter_sql($val) . "'";
        else
        {
            $val = substr($val, 1, strlen($val) - 4);
            $query_values[] = filter_sql($val);
        }

        $count++;
    }

    $fields_query = implode(',', $query_fields);
    $values_query = implode(',', $query_values);




    //Complete Query
    $query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";

    //if(!mysql_query($query)) die($query.'<br>'.mysql_error());
    $db->total_queries++;
    $db->total_queries_sql[] = $query;
    $db->Execute($query);
    
    if (mysql_error())
    {
        //if(LOG_DB_ERRORS)
            
        die($db->db_query . '<br>' . mysql_error());
    }

    return $db->insert_id();
}

/**
 * Function used to count fields in mysql
 * @param TABLE NAME
 * @param Fields
 * @param condition
 */
function dbcount($tbl, $fields = '*', $cond = false)
{
    global $db;
    if ($cond)
        $condition = " Where $cond ";
    $query = "Select Count($fields) AS counted From $tbl $condition";
    $result = $db->Execute($query);

    $db->total_queries++;
    $db->total_queries_sql[] = $query;

    $counted = $result->fields['counted'];
    return $counted;
}

/**
 * An easy function for erorrs and messages (e is basically short form of exception)
 * I dont want to use the whole Trigger and Exception code, so e pretty works for me :D
 * @param TEXT $msg
 * @param TYPE $type (e for Error, m for Message
 * @param INT $id Any Predefined Message ID
 */
function e($msg = NULL, $type = 'e', $rel = NULL, $id = NULL)
{
    global $eh;
    if (!empty($msg))
        return $eh->e($msg, $type, $rel, $id);
}

/**
 * Function used to return rel list after e function is called
 * our eh also creates a relative list so that we can
 * 'focus' on textfields in case of error generation 
 */
function get_rel_list()
{
    global $eh;
    $array = array(
        'err' => $eh->error_rel,
        'msg' => $eh->message_rel,
        'war' => $eh->warning_rel
    );
    return $array;
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
function pr($text, $wrap_pre = false)
{
    if (!$wrap_pre)
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
    $func = $params['name'];
    if (function_exists($func))
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
 * This funcion used to call function dynamically in smarty
 */
function load_form($param)
{
    $func = $param['name'];
    if (function_exists($func))
        return $func($param);
}

/**
 * Function used to get PHP Path
 */
function php_path()
{
    if (PHP_PATH != '')
        return PHP_PATH;
    else
        return "/usr/bin/php";
}

/**
 * Functon used to get binary paths
 */
function get_binaries($path)
{
    if (is_array($path))
    {
        $type = $path['type'];
        $path = $path['path'];
    }

    if ($type == '' || $type == 'user')
    {
        $path = strtolower($path);
        switch ($path)
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
    }
    else
    {
        $path = strtolower($path);
        switch ($path)
        {
            case "php":
                $return_path = shell_output("which php");
                if ($return_path)
                    return $return_path;
                else
                    return "Unable to find PHP path";
                break;

            case "mp4box":
                $return_path = shell_output("which MP4Box");
                if ($return_path)
                    return $return_path;
                else
                    return "Unable to find mp4box path";
                break;

            case "flvtool2":
                $return_path = shell_output("which flvtool2");
                if ($return_path)
                    return $return_path;
                else
                    return "Unable to find flvtool2 path";
                break;

            case "ffmpeg":
                $return_path = shell_output("which ffmpeg");
                if ($return_path)
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
function unhtmlentities($string)
{
    $trans_tbl = get_html_translation_table(HTML_ENTITIES);
    $trans_tbl = array_flip($trans_tbl);
    return strtr($string, $trans_tbl);
}

/**
 * Function used to execute command in background
 */
function bgexec($cmd)
{
    if (substr(php_uname(), 0, 7) == "Windows")
    {
        //exec($cmd." >> /dev/null &");
        exec($cmd);
        //pclose(popen("start \"bla\" \"" . $exe . "\" " . escapeshellarg($args), "r")); 
    }
    else
    {
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
 * Function used to convert input to categories
 * @param input can be an array or #12# like
 */
function convert_to_categories($input)
{
    if (is_array($input))
    {
        foreach ($input as $in)
        {
            if (is_array($in))
            {
                foreach ($in as $i)
                {
                    if (is_array($i))
                    {
                        foreach ($i as $info)
                        {
                            $cat_details = get_category($info);
                            $cat_array[] = array($cat_details['categoryid'], $cat_details['category_name']);
                        }
                    }
                    elseif (is_numeric($i))
                    {
                        $cat_details = get_category($i);
                        $cat_array[] = array($cat_details['categoryid'], $cat_details['category_name']);
                    }
                }
            }
            elseif (is_numeric($in))
            {
                $cat_details = get_category($in);
                $cat_array[] = array($cat_details['categoryid'], $cat_details['category_name']);
            }
        }
    }
    else
    {
        preg_match_all('/#([0-9]+)#/', $default['category'], $m);
        $cat_array = array($m[1]);
        foreach ($cat_array as $i)
        {
            $cat_details = get_category($i);
            $cat_array[] = array($cat_details['categoryid'], $cat_details['category_name']);
        }
    }

    $count = 1;
    if (is_array($cat_array))
    {
        foreach ($cat_array as $cat)
        {
            echo '<a href="' . $cat[0] . '">' . $cat[1] . '</a>';
            if ($count != count($cat_array))
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
    foreach ($input as $key => $i)
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
 * Function used to display hint
 */
function hint($hint)
{
    
}

/**
 * Function used to check weather erro exists or not
 */
function error($param = 'array')
{
    if (count(error_list()) > 0)
    {
        if ($param != 'array')
        {
            if ($param == 'single')
                $param = 0;
            $msg = error_list();
            return $msg[$param];
        }
        return error_list();
    }else
    {
        return false;
    }
}

/**
 * Function used to check weather msg exists or not
 */
function msg($param = 'array')
{
    if (count(msg_list()) > 0)
    {
        if ($param != 'array')
        {
            if ($param == 'single')
                $param = 0;
            $msg = msg_list();
            return $msg[$param];
        }
        return msg_list();
    }else
    {
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
function create_query_limit($page, $result)
{
    $limit = $result;
    if (empty($page) || $page == 0 || !is_numeric($page))
    {
        $page = 1;
    }
    $from = $page - 1;
    $from = $from * $limit;

    return $from . ',' . $result;
}

/**
 * Function used to return LANG variable
 */
function lang($var, $sprintf = false)
{
    global $LANG, $Cbucket;

    $array_str = array
        ('{title}');
    $array_replace = array
        ($Cbucket->configs['site_title']);

    if ($LANG[$var])
    {
        $phrase = str_replace($array_str, $array_replace, $LANG[$var]);
    }
    else
    {
        $phrase = str_replace($array_str, $array_replace, $var);
    }

    if ($sprintf)
    {
        $sprints = explode(',', $sprintf);
        if (is_array($sprints))
        {
            foreach ($sprints as $sprint)
            {
                $phrase = sprintf($phrase, $sprint);
            }
        }
    }

    return $phrase;
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
    if (is_array($funcs) && count($funcs) > 0)
        return $funcs;
    else
        return false;
}

/**
 * Function used to get config value
 * of ClipBucket
 */
function config($input, $value = false)
{
    global $Cbucket, $myquery;
    if (!$value)
        return $Cbucket->configs[$input];
    else
    {
        $myquery->Set_Website_Details($input, '|no_mc|' . $value);
    }
}

function get_config($input)
{
    return config($input);
}

/**
 * Function used to incream number of view
 * in object
 */
function increment_views($id, $type = NULL)
{
    global $db;
    switch ($type)
    {
        case 'v':
        case 'video':
        default:
            {
                $video = get_video_details($id);
                if (!$video)
                {
                    return false;
                }

                if ($video['active'] != 'yes' || $video['status'] != 'Successful')
                {
                    return false;
                }

                if (function_exists('vi_user_type'))
                {
                    $_user = vi_user_type();
                    if ($_user['user_type'] == 4)
                    {
                        return false;
                    }
                }

                if (!isset($_COOKIE['video_' . $id]))
                {
                    $db->update(tbl("video"), array("views", "last_viewed"), array("|f|views+1", NOW()), " videoid='$id' OR videokey='$id'");
                    setcookie('video_' . $id, 'watched', time() + 3600);
                }
            }
            break;
        case 'u':
        case 'user':
        case 'channel':
            {

                if (!isset($_COOKIE['user_' . $id]))
                {
                    $db->update(tbl("users"), array("profile_hits"), array("|f|profile_hits+1"), " userid='$id'");
                    setcookie('user_' . $id, 'watched', time() + 3600);
                }
            }
            break;
        case 't':
        case 'topic':
            {
                if (!isset($_COOKIE['topic_' . $id]))
                {
                    $db->update(tbl("group_topics"), array("total_views"), array("|f|total_views+1"), " topic_id='$id'");
                    setcookie('topic_' . $id, 'watched', time() + 3600);
                }
            }
            break;
            break;
        case 'g':
        case 'group':
            {
                if (!isset($_COOKIE['group_' . $id]))
                {
                    $db->update(tbl("groups"), array("total_views"), array("|f|total_views+1"), " group_id='$id'");
                    setcookie('group_' . $id, 'watched', time() + 3600);
                }
            }
            break;
        case "c":
        case "collect":
        case "collection":
            {
                if (!isset($_COOKIE['collection_' . $id]))
                {
                    $db->update(tbl("collections"), array("views"), array("|f|views+1"), " collection_id = '$id'");
                    setcookie('collection_' . $id, 'viewed', time() + 3600);
                }
            }
            break;

        case "photos":
        case "photo":
        case "p":
            {
                if (!isset($_COOKIE['photo_' . $id]))
                {
                    $db->update(tbl('photos'), array("views", "last_viewed"), array("|f|views+1", NOW()), " photo_id = '$id'");
                    setcookie('photo_' . $id, 'viewed', time() + 3600);
                }
            }
    }
}

function cbdate($format = NULL, $timestamp = NULL)
{
    if (!$format)
    {
        $format = config("datE_format");
    }
    if (!$timestamp)
        return date($format);
    else
        return date($format, $timestamp);
}

/**
 * Function used to count pages
 * @param TOTAL RESULTS NUM
 * @param NUMBER OF RESULTS to DISPLAY NUM
 */
function count_pages($total, $count)
{
    if ($count < 1)
        $count = 1;
    $records = $total / $count;
    return $total_pages = round($records + 0.49, 0);
}

/**
 * This function used to check
 * weather user is online or not
 * @param : last_active time
 * @param : time margin
 */
function is_online($time, $margin = '5')
{
    $margin = $margin * 60;
    $active = strtotime($time);
    $curr = time();
    $diff = $curr - $active;
    if ($diff > $margin)
        return 'offline';
    else
        return 'online';
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
function nicetime($date, $istime = false)
{
    if (empty($date))
    {
        return lang('no_date_provided');
    }

    $periods = array(lang("second"), lang("minute"), lang("hour"), lang("day"), lang("week"), lang("month"), lang("year"), lang("decade"));
    $lengths = array(lang("60"), lang("60"), lang("24"), lang("7"), lang("4.35"), lang("12"), lang("10"));

    $now = time();

    if (!$istime)
        $unix_date = strtotime($date);
    else
        $unix_date = $date;

    // check validity of date
    if (empty($unix_date) || $unix_date < 1)
    {
        return lang("bad_date");
    }

    // is it future date or past date
    if ($now > $unix_date)
    {
        //time_ago
        $difference = $now - $unix_date;
        $tense = "time_ago";
    }
    else
    {
        //from_now
        $difference = $unix_date - $now;
        $tense = "a moment ago";
    }

    for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++)
    {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if ($difference != 1)
    {
        $periods[$j].= "s";
    }


    return sprintf(lang($tense), $difference, $periods[$j]);
}

/**
 * Function used to format outgoing link
 */
function outgoing_link($out)
{
    preg_match("/http/", $out, $matches);
    if (empty($matches[0]))
        $out = "http://" . $out;
    return '<a href="' . $out . '" target="_blank">' . $out . '</a>';
}

/**
 * Function used to get country via country code
 */
function get_country($code)
{
    global $db;
    $result = $db->select(tbl("countries"), "name_en,iso2", " iso2='$code' OR iso3='$code'");
    if ($db->num_rows > 0)
    {
        $flag = '';
        $result = $result[0];
        if (SHOW_COUNTRY_FLAG)
            $flag = '<img src="' . BASEURL . '/images/icons/country/' . strtolower($result['iso2']) . '.png" alt="" border="0">&nbsp;';
        return $flag . $result['name_en'];
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
    if (defined($plugin . "_install"))
        return true;
    else
        return false;
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
    global $while_installing, $Cbucket;
    switch ($type)
    {
        case "before":
            {
                if (file_exists('files/temp/install.me') && !file_exists('includes/clipbucket.php'))
                {
                    header('Location: ' . get_server_url() . '/cb_install');
                }
            }
            break;

        case "after":
            {
                if (file_exists('files/temp/install.me'))
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
    if (preg_match('/admin_area/i', $DirName))
    {
        $DirName = str_replace('/admin_area', '', $DirName);
    }
    if (preg_match('/cb_install/i', $DirName))
    {
        $DirName = str_replace('/cb_install', '', $DirName);
    }
    return get_server_protocol() . $_SERVER['HTTP_HOST'] . $DirName;
}

function get_server_protocol()
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
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
    if (count($pattern) == 1)
    {
        // no file extension found
        return;
    }

    if (count($pattern) > 1)
    {
        $filenamepart = $pattern[count($pattern) - 1][0];
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
    $lastdot = $pattern[count($pattern) - 1][1];
    #now extract the filename using the basename function
    $filename = basename(substr($string, 0, $lastdot - 1));

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
    $code .= '<object width="' . EMBED_VDO_WIDTH . '" height="' . EMBED_VDO_HEIGHT . '">';
    $code .= '<param name="allowFullScreen" value="true">';
    $code .= '</param><param name="allowscriptaccess" value="always"></param>';
    //Replacing Height And Width
    $h_w_p = array("{Width}", "{Height}");
    $h_w_r = array(EMBED_VDO_WIDTH, EMBED_VDO_HEIGHT);
    $embed_code = str_replace($h_w_p, $h_w_r, $vdetails['embed_code']);
    $code .= unhtmlentities($embed_code);
    $code .= '</object>';
    return $code;
}

/**
 * function used to convert input to proper date created formate
 */
function datecreated($in)
{

    $date_els = explode('-', $in);

    //checking date format
    $df = config("date_format");
    $df_els = explode('-', $df);

    foreach ($df_els as $key => $el)
        ${strtolower($el) . 'id'} = $key;

    $month = $date_els[$mid];
    $day = $date_els[$did];
    $year = $date_els[$yid];

    if ($in)
        return date("Y-m-d", strtotime($year . '-' . $month . '-' . $day));
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
    if (!$sub_dir)
        return $base = $protocol . $_SERVER['HTTP_HOST'] . untrailingslashit(stripslashes(dirname(($_SERVER['SCRIPT_NAME']))));
    else
        return $base = $protocol . $_SERVER['HTTP_HOST'] . untrailingslashit(stripslashes(dirname(dirname($_SERVER['SCRIPT_NAME']))));
}

function base_url()
{
    return baseurl();
}

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
function trailingslashit($string)
{
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
function untrailingslashit($string)
{
    return rtrim($string, '/');
}

/**
 * Determine if SSL is used.
 *
 * @since 2.6.0
 *
 * @return bool True if SSL, false if not used.
 */
function is_ssl()
{
    if (isset($_SERVER['HTTPS']))
    {
        if ('on' == strtolower($_SERVER['HTTPS']))
            return true;
        if ('1' == $_SERVER['HTTPS'])
            return true;
    } elseif (isset($_SERVER['SERVER_PORT']) && ( '443' == $_SERVER['SERVER_PORT'] ))
    {
        return true;
    }
    return false;
}

/**
 * This will update stats like Favorite count, Playlist count
 */
function updateObjectStats($type = 'favorite', $object = 'video', $id, $op = '+')
{
    global $db;

    switch ($type)
    {
        case "favorite": case "favourite":
        case "favorites": case "favourties":
        case "fav":
            {
                switch ($object)
                {
                    case "video":
                    case "videos": case "v":
                        {
                            $db->update(tbl('video'), array('favourite_count'), array("|f|favourite_count" . $op . "1"), " videoid = '" . $id . "'");
                        }
                        break;

                    case "photo":
                    case "photos": case "p":
                        {
                            $db->update(tbl('photos'), array('total_favorites'), array("|f|total_favorites" . $op . "1"), " photo_id = '" . $id . "'");
                        }
                        break;

                    /* case "collection":
                      case "collections": case "cl":
                      {
                      $db->update(tbl('photos'),array('total_favorites'),array("|f|total_favorites".$op."1")," photo_id = '".$id."'");
                      }
                      break; */
                }
            }
            break;

        case "playlist": case "playList":
        case "plist":
            {
                switch ($object)
                {
                    case "video":
                    case "videos": case "v":
                        {
                            $db->update(tbl('video'), array('playlist_count'), array("|f|playlist_count" . $op . "1"), " videoid = '" . $id . "'");
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
    if (file_exists(TEMP_DIR . '/conv_lock.loc'))
        return true;
    else
        return false;
}

/**
 * Function used to return a well-formed queryString
 * for passing variables to url
 * @input variable_name
 */
function queryString($var = false, $remove = false)
{
    $queryString = $_SERVER['QUERY_STRING'];

    if ($var)
        $queryString = preg_replace("/&?($var)=([\w+\s\b\.?\S])[^&]*/", "", $queryString);

    if ($remove)
    {
        if (!is_array($remove))
            $queryString = preg_replace("/&?($remove)=([\w+\s\b\.?\S])[^&]*/", "", $queryString);
        else
            foreach ($remove as $rm)
        {
                $queryString = preg_replace("/&?($rm)=([\w+\s\b\.?\S])[^&]*/", "", $queryString);
        }
    }

    if ($queryString)
        $preUrl = "?$queryString&";
    else
        $preUrl = "?";

    $preUrl = preg_replace(array("/(\&{2,10})/", "/\?\&/"), array("&", "?"), $preUrl);

    return $preUrl . $var;
}

/**
 * Following two functions are taken from
 * tutorialzine.com's post 'Creating a Facebook-like Registration Form with jQuery'
 * These function are written by Martin Angelov.
 * Read post here: http://tutorialzine.com/2009/08/creating-a-facebook-like-registration-form-with-jquery/
 */
function generate_options($params)
{
    $reverse = false;

    if ($params['from'] > $params['to'])
    {
        $tmp = $params['from'];
        $params['from'] = $params['to'];
        $params['to'] = $tmp;

        $reverse = true;
    }


    $return_string = array();
    for ($i = $params['from']; $i <= $params['to']; $i++)
    {
        //$return_string[$i] = ($callback?$callback($i):$i);
        $return_string[] = '<option value="' . $i . '">' . ($params['callback'] ? $params['callback']($i) : $i) . '</option>';
    }

    if ($reverse)
    {
        $return_string = array_reverse($return_string);
    }


    return join('', $return_string);
}

function callback_month($month)
{
    return date('M', mktime(0, 0, 0, $month, 1));
}

/**
 * Function use to download file to server
 * 
 * @param URL
 * @param destination
 */
function snatch_it($snatching_file, $destination, $dest_name, $rawdecode = true)
{
    global $curl;
    if ($rawdecode == true)
        $snatching_file = rawurldecode($snatching_file);
    $destination . '/' . $dest_name;
    $fp = fopen($destination . '/' . $dest_name, 'w+');
    $ch = curl_init($snatching_file);
    curl_setopt($ch, CURLOPT_TIMEOUT, 600);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2');
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);
}

/**
 * Function check curl
 */
function isCurlInstalled()
{
    if (in_array('curl', get_loaded_extensions()))
    {
        return true;
    }
    else
    {
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
        'uploadSwfPath' => JS_URL . '/uploadify/uploadify.swf',
        'uploadScriptPath' => BASEURL . '/actions/file_uploader.php',
    );

    $photoUploaderDetails = array
        (
        'uploadSwfPath' => JS_URL . '/uploadify/uploadify.swf',
        'uploadScriptPath' => BASEURL . '/actions/photo_uploader.php',
    );


    assign('uploaderDetails', $uploaderDetails);
    assign('photoUploaderDetails', $photoUploaderDetails);
    //Calling Custom Functions
    cb_call_functions('uploaderDetails');
}

/**
 * Function isSectionEnabled
 * This function used to check weather INPUT section is enabled or not
 */
function isSectionEnabled($input, $restrict = false)
{
    global $Cbucket;

    $section = $Cbucket->configs[$input . 'Section'];

    if (!$restrict)
    {
        if ($section == 'yes')
            return true;
        else
            return false;
    }else
    {
        if ($section == 'yes' || THIS_PAGE == 'cb_install')
        {
            return true;
        }
        else
        {
            template_files('blocked.html');
            display_it();
            exit();
        }
    }
}

function array_depth($array)
{
    $ini_depth = 0;

    foreach ($array as $arr)
    {
        if (is_array($arr))
        {
            $depth = array_depth($arr) + 1;

            if ($depth > $ini_depth)
                $ini_depth = $depth;
        }
    }

    return $ini_depth;
}

/**
 * JSON_ENCODE short
 */
function je($in)
{
    return json_encode($in);
}

/**
 * JSON_DECODE short
 */
function jd($in, $returnClass = false)
{
    if (!$returnClass)
        return json_decode($in, true); else
        return json_decode($in);
}

/**
 * function used to update last commented option 
 * so comment cache can be refreshed
 */
function update_last_commented($type, $id)
{
    global $db;

    if ($type && $id)
    {
        switch ($type)
        {
            case "v":
            case "video":
            case "vdo":
            case "vid":
            case "videos":
                $db->update(tbl("video"), array('last_commented'), array(now()), "videoid='$id'");

                break;

            case "c":
            case "channel":
            case "user":
            case "u":
            case "users":
            case "channels":
                $db->update(tbl("users"), array('last_commented'), array(now()), "userid='$id'");
                break;

            case "cl":
            case "collection":
            case "collect":
            case "collections":
            case "collects":
                $db->update(tbl("collections"), array('last_commented'), array(now()), "collection_id='$id'");
                break;

            case "p":
            case "photo":
            case "photos":
            case "picture":
            case "pictures":
                $db->update(tbl("photos"), array('last_commented'), array(now()), "photo_id='$id'");
                break;

            case "t":
            case "topic":
            case "topics":
                $db->update(tbl("group_topics"), array('last_post_time'), array(now()), "topic_id='$id'");
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
    global $cbfeeds, $cbphoto, $userquery;

    $action = $array['action'];
    if ($array['uid'])
        $userid = $array['uid'];
    else
        $userid = userid();

    switch ($action)
    {
        case "upload_photo":
            {

                $feed['action'] = 'upload_photo';
                $feed['object'] = 'photo';
                $feed['object_id'] = $array['object_id'];
                $feed['uid'] = $userid;
                ;

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
                $feed['uid'] = $userid;
                ;

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
function this_plugin($pluginFile = NULL)
{
    if (!$pluginFile)
        global $pluginFile;
    return basename(dirname($pluginFile));
}

/**
 * function used to get user agent details
 * Thanks to ruudrp at live dot nl 28-Nov-2010 11:31 PHP.NET
 */
function get_browser_details($in = NULL, $assign = false)
{
    //Checking if browser is firefox
    if (!$in)
        $in = $_SERVER['HTTP_USER_AGENT'];

    $u_agent = $in;
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version = "";

    //First get the platform?
    if (preg_match('/linux/i', $u_agent))
    {
        $platform = 'linux';
    }
    elseif (preg_match('/iPhone/i', $u_agent))
    {
        $platform = 'iphone';
    }
    elseif (preg_match('/iPad/i', $u_agent))
    {
        $platform = 'ipad';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent))
    {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent))
    {
        $platform = 'windows';
    }

    if (preg_match('/Android/i', $u_agent))
    {
        $platform = 'android';
    }

    // Next get the name of the useragent yes seperately and for good reason
    if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif (preg_match('/Firefox/i', $u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif (preg_match('/Chrome/i', $u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif (preg_match('/Safari/i', $u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif (preg_match('/Opera/i', $u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif (preg_match('/Netscape/i', $u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
    elseif (preg_match('/Googlebot/i', $u_agent))
    {
        $bname = 'Googlebot';
        $ub = "bot";
    }
    elseif (preg_match('/msnbot/i', $u_agent))
    {
        $bname = 'MSNBot';
        $ub = "bot";
    }
    elseif (preg_match('/Yahoo\! Slurp/i', $u_agent))
    {
        $bname = 'Yahoo Slurp';
        $ub = "bot";
    }


    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
            ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!@preg_match_all($pattern, $u_agent, $matches))
    {
        // we have no matching number just continue
    }

    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1)
    {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent, "Version") < strripos($u_agent, $ub))
        {
            $version = $matches['version'][0];
        }
        else
        {
            $version = $matches['version'][1];
        }
    }
    else
    {
        $version = $matches['version'][0];
    }

    // check if we have a number
    if ($version == null || $version == "")
    {
        $version = "?";
    }

    $array = array(
        'userAgent' => $u_agent,
        'name' => $bname,
        'version' => $version,
        'platform' => $platform,
        'bname' => strtolower($ub),
        'pattern' => $pattern
    );

    if ($assign)
        assign($assign, $array); else
        return $array;
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
    if (is_array($funcs) && count($funcs) > 0)
    {
        foreach ($funcs as $func)
        {
            if (function_exists($func))
            {
                return $func($u);
            }
        }
    }
}

/**
 * Function used to load clipbucket title
 */
function cbtitle($params = false)
{
    global $cbsubtitle;

    $sub_sep = $params['sub_sep'];
    if (!$sub_sep)
        $sub_sep = '-';

    //Getting Subtitle
    echo TITLE;
    if (!$cbsubtitle)
        echo " $sub_sep " . SLOGAN;
    else
        echo " $sub_sep " . $cbsubtitle;
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
    echo "<h1>Arslan Hassan</h1>";
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
 * function used to check
 * remote link is valid or not
 */
function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    if ($result !== FALSE)
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
function get_counter($section, $query)
{
    if (!config('use_cached_pagin'))
        return false;

    global $db;

    $timeRefresh = config('cached_pagin_time');
    $timeRefresh = $timeRefresh * 60;

    $validTime = time() - $timeRefresh;

    unset($query['order']);
    $je_query = json_encode($query);
    $query_md5 = md5($je_query);
    $select = $db->select(tbl('counters'), "*", "section='$section' AND query_md5='$query_md5' 
		AND '$validTime' < date_added");
    if ($db->num_rows > 0)
    {
        return $select[0]['counts'];
    }else
        return false;
}

/**
 * function used to insert or update counter
 */
function update_counter($section, $query, $counter)
{
    global $db;
    unset($query['order']);

    $newQuery = array();

    //Cleaning values...
    foreach ($query as $field => $value)
        $newQuery[$field] = mysql_clean($value);

    $counter = mysql_clean($counter);
    $query = $newQuery;

    $je_query = json_encode($query);
    $query_md5 = md5($je_query);
    $count = $db->count(tbl('counters'), "*", "section='$section' AND query_md5='$query_md5'");
    if ($count)
    {
        $db->update(tbl('counters'), array('counts', 'date_added'), array($counter, strtotime(now())), "section='$section' AND query_md5='$query_md5'");
    }
    else
    {
        $db->insert(tbl('counters'), array('section', 'query', 'query_md5', 'counts', 'date_added'), array($section, '|no_mc|' . $je_query, $query_md5, $counter, strtotime(now())));
    }
}

/**
 * function used to register a module file, that will be later called
 * by load_modules() function
 */
function register_module($mod_name, $file)
{
    global $Cbucket;
    $Cbucket->modules_list[$mod_name][] = $file;
}

/**
 * function used to load module files
 */
function load_modules()
{
    global $Cbucket, $lang_obj, $signup, $Upload, $cbgroup,
    $adsObj, $formObj, $cbplugin, $eh, $sess, $cblog, $imgObj,
    $cbvideo, $cbplayer, $cbemail, $cbpm, $cbpage, $cbindex,
    $cbcollection, $cbphoto, $cbfeeds, $userquery, $db, $pages, $cbvid;

    foreach ($Cbucket->modules_list as $cbmod)
    {
        foreach ($cbmod as $modfile)
            if (file_exists($modfile))
                include($modfile);
    }
}

/**
 * FUNCTION Used to convert XML to Array
 * @Author : http://www.php.net/manual/en/function.xml-parse.php#87920
 */
function xml2array($url, $get_attributes = 1, $priority = 'tag', $is_url = true)
{
    $contents = "";
    if (!function_exists('xml_parser_create'))
    {
        return false;
    }
    $parser = xml_parser_create('');

    if ($is_url)
    {
        if (!($fp = @ fopen($url, 'rb')))
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/3.0.0.2');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $contents = curl_exec($ch);
            curl_close($ch);

            if (!$contents)
                return false;
        }
        while (!feof($fp))
        {
            $contents .= fread($fp, 8192);
        }
        fclose($fp);
    }
    else
    {
        $contents = $url;
    }

    xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
    xml_parse_into_struct($parser, trim($contents), $xml_values);
    xml_parser_free($parser);
    if (!$xml_values)
        return; //Hmm...
    $xml_array = array();
    $parents = array();
    $opened_tags = array();
    $arr = array();
    $current = & $xml_array;
    $repeated_tag_index = array();
    foreach ($xml_values as $data)
    {

        unset($attributes, $value);
        extract($data);
        $result = array();
        $attributes_data = array();
        if (isset($value))
        {
            if ($priority == 'tag')
                $result = $value;
            else
                $result['value'] = $value;
        }
        if (isset($attributes) and $get_attributes)
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
            $parent[$level - 1] = & $current;
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
                if (isset($current[$tag][0]))
                {
                    $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                    $repeated_tag_index[$tag . '_' . $level]++;
                }
                else
                {
                    $current[$tag] = array(
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 2;
                    if (isset($current[$tag . '_attr']))
                    {
                        $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                        unset($current[$tag . '_attr']);
                    }
                }
                $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                $current = & $current[$tag][$last_item_index];
            }
        }
        elseif ($type == "complete")
        {
            if (!isset($current[$tag]))
            {
                $current[$tag] = $result;
                $repeated_tag_index[$tag . '_' . $level] = 1;
                if ($priority == 'tag' and $attributes_data)
                    $current[$tag . '_attr'] = $attributes_data;
            }
            else
            {
                if (isset($current[$tag][0]) and is_array($current[$tag]))
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
                    $current[$tag] = array(
                        $current[$tag],
                        $result
                    );
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $get_attributes)
                    {
                        if (isset($current[$tag . '_attr']))
                        {
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
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
            $current = & $parent[$level - 1];
        }
    }

    return ($xml_array);
}

function array2xml($array, $level = 1)
{
    $xml = '';
    // if ($level==1) {
    //     $xml .= "<array>\n";
    // }
    foreach ($array as $key => $value)
    {
        $key = strtolower($key);
        if (is_object($value))
        {
            $value = get_object_vars($value);
        }// convert object to array

        if (is_array($value))
        {
            $multi_tags = false;
            foreach ($value as $key2 => $value2)
            {
                if (is_object($value2))
                {
                    $value2 = get_object_vars($value2);
                } // convert object to array
                if (is_array($value2))
                {
                    $xml .= str_repeat("\t", $level) . "<$key>\n";
                    $xml .= array2xml($value2, $level + 1);
                    $xml .= str_repeat("\t", $level) . "</$key>\n";
                    $multi_tags = true;
                }
                else
                {
                    if (trim($value2) != '')
                    {
                        if (htmlspecialchars($value2) != $value2)
                        {
                            $xml .= str_repeat("\t", $level) .
                                    "<$key2><![CDATA[$value2]]>" . // changed $key to $key2... didn't work otherwise.
                                    "</$key2>\n";
                        }
                        else
                        {
                            $xml .= str_repeat("\t", $level) .
                                    "<$key2>$value2</$key2>\n"; // changed $key to $key2
                        }
                    }
                    $multi_tags = true;
                }
            }
            if (!$multi_tags and count($value) > 0)
            {
                $xml .= str_repeat("\t", $level) . "<$key>\n";
                $xml .= array2xml($value, $level + 1);
                $xml .= str_repeat("\t", $level) . "</$key>\n";
            }
        }
        else
        {
            if (trim($value) != '')
            {
                echo "value=$value<br>";
                if (htmlspecialchars($value) != $value)
                {
                    $xml .= str_repeat("\t", $level) . "<$key>" .
                            "<![CDATA[$value]]></$key>\n";
                }
                else
                {
                    $xml .= str_repeat("\t", $level) .
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
 * Function used to get latest ClipBucket version info
 */
function get_latest_cb_info()
{
    if ($_SERVER['HTTP_HOST'] != 'localhost')
        $url = 'http://clip-bucket.com/versions.xml';
    else
        $url = 'http://localhost/clipbucket/2.x/2/upload/tester/versions.xml';
    $version = xml2array($url);
    if (!$version)
    {
        return false;
    }
    else
    {
        return $version['phpbucket']['clipbucket'][0];
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
    $rss_link = cblink(array("name" => "rss"));
    $rss_feeds = array();
    $rss_feeds[] = array("title" => "Recently added videos", "link" => $rss_link . "recent");
    $rss_feeds[] = array("title" => "Most Viewed Videos", "link" => $rss_link . "views");
    $rss_feeds[] = array("title" => "Top Rated Videos", "link" => $rss_link . "rating");
    $rss_feeds[] = array("title" => "Videos Being Watched", "link" => $rss_link . "watching");
    $rss_feeds = apply_filters($rss_feeds, 'rss_feeds');

    $funcs = get_functions('rss_feeds');
    if (is_array($funcs))
    {
        foreach ($funcs as $func)
        {
            return $func($params);
        }
    }

    if ($params['link_tag'])
    {
        foreach ($rss_feeds as $rss_feed)
        {
            echo "<link rel=\"alternate\" type=\"application/rss+xml\"
				title=\"" . $rss_feed['title'] . "\" href=\"" . $rss_feed['link'] . "\" />\n";
        }
    }
}

/**
 * Function used to insert Log
 */
function insert_log($type, $details)
{
    global $cblog;
    $cblog->insert($type, $details);
}

/**
 * Function used to get db size
 */
function get_db_size()
{
    $result = mysql_query("SHOW TABLE STATUS");
    $dbsize = 0;
    while ($row = mysql_fetch_array($result))
    {
        $dbsize += $row["Data_length"] + $row["Index_length"];
    }
    return $dbsize;
}

/**
 * Function used to check weather user has marked comment as spam or not
 */
function marked_spammed($comment)
{
    $spam_voters = explode("|", $comment['spam_voters']);
    $spam_votes = $comment['spam_votes'];
    $admin_vote = in_array('1', $spam_voters);
    if (userid() && in_array(userid(), $spam_voters))
    {
        return true;
    }
    elseif ($admin_vote)
    {
        return true;
    }
    else
    {
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
    switch ($type)
    {
        case "v":
            {
                return "video";
            }
            break;
    }
}

/**
 * @name slug
 * @todo generate slug
 * @param STRING title
 */
function slug($title)
{

    $title = SEO($title, false, false);
    $title = SEO(clean(str_replace(' ', '-', $title)), false, false);

    if (substr($title, strlen($title) - 1, 1) == '-')
    {
        $title = substr($title, 0, strlen($title) - 1);
    }

    $title = mb_strtolower($title, 'UTF-8');
    return $title;
}

/**
 * @name add_slug
 * @todo Add Slug in database for pretty urls
 * @param STRING slug
 * @param INT object ID
 * @param STRING object type
 */
function add_slug($slug, $id, $type)
{
    global $db;
    $counts = 0;

    $theSlug = $slug;
    while (1)
    {
        if (!slug_exists($theSlug, $type))
            break;
        else
        {
            $counts++;
            $theSlug = $slug . '-' . $counts;
        }
    }

    $db->insert(tbl('slugs'), array('object_id', 'object_type', 'slug'), array($id, $type, $theSlug));


    return array('id' => $db->insert_id(), 'slug' => $theSlug);
}

/**
 * @name slug_exists
 * @todo checks if slug exists or not
 * @param STRING slug
 * @param STRING type
 * @return ARRAY slug
 */
function slug_exists($slug, $type = NULL, $id = NULL)
{
    global $db;

    $typeQuery = "";

    if ($type)
        $typeQuery = " AND object_type='$type' ";
    if ($id)
        $idQuery = " AND object_id = '$id' ";

    $result = $db->select(tbl('slugs'), '*', "slug ='$slug' $typeQuery $idQuery");

    if ($db->num_rows > 0)
        return $result[0];
    else
        return false;
}

/**
 * @name get_slug
 * @todo Get slug of an object from tID and tpy
 * @param STRING ID
 * @param STRING type
 */
function get_slug($id, $type)
{
    global $db;
    $results = $db->select(tbl('slugs'), '*', "object_id='$id' AND object_type='$type' ");
    if ($db->num_rows > 0)
    {
        foreach ($results as $result)
        {
            if ($result['in_use'] == 'yes')
                return $result;
        }

        return $results[0];
    }else
    {
        return false;
    }
}

/**
 * set_config
 * runtime configuration.. 
 * overwrites existin $Cbucket->configs ...
 * 
 * @param STRING $var
 * @param STRING $val
 * 
 * @return true
 */
function set_config($var, $val)
{
    global $Cbucket;
    $Cbucket->configs[$var] = $val;
    return true;
}

if (!function_exists('cb_show_page'))
{

    function cb_show_page($var = false)
    {
        global $Cbucket;
        if (gettype($var) != 'boolean' || !is_bool($var))
        {
            $var = false;
        }
        return $Cbucket->show_page = $show;
    }

}

if (!function_exists('cb_filename'))
{

    function cb_filename()
    {
        $filename = time() . RandomString(6);
        return $filename;
    }

}

if (!function_exists('cb_parse_args_string'))
{

    function cb_parse_args_string($string = null)
    {
        if (is_null($string))
            return false;

        // Breaking string into configurations
        $args = array();
        $configurations = array_map('trim', explode("|", $string));
        foreach ($configurations as $config)
        {
            $values = array_map('trim', explode(":", $config));
            if (count($values) == 2)
            {
                $args[$values[0]] = $values[1];
            }
        }

        if (!empty($args))
        {
            return $args;
        }
        else
        {
            return false;
        }
    }

}

function validate_image_file($file, $ext = null)
{
    global $imgObj;
    return $imgObj->ValidateImage($file, $ext);
}

function get_mature_thumb($object, $size = null, $output = null)
{

    /* Calling custom function */
    $funcs = cb_get_functions('mature_thumb');
    if (is_array($funcs))
    {
        foreach ($funcs as $func)
        {
            if (function_exists($func['func']))
            {
                $thumb = $func['func']($object, $size, $output);
                if ($thumb)
                {
                    return $thumb;
                }
            }
        }
    }

    if ($size == 'big')
    {
        $size = '-big';
    }
    else
    {
        $size = null;
    }

    $name = "unsafe" . $size . ".jpg";
    $path = BASEURL . '/images/' . $name;

    if ($output)
    {
        return cb_output_img_tag($path);
    }
    else
    {
        return $path;
    }
}

/**
 * Get comment author...
 * 
 * @param ARRAY $comment
 * @return STRING $author
 * @Author Arslan
 */
function comment_author($comment)
{
    $comment = apply_filters($comment, 'comment_author');

    if ($comment['userid'])
    {
        //means registered user has made a comment..
        return $comment['username'];
    }
    else
    {
        //Shows what guest has put in for name
        return $comment['anonym_name'];
    }
}

/**
 * Alias of comment_author
 * @param type $comment
 * @return type 
 */
function get_comment_author($comment)
{
    return comment_author($comment);
}

/**
 * Can Delete Comment...
 * 
 * As the name suggests, it is used to check weather logged in user has
 * rights to delete the comment or not.
 * 
 * @param $comment
 * @Author Arslan Hassan
 * @return BOOLEAN 
 * @link http://docs.clip-bucket.com/user-manual/developers-guide/functions/can_delete_comment
 */
function can_delete_comment($comment, $userid = false)
{

    if (!$userid)
        $userid = userid();

    if (has_access('admin_del_access')
            OR $comment['userid'] == $userid
            OR $comment['type_owner_id'] == $userid)
    {
        return true;
    }

    return false;
}

/**
 * is comment spam? checks weather a comment is spam or not.
 * first it checks logged in user has made the comment spam or not
 * 2nd it checks if spam count crosses the limit of flag counts
 * 
 * @author Arslan Hassan
 * @link http://docs.clip-bucket.com/user-manual/developers-guide/functions/is_comment_spam
 * @param ARRAY $commment
 * @return BOOLEAN
 * 
 */
function is_comment_spam($comment)
{
    $comment = apply_filters($comment, 'comment_spam');

    $uid = userid();

    $return = array(
        'global_spam' => false,
        'user_spam' => false,
    );

    /* Here voters are those who marked comment as spam so dont 
     * get confused that they are favoring the commentator
     */
    $voters = json_decode($comment['spam_voters'], true);

    if (!$voters)
        $voters = array();

    if ($uid && in_array($uid, $voters))
    {
        $return['user_spam'] = true;
    }

    /**
     * Checking if spam counts is exceeding the limit.. 
     */
    if ($comment['spam_votes'] >= config('comment_spam_limit')
            && config('comment_spam_limit'))
    {
        $return['global_spam'] = true;
    }


    $return = apply_filters($return, 'comment_spam_output');


    return $return;
}

/**
 * Function used to make Conditions for our function easy.
 * in most of our functions, we use $cond, a variable that holds
 * condition that is applied on our mysql query. We first check
 * if $cond is empty or not, if not we add AND/OR and then concate 
 * our next condition, this part was very sluggish and this fucntion
 * will solve our problem
 * 
 * @param STRING $condition
 * @param STRING $operation (AND / OR) default is AND
 * @param STRING $cond a variable that holds complete condition by
 * deafult global $cond is used, but if param is given, it will overide
 */
function cond($condition, $operater = 'AND', $var = NULL)
{

    if (!$var)
    {
        global $cond;
    }else
        $cond = $var;

    if ($cond && $cond != " ")
        $cond .= " $operater ";

    $cond .= $condition;

    return $cond;
}

/**
 * CB New Insert function to make dev easy 
 * 
 * @param STRING tbl_name
 * @param ARRAY fields=>values
 */
function cb_insert($tbl, $array)
{
    global $db;

    $fields = array();
    $values = array();


    foreach ($array as $index => $val)
    {
        $fields[] = $index;
        $values[] = $val;
    }

    return $db->insert($tbl, $fields, $values);
}

/**
 * Function used to check weather FFMPEG has Required Modules installed or not
 */
function get_ffmpeg_codecs($data = false)
{
    $req_codecs = array
        ('libxvid' => 'Required for DIVX AVI files',
        'libmp3lame' => 'Required for proper Mp3 Encoding',
        'libfaac' => 'Required for AAC Audio Conversion',
        // 'libfaad'	=> 'Required for AAC Audio Conversion',
        'libx264' => 'Required for x264 video compression and conversion',
        'libtheora' => 'Theora is an open video codec being developed by the Xiph.org',
        'libvorbis' => 'Ogg Vorbis is a new audio compression format',
    );

    if ($data)
        $version = $data;
    else
        $version = shell_output(get_binaries('ffmpeg') . ' -i xxx -acodec copy -vcodec copy -f null /dev/null 2>&1');
    preg_match_all("/enable\-(.*) /Ui", $version, $matches);
    $installed = $matches[1];

    $the_codecs = array();

    foreach ($installed as $inst)
    {
        if (empty($req_codecs[$inst]))
            $the_codecs[$inst]['installed'] = 'yes';
    }

    foreach ($req_codecs as $key => $codec)
    {
        $the_req_codecs[$key] = array();
        $the_req_codecs[$key]['required'] = 'yes';
        $the_req_codecs[$key]['desc'] = $req_codecs[$key];
        if (in_array($key, $installed))
            $the_req_codecs[$key]['installed'] = 'yes';
        else
            $the_req_codecs[$key]['installed'] = 'no';
    }

    $the_codecs = array_merge($the_req_codecs, $the_codecs);
    return $the_codecs;
}

/**
 * Function used to cheack weather MODULE is INSTALLED or NOT
 */
function check_module_path($params)
{
    $rPath = $path = $params['path'];

    if ($path['get_path'])
        $path = get_binaries($path);
    $array = array();
    $result = shell_output($path . " -version");

    if ($result)
    {
        if (strstr($result, 'error') || strstr(($result), 'No such file or directory'))
        {
            $error['error'] = $result;

            if ($params['assign'])
                assign($params['assign'], $error);

            return false;
        }

        if ($params['assign'])
        {
            $array['status'] = 'ok';
            $array['version'] = parse_version($params['path'], $result);

            assign($params['assign'], $array);
        }
        else
        {
            return $result;
        }
    }
    else
    {
        if ($params['assign'])
            assign($params['assign']['error'], "error");
        else
            return false;
    }
}

/**
 * Function used to parse version from info
 */
function parse_version($path, $result)
{
    switch ($path)
    {
        case 'ffmpeg':
            {
                //Gett FFMPEG SVN version
                preg_match("/svn-r([0-9]+)/i", strtolower($result), $matches);
                //pr($matches);
                if (is_numeric(floatval($matches[1])) && $matches[1])
                {
                    return 'Svn ' . $matches[1];
                }
                //Get FFMPEG version
                preg_match("/FFmpeg version ([0-9.]+),/i", strtolower($result), $matches);
                if (is_numeric(floatval($matches[1])) && $matches[1])
                {
                    return $matches[1];
                }

                //Get FFMPEG GIT version
                preg_match("/ffmpeg version n\-([0-9]+)/i", strtolower($result), $matches);

                if (is_numeric(floatval($matches[1])) && $matches[1])
                {
                    return 'Git ' . $matches[1];
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
                preg_match("/flvtool2 ([0-9\.]+)/i", $result, $matches);
                if (is_numeric(floatval($matches[1])))
                {
                    return $matches[1];
                }
                else
                {
                    return false;
                }
            }
            break;
        case 'mp4box':
            {
                preg_match("/version (.*) \(/Ui", $result, $matches);
                //pr($matches);
                if (is_numeric(floatval($matches[1])))
                {
                    return $matches[1];
                }
                else
                {
                    return false;
                }
            }
    }
}

/**
 * Display time and using javascript
 * update it regularly...
 */
function what_time($time, $is_time = true)
{
    if (!$is_time)
        $time = strtotime($time);
   
    $date = date('Y-m-d H:i:s', $time);
    $date = niceTime($date);
    
    $final_date= '<span class="cb_time" data-time="'.date("Y-m-d",$time).'T'.date("H:i:s",$time).'Z" >'.$date.'</span>';
    
    return $final_date;
}



/**
 * register an object to get data later
 * 
 * @param STRING $type
 * @param OBJECT $obj
 */
function register_object($type,$obj)
{
    global ${$obj},$Cbucket;
    
    $theObj = ${$obj};
    
    if($theObj)
    {
        $Cbucket->objects[$type] = array('type'=>$type,'obj'=>$obj);
    }
}

/**
 * Get object
 * 
 * ${$obj}->get($objectId,$type,$conditions=NULL);
 * 
 * @param STRING $type
 * @param INT $obj_id
 * @param STRING $condtion
 */
function get_object($type,$objId,$cond=NULL)
{
    global $Cbucket;
   
    if($Cbucket->objects[$type])
    {
       
        $obj = $Cbucket->objects[$type]['obj'];
        global ${$obj};
        $theObj = ${$obj};

        if(method_exists($theObj,'get'))
        {
            return $theObj->get($objId,$cond);
        }
    }
}


/**
 * Get Content 
 * 
 * @global type $Cbucket
 * @global type $obj
 * @param type $type of content
 * @param type $obj of content
 * @param type $cond if any
 * @return type
 */
function get_content($type,$objContent,$cond=NULL)
{
    
    global $Cbucket;

    if($Cbucket->objects[$type])
    {

        $obj = $Cbucket->objects[$type]['obj'];
        global ${$obj};
        $theObj = ${$obj};

        if(method_exists($theObj,'get_content'))
        {
            return $theObj->get_content($objContent,$cond);
        }
    }
}

/**
 * function used to get content link
 * 
 */
function get_content_link($type,$content,$cond=NULL)
{
    global $Cbucket;
    if($Cbucket->objects[$type])
    {

        $obj = $Cbucket->objects[$type]['obj'];
        global ${$obj};
        $theObj = ${$obj};

        if(method_exists($theObj,'get_link'))
        {
            return $theObj->get_link($content,$cond);
        }
    }
}

/**
 * Cb error
 * @param type $e
 * @throws Exception
 */



function cb_error($e)
{
    e($e);
    throw new Exception($e);
}


/**
 * get client ip
 */
function client_ip()
{
    return $_SERVER['REMOTE_ADDR'];
}


function start_where()
{
    global $Cbucket;
    unset($Cbucket->sql_where);
    $Cbucket->sql_where = '';
}

function add_where($query,$cond="AND")
{
    global $Cbucket;
    if($Cbucket->sql_where)
       $Cbucket->sql_where .= " ".$cond;
    $Cbucket->sql_where .= " ".$query;
    
    return $Cbucket->sql_where;
}

function get_where()
{
    global $Cbucket;
    return $Cbucket->sql_where;
}

function end_where()
{
    global $Cbucket;
    unset($Cbucket->sql_where);
}

//Including videos functions
include("functions_videos.php");
//Including Users Functions
include("functions_users.php");
//Group Functions
include("functions_groups.php");
//Collections Functions
include("functions_collections.php");
//Exif
include('exif_source.php');

include("functions_upload.php");
include("functions_feeds.php");

include("functions_comments.php");
include("functions_filters.php");
include("functions_actions.php");
include("functions_widgets.php");
include("functions_photos.php");
include("functions_forms.php");
include("functions_templates.php");
include("functions_players.php");
?>