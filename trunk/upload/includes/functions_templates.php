<?php
        
        /**
         * Fetch Smarty Template
         * 
         * @param type $name
         * @param type $inside
         * @return type 
         */
	function Fetch($name,$inside=FALSE)
	{
		if($inside)
			$file = CBTemplate::fetch($inside.$name);
		else
			$file = CBTemplate::fetch(LAYOUT.'/'.$name);
			
		return $file;			
	}
	
        
	
        /**
         * Function used to render Smarty Template
         * 
         * @global type $admin_area
         * @param type $template
         * @param type $layout 
         */
	
	function Template($template,$layout=true)
        {
            
                global $admin_area,$Cbucket;
                
                
                //Getting list of variables and classes to make them global..
                if($Cbucket->templateClasses)
                {
                    foreach ($Cbucket->templateClasses as $tClasskey)
                    {
                        global ${$tClasskey};
                    }
                }

                if($Cbucket->template_details['php']!='on')
                {
                
                    if($layout)
                    CBTemplate::display(LAYOUT.'/'.$template);
                    else
                    CBTemplate::display($template);

                }else
                {
                    if($layout)
                        $template_file = LAYOUT.'/'.$template;
                    else
                        $template_file = $template;
                    
                    if(file_exists($template_file))
                        include($template_file);
                }
	}
        
        
         
        /**
         * assign smarty variable
         * 
         * @param type $name
         * @param type $value 
         */
        function Assign($name,$value)
	{
            global $Cbucket;
            $CBucket->templateVars[$name] = $value;
            CBTemplate::assign($name,$value);
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
                
                //Assign Vars
                if($params)
                {
                    foreach($params as $name => $value)
                    {
                        if($name!='file')
                        {
                            assign($name,$value);
                        }
                    }
                }
                
		
		if(file_exists(LAYOUT.'/'.$file))
                {
                    echo '<!-- Including '.$file.' -->';
                    Template($file);
                }elseif(file_exists($file))
                {
                    echo '<!-- Including '.$file.' -->';
                    Template($file,false);
                } elseif(file_exists(STYLES_DIR.'/global/'.$file))
                {
                    echo '<!-- Including '.$file.' -->';
                    Template(STYLES_DIR.'/global/'.$file,false);
                }
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
        
        
        
        function showpagination($total,$page,$link,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		global $pages;
		return $pages->pagination($total,$page,$link,$extra_params,$tag);
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
	 * This function used to include headers in <head> tag
	 * it will check weather to include the file or not
	 * it will take file and its type as an array
	 * then compare its type with THIS_PAGE constant
	 * if header has TYPE of THIS_PAGE then it will be inlucded
	 */
	function include_header($params)
	{
		$file = $params['file'];
		$type = $params['type'];
		
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
         * function used to get theme options
         * @todo Write documentation
         */
        function theme_config($name)
        {
            global $Cbucket;
            
            if($Cbucket->theme_configs)
                $theme_configs = $Cbucket->theme_configs;
            else
                $theme_configs = theme_configs();
            
            $value = $value[$name];
        }
        
        /**
         * Get them configurations
         * @global type $Cbucket
         * @return type
         */
        
        function theme_configs()
        {
            global $Cbucket;
            
            $value = config($Cbucket->template.'-options');
            $value = json_decode($value,true);
            $value = $value['options'];
            
            return $value;
        }
        
        
        /**
	  * add link in admin area left menu
	  *
	  * Function used to add items in admin menu
	  * This function will insert new item in admin menu
	  * under given header, if the header is not available 
	  * it will create one, ( Header means titles ie 'Plugins' 'Videos' etc)
	  * http://docs.clip-bucket.com/add_admin_menu-function for reference
	  *
	  * @todo Write documentation
	  */
	 function add_admin_menu($params,$name=false,$link=false,$plug_folder=false,$is_player_file=false)
	 {
		global $Cbucket;
                
                if(!is_array($params))
                {
                    $params = _add_admin_menu($params,$name,$link,$plug_folder,$is_player_file);
                    add_admin_sub_menu($params);
                    return true;
                }
                
                $defaults = array(
                    'title' => lang('Settings'),
                    'id'    => 'settings',
                    'icon'  => 'icon-gauge',
                    'access' => 'admin-access'
                );
                
                $params = array_merge($defaults,$params);
                
                return $Cbucket->AdminMenu[$params['id']] = $params;
                
	 }
         
         /**
          * add multiple admin menus
          */
         function add_admin_menus($menus)
         {
             if(is_array($menus))
             {
                 foreach($menus as $menu)
                     add_admin_menu ($menu);
             }
         }
         
         /**
          * @todo write documentation
          */
         function add_admin_sub_menu($params)
         {
             global $Cbucket;
             $defaults = array(
                 'parent_id' => 'tool-box',
                 'access' => 'admin_access',
             );
             
             $params = array_merge($defaults,$params);
             
             if($params['title'])
             {
                 $id = $params['id'];
                 if(!$id)
                     $id = SEO ($params['title']);
                 
                 $menu = array(
                     'id' => $id,
                     'parent_id' => $params['parent_id'],
                     'access'=> $params['access'],
                     'title' => $params['title'],
                     'link' => $params['link'],
                     'icon' => $params['icon'], 
                 );
                 
                 if($Cbucket->AdminMenu[$params['parent_id']])
                 {
                     $Cbucket->AdminMenu[$params['parent_id']]['sub_menu'][] = $menu;
                 }else
                 {
                     //Add menu to misc menu
                     $Cbucket->AdminMenu['miscellaneous']['sub_menu'][] = $menu;
                 }
             }
             
         }
         
         function add_admin_sub_menus($params)
         {
             if(is_array($params))
             {
                 foreach($params as $parent => $child)
                 {
                     
                     foreach($child as $ch)
                     {
                        $ch['parent_id'] = $parent;
                        add_admin_sub_menu($ch);
                     }
                 }
             }
         }
         
         function _add_admin_menu($header='Tool Box',$name=false,$link=false,$plug_folder=false,$is_player_file=false)
         {
             global $Cbucket;
                
            //Get Menu
            $menu = $Cbucket->AdminMenu;

            if($plug_folder)
                    $link = 'plugin.php?folder='.$plug_folder.'&file='.$link;
            if($is_player_file)
                    $link .= '&player=true';

            //Add New Menu
            $menu[$header][$name] = $link;
            
            //Add sub menu function here...
            
            $params = array(
                'title' => $name,
                'parent_id' => SEO($header),
                'id' => SEO('title'),
                'link' => $link
            );
            
            return $params;
         }
	 
         /**
          * get admin menu
          * 
          * @todo Write documentation
          */
         function get_admin_menu()
         {
             global $Cbucket;
             
             $array = $Cbucket->AdminMenu;
             
             //Apply Filters
             $array = apply_filters($array, 'admin_menu');
             
             return $array;
         }
         
         
         /**
          * get list of icons in category-icons folder
          */
         function get_category_icons()
         {
             //Check if there is a folder
             //template for category icons
             if(file_exists(FRONT_TEMPLATEDIR.'/category-icons'))
             {
                 $dir = FRONT_TEMPLATEDIR.'/category-icons';
                 $dir_url = FRONT_TEMPLATEURL.'/category-icons';
             }else
             {
                 $dir = BASEDIR.'/images/category-icons';
                 $dir_url = BASEURL.'/images/category-icons';
             }
             
             
             //Blank list of images
             $images = array();
                 
             if(file_exists($dir))
             {
                 //Only get PNGs
                 $imgList = glob($dir.'/*.png');
                 
                 if($imgList)
                 {
                     foreach($imgList as $img)
                     {
                         list($width, $height, $type, $attr) = getimagesize($img);
                         if($width && $height)
                             $images[] = $img;
                     }
                 }
             }
             
             $final_images = array();
             if($images)
             {
                 foreach($images as $image)
                 {
                     $imagearr = explode('/',$image);
                     $imageName = $imagearr[count($imagearr) - 1];
                     
                     $final_images[$imageName] = array('url'=>$dir_url.'/'.$imageName,
                         'path' => $dir.'/'.$imageName);
                 }
             }
             
             return $final_images;
         }
         
         /**
          * Loading Pointer
          * 
          * Displays a loading image with the given ID
          * we need this pointer on many places to let user know if the
          * process is finised or not to improve UI
          * 
          * @param ID String
          * @return Image wraped in img tag with ID and hidden by default 
          */
         function loading_pointer($params)
         {
             $id = $params['place'] ? $params['place'] : $params['id'];
             
             $img = TEMPLATEURL.'/images/loaders/1.gif';
             
             return '<img src="'.$img.'" id="'.$id.'-loader" class="loading_pointer '.$params['class'].'">';
             
         }
         
         
         /**
          * Shortify Numbers
          * 
          * display large numbers in short forms by adding K
          * and triming the rest
          * 100,000 => 100K 105,2345 => 105.2K 
          * 
          * @param INT $numbers
          * @return STRING $shortened
          */
         function shortify($numbers)
         {
             if(is_numeric($numbers))
             {
                 if($numbers>1000)
                 {
                     $new = round($numbers/1000,1);
                     return $new.'K';
                 }
             }
         }
         
         
         /**
          * Displays the rating in the template in an ajax request 
          * @todo Write Documentation
          * 
          * filters isliye lagay hain take array main radobadal ki ja skay
          * cb_call_functions baad main issy array ko istemal kr k rating
          * show krwa dega, is k liye pehle cb_register_function krwana
          * parre ga.
          * 
          * return isliye kuch nhin krwaya kion k cb_call_funcion b kuch return
          * nhin kr ra hai wo ilsye k ye content ko format nhin krta
          * balke jitne registered functions hote hain unko call krta aur bich
          * me hi echo hota
          */
         function showRating($rating)
         {
             $rating = apply_filters($rating, 'show-rating');
             cb_call_functions('show_rating',$rating);
         }
?>