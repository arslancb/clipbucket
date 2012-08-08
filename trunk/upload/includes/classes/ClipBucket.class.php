<?php
/**
 * @ Author Arslan Hassan
 * @ License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @ Class : CLipBucket Class
 * @ date : 12 MARCH 2009
 * @ Version : v1.8
 */

class ClipBucket 
{
	var $BASEDIR;
	var $JSArray = array();
	var $AdminJSArray = array();
	var $moduleList = array();
	var $actionList = array();
	var $anchorList = array();
	var $ids = array(); //IDS WILL BE USED FOR JS FUNCTIONS
	var $AdminMenu = array();
	var $configs = array();
	var $header_files = array();// these files will be included in <head> tag
	var $admin_header_files = array();// these files will be included in <head> tag
	var $anchor_function_list = array();
	var $show_page = true;
	var $upload_opt_list = array();//this will have array of upload opts like upload file, emebed or remote upload
	var $temp_exts = array(); //Temp extensions
	var $actions_play_video = array();
	var $template_files = array();
	var $cur_template = 'cbv2new';
	var $links = array();
	var $captchas = array();
	var $clipbucket_footer = array('cb_bottom');
	var $clipbucket_functions = array();
	var $head_menu = array();
	var $foot_menu = array();
	var $template = "";
	
	var $in_footer = false;
	
	var $cbinfo = array();
	
	var $search_types = array();
	
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

	/**
	 * This array contains
	 * all functions that are called
	 * on CBvideo::remove_files
	 */
        var $on_delete_video = array();

        
        var $admin_blocks = array();
	 
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
		
                $this->addJS(array('jquery_plugs/cookie.js','functions.js'));
		
		//Updating Upload Options		
		$this->temp_exts = array('ahz','jhz','abc','xyz','cb2','tmp','olo','oar','ozz');
		$this->template = $this->configs['template_dir'];
		
		if(!defined("IS_CAPTCHA_LOADING"))
		$_COOKIE['total_captchas_loaded'] = 0;
		
		$this->clean_requests();
		
		if(!isset($_GET['sort']))
			$_GET['sort'] = 'most_recent';
		if(!isset($_GET['time']))
			$_GET['time'] = 'all_time';
		if(!isset($_GET['page']))
			$_GET['page'] = 1;
	}
	
	
	function getBasedir()
	{
	   $dirname = dirname(__FILE__);
	   $dirname = preg_replace(array('/includes/','/classes/'),'',$dirname);
	   $dirname = substr($dirname,0,strlen($dirname) -2);
	   return $dirname == '/' ? '' : $dirname;
	}
        
	
	function addJS($files,$scope='global')
	{
            return add_js($files,$scope);
	}
        
        
	function add_js($files,$scope='global')
	{
		$this->addJS($files,$scope);
	}
	
	
	function addAdminJS($files)
	{
		if(is_array($files))
		{
			foreach($files as $key=> $file)
				$this->AdminJSArray[$key] = $file;
		}else{
			$this->AdminJSArray[$files] = 'global';
		}
	
	}
	
	/**
	 * Function add_header()
	 * this will be used to add new files in header array
	 * this is basically for plugins
	 * @param FILE
	 * @param PAGES (array)
	 */
	function add_header($file,$place='global')
	{
		if(!is_array($place))
		{
			$place = array($place);
		}
		$this->header_files[$file] = $place;
	}
	
	
	/**
	 * Function add_admin_header()
	 * this will be used to add new files in header array
	 * this is basically for plugins
	 * @param FILE
	 * @param PAGES (array)
	 */
	function add_admin_header($file,$place='global')
	{
		if(!is_array($place))
		{
			$place = array($place);
		}
		$this->admin_header_files[$file] = $place;
	
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
	 
	 
	function LatestAdminMenu()
	{
            global $userquery;
            $per = $userquery->get_user_level(userid());

           
            return $NewMenu;	 				  																																		
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
		$results = $db->select(tbl("countries"),"*");
		switch($type)
		{
			case id:
			foreach($results as $result)
			{
				$carray[$result['country_id']] = $result['name_en'];
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
				$carray[$result['country_id']] = $result['name_en'];
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
		
		if(isset($_SESSION['the_template']) && $cbtpl->is_template($_SESSION['the_template']))
			$template = $_SESSION['the_template'];
		if($_GET['template'])
		{
			if(is_dir(STYLES_DIR.'/'.$_GET['template']) && $_GET['template'])
				$template = $_GET['template'];
		}
		if(isset($_GET['set_the_template']) && $cbtpl->is_template($_GET['set_the_template']))
			$template = $_SESSION['the_template'] = $_GET['set_the_template'];
		if(!is_dir(STYLES_DIR.'/'.$template) || !$template)
			$template = 'cbv2new';
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
		
		$this->template_details = $cbtpl->get_template_details($template);
		
                //CHecking if there is any php file, include it like a BOSS!
                //if($this->template_details['php_file'])
                //    include($this->template_details['php_file']);
                
                $this->template = $template;
                
                return	 $this->template;
	}
	
	
	/**
	 * Function used to list available extension for clipbucket
	 */
	function list_extensions()
	{
		$exts = $this->configs['allowed_types'];
		$exts = preg_replace('/ /','',$exts);
		$exts = explode(',',$exts);
		$new_form = '';
		foreach($exts as $ext)
		{
			if(!empty($new_form))
				$new_form .=";";
			$new_form .= "*.$ext";
		}
		
		return $new_form;
	}
	
	
	/**
	 * Function used to load head menu
	 */
	function head_menu($params=NULL)
	{
		global $cbpage;
		$this->head_menu[] = array('name'=>lang("menu_home"),'link'=>BASEURL,"this"=>"home","section"=>"home","extra_attr"=>"");
		$this->head_menu[] = array('name'=>lang("videos"),'link'=>cblink(array('name'=>'videos')),"this"=>"videos","section"=>"home");
		$this->head_menu[] = array('name'=>lang("menu_channels"),'link'=>cblink(array('name'=>'channels')),"this"=>"channels","section"=>"channels");
		$this->head_menu[] = array('name'=>lang("groups"),'link'=>cblink(array('name'=>'groups')),"this"=>"groups","section"=>"groups");
		$this->head_menu[] = array('name'=>lang("Collections"),'link'=>cblink(array('name'=>'collections')),"this"=>"collections","section"=>"collections");
		if(!userid())
		$this->head_menu[] = array('name'=>lang("signup"),'link'=>cblink(array('name'=>'signup')),"this"=>"signup");
		
		$this->head_menu[] = array('name'=>lang("photos"),'link'=>cblink(array('name'=>'photos')),"this"=>"photos");

		/* Calling custom functions for headMenu. This can be used to add new tabs */
		//cb_call_functions('headMenu');
		if($params['assign'])
			assign($params['assign'],$this->head_menu);
		else
			return $this->head_menu;
	}
	
		
	function cbMenu($params=NULL)
	{
		$this->head_menu($params);
		if(!$params['tag'])
			//$params['tag'] = 'li';

		if(!$params['class'])
			$params['class'] = '';


		if(!$params['getSubTab'])
			$params['getSubTab'] = '';
		
		if(!$params['parentTab'])
			$params['parentTab'] = '';
			
		if(!$params['selectedTab'])
			$params['selectedTab'] = '';
		{
			$headMenu = $this->head_menu;
			
			$custom = $this->custom_menu;
			
			if(is_array($custom))
				$headMenu = array_merge($headMenu,$custom);
			/* Excluding tabs from menu */	
			if($params['exclude'])
			{
				if(is_array($params['exclude']))
					$exclude = $params['exclude'];
				else
					$exclude = explode(",",$params['exclude']);
	
				foreach($headMenu as $key=>$hm)
				{
					foreach($exclude as $ex)
					{
						$ex = trim($ex);
						if(strtolower(trim($hm['name'])) == strtolower($ex))
							unset($headMenu[$key]);	
					}
				}
			}
						
			$output = '';
			//if(($params['tag']))
			//		$output .= "<".$params['tag'].">";
			foreach($headMenu as $menu)
			{
				if(isSectionEnabled($menu['this']))
				{
					$selected = current_page(array("page"=>$menu['this']));
					
					$output .= "<li ";
					$output .= "id = 'cb".$menu['name']."Tab'";
						
					$output .= " class = '";
					if($params['class'])
						$output .= $params['class'];
					if($selected)
						$output .= " selected";
					$output .= "'";
								
					if($params['extra_params'])
						$output .= ($params['extra_params']);					
					$output .= ">";
						$output .= "<a href='".$menu['link']."'>";
						$output .= $menu['name']."</a>";
					$output .= "</li>";
				}
					
			}
			//if(($params['tag']))
			//		$output .= "</".$params['tag'].">";
			
			if($params['echo'])
				echo $output;
			else		
				return $output;		
		}
	}
	
	/**
	 * Function used to load head menu
	 */
	function foot_menu($params=NULL)
	{
		global $cbpage;
		$this->foot_menu[] = array('name'=>lang("menu_home"),'link'=>BASEURL,"this"=>"home");
		$this->foot_menu[] = array('name'=>lang("contact_us"),'link'=>cblink(array('name'=>'contact_us')),"this"=>"home");		
		
		
		if(userid())
			$this->foot_menu[] = array('name'=>lang("my_account"),'link'=>cblink(array('name'=>'my_account')),"this"=>"home");		
		
		$pages = $cbpage->get_pages(array('active'=>'yes','display_only'=>'yes','order'=>'page_order ASC'));
		 
		if($pages)
		foreach($pages as $p)
			$this->foot_menu[] = array('name'=>$p['page_name'],'link'=>$cbpage->page_link($p),"this"=>"home");
		
//		if($cbpage->is_active(2))
//			$this->foot_menu[] = array('name'=>lang("privacy_policy"),'link'=>$cbpage->get_page_link(2),"this"=>"home");
//		
//		if($cbpage->is_active(3))
//			$this->foot_menu[] = array('name'=>lang("terms_of_serivce"),'link'=>$cbpage->get_page_link(3),"this"=>"home");
//		
//		if($cbpage->is_active(4))
//			$this->foot_menu[] = array('name'=>lang("help"),'link'=>$cbpage->get_page_link(4),"this"=>"groups");

//		
		if($params['assign'])
			assign($params['assign'],$this->foot_menu);
		else
			return $this->foot_menu;
	}
	
	/**
	 * Function used to call footer
	 */
	function footer()
	{
		ANCHOR(array('place'=>'the_footer'));
	}
	
	
	/**
	 * Function used to get News From ClipBucket Blog
	 */
	function get_cb_news()
	{
		$feeds = 5;
		$text = 400;
		
		//if($_SERVER['HTTP_HOST']!='localhost')
			$url = 'http://blog.clip-bucket.com/feed/';
		
		$news = xml2array($url);
		if(!$news)
		{
			return false;
		}else
		{
			$items = array();
			$item = $news['rss']['channel']['item'];
			for($i=0;$i<$feeds;$i++)
				$items[] = $item[$i];
			
			return $items;
		}
		
	}
	
	
	/**
	 * Fucntion used to clean requests
	 */
	function clean_requests()
	{
		$posts = $_POST;
		$gets = $_GET;
		$request = $_REQUEST;
		

		//Cleaning post..
		if(is_array($posts) && count($posts)>0)
		{
			$clean_posts = array();
			foreach($posts as $key => $post)
			{
				if(!is_array($post))
				{
					$clean_posts[$key] = preg_replace(array('/\|no_mc\|/','/\|f\|/'),'',$post);
				}else
					$clean_posts[$key] = $post;
			}
			$_POST = $clean_posts;
		}
		
		//Cleaning get..
		if(is_array($gets) && count($gets)>0)
		{
			$clean_gets = array();
			foreach($gets as $key => $get)
			{
				if(!is_array($get))
				{
					$clean_gets[$key] = preg_replace(array('/\|no_mc\|/','/\|f\|/'),'',$get);
				}else
					$clean_gets[$key] = $get;
			}
                        
                        //Cleaning Sorts
                        $sorts = array('most_recent','most_viewed','featured','top_rated','most_commented');
                        if($this->more_sorts)
                            $sorts = array_merge($this->more_sorts,$sorts);
                        
                        if(!in_array($clean_gets['sort'],$sorts))
                            $clean_gets['sort'] = 'most_recent';
                        
                        //Cleaning Cat
                        if(!is_numeric($clean_gets['cat']))
                            $clean_gets['cat'] = 'all';
                        
                        //Cleaning time
                        $times = array('all_time','today','yesterday','this_week','last_week',
                            'this_month','last_month','this_year','last_year');
                        if($this->more_times)
                            $times = array_merge($this->more_times,$times);
                        
                        if(!in_array($clean_gets['time'],$times))
                            $clean_gets['time'] = 'all_time';
                        
                        
			$_GET = $clean_gets;
		}
		
		//Cleaning request..
		if(is_array($request) && count($request)>0)
		{
			$clean_request = array();
			foreach($request as $key => $request)
			{
				if(!is_array($request))
				{
					$clean_request[$key] = preg_replace(array('/\|no_mc\|/','/\|f\|/'),'',$request);
				}else
					$clean_request[$key] = $request;
			}
			$_REQUEST = $clean_request;
		}
		
	}
}

?>