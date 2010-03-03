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
	
	/**
	 * This will display language selector for front UI
	 */
	function display_languages($id='lang_selector',$class='lang_selector',$onchange='')
	{
		global $lang_obj;
		$langs = $lang_obj->get_langs('yes');
		
		if($class)
			$class_attr = ' class="'.$class.'" ';
		
		if($onchange)
			$onchange_attr = ' onChange="'.$onchange.'" ';
			
		$obj = "<select id='lang_selector' $class_attr $onchange_attr>\n";
		$obj .= "<option value=''>".lang("chane_lang")."</option>\n";
		foreach($langs as $lang)
		{
			if($lang_obj->lang == $lang['language_code'])
				$selected = ' selected="selected" ';
			else
				$selected =  '';
				
			if($lang['language_name']!='')
				$obj .= "<option value='".$lang['language_code']."' $selected>".$lang['language_name']."</option>\n";
		}
		$obj .="</select>\n";
		return $obj;
	}
}

?>