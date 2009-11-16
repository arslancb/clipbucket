<?php
/**
 * @ Author Arslan Hassan
 * @ License : CBLA
 * @ Class : CLipBucket Class
 * @ date : 12 MARCH 2009
 * @ Version : v1.8
 */

class ClipBucket 
{
	var $BASEDIR;
	var $JSArray = array();
	var $moduleList = array();
	var $actionList = array();
	var $anchorList = array();
	var $ids = array(); //IDS WILL BE USED FOR JS FUNCTIONS
	var $AdminMenu = array();
	var $configs = array();
	var $header_files = array();// these files will be included in <head> tag
	var $anchor_function_list = array();
	var $show_page = true;
	var $upload_opt_list = array();//this will have array of upload opts like upload file, emebed or remote upload
	var $temp_exts = array(); //Temp extensions
	var $actions_play_video = array(); 
	var $template_files = array();
	var $cur_template = 'clipbucketblue';
	var $links = array();
	
	/**
	 * All Functions that are called
	 * before after converting a video
	 * are saved in these arrays
	 */
	 
	 var $before_convert_functions = array();
	 var $after_convert_functions = array();
	
	
	/**
	 * This array contains
	 * all functions that are called
	 * when we call video to play on watch_video page
	 */
	 var $watch_video_functions = array();
	 
	 
	 /**
	  * Email Function list
	  */
	 var $email_functions = array();
	 
	 
	function ClipBucket ()
	{
		global $pages;
		//Assign Configs
		$this->configs = $this->get_configs();
		//Get Current Page and Redirects it to without www.
		$pages->redirectOrig();
		//Get Base Directory
		$this->BASEDIR = $this->getBasedir();
		//Listing Common JS File
		$this->addJS(array
					 (
					 'ajax.js'			=> 'homeactive',
					 'jquery.js'		=> 'global',
					 'dropdown.js'		=> 'global',
					 'flashobject.js'	=> 'global',
					 'rating_update.js'	=> 'global',
					 'checkall.js'		=> 'global',
					 'redir.js'			=> 'global',
					 'functions.js'		=> 'global',
					 'swfobject.js'		=> 'global',
					 'swfobject.obj.js'		=> 'global',
					  ));
		
		//This is used to create Admin Menu
		$this->AdminMenu = $this->get_admin_menu();
		
		//Updating Upload Options
		$this->upload_opt_list = array
		(
		 'file_upload_div'	=>	array(
							  'title'		=>	'Upload File',
							  'func_class'	=> 	'Upload',
							  'load_func'	=>	'load_upload_form',
							  ),
		 'remote_upload_div' => array(
								  'title'	=> 'Remote Upload',
								  'func_class' => 'Upload',
								  'load_func' => 'load_remote_upload_form',
								  )
		 );
		
		$this->temp_exts = array('ahz','jhz','abc','xyz','cb2','tmp','olo','oar','ozz');
		$this->template = $this->configs['template_dir'];

	}
	
	
	function getBasedir()
	{
	   $dirname = dirname(__FILE__);
	   $dirname = preg_replace(array('/includes/','/classes/'),'',$dirname);
	   $dirname = substr($dirname,0,strlen($dirname) -2);
	   return $dirname == '/' ? '' : $dirname;
	}
	
	function addJS($files)
	{
		if(is_array($files))
		{
			foreach($files as $key=> $file)
				$this->JSArray[$key] = $file;
		}else{
			$this->JSArray[$files] = 'global';
		}
	
	}
	function add_js($files)
	{
		$this->addJS($files);
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
		if(is_array($files))
		{
			foreach($files as $key=> $file)
				$this->header_files[$key] = $file;
		}else{
			$this->header_files[$files] = 'global';
		}
	}
	
	/**
	 * Function used to get list of function of any type
	 * @param : type (category,title,date etc)
	 */
	 function getFunctionList($type)
	 {
		return $this->actionList[$type];
	 }
	 
	 
	/**
	 * Function used to get anchors that are registered in plugins
	 */
	 function get_anchor_codes($place)
	 {
		 //Geting list of codes available for $place
		 $list = $this->anchorList[$place];
		 return $list;
	 }
	 
	 /**
	 * Function used to get function of anchors that are registered in plugins
	 */
	 function get_anchor_function_list($place)
	 {
		 //Geting list of functions
		 $list = $this->anchor_function_list[$place];
		 return $list;
	 }
	 
	 /**
	  * Function used to create admin menu
	  */
	  function get_admin_menu()
	  {
		  $menu_array = array
		  (
		   //Statistics
		   'Statiscs & Reports'	=> 
		   array(
					'Daily Stats'=>'statiscts.php',
					'Full Reports'=>'reports.php'
				),
		   
		    //Language Editor
		   'Language'=>
		   array(
				 'Language Settings' => 'language_settings.php',
				 'Add New Phrases'	=> 'add_phrase.php',
				 ),
		   //Configurations
		   'Configurations'		=> 
		   array(
				'Website Configurations'=>'main.php',
				'Email Settings'=>'email_settings.php',
				),
		   
		   //Video
		   'Videos'				=> 
		   array(
				'Videos Manager'=>'video_manager.php',
				'Manage Categories'=>'category.php',
				'List Flagged Videos'=>'flagged_videos.php',
				'Upload Videos'	=>'mass_uploader.php',
				'List Inavtive Videos'=>'video_manager.php?view=search&&active=no'
				),
		   
		   //Users
		   'Users'				=> 
		   array(
				 'Manage Members' => 'members.php?view=showall',
				 'Add Member'=>'members.php?view=addmember',
				 'User Levels'=>'user_levels.php',
				 'Search Members'=>'members.php?view=search',
				 'Inactive Only'=>'members.php?view=inactive',
				 'Active Only'=>'members.php?view=active',
				 'Mass Email'=>'mass_email.php'
				),
		   
		   //Groups
		   'Groups'				=> 
		   array(
				 'Add Group'=>'add_group.php',
				 'Manage Groups'=>'groups_manager.php',
				 'Manage Categories'=>'group_category.php?view=show_category'
				),
		   
		   //Advertisments
		   'Advertisement'		=>
		   array(
				  'Manage Advertisments'=>'ads_manager.php',
				  'Manage Placements'=>'ads_add_placements.php',
				),
		   
		   //Template Manager
		   'Template Manager'=>
		   array(
				 'Template Editor'=>'templates.php',
				 'Logo Changer'	=>'logo_change.php'
				
				),
		   
		   
		   //Plugin Manager
		   'Plugin Manager'=>
		   array(
				'Plugin Manager'=>'plugin_manager.php'
				),
		   
		   //Tool Box
		   'Tool Box'=>
		   array(
				 'Module Manager'=>'module_manager.php',
				 'PHP Info'	=> 'phpinfo.php',
				 'FFMPEG Info'=>'',
				 'All Modules Info'=>'',
				 'View Encoding Status'=>'',
				),
		   
		   //Playes
		   'Manage Players'=>
		   array
		   		(
				 'Manage Players' => 'manage_players.php'
				 )		   
		   );
		  
		  
		  return $menu_array;
	  }	 
	
	/**
	 * Function used to assign ClipBucket configurations
	 */
	function get_configs()
	{
		global $myquery;
		return $myquery->Get_Website_Details();
	}
	
	/**
	 * Funtion cused to get list of countries
	 */
	function get_countries($type=iso2)
	{
		global $db;
		$results = $db->select("countries","*");
		
		switch($type)
		{
			case id:
			foreach($results as $result)
			{
				$carray[$result['id']] = $result['name_en'];
			}
			break;
			case iso2:
			foreach($results as $result)
			{
				$carray[$result['iso2']] = $result['name_en'];
			}
			break;
			case iso3:
			foreach($results as $result)
			{
				$carray[$result['iso3']] = $result['name_en'];
			}
			break;
			default:
			foreach($results as $result)
			{
				$carray[$result['id']] = $result['name_en'];
			}
			break;
		}
		
		return $carray;
	}
	
	/**
	 * Function used to set show_page = false or true
	 */
	function show_page($val=true)
	{
		$this->show_page = $val;
	}
	
	
	/**
	 * Function used to set template (Frontend)
	 */
	function set_the_template()
	{
		global $cbtpl,$myquery;
		$template = $this->template;
		
		if($_GET['template'])
		{
			if(is_dir(STYLES_DIR.'/'.$_GET['template']) && $_GET['template'])
				$template = $_GET['template'];
		}
		if(!is_dir(STYLES_DIR.'/'.$template) || !$template)
			$template = 'clipbucketblue';
		if(!is_dir(STYLES_DIR.'/'.$template) || !$template)
		{
			$template = $cbtpl->get_any_template();		 
		}
		 
		if(!is_dir(STYLES_DIR.'/'.$template) || !$template)
			exit("Unable to find any template, please goto <a href='http://clip-bucket.com/no-template-found'><strong>ClipBucket Support!</strong></a>");
		
		
		if($_GET['set_template'])
		{
			$myquery->set_template($template);
		}
		
		define('TEMPLATE',$template);
	}
	
}

?>