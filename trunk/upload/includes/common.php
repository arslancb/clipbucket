<?php
/*
 ********************************************************************
 * @Software    ClipBucket
 * @Author      Arslan Hassan, et al
 * @copyright	Copyright (c) 2007 - 2010 {@link http://www.clip-bucket.com}
 * @license		http://www.clip-bucket.com
 * @version		v2
 * $Id: common.php 729 2011-09-20 14:04:11Z ahzulfi $
 * @since 		2007-10-15
 * @License		Attribution Assurance License
 *******************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User 
 License First and Agree its
 Terms of use at http://www.opensource.org/licenses/attribution.php
 *******************************************************************
 Copyright (c) 2007 - 2010 Clip-Bucket.com. All rights reserved.
 *******************************************************************
 */

ob_start();

define('IN_CLIPBUCKET',true);
define("DEVELOPMENT_MODE",FALSE);
define("DEV_INGNORE_SYNTAX",TRUE);

//Setting Cookie Timeout
define('COOKIE_TIMEOUT',86400*1); // 1
define('GARBAGE_TIMEOUT',COOKIE_TIMEOUT);
define("REMBER_DAYS",7);


if(!@$in_bg_cron)
{
	//Setting Session Max Life
	ini_set('session.gc_maxlifetime', GARBAGE_TIMEOUT);
	session_set_cookie_params(COOKIE_TIMEOUT,'/');
	
	
	//IGNORE CB ERRORS
	$ignore_cb_errors = FALSE;
	
	/*$sessdir = '/tmp/'.$_SERVER['HTTP_HOST'];
	if (!is_dir($sessdir)) { mkdir($sessdir, 0777); }
	ini_set('session.save_path', $sessdir);*/
	
	session_start();
}

    //Required Files

    require_once('functions.php');
    check_install('before');
	require_once('dbconnect.php');
	require_once('classes/ClipBucket.class.php');
	
	require_once('classes/my_queries.class.php');
	require_once('classes/actions.class.php');
	require_once('classes/category.class.php');
	require_once('classes/user.class.php');
	require_once('classes/lang.class.php');
	require_once('classes/pages.class.php');
	
	
	$myquery 	= new myquery();
	$row 		= $myquery->Get_Website_Details();
	
	
	define('DEBUG_LEVEL', 2);

	switch(DEBUG_LEVEL)
	{
		case 0:
		{
			error_reporting(0);
			ini_set('display_errors', '0');
		}
		break;
		case 1:
		{
			error_reporting(E_ALL);
			ini_set('display_errors', '1');
		}
		break;
		
		case 2:
		default:
		{
			
			if(phpversion() >= '5.3.0')
			{
				error_reporting(E_ALL ^E_NOTICE ^E_DEPRECATED);
				ini_set('display_errors', '1');
			}
			else
			{
				error_reporting(E_ALL ^E_NOTICE);
				ini_set('display_errors', '1');
			}
		}
	}


	$pages 		= new pages();	
	$ClipBucket = $Cbucket	= new ClipBucket();
	
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
			
	//define('BASEURL',baseurl(BACK_END));
	$clean_base = false;
	if(defined("CLEAN_BASEURL"))
		$clean_base = CLEAN_BASEURL;
	
	//define('BASEURL',$pages->GetBaseUrl($clean_base));
	define('BASEURL',$baseurl);
	
	$userquery 	= new userquery();
	$lang_obj	= new language;
		
	
	//Setting Time Zone date_default_timezone_set()
	
	require_once('classes/search.class.php');
	
	
	require_once('classes/calcdate.class.php');
	require_once('classes/signup.class.php');
	require_once('classes/image.class.php');
	require_once('classes/upload.class.php');
	require_once('classes/ads.class.php');
	require_once('classes/form.class.php');
	require_once('classes/plugin.class.php');
	require_once('classes/errorhandler.class.php');
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
	require_once('classes/reindex.class.php');
	require_once('classes/collections.class.php');
	require_once('classes/photos.class.php');
	require_once('classes/menuhandler.class.php');
	require_once('classes/cbfeeds.class.php');
	
	//Adding Gravatar
	require_once('classes/gravatar.class.php');

	require_once 'languages.php';
	
	$lang_obj->init();
	$LANG = $lang_obj->lang_phrases('file');

	
	$calcdate	= new CalcDate();
	$signup 	= new signup();	
	$Upload 	= new Upload();
	$cbgroup 	= new CBGroups();
	$adsObj		= new AdsManager();
	$formObj	= new formObj();
	
	
	$cbplugin	= new CBPlugin();
	$eh			= new EH();
	
	$sess		= new Session();
	$cblog		= new CBLogs();
	$imgObj		= new ResizeImage();
	$cbvideo	= $cbvid = new CBvideo();
	$cbplayer	= new CBPlayer();
	$cbemail	= new CBEmail();
	$cbsearch	= new CBSearch();
	$cbpm		= new cb_pm();
	$cbpage		= new cbpage();
	$cbindex	= new CBreindex();
	$cbcollection = new Collections();
	$cbphoto    = new CBPhotos();
	
	$cbfeeds 	= new cbfeeds();
//	$cbmenu		= new MenuHandler();
	check_install('after');
	require 'defined_links.php';
	
	@include("clipbucket.php");
	$Cbucket->cbinfo = array("version"=>VERSION,"state"=>STATE,"rev"=>REV,"release_date"=>RELEASED);


//Holds Advertisment IDS that are being Viewed
	$ads_array = array();




	if(phpversion() < '5.2.0')
	{
		require_once($Cbucket->BASEDIR.'/includes/classes/Services_JSON.php');
		$json = new Services_JSON();
	}

//Website Details

    define('CB_VERSION', $row['version']);
    define('TITLE',$row['site_title']);
	define('SLOGAN',$row['site_slogan']);
	
	

 //Seo URLS
 
 	define('SEO',$row['seo']); //Set yes / no

 //Registration & Email Settings
 
 	define('EMAIL_VERIFICATION',$row['email_verification']);	
	define('ALLOW_REG',$row['allow_registration']);
	define('WEBSITE_EMAIL',$row['website_email']);
	define('SUPPORT_EMAIL',$row['support_email']);
	define('WELCOME_EMAIL',$row['welcome_email']);
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

 //Defining Photo Limits
	define('MAINPLIST',$row['photo_main_list']);
	define('HOMEPLIST',$row['photo_home_tabs']);
	define('SEARCHPLIST',$row['photo_search_result']);
	define('CHANNELPLIST',$row['photo_channel_page']);
	define('USERPLIST',$row['photo_user_photos']);
	define('UFAVPLIST',$row['photo_user_favorites']);
	define('OTHERPLIST',$row['photo_other_limit']);

 //Defining Collection Limits
	define('COLLPP',$row['collection_per_page']);
	define('COLLHP',$row['collection_home_page']);
	define('COLLIP',$row['collection_items_page']);
	define('COLLSP',$row['collection_search_result']);
	define('COLLCP',$row['collection_channel_page']);
	define('COLLUCP',$row['collection_user_collections']);
	define('COLLUFP',$row['collection_user_favorites']);
		
 //Video Options	
 	define('VIDEO_COMMENT',$row['video_comments']);
	define('VIDEO_RATING',$row['video_rating']);
	define('COMMENT_RATING',$row['comment_rating']);
	define('VIDEO_DOWNLOAD',$row['video_download']);
	define('VIDEO_EMBED',$row['video_embed']);
	

	
	
	
	define('TEMPLATEFOLDER','styles');							//Template Folder Name, usually STYLES
	define('STYLES_DIR',BASEDIR.'/'.TEMPLATEFOLDER);
	
// Define Lang Select & Style Select

    define('ALLOW_LANG_SELECT',$row['allow_language_change']);
    define('ALLOW_STYLE_SELECT',$row['allow_template_change']);

	define('FLVPLAYER',$row['player_file']);
    define('SUBTITLE',$row['code_dev']);
	//Javascript Directory Name
	define('ADMINDIR','admin_area');
	define('ADMINBASEDIR',BASEDIR.'/admin_area');							//Admin Accissble Folder
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
	
	define("USER_THUMBS_DIR",BASEDIR.'/images/avatars');
	define("USER_BG_DIR",BASEDIR.'/images/backgrounds');
	
	define("ICONS_URL",BASEURL.'/images/icons');
	
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
	
	define("USER_THUMBS_URL",BASEURL.'/images/avatars');
	define("USER_BG_URL",BASEURL.'/images/backgrounds');
	
 //Required Settings For Video Conversion
 
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

	//COLLECTIONS ICON DIR
	define('COLLECT_THUMBS_DIR',BASEDIR.'/images/collection_thumbs');
	define('COLLECT_THUMBS_URL',BASEURL.'/images/collection_thumbs');
	
	//PHOTOS DETAILS	
	define('PHOTOS_DIR',FILES_DIR."/photos");
	define('PHOTOS_URL',FILES_URL."/photos");
	
	//ADVANCE CACHING
	define('CACHE_DIR',BASEDIR.'/cache');
	define('COMM_CACHE_DIR',CACHE_DIR.'/comments');
	define('COMM_CACHE_TIME',1000) ; //in seconds
	
	//User Feeds
	define("USER_FEEDS_DIR",CACHE_DIR.'/userfeeds');
	
	//Number of activity feeds to display on channel page
	define("USER_ACTIVITY_FEEDS_LIMIT",15);
	
	//SETTING PHOTO SETTING
	$cbphoto->thumb_width = $row['photo_thumb_width'];
	$cbphoto->thumb_height = $row['photo_thumb_height'];
	$cbphoto->mid_width = $row['photo_med_width'];
	$cbphoto->mid_height = $row['photo_med_height'];
	$cbphoto->lar_width = $row['photo_lar_width'];
	$cbphoto->cropping = $row['photo_crop'];
	$cbphoto->position = $row['watermark_placement'];	

	//Enable youtube videos
	define("YOUTUBE_ENABLED",$row['youtube_enabled']);
	
	define("EMBED_VDO_WIDTH",$row['embed_player_width']);
	define("EMBED_VDO_HEIGHT",$row['embed_player_height']);
	

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
	$cbvideo->init();
	$cbpm->init();
	$cbphoto->init_photos();
    $thisurl = curPageURL();
	//Setting Up Group Class
	$cbgroup->gp_thumb_width = config('grp_thumb_width');
	$cbgroup->gp_thumb_height = config('grp_thumb_height');
	
    Assign('THIS_URL', $thisurl);
	
	define("ALLOWED_GROUP_CATEGORIES",$row['grp_categories']);
	define('ALLOWED_VDO_CATS',$row['video_categories']);
	define('ALLOWED_CATEGORIES',3);
	
	if($Cbucket->LatestAdminMenu())
	$Cbucket->AdminMenu = array_merge($Cbucket->LatestAdminMenu(),$Cbucket->AdminMenu);
	
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
	
	assign('icons_url',ICONS_URL);
	
	
	
	
	$ClipBucket->upload_opt_list = array
	(
	 'file_upload_div'	=>	array(
						  'title'		=>	lang('upload_file'),
						  'func_class'	=> 	'Upload',
						  'load_func'	=>	'load_upload_form',
						  ),
	 'remote_upload_div' => array(
							  'title'	=> lang('remote_upload'),
							  'func_class' => 'Upload',
							  'load_func' => 'load_remote_upload_form',
							  )
	 );
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
$Smarty->assign_by_ref('cbcollection',$cbcollection);
$Smarty->assign_by_ref('cbphoto',$cbphoto);
$Smarty->assign_by_ref('cbfeeds',$cbfeeds);
//$Smarty->assign_by_ref('cbmenu',$cbmenu);
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
$Smarty->register_function('show_collection_form','show_collection_form');
$Smarty->register_function('lang','smarty_lang');
$Smarty->register_function('get_videos','get_videos');
$Smarty->register_function('get_users','get_users');
$Smarty->register_function('get_groups','get_groups');
$Smarty->register_function('get_photos','get_photos');
$Smarty->register_function('get_collections','get_collections');
$Smarty->register_function('private_message','private_message');
$Smarty->register_function('show_video_rating','show_video_rating');
$Smarty->register_function('load_captcha','load_captcha');
$Smarty->register_function('cbtitle','cbtitle');
$Smarty->register_function('head_menu','head_menu');
$Smarty->register_function('foot_menu','foot_menu');
$Smarty->register_function('include_header','include_header');
$Smarty->register_function('include_template_file','include_template_file');
$Smarty->register_function('include_js','include_js');
$Smarty->register_function('get_binaries','get_binaries');
$Smarty->register_function('check_module_path','check_module_path');
$Smarty->register_function('rss_feeds','rss_feeds');
$Smarty->register_function('website_logo','website_logo');
$Smarty->register_function('get_photo','get_photo');
$Smarty->register_function('uploadButton','upload_photo_button');
$Smarty->register_function('embedCodes','photo_embed_codes');
$Smarty->register_function('DownloadButtonP','photo_download_button');
$Smarty->register_function('loadPhotoUploadForm','loadPhotoUploadForm');
$Smarty->register_function('cbCategories','getSmartyCategoryList');
$Smarty->register_function('getComments','getSmartyComments');
$Smarty->register_function('fb_embed_video','fb_embed_video');
$Smarty->register_function('cbMenu','cbMenu');

$Smarty->register_function('makeGroupAdmin','makeGroupAdmin');
$Smarty->register_function('removeGroupAdmin','removeGroupAdmin');
$Smarty->register_function('isGroupAdmin','isGroupAdmin');

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
$Smarty->register_modifier('formatfilesize','formatfilesize');
$Smarty->register_modifier('getWidth','getWidth');
$Smarty->register_modifier('getHeight','getHeight');
$Smarty->register_modifier('get_collection_name','get_collection_name');
$Smarty->register_modifier('json_decode','jd');
$Smarty->register_modifier('getGroupPrivacy','getGroupPrivacy');

assign('updateEmbedCode','updateEmbed');
/*
 * Registering Video Remove Functions
 */
register_action_remove_video('remove_video_thumbs');
register_action_remove_video('remove_video_log');
register_action_remove_video('remove_video_files');


include('admin.functions.php');
//error_reporting(E_ALL ^E_NOTICE ^E_DEPRECATED);


////Removing www. as it effects SEO and updating Config
//$wwwcheck = preg_match('/:\/\/www\./',$baseurl,$matches);
//if(count($matches)>0)
//{
//	$baseurl = preg_replace('/:\/\/www\./','://',$baseurl);
//	$myquery->Set_Website_Details('baseurl',$baseurl);
//}

	//Other settings
	define("SEND_COMMENT_NOTIFICATION",config("send_comment_notification"));
	define("SEND_VID_APPROVE_EMAIL",config("approve_video_notification"));
	


?>