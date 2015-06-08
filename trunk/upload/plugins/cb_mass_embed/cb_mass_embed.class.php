<?php

/**
 * This class is used to control all video websites
 * embedding
 * @Auhor : Arslan Hassan
 * @License : CBLA
 * @Software : ClipBucket
 *
 *
 * RESELLING or DISTRUBUTION IS PROHIBITTED
 */
 

class cb_mass_embed
{
	
	/**
	 * Keywords are you used to get videos
	 * from website based on these words or say tags 
	 * that we can use either in XML or for SEARCHING
	 */
	var $keywords = "sports,games,ps3,laptops";
	 
	 
	/**
	 * Sort type is used to tell out script to get results
	 * based on either relevance, published,views, rating
	 */
	var $sort_type = "relevance";
	 
	
	/**
	 * Time is used to tell script to get videos in particular time only
	 * this will only work with Youtube
	 * today (1 day), this_week (7 days), this_month (1 month) and all_time. The default value for this parameter is all_time.
	 */
	var $result_time = 'all_time';
	 
	/**
	 * Results are used to tell script number of results get
	 * once script is called
	 */
	var $results = 20;
	 
	/**
	 * feed_url is used to save FEED URL in a string that will be called using
	 * cURL to fetch results
	 */
	var $feed_url;
	 
	/**
	 * when feed_url is called, the result we get will be in HTML format
	 * that we will parse later but first we need to store it in html_data
	 */
	var $html_data;
	
	
	/**
	 * this will tell our script weather feed is XML based or not
	 */
	var $xml_api = true;
	
	/**
	 * This var will carry all data as array
	 */
	var $results_array = array();
	
	/**
	 * Database table
	 */
	var $tbl = 'mass_embed';
	var $ctbl = 'mass_embed_configs';
	
	
	/**
	 * This Value holds Configs value
	 */
	var $configs = array();
	
	/**
	 * userid, this will tell our embed whom to assign embedde videos
	 * if null, it will use logged in user
	 */
	var $userid = NULL;
	
	var $license_status = true;
	
	
	/**
	 * This variable will tell weather to insert views and other stats or not
	 */
	var $import_stats = false;
	
	/**
	 * This variable will tell weather to import comments or not
	 */
	var $import_comments = false;
	
	/**
	 * This variable holds the value of total results that are inserted
	 */
	var $_total_results = 0;
	
	
	
	/**
	 * Variable that holds "ALREADY EXIST" number of videos
	 */
	var $already_exist = 0;
	var $unknown_urls = 0;
	var $_data_exists = false;
	
	
	var $insert_vids = array();
	
	var $results_found = 0;
	
	var $tries = 0;
	var $max_tries = 3;
	var $this_page = 1;
	
	/**
	 * __CONSTRUCTOR
	 * Saves the configurations value
	 */
	function cb_mass_embed()
	{
		$this->get_configs();
		
		$this->results = $this->configs['results'];
		$this->keywords = $this->configs['keywords'];
		$this->sort_type = $this->configs['sort_type'];
		$this->result_time = $this->configs['time'];
		$this->import_stats = $this->configs['import_stats'];
		$this->import_comments = $this->configs['import_comments'];
	}
	
	
	
	/**
	 * Function used to send request and fetch results from the website
	 */
	function get_results()
	{
		
		if(!$this->xml_api)
		{
			$results = $this->open_page($this->feed_url);
			if(!$results)
				$this->html_data = 'no_data';
			else
				$this->html_data = $results;
		}else
		{
			
		}
	}
	
	
	
	/**
	 * This function is used to open a page using CURL
	 */
	function open_page($url)
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$page = curl_exec($ch);
		
		if (!curl_errno($ch))
		{
			curl_close($ch);
		}
		else
		{
			$page = false;
		}
		return $page;
	}
	
	
	/*
	 * Function : Snatch it
	 * paramaters : URL of ile to be snatched
	 * 			 : Destination Folder
	 * 			 : Snatched File Name after snatching
	 */
	
	function snatch_it($snatching_file,$destination,$dest_name,$rawdecode=true)
	{
		if($rawdecode==true)
		$snatching_file= rawurldecode($snatching_file);
		$destination.'/'.$dest_name;
		$fp = fopen ($destination.'/'.$dest_name, 'w+');
		$ch = curl_init($snatching_file);
		curl_setopt($ch, CURLOPT_TIMEOUT, 600);
		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_USERAGENT, 
		'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2');
		curl_exec($ch);
		curl_close($ch);
		fclose($fp);
	}
	
	/**
	 * Function used to check weather data already exists or not
	 */
	function data_exists($id,$count_result=true,$website=false)
	{
		global $db;
		if(!$id)
			return false;
		
		if(!$website)
			$website = $this->website;
		
		$count = $db->count(tbl($this->tbl),"mass_embed_id","mass_embed_unique_id='".$id."' AND mass_embed_website='".$website."'");
		
		if($count>0)
		{
			if($count_result)
			$this->already_exist = $this->already_exist + 1 ;
			$this->_data_exists = true;
		}
		return $count;
	}
	
	/**
	 * Funcntion used to insert data in database
	 */
	function download_and_insert_data()
	{
		
		global $db,$cbvid,$Upload;
		
		$results = $this->results_array;
		
		foreach($results as $result)
		{
		
			#pr($result,true);
			$file_key = time().RandomString(5);
			//Inserting data in database for our record
			$db->insert(tbl($this->tbl),array("mass_embed_website","mass_embed_url","mass_embed_unique_id","date_added"),
			array($result['website'],$result['url'],$result['unique_id'],now()));
			
			//Now Downloading Thumbs
			$count = 1;
			foreach($result['thumbs'] as $key=>$thumb)
			{
				if($key!=='big')
				{
					$this->snatch_it(urlencode($thumb),THUMBS_DIR,$file_key."-$count.jpg");
				}else
				{
					$this->snatch_it(urlencode($thumb),THUMBS_DIR,$file_key."-big.jpg");
				}
				$count++;
			}
			
			//Setting Category
			$cat = $cbvid->get_default_category();
			//Matching Category
			if($this->categorization=='auto'){
				$thecat = $this->match_cat($result['tags']);
				if(!$thecat)
					$thecat = $this->match_cat($this->keywords);
			}
			if($thecat)
				$result['category'] = $thecat;
			else	
				$result['category'][] = $cat['category_id'];
			if($this->categorization=='selected')
			{
				$result['category'] = array();
				$result['category'][] = $this->set_category;	
			}
			
			$result['file_name'] = $file_key ;
			
			//Inserting Data
			$result['userid'] = $this->userid;
			
			/*if(is_array($result['tags'])){
				pr($result['tags'],true);
			}*/
			if(empty($result['tags']))
			$result['tags']='video';

			if(empty($result['Description'])){
				$result['Description']='Description';
			}
			if(empty($result['description'])){
				$result['description']='description';
			}

			if(isset($result['comments'])){
				$comment_arry = $result['comments'];
				unset($result['comments']);
			}
			$vid = $Upload->submit_upload($result);
			
			if($vid)
			$this->_total_results++;

			$this->insert_vids[] = $vid;
			
			embed_video_check($vid);
			
			$vid_fields[] = 'mass_embed_status';
			$vid_vals[] = 'pending';
			
			$vid_fields[] = 'unique_embed_code';
			$vid_vals[] = $result['unique_id'];
			
			if($_POST['use_epn'])
			{
				$vid_fields[] = 'ebay_epn_keywords';
				$vid_vals[] = $_POST['epn_keywords'];
				
				$vid_fields[] = 'ebay_pre_desc';
				$vid_vals[] = $_POST['epn_desc'];
			}
			
			//Setting Embed Status to Pending & Inserting Rating and other data
			$db->update(tbl("video"),$vid_fields,$vid_vals," videoid='$vid'");
			
			//CHecking weather update rating and views or not
			if($this->import_stats)
			{
				$db->update(tbl("video"),array("views","rating","rated_by","date_added"),
				array($result['views'],$result['rating'],$result['rated_by'],$result['date_added'])," videoid='$vid'");
			}
			
			if(is_array($comment_arry) &&  $this->import_comments )
			{
				#pr($result['comments']);
				#exit('got it....');
				foreach($comment_arry as $comment)
				{
					//echo $comment['comment'].' = '.$vid.'<br/>';

					$_POST['name'] = $comment['name'];
					$_POST['email'] = $comment['email'];
					$_POST['comment'] = $comment['comment'];
					if($_POST['comment']!='')
					$cbvid->add_comment($_POST['comment'],$vid,NULL,true);
				}
				
			}
			
		}
		
				
	}
	
	
	/**
	 * Funcntion used to insert data in database
	 */
	function display_data()
	{
		
		global $db,$cbvid,$Upload;
		
		$results = $this->results_array;
		pr($results);
				
	}
	
	/**
	 * Function used to get available APIs
	 */
	function get_apis()
	{
		$apis = array();
		$files = glob(dirname(__FILE__).'/apis/*.api.php');
		foreach($files as $file)
		{
			$file_name = getName($file);
			$file_name = getName($file_name);
			$apis[] = $file_name;
		}		
		rsort($apis);
		return $apis;
	}
	
	/**
	 * Get Configs
	 */
	function get_configs()
	{
		global $db;
		$results = $db->select(tbl($this->ctbl),"*");
		$configs = array();
		foreach($results as $result)
		{
			$configs[$result['config_name']] = $result['config_value'];
		}
		
		return $this->configs = $configs;
	}
	
	/**
	 * Function used to set config
	 */
	function set_config($name,$value,$load_configs=true)
	{
		global $db;

		if($db->count(tbl($this->ctbl),"config_id"," config_name='$name'")>0)
		{
			$db->update(tbl($this->ctbl),array("config_value"),array($value)," config_name='$name' ");
		}else
		{
			$db->insert(tbl($this->ctbl),array("config_name","config_value"),array($name,$value));
		}
		if($load_configs)
		$this->get_configs();
	}
	
	function config($name)
	{
		return $this->configs[$name];
	}
	
	/**
	 * Function used to get available apis
	 */
	function get_installed_apis()
	{
		$apis = $this->configs['apis'];
		$apis = explode(",",$apis);
		$the_apis = array();
		foreach($apis as $api)
		{
			$the_apis[$api] = $api;
		}
		
		return $the_apis;
	}
	

	
	/**
	 * Function used to get Category keywords
	 */
	function get_cat_keywords()
	{
		$cat_keys = $this->configs['category_keywords'];
		$cat_keys = explode("|",$cat_keys);
		$array = array();
		foreach($cat_keys as $ck)
		{
			list($id,$keys) = explode(":",$ck);	
			$array[$id] = $keys;
		}
		return $array;
	}
	
	
	
	/**
	 * Function used to match category from an array to array
	 */
	function match_cat($cat_attr)
	{
		if(is_array($cat_attr))
			$cat_attr = implode(',',$cat_attr);

		if(is_string($catt_attr))
		$cat_attr = strtolower($cat_attr);
		#$cat_attr = explode(",",$cat_attr);
		$cats = $this->get_cat_keywords();	
		
		$thecats = array();
		$count = 1;
		#$cat_attr ='laptop, world, new, ios';
		foreach($cats as $key => $cat)
		{
			if($cat)
			{
				$cattags = explode(",",$cat);
				foreach($cattags as $cattag)
				{
					if($cattag)
					{
						$cattag = strtolower($cattag);
						$cat_attr =strtolower($cat_attr);					
						if(substr($cattag,0,1)==' ')
							$cattag = substr($cattag,1,strlen($cattag));
							
						#echo "Matching <strong>$cattag</strong> in --- $cat_attr";
						
						if(strpos($cat_attr,$cattag) !== false )
						{
							#echo "$cat_attr found in $cat<br>";
							$thecats[] =  $key; 
							$count++;
							#echo " (<strong>FOUND</strong>) ";
						}
						#echo "<br>";
					}
				}
			}
		}
		//echo "thecate";
		//pr($thecats,true);
		//echo "end thecate";
		#exit('check for cate'.$thecats);
		if(count($thecats)>0)
			return $thecats;
		else
			return false;
	}
	
	
	
	
	/**
	 * Function used to get input and parse details
	 * to sperate them into proper urls
	 * once they are done, it will get details 1 by 1 and
	 * stores them in array and follow the general method
	 * @param : Input URLS
	 */
	function the_mass_links($input)
	{
		$apis = $this->get_installed_apis();
		foreach($apis as $api)
		{
			include_once(PLUG_DIR."/cb_mass_embed/apis/$api.api.php");
		}
			
		$input = preg_replace(array("/\t/","/ /"),"",$input);
		$links = explode("\n",$input);
		$data = array();
		
		if(is_array($links))
		{
			foreach($links as $link)
			{
				$link_details = $this->get_details_from_url($link);
				
				if($link_details)
					$data[] = $link_details;
				else
				{
					if(!$this->_data_exists)
					$this->unknown_urls++;
				}
			}	
			$this->results_array = $data;			
		}
	}
	
	/**
	 * Function used to check which url it belongs to
	 * and then get its deatils accordingly
	 */
	function get_details_from_url($url)
	{

		//first run the API loop, check which website it belongs to and then
		// get its details
		$apis = $this->get_installed_apis();
		foreach($apis as $api)
		{
			$embed = new $api();

			$details = $embed->get_details_from_url($url);
			$this->already_exist += $embed->already_exist;
			$this->_data_exists = $embed->_data_exists;

			if($details)
				return $details;
		}
		return false;		
	}
	
		
	
	/**
	 * @ function name : validate_wp_input
	 * @ return : true : false
	 *
	 * This function is used to validate wordpress url, username and password
	 * first will check wordpress url, then login via author, valdiate
	 * secret key and then return any error otherwise return true
	 */
	function validate_wp_input($disable_on_error=true)
	{
		$wp_url = $this->config('wp_blog_url');
		$wp_secret_key = $this->config('cb_wp_secret_key');
		$wp_user = $this->config('wp_author_user');
		$wp_pass = $this->config('wp_author_pass');
		
		//connecting to wordpress blog...
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$wp_url);
		curl_setopt($ch,CURLOPT_HEADER,true);
		curl_setopt($ch,CURLOPT_NOBODY,true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$data = curl_exec($ch);
		curl_close($ch);
		preg_match("/HTTP\/1\.[1|0]\s(\d{3})/",$data,$matches);
		
		$auth = true;
		if($matches[1]!='200')
		{
			e("Unable to connect wordpress blog : $wp_url<br>$data");
			$auth = false;
		}
		
		if(!$auth)
		{
			if($disable_on_error);
			$this->set_config('enable_wp_integeration','no');
			return false;
		}
		
		//Now authenticating user	
	}
	
	/**
	 * Function used to check weather categoires are synced with wordpress blog or not
	 * @param BOOLEAN $return_date return date of last synced or not
	 * @return BOOLEAN $status $date return date and status of category sync
	 */
	function category_synced($returnDate)
	{
		$synced = $this->config('category_synced');
		$theSyncDate = $this->config('category_synced_date');
		
		if($returnDate && $theSyncDate)
			$synced_date = nicetime($theSyncDate);
		if($synced=='yes')
			return '<span style="color:#63d200">YES</span> '.$synced_date;
		else
			return '<span style="color:#ed0000">NO</span> '.$synced_date;
	}
	
	/**
	 * Function used to sync categories
	 */
	function sync_categories()
	{
		if($this->config('enable_wp_integeration')=='yes')
		{
			//lets try syncing categories
			$wp_url = $this->config('wp_blog_url');
			$sync_url = $wp_url.'?do_the_cats=yes';
			$post_fields['cb_sync_categories'] = 'yes';
			$raw_cats = getCategoryList();
			foreach($raw_cats as $cat)
			{
				$categories[$cat['category_id']] = $cat['category_name'];
			}
			$post_fields['categories'] = json_encode($categories);
			$post_fields['secrey_key'] = $this->config('cb_wp_secret_key');
			$ch = curl_init();
			curl_setopt($ch,CURLOPT_URL,$sync_url);
			curl_setopt($ch,CURLOPT_POST,true);
			curl_setopt($ch,CURLOPT_POSTFIELDS,$post_fields);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			$data = curl_exec($ch);
			curl_close($ch);
			
			if($data=='invalid_secret_key')
			{
				e("Invalid Secret Key");
				return false;
			}else
			{
				$this->set_config('synced_cats','|no_mc|'.$data);
				$this->set_config('category_synced','yes');
				$this->set_config('category_synced_date',NOW());
				return $data;
			}
			
		}else
			e("Wordpress integeration is not enabled, please enable it then try");
	}
	
	
	/**
	 * Function used to convert clipbucket categories to wordpress categories
	 */
	function cb_to_wp_cat($input,$json=true)
	{
		if(!is_array($input))
		{
			preg_match_all('/#([0-9]+)#/',$input,$m);
			$cat_array = array($m[1]);
			$cat_array = $cat_array[0];
		}else
			$cat_array = $input;
		$wp_cats = array();
		$syncd_cats = json_decode($this->config('synced_cats'),true);
		
		foreach($cat_array as $cat)
		{
			foreach($syncd_cats as $cid => $sc)
			{
				if($cid==$cat)
					$wp_cats[] = $sc;
			}
		}
		
		if(!$json)
		return $wp_cats;
		else
		return json_encode($wp_cats);
	}
	
	
	/**
	 * function used to post videos to worpdress
	 */
	function post_to_wp($posts)
	{
		if($this->config('enable_wp_integeration')!='yes')
		{
			e("Wordpress integeration is not active");
			return false;
		}
	
		$post_field['posts'] = json_encode($posts);
		$post_field['secret_key'] = $this->config('cb_wp_secret_key');
		$post_field['cb_insert_posts'] = 'yes';
		
		$wp_url = $this->config('wp_blog_url');
		$sync_url = $wp_url.'?do_the_videos=yes';
			
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$sync_url);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$post_field);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		$data = curl_exec($ch);
		curl_close($ch);
		
		if($data=='invalid_secret_key')
		{
			e("Invalid secret key for wordpress integeration");
		}
		
		if($data['videos_added'])
		{
			e("Videos have been added wordpress, successfully","m");
		}
	}

}


?>