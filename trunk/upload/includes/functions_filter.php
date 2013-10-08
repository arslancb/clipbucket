<?php        
        
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