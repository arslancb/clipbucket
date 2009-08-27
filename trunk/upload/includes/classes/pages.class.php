<?php

/**
 **************************************************************************************************
 Don Not Edit These Classes , It May cause your script not to run properly
 This source file is subject to the ClipBucket End-User License Agreement, available online at:
 http://clip-bucket.com/cbla
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
		}
		
		return $serverProtocol. '://' .$serverName;
	}
		
	function GetBaseUrl()
	{
		$serverURL      = $this->GetServerUrl();
		$scriptPath     = NULL;
		if ( isset($_SERVER['SCRIPT_NAME']) ) {
			$scriptPath = $_SERVER['SCRIPT_NAME'];
			$scriptPath = ( $scriptPath == '/' ) ? '' : dirname($scriptPath);
		}
		  
		$baseURL = $serverURL . $scriptPath;
		$baseURL = preg_replace('/:\/\/www\./','',$baseURL);
		return $baseURL;
	}
	
	function GetCurrentUrl()
	{
		$serverURL      = $this->GetServerUrl();
		$requestURL     = $_SERVER['REQUEST_URI'];
		  
		return $serverURL . $requestURL;
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
		$newPage = preg_replace('/:\/\/www\./','',$curpage);
		if($curpage !=$newPage)
			redirect_to($newPage);
	}
	
	function createUrl($url,$params_array,$remove_param=false,$urlencode=false)
	{
		//Cleaning Url and Create Existing Params as Array
		$list1 = explode('&',$url);
		$semi_clean_url = preg_replace('/&(.*)/','',$url);
		$list2= explode('?',$semi_clean_url);
		$clean_url = preg_replace('/\?(.*)/','',$semi_clean_url);
		$lists=array_merge($list1,$list2);
		foreach($lists as $list)
		{
			preg_match('/http:\/\//',$list,$matches);
			if(empty($matches[0]))
			{
				if($remove_param!=true)
				{
					list($param,$value) = explode('=',$list);
					if(empty($params_array[$param]))
					$params_array[$param] = $value;
					else
					$params_array[$param] = $params_array[$param];
				}
			}
		}
		$count = 0;
		$total = count($params_array);
		if($total>0 && !empty($params_array))
		{
			foreach($params_array as $param => $value)
			{
				$count++;
				if($count==1)
					$url_param .= '?';
				$url_param .=$param.'='.$value;
				if($count != $total)
					$url_param .='&';
			}
		}
		
		$finalUrl = $clean_url.$url_param ;
		if($urlencode ==true)
			$finalUrl = urlencode($finalUrl);
		return $finalUrl;
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
	function create_link($page,$link,$extra_params=NULL,$tag,$return_param=false)
	{
		$page_pattern = '#page#';
		$param_pattern = '#params#';
		$page_url_param = $this->url_page_var;
		$page_link_pattern = $page_url_param.'='.$page_pattern;
		
		preg_match('/\?/',$link,$matches);
		
		if(!empty($matches[0]))
		{
			$page_link = '&'.$page_link_pattern;
		}else{
			$page_link = '?'.$page_link_pattern;
		}
		
		$link = $link.$page_link;
		$params = 'href="'.$link.'"';
		$params .= ' '.$extra_params;
	
		$final_link = preg_replace(array("/$page_pattern/i","/$param_pattern/i"),array($page,$params),$tag);
		$final_link = preg_replace(array("/$page_pattern/i","/$param_pattern/i"),array($page,$params),$final_link);
		
		if($return_param)
		{
			return preg_replace("/$page_pattern/i",$page,$params);
		}
		
		return $final_link;
	}

	/**
	 * Function used to create pagination
	 * @param : total number of pags
	 * @param : current page
	 * @param : extra paraments in the tag ie <a other_params_go_here
	 * @param : tag used for pagination
	 */
	function pagination($total,$page,$link,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		
		$total_pages = $total;
		$pagination_start = 10;
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
					$start .= '<span class ="selected">'.$i.'</span>';
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
					$end .= '<span class ="selected">'.$i.'</span>';
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
						$mid .= '<span class ="selected">'.$i.'</span>';
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
					$pagination_smart .= '<span class ="selected">'.$i.'</span>';
				else
					$pagination_smart .=$this->create_link($i,$link,$extra_params,$tag);
			}
			
			return $pagination_smart;
		}
	}
	
	
	/**
	 * Function used to create pagination and assign values that can bee used in template
	 */
	function paginate($total,$page,$link,$extra_params=NULL,$tag='<a #params#>#page#</a>')
	{
		$this->pagination = $this->pagination($total,$page,$link,$extra_params,$tag);
		//Assigning Varaiable that can be used in templates
		assign('pagination',$this->pagination);
		
		assign('next_link',$this->next_link);
		assign('pre_link',$this->pre_link);
		
		assign('first_link',$this->first_link);
		assign('last_link',$this->last_link);
	}
	
}
?>