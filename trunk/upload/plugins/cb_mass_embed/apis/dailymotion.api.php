<?php

/**
 * This class is used to get videos from
 * Dailymotion.com
 *
 * Parameters for all API requests:
 * client
 * key
 *
 * Search parameters:
 * keyword
 * search_type
 * page
 */
 


class dailymotion extends cb_mass_embed
{
	
	
	var $xml_api = false;
	
	var $result_offset = 1;
	
	var $results_got = 0;
	
	var $website = 'dailymotion';
	
	
	/**
	 * Function used to create API FEED URL
	 * this url will be called by SERVER and FETCH results and save it in 
	 * $html_data
	 * http://www.dailymotion.com/rss/relevance/search/asd/1
	 */
	function get_feed_url()
	{
		$APIUrl = 'http://www.dailymotion.com/rss/';
		
		#Sorting
		if($this->get_sort_type())
		{
			$APIUrl .= $this->get_sort_type();
			#Setting time Frame
			if($this->get_time_span())
			{
				$APIUrl .= '-'.$this->get_time_span();
			}
			$APIUrl .= '/';
		}
		#Adding Search Keyword
		$APIUrl .='search/';
		#Adding Query Keywords
		$APIUrl .= $this->get_keyword_query().'/';
		#Setting The Offset
		$APIUrl .= $this->result_offset;
		
		return $this->feed_url = $APIUrl ;
	}
	
	
	
	/**
	 * Function used to return API ready result type
	 * ie if user set RELEVENCE then Youtube's API result type would be orderby=relevance
	 */
	function get_sort_type()
	{
		$type = $this->sort_type;
		$d_sorting = array
		('relevance'=>'relevance', 'published'=>NULL,
		 'views'=>'visited','rating'=>'rated'
		 );
		$sorttype = $d_sorting[$type];
		if($sorttype)
			return $sorttype;
		else
			return '';
	}
	
	
	/**
	 * Function used to get max results that an API can request
	 */
	function max_results()
	{
		if($this->max_results>50)
			return 50;
		else
			return $this->max_results;
	}
	
	/**
	 * Function used to convert KEYWORDS into QUERY
	 */
	function get_keyword_query()
	{
		$keywords = $this->keywords;
		$keywords = preg_replace("/, /",",",$keywords);
		return $keywords = urlencode(preg_replace("/ ,/",",",$keywords));
	}
	
	/**
	 * Function used to parse the feed and convert it into an array
	 */
	function parse_and_get_results()
	{
		$counter=0;
		$this->results_got = 1;
		$vids = array();
		while($this->results_got<=$this->results)
		{
			if($this->tries > $this->max_tries)
				break;
			$this->tries++;
			//echo $this->get_feed_url().'<br/>';
			$array = xml2array($this->get_feed_url());
			//$array = xml2array('http://www.dailymotion.com/rss/commented/search/smart/1');
			$entries = $array['rss']['channel']['item'];
			
			#pr($entries,true);
			if(empty($entries[0]['title']) && empty($entries['title']))
				break;
			if($entries['title'])
				$entries = array($entries);
			//echo 'entries <br/>'.count($entries);
			$this->results_found = count($entries);
			
			if($this->results_found > $this->results)
				$this->results_found = $this->results;
			
			foreach($entries as $entry)
			{	$counter++;
				if($this->results_got > $this->results)
					break;
				if(is_array($entry) && isset($entry['title']) && (isset($entry['itunes:keywords'])
					||isset($entry['media:player'])) && !$this->data_exists($entry['dm:id']))
				{
					$count = $this->results_got;
					$vids[$count]['title'] = $entry['title'];
					$desc = preg_match('/<p>(.*)<\/p><p>/',$entry['description'],$matches);
					$vids[$count]['description'] = $matches[1];
					$vids[$count]['tags'] = $entry['itunes:keywords'];
					
					$vids[$count]['embed_code'] = $this->embed_video_code($entry['media:group']['media:content']['1_attr']['url']);
					$vids[$count]['duration'] = $entry['media:group']['media:content']['0_attr']['duration'];
					$vids[$count]['views'] = $entry['dm:views'];
					$vids[$count]['rating'] = $entry['dm:videorating']*2;
					$vids[$count]['rated_by'] = $entry['dm:videovotes'];
			 		$vids[$count]['date_added'] = date("Y-m-d H:i:s",strtotime($entry['pubDate']));
					$vids[$count]['category_attr'] = strtolower($entry['media:group']['media:category']);
					
					$vids[$count]['website'] = $this->website;
					$vids[$count]['url'] = $entry['link'];
					$vids[$count]['unique_id'] = $entry['dm:id'];
					
					preg_match('/src="(.*)"/U',$entry['description'],$matches);
					$vids[$count]['thumbs'][] = $matches[1];

					$vids[$count]['thumbs']['big'] = $entry['media:thumbnail_attr']['url'];
					
					//Fetching Comments
					$comments = $this->get_comments($entry['link']);
					if($comments)
						$vids[$count]['comments'] = $comments;
						
					$this->results_got++;
				}		
			}
						
			$this->get_the_offset();
		}
		//echo $this->results_got;
		//echo '<br/>';
		//echo $counter;
		//echo '<br/>';
		//echo 'results'.$this->results.'<br/>';
		//echo 'got'.$this->results_got.'<br/>';
		//echo 'videos'.count($vids);
		//exit;
		return $this->results_array = $vids;
	}
	
	
	
	function parse_get_results($apiFeed=NULL)
	{
		
		$this->results_got = 1;
		$vids = array();
		
		$this->tries++;
		#echo $this->get_feed_url().'<br/>'; 
		if(!$apiFeed)
		$array = xml2array($this->get_feed_url());
		else
		$array = xml2array($apiFeed);
		
	
		$entries = $array['rss']['channel']['item'];
		
		if(empty($entries[0]['title']) && empty($entries['title']))
			break;
		if($entries['title'])
			$entries = array($entries);		
			
		$this->results_found = count($entries);
		
		foreach($entries as $entry)
		{
			if($this->results_got > $this->results)
					break;
				
				if(!$this->ignore_data_exists)
					$data_exists = $this->data_exists($entry['dm:id']);
				else
					$data_exists = false;
			if(is_array($entry) && isset($entry['title']) && (isset($entry['itunes:keywords'])
				||isset($entry['media:player'])) && !$data_exists)
			{
				$count = $this->results_got;
				$vids[$count]['title'] = $entry['title'];
				$desc = preg_match('/<p>(.*)<\/p><p>/',$entry['description'],$matches);
				$vids[$count]['description'] = $matches[1];
				$vids[$count]['tags'] = $entry['itunes:keywords'];
				
				$vids[$count]['embed_code'] = $this->embed_video_code($entry['media:group']['media:content']['1_attr']['url']);
				$vids[$count]['duration'] = $entry['media:group']['media:content']['0_attr']['duration'];
				$vids[$count]['views'] = $entry['dm:views'];
				$vids[$count]['rating'] = $entry['dm:videorating']*2;
				$vids[$count]['rated_by'] = $entry['dm:videovotes'];
				$vids[$count]['date_added'] = date("Y-m-d H:i:s",strtotime($entry['pubDate']));
				$vids[$count]['category_attr'] = strtolower($entry['media:group']['media:category']);
				
				$vids[$count]['website'] = $this->website;
				$vids[$count]['url'] = $entry['link'];
				$vids[$count]['unique_id'] = $entry['dm:id'];
				
				preg_match('/src="(.*)"/U',$entry['description'],$matches);
				$vids[$count]['thumbs'][] = $matches[1];

				$vids[$count]['thumbs']['big'] = $entry['media:thumbnail_attr']['url'];
				
				//Fetching Comments
				$comments = $this->get_comments($entry['link']);
				if($comments)
					$vids[$count]['comments'] = $comments;
					
				$this->results_got++;
			}		
		}
					
		$this->get_the_offset();
		return $vids;
	}

	/**
	 * Function used to generate Embed Video Code
	 */
	function embed_video_code($code)
	{
		$sample = '<object width="425" height="344"><param name="movie" value="{FILE}"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="{FILE}" type="application/x-shockwave-flash" width="425" height="344" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
		return preg_replace('/{FILE}/',$code,$sample);
	}
	
	/**
	 * function used to get offseet
	 * if will return doubles the MAX_RESULT each time it is called
	 */
	function get_the_offset()
	{
		return $this->result_offset += $this->result_offset;
	}
	
	/**
	 * Function used to convert time span into period
	 * so that only videos uploaded in specifi time frame are show or embed
	 */
	function get_time_span()
	{
		$time = $this->result_time;
		$dmtime = array('today'=>'today','this_month'=>'month','this_week'=>'week');
		if($dmtime[$time])
			return $dmtime[$time];
		else
			return NULL;
	}
	
	/**
	 * Function used to get video comments
	 */
	function get_comments($url)
	{
		
		if($url =='' || !$this->import_comments)
			return false;
		
		$content =str_replace("\n", " ", trim($this->open_page($url))); 
		#pr($content,true);
		#preg_match_all('/<div class="dmpi_comment_item" id="comment_(.*)">(.*)<span class="dmco_simplelink dmpi_user_title name dmco_simplelink dmco_link linkable(.*)">(.*)<\/span>(.*)<div class="dmco_html">(.*)<\/div>/Uim',$content,$matches);
		preg_match_all('/<div id="comment_(.*?)"><div (.*?)>(.*?)<\/div><div (.*?)><div (.*?)>(.*?)<\/div><div (.*?)>(.*?)<\/div><\/div><\/div>/Uim',$content,$matches);
		#pr($matches,true);
		$comments = $matches[6];
		$users = $matches[4];
		$total = count($comments);
		$comm_array = array();
		for($i=0;$i<$total;$i++)
		$comm_array[] = array('name'=>$users[$i],'comment'=>strip_tags($comments[$i]),'email'=>'anonymous@dailymotion.com');
		
		return $comm_array;
	}
	
	/**
	 * Function used to get details from url
	 */
	function get_details_from_url($url)
	{
		$explodeUrl	= explode("video/", $url);
		if(strstr($explodeUrl[1], "/"))
		{
			$explodeUrl = explode("/", $explodeUrl[1]);
			$videoid = $explodeUrl[0];
		}elseif(strstr($explodeUrl[1],"_"))
		{
			$explodeUrl = explode("_", $explodeUrl[1]);
			$videoid = $explodeUrl[0];
		}else{
			$videoid = $explodeUrl[1];
		}
		
		//Now Checking if ID is empty or not
		if(!empty($videoid) && !$this->data_exists($videoid))
		{
			$entry = xml2array("http://www.dailymotion.com/rss/video/".$videoid);
			
			$entry = $entry['rss']['channel']['item'];
			$vids['title'] = $entry['title'];
			$desc = preg_match('/<p>(.*)<\/p><p>/',$entry['description'],$matches);
			$vids['description'] = $matches[1];
			$vids['tags'] = $entry['itunes:keywords'];
			
			$vids['embed_code'] = $entry['media:player'];
			$vids['duration'] = $entry['media:group']['media:content']['0_attr']['duration'];
			$vids['views'] = $entry['dm:views'];
			$vids['rating'] = $entry['dm:videorating']*2;
			$vids['rated_by'] = $entry['dm:videovotes'];
			$vids['date_added'] = date("Y-m-d H:i:s",strtotime($entry['pubDate']));
			$vids['category_attr'] = strtolower($entry['itunes:keywords']);
			
			$vids['website'] = $this->website;
			$vids['url'] = $entry['link'];
			$vids['unique_id'] = $videoid;

			$vids['thumbs'][] = $entry['media:thumbnail_attr']['url'];
			$vids['thumbs']['big'] = $entry['media:thumbnail_attr']['url'];
					
			$vids['date_added'] = date("Y-m-d H:i:s",strtotime($entry['pubDate']));
			
			//Fetching Comments
			$comments = $this->get_comments($entry['link']);
			if($comments)
				$vids['comments'] = $comments;
			return $vids;
		}
		else
			return false;
	}
}

?>