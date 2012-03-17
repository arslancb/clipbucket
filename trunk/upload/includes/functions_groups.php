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
	

?>