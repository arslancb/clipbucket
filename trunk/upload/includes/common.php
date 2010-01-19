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

ob_start();

define("DEVELOPMENT_MODE",TRUE);
define("DEV_INGNORE_SYNTAX",TRUE);

//Setting Cookie Timeout
define('COOKIE_TIMEOUT',315360000); // 10 years
define('GARBAGE_TIMEOUT',COOKIE_TIMEOUT);

//Setting Session Max Life
ini_set('session.gc_maxlifetime', GARBAGE_TIMEOUT);
session_set_cookie_params(COOKIE_TIMEOUT,'/');


//IGNORE CB ERRORS
$ignore_cb_errors = FALSE;

/*$sessdir = '/tmp/'.$_SERVER['HTTP_HOST'];
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
	require_once('classes/actions.class.php');
	require_once('classes/category.class.php');
	require_once('classes/search.class.php');
	require_once('classes/my_queries.class.php');
	require_once('classes/user.class.php');
	require_once('classes/calcdate.class.php');
	require_once('classes/signup.class.php');
	require_once('classes/image.class.php');
	require_once('classes/upload.class.php');
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
	require_once('classes/groups.class.php');
	require_once('classes/video.class.php');
	require_once('classes/player.class.php');
	require_once('classes/cbemail.class.php');
	require_once('classes/pm.class.php');
	require_once('classes/cbpage.class.php');
	require_once 'languages.php';
	
	$pages 		= new pages();	
	$myquery 	= new myquery();
	$userquery 	= new userquery();
	$calcdate	= new CalcDate();
	$signup 	= new signup();	
	$Upload 	= new Upload();
	$cbgroup 	= new CBGroups();
	$adsObj		= new AdsManager();
	$formObj	= new formObj();
	$ClipBucket = $Cbucket	= new ClipBucket();
	$row 		= $myquery->Get_Website_Details();
	$email_data = $myquery->Get_Email_Settings();
	$cbplugin	= new CBPlugin();
	$eh			= new EH();
	$lang_obj	= new language;
	$sess		= new Session();
	$cblog		= new CBLogs();
	$imgObj		= new ResizeImage();
	$cbvideo	= $cbvid = new CBvideo();
	$cbplayer	= new CBPlayer();
	$cbemail	= new CBEmail();
	$cbsearch	= new CBSearch();
	$cbpm		= new cb_pm();
	$cbpage		= new cbpage();

	require 'defined_links.php';
	
	include("clipbucket.php");
	$Cbucket->cbinfo = array("version"=>VERSION,"state"=>STATE,"rev"=>REV,"release_date"=>RELEASED);


//Holds Advertisment IDS that are being Viewed
	$ads_array = array();
// Report all errors

/*define('DEBUG_LEVEL', $row['debug_level']);
if(DEBUG_LEVEL == 1)
{
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
}
elseif(DEBUG_LEVEL == 2)
{
	error_reporting(E_ALL ^ E_NOTICE ^E_DEPRECATED);
    ini_set('display_errors', '1');
}
else
{
 error_reporting(0);
 ini_set('display_errors', '0');
}*/

 //ini_set('display_errors', '1');
 error_reporting(E_ALL ^E_NOTICE);

//Website Details

    define('CB_VERSION', $row['version']);
    define('TITLE',$row['site_title']);
	define('SLOGAN',$row['site_slogan']);
	
	

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
	define('DATE_FORMAT',"d-m-Y");
	
 //Listing Of Videos , Channels
 
 	define('VLISTPP',$row['videos_list_per_page']);				//Video List Per page
	define('VLISTPT',$row['videos_list_per_tab']);				//Video List Per tab
	define('CLISTPP',$row['channels_list_per_page']);			//Channels List Per page
	define('CLISTPT',$row['channels_list_per_tab']);			//Chaneels List Per tab
	define('GLISTPP',$row['groups_list_per_page']);				//Groups List Per page
	define('SLISTPP',$row['search_list_per_page']);				//Search Results List Per page
	define('RVLIST',$row['recently_viewed_limit']);				//Search Results List Per page
	
 //Video Options
 	define('ALLOWED_VDO_CATS',$row['video_categories']);
	define('ALLOWED_CATEGORIES',3);
	
 	define('VIDEO_COMMENT',$row['video_comments']);
	define('VIDEO_RATING',$row['video_rating']);
	define('COMMENT_RATING',$row['comment_rating']);
	define('VIDEO_DOWNLOAD',$row['video_download']);
	define('VIDEO_EMBED',$row['video_embed']);
	

	define('BASEDIR',$Cbucket->BASEDIR);
	if(!file_exists(BASEDIR.'/index.php'))
	die('Basedir is incorrect, please set the correct basedir value in \'config\' table');
	
	
	$baseurl = $row['baseurl'];
	//Removing www. as it effects SEO and updating Config
	$wwwcheck = preg_match('/:\/\/www\./',$baseurl,$matches);
	if(count($matches)>0)
	{
		$baseurl = preg_replace('/:\/\/www\./','://',$baseurl);		
	}			
			
	define('BASEURL',$baseurl);
	
	
	define('TEMPLATEFOLDER','styles');							//Template Folder Name, usually STYLES
	define('STYLES_DIR',BASEDIR.'/'.TEMPLATEFOLDER);
	
// Define Lang Select & Style Select

    define('ALLOW_LANG_SELECT',$row['allow_language_change']);
    define('ALLOW_STYLE_SELECT',$row['allow_template_change']);

	define('FLVPLAYER',$row['player_file']);
    define('SUBTITLE',$row['code_dev']);
	//Javascript Directory Name
	define('ADMINDIR','admin_area');							//Admin Accissble Folder
	define('ADMIN_BASEURL',BASEURL.'/'.ADMINDIR);
	define('MODULEDIR',BASEDIR.'/modules');						//Modules Directory
	
//DIRECT PATHS OF VIDEO FILES
	define('FILES_DIR',BASEDIR.'/files');
	define('VIDEOS_DIR',FILES_DIR.'/videos');
	define('THUMBS_DIR',FILES_DIR.'/thumbs');
	define('ORIGINAL_DIR',FILES_DIR.'/original');
	define('TEMP_DIR',FILES_DIR.'/temp');
	define('CON_DIR',FILES_DIR.'/conversion_queue');
	define('MASS_UPLOAD_DIR',FILES_DIR.'/mass_uploads');
	define('LOGS_DIR',FILES_DIR.'/logs');
	
	define('JS_DIR',BASEDIR.'/js');
	define('JS_URL',BASEURL.'/js');
	
//DIRECT URL OF VIDEO FILES
	define('FILES_URL',BASEURL.'/files');
	define('VIDEOS_URL',FILES_URL.'/videos');
	define('THUMBS_URL',FILES_URL.'/thumbs');
	define('ORIGINAL_URL',FILES_URL.'/original');
	define('TEMP_URL',FILES_URL.'/temp');
	define("PLAYER_DIR",BASEDIR.'/player');
	define("PLAYER_URL",BASEURL.'/player');
	
	
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
	define('PHP_PATH', $row['php_path']);
	
	//Defining Plugin Directory
	define('PLUG_DIR',BASEDIR.'/plugins');
	define('PLUG_URL',BASEURL.'/plugins');
	
	define('MAX_COMMENT_CHR',$Cbucket->configs['max_comment_chr']);
	define('USER_COMMENT_OWN',$Cbucket->configs['user_comment_own']);
	
	
	//Defining Category Thumbs directory
	define('CAT_THUMB_DIR',BASEDIR.'/images/category_thumbs');
	define('CAT_THUMB_URL',BASEURL.'/images/category_thumbs');
	
	//Defining Group Thumbs directory
	define('GP_THUMB_DIR',BASEDIR.'/images/groups_thumbs');
	define('GP_THUMB_URL',BASEURL.'/images/groups_thumbs');	
	
	//TOPIC ICON DIR
	define('TOPIC_ICON_DIR',BASEDIR.'/images/icons/topic_icons');
	define('TOPIC_ICON_URL',BASEURL.'/images/icons/topic_icons');	

	include 'functions.php';
	include 'plugin.functions.php';
	include 'plugins_functions.php';
	require BASEDIR.'/includes/templatelib/Template.class.php';
	require BASEDIR.'/includes/classes/template.class.php';
	require BASEDIR.'/includes/classes/objects.class.php';
	
	require BASEDIR.'/includes/active.php';
	
	$cbtpl = new CBTemplate();
	$cbobjects = new CBObjects();
	$swfobj		= new SWFObject();
	//Initializng Userquery class
	$userquery->init();
	$cbpm->init();
    $thisurl = curPageURL();
    Assign('THIS_URL', $thisurl);
	
 	//Assigning Smarty Tags & Values
    Assign('CB_VERSION',CB_VERSION);
    Assign('FFMPEG_FLVTOOLS_BINARY',FFMPEG_FLVTOOLS_BINARY);
    Assign('FFMPEG_MPLAYER_BINARY',FFMPEG_MPLAYER_BINARY);
    Assign('PHP_PATH',PHP_PATH);
    Assign('FFMPEG_BINARY',FFMPEG_BINARY);
    Assign('FFMPEG_MENCODER_BINARY',FFMPEG_MENCODER_BINARY);
    Assign('js',JS_URL);
	Assign('title',TITLE);
	Assign('slogan',SLOGAN);	
	Assign('flvplayer',FLVPLAYER);
	Assign('avatardir',BASEURL.'/images/avatars');
	Assign('whatis',$row['whatis']);
	Assign('category_thumbs',CAT_THUMB_URL);
	Assign('gp_thumbs_url',GP_THUMB_URL);
	Assign('video_thumbs',THUMBS_URL);
	//Assign('ads',$ads);

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
	

//Assign Player Div Id
	Assign('player_div_id',$row['player_div_id']);

//Asigning Page
	Assign('page',PAGE);
	
//Add Modules
require('modules.php');	


//Checking Website Template
$Cbucket->set_the_template();

/*
REGISTER OBJECTS FOR SMARTY
*/

$Smarty->assign_by_ref('pages', $pages);
$Smarty->assign_by_ref('myquery', $myquery);
$Smarty->assign_by_ref('userquery', $userquery);
$Smarty->assign_by_ref('signup', $signup);
$Smarty->assign_by_ref('Upload', $Upload);
$Smarty->assign_by_ref('cbgroup', $cbgroup);
$Smarty->assign_by_ref('db', $db);
$Smarty->assign_by_ref('adsObj', $adsObj);
$Smarty->assign_by_ref('formObj', $formObj);
$Smarty->assign_by_ref('Cbucket', $Cbucket);$Smarty->assign_by_ref('ClipBucket', $Cbucket);
$Smarty->assign_by_ref('eh', $eh);
$Smarty->assign_by_ref('lang_obj', $lang_obj);
$Smarty->assign_by_ref('cbvid', $cbvid);
$Smarty->assign_by_ref('cbtpl',$cbtpl);
$Smarty->assign_by_ref('cbobjects',$cbobjects);
$Smarty->assign_by_ref('cbplayer',$cbplayer);
$Smarty->assign_by_ref('cbsearch',$cbsearch);
$Smarty->assign_by_ref('cbpm',$cbpm);
$Smarty->assign_by_ref('cbpage',$cbpage);
$Smarty->assign_by_ref('cbemail',$cbemail);

/*
REGISERTING FUNCTION FOR SMARTY TEMPLATES
*/


function show_video_rating($params){ global $cbvid; return $cbvid->show_video_rating($params); }

$Smarty->register_function('AD','getAd');
$Smarty->register_function('get_thumb','getSmartyThumb');
$Smarty->register_function('getThumb','getSmartyThumb');
$Smarty->register_function('videoLink','videoSmartyLink');
$Smarty->register_function('group_link','group_link');
$Smarty->register_function('show_rating','show_rating');
$Smarty->register_function('ANCHOR','ANCHOR');
$Smarty->register_function('FUNC','FUNC');
$Smarty->register_function('avatar','avatar');
$Smarty->register_function('load_form','load_form');
$Smarty->register_function('get_all_video_files',get_all_video_files_smarty);
$Smarty->register_function('input_value','input_value');
$Smarty->register_function('userid','userid');
$Smarty->register_function('FlashPlayer','flashPlayer');
$Smarty->register_function('HQFlashPlayer','HQflashPlayer');
$Smarty->register_function('link','cblink');
$Smarty->register_function('show_share_form','show_share_form');
$Smarty->register_function('show_flag_form','show_flag_form');
$Smarty->register_function('show_playlist_form','show_playlist_form');
$Smarty->register_function('lang','smarty_lang');
$Smarty->register_function('get_videos','get_videos');
$Smarty->register_function('get_users','get_users');
$Smarty->register_function('get_groups','get_groups');
$Smarty->register_function('private_message','private_message');
$Smarty->register_function('show_video_rating','show_video_rating');
$Smarty->register_function('load_captcha','load_captcha');
$Smarty->register_function('cbtitle','cbtitle');
$Smarty->register_function('head_menu','head_menu');
$Smarty->register_function('foot_menu','foot_menu');
$Smarty->register_function('include_header','include_header');
$Smarty->register_function('include_js','include_js');
$Smarty->register_function('get_binaries','get_binaries');
$Smarty->register_function('check_module_path','check_module_path');
$Smarty->register_function('rss_feeds','rss_feeds');

$Smarty->register_modifier('SetTime','SetTime');
$Smarty->register_modifier('getname','getname');
$Smarty->register_modifier('getext','getext');
$Smarty->register_modifier('form_val','form_val');
$Smarty->register_modifier('get_from_val','get_from_val');
$Smarty->register_modifier('post_form_val','post_form_val');
$Smarty->register_modifier('request_form_val','request_form_val');
$Smarty->register_modifier('get_thumb_num','get_thumb_num');
$Smarty->register_modifier('ad','ad');
$Smarty->register_modifier('get_user_level','get_user_level');
$Smarty->register_modifier('is_online','is_online');
$Smarty->register_modifier('get_age','get_age');
$Smarty->register_modifier('outgoing_link','outgoing_link');
$Smarty->register_modifier('nicetime','nicetime');
$Smarty->register_modifier('country','get_country');
$Smarty->register_modifier('cbsearch',new cbsearch());
$Smarty->register_modifier('flag_type','flag_type');
$Smarty->register_modifier('get_username','get_username');


/*
 * 123465798
 */
register_action_remove_video('remove_video_thumbs');
register_action_remove_video('remove_video_log');
register_action_remove_video('remove_video_files');


include('admin.functions.php');
//error_reporting(E_ALL ^E_NOTICE ^E_DEPRECATED);



//Removing www. as it effects SEO and updating Config
$wwwcheck = preg_match('/:\/\/www\./',$baseurl,$matches);
if(count($matches)>0)
{
	$baseurl = preg_replace('/:\/\/www\./','://',$baseurl);
	$myquery->Set_Website_Details('baseurl',$baseurl);
}
?>