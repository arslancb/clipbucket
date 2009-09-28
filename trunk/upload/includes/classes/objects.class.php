<?php

/**
 * Author : Arslan Hassan
 * Software : ClipBucket
 *
 * This Class is used to display different
 * objects of ClipBucket
 */


class CBObjects
{
	
	
	/**
	 * Function used to display templates in drop down
	 */
	function display_templates()
	{
		//Get Tempplates
		$templates = CBTemplate::get_templates();
		
		$dd = "<select>\n";
		foreach($templates as $template)
		{
			if($template['name']!='')
				$dd .= "<option value='".$template['dir']."'>".$template['name']."</option>\n";
		}		
		$dd .="</select>\n";
		
		return $dd;
	}
}