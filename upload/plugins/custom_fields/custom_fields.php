<?php

/*
Plugin Name: Custom Fields Beta for ClipBucket
Description: Add custom fields in your video upload form :)
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://clip-bucket.com/
Plugin Type: global
*/


define('CUSTOM_FIELDS_MOD',TRUE);

if(!function_exists('add_custom_field'))
{
	/**
	 * Function used to add custom field in upload form
	 */
	function add_custom_field($array)
	{
		global $db,$LANG;
		foreach($array as $key=>$attr)
		{
			
			if($key=='name' || $key=='title')
			{
				if(empty($attr))
					e(sprintf(lang('cust_field_err'),$key));
			}
			
			if(!error_list())
			{
				if(!empty($attr))
				{
					$fields_array[] = 'custom_field_'.$key;
					$value_array[] = mysql_clean($attr);
				}
				
				if($key=='db_field')
				{
					$db->execute("ALTER TABLE ".tbl('video')." ADD `".$attr."` TEXT NOT NULL");
				}
			}
			
		}
		
		if(!error_list())
		$db->insert(tbl("custom_fields"),$fields_array,$value_array);		
	}
	
	
	/**
	 * Function used add fields in upload form
	 */
	function load_form_fields()
	{
		global $db;
		$results = $db->select(tbl("custom_fields"),"*");
		if(count($results[0])>0)
		{
			foreach($results as $result)
			{
				$name = $result['custom_field_name'];
				foreach($result as $field => $value)
				{
					$field_array[$name][substr($field,13,strlen($field))] = $value;
				}
			}
		}
		
		if(count($field_array)>0)
			return false;
		return $field_array;
	}
	
	
	//Adding Admin Menu
	add_admin_menu('Custom Fields','Add New Custom Field','add_custom_fields.php');
	add_admin_menu('Custom Fields','Manage Custom Fields','manage_custom_fields.php');
	
	if(load_form_fields())
		register_custom_form_field(load_form_fields());
	
}


?>