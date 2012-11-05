<?php

    /**
	 * Funcion used to call functions
	 * when video is going to watched
	 * ie in watch_video.php
	 */
	function call_watch_video_function($vdo)
	{
		global $userquery;

		$funcs = get_functions('watch_video_functions');
            
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				
				if(function_exists($func))
				{
					$func($vdo);
				}
			}
		}

		increment_views($vdo['videoid'],'video');

		if(userid())
			$userquery->increment_watched_vides(userid());

	}
	
	/**
	 * Funcion used to call functions
	 * when video is going
	 * on CBvideo::remove_files
	 */
	function call_delete_video_function($vdo)
	{
		$funcs = get_functions('on_delete_video');
		if(is_array($funcs) && count($funcs) > 0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($vdo);
				}
			}
		}
	}

	
	/**
	 * Funcion used to call functions
	 * when video is going to dwnload
	 * ie in download.php
	 */
	function call_download_video_function($vdo)
	{		
		global $db;
		$funcs = get_functions('download_video_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($vdo);
				}
			}
		}
		
		//Updating Video Downloads
		$db->update(tbl("video"),array("downloads"),array("|f|downloads+1"),"videoid = '".$vdo['videoid']."'");
		//Updating User Download
		if(userid())
		$db->update(tbl("users"),array("total_downloads"),array("|f|total_downloads+1"),"userid = '".userid()."'");
	}
	
	
	/**
	 * Funcion used to call functions
	 * when user view channel
	 * ie in view_channel.php
	 */
	function call_view_channel_functions($u)
	{
		$funcs = get_functions('view_channel_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($u);
				}
			}
		}
		
		increment_views($u['userid'],"channel");
	}
	
	
	
	
	/**
	 * Funcion used to call functions
	 * when user view topic
	 * ie in view_topic.php
	 */
	function call_view_topic_functions($tdetails)
	{
		$funcs = get_functions('view_topic_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($tdetails);
				}
			}
		}
		
		increment_views($tdetails['topic_id'],"topic");
	}


	

	/**
	 * Funcion used to call functions
	 * when user view group
	 * ie in view_group.php
	 */
	function call_view_group_functions($gdetails)
	{
		$funcs = get_functions('view_group_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($gdetails);
				}
			}
		}
		increment_views($gdetails['group_id'],"group");
	}
	
	/**
	 * Funcion used to call functions
	 * when user view collection
	 * ie in view_collection.php
	 */
	function call_view_collection_functions($cdetails)
	{
		$funcs = get_functions('view_collection_functions');
		if(is_array($funcs) && count($funcs)>0)
		{
			foreach($funcs as $func)
			{
				if(function_exists($func))
				{
					$func($cdetails);
				}
			}
		};

		increment_views($cdetails['collection_id'],"collection");
	}

        
        
        /**
	 * Function used to call functions
	 */
	function call_functions($in,$params=NULL)
	{
		if(is_array($in))
		{
			foreach($in as $i)
			{
				if(function_exists($i))
					if(!$params)
						$i();
					else
						$i($params);
			}
		}else
		{
			if(function_exists($in))
					if(!$params)
						$in();
					else
						$in($params);
		}
		
	}
        
        
        /**
         * function used to apply filters or should I say functions on 
         * anything given
         * 
         * first a filter must be registered using register_filter
         * 
         * @param STRING $content or $object on which filters are applied
         * @param STRING $type type of filter
         */
        
        function apply_filters($content,$type)
        {
            //Get list of filters
            $filters = get_filters($type);
            
            if($filters)
            {
                foreach($filters as $filter)
                {
                    if(function_exists($filter['filter']))
                    {
                        $params = $filter['params'];
                        if($params)
                            $content = $filter['filter']($content,$params);
                        else
                            $content = $filter['filter']($content);
                    }
                }
            }
            
            return $content;
        }
        
        /**
         * get list of filters of given type
         * 
         * @param STRING type
         * @return ARRAY filters
         */
        function get_filters($type)
        {
            global $Cbucket;

            if(isset($Cbucket->filters[$type]));
                return $Cbucket->filters[$type];
        }
        
        /**
         * register ae clipbucket filter
         * 
         * @param STRING filtername
         * @param STRING function nam
         * @param ARRAY paramters to be passed when registering a filter
         */
        function register_filter($name,$func,$params=false)
        {
            global $Cbucket;
            
            if($name && function_exists($func))
            {
                $Cbucket->filters[$name][] = array('filter' => $func,'params'=>$params);
            }
        }
        
        
        

?>