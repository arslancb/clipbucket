<?php

/*
 ********************************************************************
 * @Software    ClipBucket
 * @Author      ArslanHassan
 * @copyright	Copyright (c) 2007-2008 {@link http://www.clip-bucket.com}
 * @license		http://www.clip-bucket.com
 * @version		v1.7.1
 * @since 		2007-10-15
 * @License		CBLA
 *******************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User 
 License First and Agree its
 Terms of use at http://clip-bucket.com/cbla
 *******************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 *******************************************************************
 */

ob_start('ob_gzhandler');

//Setting Cookie Timeout
define('COOKIE_TIMEOUT',315360000); // 10 years
define('GARBAGE_TIMEOUT',COOKIE_TIMEOUT);

//Setting Session Max Life
ini_set('session.gc_maxlifetime', GARBAGE_TIMEOUT);
session_set_cookie_params(COOKIE_TIMEOUT,'/');

/*
$sessdir = '/tmp/'.$_SERVER['HTTP_HOST'];
if (!is_dir($sessdir)) { mkdir($sessdir, 0777); }
ini_set('session.save_path', $sessdir);*/

session_start();

// Check Installation Directory
if(file_exists(dirname(__FILE__).'/../install/isinstall.php')){
	header('location:../install');
}
//Required Files

	require_once('dbconnect.php');
	require_once('classes/pages.class.php');
	require_once('classes/my_queries.class.php');
	require_once('classes/user.class.php');
	require_once('classes/calcdate.class.php');
	require_once('classes/signup.class.php');
	require_once('classes/image.class.php');
	require_once('classes/upload.class.php');
	require_once('classes/groups.class.php');
	require_once('classes/stats.class.php');
	require_once('classes/ads.class.php');
	require_once('classes/form.class.php');
	require_once('classes/ClipBucket.class.php');
	require_once('classes/plugin.class.php');
	require_once('classes/errorhandler.class.php');
	require_once('classes/lang.class.php');
	require_once('classes/session.class.php');
	require_once('classes/log.class.php');
	require_once('classes/swfObj.class.php');
	require_once('classes/image.class.php');
	require_once 'languages.php';
			
	$pages 		= new pages();	
	$myquery 	= new myquery();
	$userquery 	= new userquery();
	$calcdate	= new CalcDate();
	$signup 	= new signup();	
	$Upload 	= new Upload();
	$groups 	= new groups();
	$stats 		= new stats();
	$adsObj		= new AdsManager();
	$formObj	= new formObj();
	$ClipBucket = $Cbucket	= new ClipBucket();
	$row 		= $myquery->Get_Website_Details();
	$email_data = $myquery->Get_Email_Settings();
	$cbplugin	= new CBPlugin();
	$eh			= new EH();
	$lang_obj	= new language;
	$sess	= new Session();
	$cblog		= new CBLogs();
	$swfobj		= new SWFObject();
	$imgObj		= new ResizeImage();
	

//Initializng Userquery class
$userquery->init();


//Holds Advertisment IDS that are being Viewed
	$ads_array = array();
// Report all errors

define('DEBUG_LEVEL', $row['debug_level']);
if(DEBUG_LEVEL == 1)
{
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
elseif(DEBUG_LEVEL == 2)
{
	error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '1');
}
else
{
 error_reporting(0);
 ini_set('display_errors', '0');
}

error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', '1');

//Website Details

    define('CB_VERSION', $row['version']);
    define('TITLE',$row['site_title']);
	define('SLOGAN',$row['site_slogan']);
	$sitelang = @$_COOKIE['sitelang'];
	
	function ValidLang($sitelang){
	global $languages;
		if(empty($languages[$sitelang])) {
		return false;
		}else {
		return true;
		}
	}
	
    if (!isset($sitelang) || !ValidLang($sitelang))
    {
    define('LANG',$row['default_site_lang']);
    setcookie('sitelang', $row['default_site_lang'], time()+315360000, '/');
    }
    else
    {
    define('LANG',$sitelang);
    }
	

 //Seo URLS
 
 	define('SEO',$row['seo']); //Set yes / no

 //Registration & Email Settings
 
 	define('EMAIL_VERIFICATION',$row['email_verification']);	
	define('ALLOW_REGISTERATION',$row['allow_registeration']);
	define('WEBSITE_EMAIL',$email_data['website_email']);
	define('SUPPORT_EMAIL',$email_data['support_email']);
	define('WELCOME_EMAIL',$email_data['welcome_email']);
	@define('VIDEO_REQUIRE_LOGIN',$row['video_require_login']);
	define('ACTIVATION',$row['activation']);
	
 //Listing Of Videos , Channels
 
 	define('VLISTPP',$row['videos_list_per_page']);				//Video List Per page
	define('VLISTPT',$row['videos_list_per_tab']);				//Video List Per tab
	define('CLISTPP',$row['channels_list_per_page']);			//Channels List Per page
	define('CLISTPT',$row['channels_list_per_tab']);			//Chaneels List Per tab
	define('GLISTPP',$row['groups_list_per_page']);				//Groups List Per page
	define('SLISTPP',$row['search_list_per_page']);				//Search Results List Per page
	define('RVLIST',$row['recently_viewed_limit']);				//Search Results List Per page
	
 //Video Options
 
 	define('VIDEO_COMMENT',$row['video_comments']);
	define('VIDEO_RATING',$row['video_rating']);
	define('COMMENT_RATING',$row['comment_rating']);
	define('VIDEO_DOWNLOAD',$row['video_download']);
	define('VIDEO_EMBED',$row['video_embed']);
	

	define('BASEDIR',$Cbucket->BASEDIR);
	if(!file_exists(BASEDIR.'/index.php'))
	die('Basedir is incorrect, please set the correct basedir value in \'config\' table');
	
	define('BASEURL',$row['baseurl']);							//Direct Path To Script ie http://yourwebsite.com/subdi
	
	
	define('TEMPLATEFOLDER','styles');							//Template Folder Name, usually STYLES
	
	if($row['allow_template_change'] == 1){
		$sitestyle = @$_COOKIE['sitestyle'];
		if (!$sitestyle || !$myquery->IsTemplate($sitestyle))
		{
		define('TEMPLATE',$row['template_dir']);
		setcookie('sitestyle', $row['template_dir'], time()+315360000, '/');
		}
		else
		{
		define('TEMPLATE',$sitestyle);
		}
	}else{
		define('TEMPLATE',$row['template_dir']);
	}

// Define Lang Select & Style Select

    define('ALLOW_LANG_SELECT',$row['allow_language_change']);
    define('ALLOW_STYLE_SELECT',$row['allow_template_change']);

	define('FLVPLAYER',$row['player_file']);
    define('SUBTITLE',$row['code_dev']);
    define('JSDIR','js');										//Javascript Directory Name
	define('ADMINDIR','admin_area');							//Admin Accissble Folder
	define('MODULEDIR',BASEDIR.'/modules');						//Modules Directory
	
//DIRECT PATHS OF VIDEO FILES
	define('FILES_DIR',BASEDIR.'/files');
	define('VIDEOS_DIR',FILES_DIR.'/videos');
	define('THUMBS_DIR',FILES_DIR.'/thumbs');
	define('ORIGINAL_DIR',FILES_DIR.'/original');
	define('TEMP_DIR',FILES_DIR.'/temp');
	define('CON_DIR',FILES_DIR.'/conversion_queue');

//DIRECT URL OF VIDEO FILES
	define('FILES_URL',BASEURL.'/files');
	define('VIDEOS_URL',FILES_URL.'/videos');
	define('THUMBS_URL',FILES_URL.'/thumbs');
	define('ORIGINAL_URL',FILES_URL.'/original');
	define('TEMP_URL',FILES_URL.'/temp');
	
 //Required Settings For Video Converion
 
 	define('VBRATE', $row['vbrate']);
	define('SRATE', $row['srate']);
	define('SBRATE', $row['sbrate']);
	define('R_HEIGHT', $row['r_height']);
	define('R_WIDTH', $row['r_width']);
	define('RESIZE', $row['resize']);
	define('KEEP_ORIGINAL', $row['keep_original']);
	define('MAX_UPLOAD_SIZE', $row['max_upload_size']);
	define('THUMB_HEIGHT', $row['thumb_height']);
	define('THUMB_WIDTH', $row['thumb_width']);
	define('FFMPEG_TYPE', $row['ffmpeg_type']);
	if(FFMPEG_TYPE == 's'){
		define('FFMPEG_BINARY', MODULEDIR.'/encoders/ffmpeg');
	}else{
		define('FFMPEG_BINARY', $row['ffmpegpath']);
	}
	define('FFMPEG_FLVTOOLS_BINARY', $row['flvpath']);
	define('PHP_PATH', $row['php_path']);
	define('FFMPEG_MENCODER_BINARY', $row['mencoderpath']);
	define('FFMPEG_MPLAYER_BINARY', $row['mplayerpath']);
	
	//Defining Plugin Directory
	define('PLUG_DIR',BASEDIR.'/plugins');
	define('PLUG_URL',BASEURL.'/plugins');
	
	
	define('ALLOWED_CATEGORIES',3);
	
	define('MAX_COMMENT_CHR',$Cbucket->configs['max_comment_chr']);
	define('USER_COMMENT_OWN',$Cbucket->configs['user_comment_own']);
	
 	//Assigning Smarty Tags & Values
	include 'functions.php';
	include 'plugins_functions.php';
	require BASEDIR.'/includes/templatelib/Template.class.php';
	require BASEDIR.'/includes/classes/template.class.php';
	require BASEDIR.'/includes/active.php';
	require BASEDIR.'/includes/defined_links.php';
	require_once('email_templates/template_writer.php');
	include(BASEDIR.'/lang/'.LANG.'/lang.php');

    $is_admin = $userquery->admin_check();
    if ($is_admin == 1)
    {
    Assign('is_admin',$is_admin);
    }

    $thisurl = curPageURL();
    Assign('THIS_URL', $thisurl);

    Assign('CB_VERSION',CB_VERSION);
    Assign('FFMPEG_FLVTOOLS_BINARY',FFMPEG_FLVTOOLS_BINARY);
    Assign('FFMPEG_MPLAYER_BINARY',FFMPEG_MPLAYER_BINARY);
    Assign('PHP_PATH',PHP_PATH);
    Assign('FFMPEG_BINARY',FFMPEG_BINARY);
    Assign('FFMPEG_MENCODER_BINARY',FFMPEG_MENCODER_BINARY);
    Assign('js',BASEURL.'/'.JSDIR);
	Assign('title',TITLE);
	Assign('slogan',SLOGAN);	
	Assign('flvplayer',FLVPLAYER);
	Assign('avatardir',BASEURL.'/images/avatars');
	Assign('whatis',$row['whatis']);
	Assign('category_thumbs',BASEURL.'/images/category_thumbs');
	Assign('video_thumbs',THUMBS_URL);
	//Assign('ads',$ads);
	Assign('meta_keywords',$row['keywords']);
	Assign('meta_description',$row['description']);
	Assign('email_verification',EMAIL_VERIFICATION);
	Assign('group_thumb',BASEURL.'/images/groups_thumbs');
	Assign('bg_dir',BASEURL.'/images/backgrounds');
	Assign('captcha_type',$row['captcha_type']);
	Assign('languages',$languages);
	
	Assign('module_dir',MODULEDIR);
	
	Assign('VIDEOS_URL',VIDEOS_URL);
	Assign('THUMBS_URL',THUMBS_URL);
	Assign('PLUG_URL',BASEURL.'/plugins');
	
//Remote and Embed
	Assign('remoteUpload',$row['remoteUpload']);
	Assign('embedUpload',$row['embedUpload']);
	
//Video Options
 	Assign('video_comment',$row['video_comments']);
	Assign('video_rating',$row['video_rating']);
	Assign('comment_rating',$row['comment_rating']);
	Assign('video_download',$row['video_download']);
	Assign('video_embed',$row['video_embed']);
	

//Assign Lang
	$LANG = $lang_obj->lang_phrases();
	Assign('LANG',$LANG);
	Assign('langf',LANG);
    Assign('lang_count',count($languages));

//Assign Footer
	
	Assign('lang_changer',		$row['allow_language_change']);
	Assign('template_changer',	$row['allow_template_change']);
	Assign('current_template',	TEMPLATE);

//Getting Template List (admin area)
	$sql = "SELECT * from template";
	$rs = $db->Execute($sql);
	$templates = $rs->getrows();

    // sort user template list (fwhite / February 05, 2009)
    foreach ($templates as $key => $temp_row) {
    $temp_name[$key]  = $temp_row[1];
    }

    // Sort the data with name ascending
    // Add data as the last parameter, to sort by the common key
    array_multisort($temp_name, SORT_ASC, $templates);
	Assign('templates', $templates);

//Assign Player Div Id
	Assign('player_div_id',$row['player_div_id']);

//Asigning Page
	Assign('page',PAGE);
	
//Add Modules
require('modules.php');	


//Checking what permissions do logged in user have
if(user_id())
{
	$userquery->permission = $userquery->get_user_level(userid());
}	

/*
REGISTER OBJECTS FOR SMARTY
*/

$Smarty->assign_by_ref('pages', $pages);
$Smarty->assign_by_ref('myquery', $myquery);
$Smarty->assign_by_ref('userquery', $userquery);
$Smarty->assign_by_ref('signup', $signup);
$Smarty->assign_by_ref('Upload', $Upload);
$Smarty->assign_by_ref('groupsObj', new groups());
$Smarty->assign_by_ref('Stats', $stats);
$Smarty->assign_by_ref('db', $db);
$Smarty->assign_by_ref('adsObj', $adsObj);
$Smarty->assign_by_ref('formObj', $formObj);
$Smarty->assign_by_ref('Cbucket', $Cbucket);$Smarty->assign_by_ref('ClipBucket', $Cbucket);
$Smarty->assign_by_ref('eh', $eh);
$Smarty->assign_by_ref('lang_obj', $lang_obj);


/*
REGISERTING FUNCTION FOR SMARTY TEMPLATES
*/


$Smarty->register_function('AD','getAd');
$Smarty->register_function('get_thumb','getSmartyThumb');
$Smarty->register_function('getThumb','getSmartyThumb');
$Smarty->register_function('videoLink','videoSmartyLink');
$Smarty->register_function('pullRating','pullSmartyRating');
$Smarty->register_function('ANCHOR','ANCHOR');
$Smarty->register_function('FUNC','FUNC');
$Smarty->register_modifier('SetTime','SetTime');
$Smarty->register_modifier('getname','getname');
$Smarty->register_modifier('getext','getext');
$Smarty->register_modifier('form_val','form_val');
$Smarty->register_function('avatar','avatar');
$Smarty->register_function('load_form','load_form');
$Smarty->register_function('get_all_video_files',get_all_video_files_smarty);
$Smarty->register_function('input_value','input_value');

$Smarty->register_function('userid','userid');
$Smarty->register_function('FlashPlayer','flashPlayer');


$Smarty->register_modifier('get_thumb_num','get_thumb_num');
?>