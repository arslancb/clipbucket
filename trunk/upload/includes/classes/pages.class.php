<?php

/**
 **************************************************************************************************
 Don Not Edit These Classes , It May cause your script not to run properly
 This source file is subject to the ClipBucket End-User License Agreement, available online at:
 http://www.opensource.org/licenses/attribution.php
 By using this software, you acknowledge having read this Agreement and agree to be bound thereby.
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/

class pages{
 	
	var $max_pages = 4; //Used for pagination, after crossing this limit, pagination will start
	var $url_page_var = 'page';
	var $pre_link = '';
	var $next_link = '';
	var $first_link = '';
	var $last_link = '';
	var $pagination = '';
	
	function GetServerUrl()
	{
		$serverName = NULL;
		if ( isset($_SERVER['SERVER_NAME']) ) {
			$serverName = $_SERVER['SERVER_NAME'];
		} elseif ( isset($_SERVER['HTTP_HOST']) ) {
			$serverName = $_SERVER['HTTP_HOST'];
		} elseif ( isset($_SERVER['SERVER_ADDR']) ) {
			$serverName = $_SERVER['SERVER_ADDR'];
		} else {
			$serverName = 'localhost';
		}
	
		$serverProtocol = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ) ? 'https' : 'http';
		$serverPort     = NULL;
		if ( isset($_SERVER['SERVER_PORT']) && !strpos($serverName, ':') &&
		   ( ($serverProtocol == 'http' && $_SERVER['SERVER_PORT'] != 80) ||
		   ($serverProtocol == 'https' && $_SERVER['SERVER_PORT'] != 443) )) {
			$serverPort = $_SERVER['SERVER_PORT'];
			$serverPort  = ":".$serverPort ;
		}
		
		return $serverProtocol. '://' .$serverName.$serverPort;
	}
		
	function GetBaseUrl($more=false)
	{
		
		$serverURL      = $this->GetServerUrl();
		$scriptPath     = NULL;
		
		
		if ( isset($_SERVER['SCRIPT_NAME']) ) {
			$scriptPath = $_SERVER['SCRIPT_NAME'];
			$scriptPath = ( $scriptPath == '/' ) ? '' : dirname($scriptPath);
		}
		  
		$base = basename(dirname($_SERVER['SCRIPT_NAME']));
		
		
		$sus_dirs = array('admin_area','includes','plugins','files','actions','cb_install');
		
		
		$remove_arr = array();
		$remove_arr[] = '/:\/\/www\./';
		
		if($more)
		$remove_arr[] = $more;
		if(in_array($base,$sus_dirs))
			$remove_arr[] = '/\/'.$base.'/';
		
		//Clearing Plugin  and Player baseurl
		if(strstr($scriptPath,'/plugins/'))
			$scriptPath = preg_replace('/(.*)\/plugins(.*)/i',"$1",$scriptPath);
		if(strstr($scriptPath,'/player/'))
			$scriptPath = preg_replace('/(.*)\/plugins(.*)/i',"$1",$scriptPath);

		$baseURL = $serverURL . $scriptPath;
		
		$baseURL = preg_replace($remove_arr,'',$baseURL);
		
		if(substr($baseURL,strlen($baseURL)-1,1)=='/')
			$baseURL = substr($baseURL,0,strlen($baseURL)-1);
			
		return $baseURL;
	}
	
	function GetCurrentUrl()
	{
		global $in_bg_cron;
		if(!$in_bg_cron)
		{
			$serverURL      = $this->GetServerUrl();
			$requestURL     = $_SERVER['REQUEST_URI'];
		  
			return $serverURL . $requestURL;
		}
	}

 	//This Function Set The PageDirect
	function page_redir(){
		setcookie("pageredir",clean($this->GetCurrentUrl()),time()+7200,'/');
		Assign('pageredir',@$_COOKIE['pageredir']);
	}
	
	//This Funtion is use to Show Admin Panels Pages
	
	function show_admin_page($page){
	$pages = array(
	'main' 				=> 'main.php',
	'server_check'		=> 'verifier.php',
	'members_showall'	=> 'members.php?view=showall',
	'members_inactive'	=> 'members.php?view=inactive',
	'members_active'	=> 'members.php?view=active',
	'members_addmember'	=> 'members.php?view=addmember',
	'members_search'	=> 'members.php?view=search'
	);
	return @$pages[$page];
	}
	
	
	//Redirects page to without www.
	function redirectOrig()
	{
		$curpage = $this->GetCurrentUrl();
		$newPage = preg_replace('/:\/\/www\./','://',$curpage);
		if($curpage !=$newPage)
			header("location:$newPage");
	}
	
	function create_url($params_array,$url=NULL,$remove_param=false,$urlencode=false)
	{
		if($url==NULL or $url == 'auto')
		{
			if($_SERVER['QUERY_STRING'])
				$url = '?'.$_SERVER['QUERY_STRING'];
		}
		
		$new_link = '';
		$new_link .= $url;
		if(is_array($params_array))
		{
			foreach($params_array as $name => $value)
			{
				if($url)
					$new_link .='&'.$name.'='.$value;
				else
					$new_link .='?'.$name.'='.$value;
			}
		}
		
		return $new_link;
	}
	
	//This Fucntion is used to Redirect to respective URL
	
	function redirect($url){
		echo '<script type="text/javascript">
		window.location = "'.$url.'"
		</script>';
	}

	/**
	 * Function used to create link
	 */
	function create_link($page,$link=NULL,$extra_params=NULL,$tag=' <a #params#>#page#</a> ',$return_param=false)
	{
		if($link==NULL or $link == 'auto')
		{
			if($_SERVER['QUERY_STRING'])
				$link = '?'.$_SERVER['QUERY_STRING'];
		}
		
				
		$page_pattern = '#page#';
		$param_pattern = '#params#';
		$page_url_param = $this->url_page_var;
		$page_link_pattern = $page_url_param.'='.$page_pattern;
		$link = preg_replace(array('/(\?page=[0-9]+)/','/(&page=[0-9]+)/','/(page=[0-9+])+/'),'',$link);
		
		preg_match('/\?/',$link,$matches);

		$no_seo = false;
		if(!empty($matches[0]))
		{
			$no_seo = true;
			$page_link = '&'.$page_link_pattern;
		}else{
			$page_link = '?'.$page_link_pattern;
		}
		
		//Now checking if url is using & and ? then do not apply PAGE using slash instead use & or ?
		$current_url = $_SERVER['REQUEST_URI'];
		preg_match('/\?/',$current_url,$cur_matches);
		if(count($cur_matches))
			$has_q = true;
		preg_match('/\.php/',$current_url,$cur_matches);
		if(count($cur_matches))
			$has_php = true;
		preg_match('/&/',$current_url,$cur_matches);
		if(count($cur_matches))
			$has_amp = true;
		
		$link = $link.$page_link;
		$params = 'href="'.$link.'"';
		$params .= ' '.$extra_params;
		
		if($has_php && ($has_amp || $has_q))
			$use_seo = false;
		else
			$use_seo = true;
			
		if(SEO=='yes' && THIS_PAGE !='search_result' && !BACK_END && $use_seo)
		{
			if(count($_GET)==0 || (count($_GET)==3 && isset($_GET['page'])))
				$params = $params;	 
			else
				$params ='href="./'.$page.'"';
		}
		
		
		$final_link = preg_replace(array("/$page_pattern/i","/$param_pattern/i"),array($page,$params),$tag);
		$final_link = preg_replace(array("/$page_pattern/i","/$param_pattern/i"),array($page,$params),$final_link);
		
		if($return_param)
		{
			return preg_replace("/$page_pattern/i",$page,$params);
		}
		
		return ' '.$final_link.' ';
	}

	/**
	 * Function used to create pagination
	 * @param : total number of pags
	 * @param : current page
	 * @param : extra paraments in the tag ie <a other_params_go_here
	 * @param : tag used for pagination
	 */
	function pagination($total,$page,$link=NULL,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		
		if($total==0)
			return false;
		if($page<=0||$page==''||!is_numeric($page))
			$page = 1;
		$total_pages = $total;
		$pagination_start = 14;
		$display_page = 7;
		$this->selected = $selected = $page;
		$hellip = '&hellip;';
		$first_hellip = '';
		$second_hellip = '';
		
		$start = '';
		$mid = '';
		$end = '';
		
		$start_last = '';
		$end_first = '';
		
		$mid_first = '';
		$mid_last = '';
		
		$differ = round(($display_page/2)+.49,0)-1;
		
		if($pagination_start < $total_pages)
		{
			
			//Starting First
			for($i=1;$i<=$display_page;$i++)
			{
				if($selected == $i)
				{
					$start .= ' <span class ="selected">'.$i.'</span> ';
				}else
					$start .= $this->create_link($i,$link,$extra_params,$tag);
				$start_last = $i;
			}
			
			
			//Starring Last
			for($i=$total_pages-$display_page;$i<=$total_pages;$i++)
			{
				if($end_first=='')
					$end_first = $i;
				
				if($selected == $i)
				{
					$end .= ' <span class ="selected">'.$i.'</span> ';
				}else
				$end .= $this->create_link($i,$link,$extra_params,$tag);
			}
			
			//Starting mid
			for($i=$selected-$differ;$i<=$selected+$differ;$i++)
			{
				if($mid_first=='')
					$mid_first = $i;
					
				if($i>$start_last && $i<$end_first)
				{
					if($selected == $i)
					{
						$mid .= ' <span class ="selected">'.$i.'</span> ';
					}else
						$mid .= $this->create_link($i,$link,$extra_params,$tag);
				}
				
				$mid_last = $i;
			}
			
			
			if($start_last < $mid_first)
				$first_hellip = $hellip;
			if($end_first > $mid_last)
				$second_hellip = $hellip;
			
			//Previous Page
			if($selected-1 > 1)
				$this->pre_link = $this->create_link($selected-1,$link,$extra_params,$tag,true);
			//Next Page
			if($selected+1 < $total)
				$this->next_link = $this->create_link($selected+1,$link,$extra_params,$tag,true);
			//First Page
			if($selected!=1)
				$this->first_link = $this->create_link(1,$link,$extra_params,$tag,true);
			//First Page
			if($selected!=$total)
				$this->last_link = $this->create_link($total,$link,$extra_params,$tag,true);
				
			return $start.$first_hellip.$mid.$second_hellip.$end;
		}else{
			$pagination_smart = '';
			for($i=1;$i<=$total_pages;$i++)
			{
				if($i == $selected)
					$pagination_smart .= ' <span class ="selected">'.$i.'</span> ';
				else
					$pagination_smart .=$this->create_link($i,$link,$extra_params,$tag);
			}
			
			//Previous Page
			if($selected-1 > 1)
				$this->pre_link = $this->create_link($selected-1,$link,$extra_params,$tag,true);
			//Next Page
			if($selected+1 < $total)
				$this->next_link = $this->create_link($selected+1,$link,$extra_params,$tag,true);
			//First Page
			if($selected!=1)
				$this->first_link = $this->create_link(1,$link,$extra_params,$tag,true);
			//First Page
			if($selected!=$total)
				$this->last_link = $this->create_link($total,$link,$extra_params,$tag,true);
				
			return $pagination_smart;
		}
	}
	
	
	/**
	 * Function used to create pagination and assign values that can bee used in template
	 */
	function paginate($total,$page,$link=NULL,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		
		$this->pagination = $this->pagination($total,$page,$link,$extra_params,$tag);
		
		//Assigning Variable that can be used in templates
		assign('pagination',$this->pagination);
		
		assign('next_link',$this->next_link);
		assign('pre_link',$this->pre_link);
		
		assign('next_page',$page+1);
		assign('pre_page',$page-1);
		
		assign('first_link',$this->first_link);
		assign('last_link',$this->last_link);
	}
	
}
?>