<?php


        /**
	 * Function used to validate category
	 * INPUT $cat array
	 */
	function validate_group_category($array=NULL)
	{
		global $cbgroup;
		return $cbgroup->validate_group_category($array);
	}
        
        
        /**
	 * function used to check weather group URL exists or not
	 */
	function group_url_exists($url)
	{
		global $cbgroup;
		return $cbgroup->group_url_exists($url);
	}
        
        
        /**
	 * function used to get groups
	 */
	function get_groups($param)
	{
		global $cbgroup;
		return $cbgroup->get_groups($param);
	}
        
        
        /**
	 * Function used display privacy in text
	 * according to provided number
	 * 0 - Public
	 * 1 - Protected
	 * 2 - Private
	 */
	 function getGroupPrivacy($privacyID)
	 {
                {
                        switch($privacyID)
                        {
                                case "0": default:
                                {
                                        return lang("group_is_public");
                                }
                                break;

                                case "1":
                                {
                                        return lang("group_is_protected");
                                }
                                break;

                                case "2":
                                {
                                        return lang("group_is_private");
                                }
                                break;
                        }
                }
	 }	
	
         
        /**
         * get group link
         * 
         * @param type $params
         * @return type 
         */
	function group_link($params)
	{
		$grp = $params['details'];
		$id = $grp['group_id'];
		$name = $grp['group_name'];
		$url = $grp['group_url'];
		
		if($params['type']=='' || $params['type']=='group')
		{
			if(SEO==yes)
				return BASEURL.'/group/'.$url;
			else
				return BASEURL.'/view_group.php?url='.$url;
		}
		
		if($params['type']=='view_members')
		{
			return BASEURL.'/view_group_members.php?url='.$url;
			if(SEO==yes)
				return BASEURL.'/group_members/'.$url;
			else
				return BASEURL.'/view_group_members.php?url='.$url;
		}
		
		if($params['type']=='view_videos')
		{
			return BASEURL.'/view_group_videos.php?url='.$url;
			if(SEO==yes)
				return BASEURL.'/group_videos/'.$url;
			else
				return BASEURL.'/view_group_videos.php?url='.$url;
		}
		
		if($params['type'] == 'view_topics')
		{
			if(SEO == "yes")
				return BASEURL."/group/".$url."?mode=view_topics";
			else
				return BASEURL."/view_group.php?url=".$url."&mode=view_topics";		
		}
		
		if($params['type'] == 'view_report_form')
		{
			if(SEO == "yes")
				return BASEURL."/group/".$url."?mode=view_report_form";
			else
				return BASEURL."/view_group.php?url=".$url."&mode=view_report_form";	
		}
	}
    
    
    
    /**
     * function used to get group fields
     * 
     * @param ARARY $extra_fields
     */
    function get_group_fields($extra=NULL)
    {
        $fields = array(
                'group_name','userid','group_url','category','total_views','total_videos','total_members','total_topics'
        );
        
        if(isset($extra) && !is_array($extra))
            $fields[] = $extra;
        elseif(isset($extra))
            $fields = array_merge($fields,$extra);
        
        $fields = apply_filters($fields, 'get_group_fields');
        
        return $fields;
    }
?>