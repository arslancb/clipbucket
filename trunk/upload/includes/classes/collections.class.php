<?php
/**
 * @ Author Arslan Hassan, Fawaz Tahir
 * @ License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @ Class : Collection Class
 * @ date : 10 October 2010
 * @ Version : v2.0.1.9
 */

class Collections extends CBCategory
{
	var $collect_thumb_width = 140;
	var $collect_thumb_height = 140;
	var $collect_small_thumb_width = 60;
	var $collect_small_thumb_height = 60;
	var $items = 'collection_items'; // ITEMS TABLE
	var $types = ''; // TYPES OF COLLECTIONS
	var $custom_collection_fields = array();
	
	/**
	 * Setting variables of different thing which will
	 * help makes this class reusble for very object
	 */
	var $objTable = 'video';
	var $objType = 'v';
	var $objClass = 'cbvideo';
	var $objFunction = 'video_exists';
	
	/**
	 * Constructor function to set values of tables
	 */
	function Collections()
	{
		$this->cat_tbl = "collection_categories";
		$this->section_tbl = "collections";
		$this->types = array('v' => lang("videos"),'p' => lang("pictures"));
	}
	
	/**
	 * Function used to check whether object exists
	 */
	function object_exists($objID)
	{
		$obj = $this->objClass;
	}	
	/**
	 * Function used to check if collection exists
	 */
	function collection_exists($id)
	{
		global $db;
		$result = $db->count(tbl($this->section_tbl),"collection_id"," collection_id = $id");
		if($result)
			return true;
		else
			return false;		
	}
	
	/**
	 * Function used to get collection
	 */
	function get_collection($id)
	{
		global $db;
		$result = $db->select(tbl($this->section_tbl),"*"," collection_id = $id");
		if($result)
			return $result[0];
		else
			return false;		
	}
	
	/**
	 * Function used to get collection items
	 */
	function get_collection_items($id)
	{
		global $db;
		$result = $db->select(tbl($this->items),"*"," collection_id = $id");
		if($result)
			return $result;
		else
			return false;		
	}
	
	/**
	 * Function used to load collections fields
	 */
	function load_required_fields($default)
	{
		if($default=NULL)
			$default = $_POST;
			
		$name = $default['collection_name'];
		$description = $default['collection_description'];
		$tags = $default['collection_tags'];
		
		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		else
		{
			preg_match_all('/#([0-9]+)#/',$default['category'],$m);
			$cat_array = array($m[1]);
		}
		
		$reqFileds = array
		(
			'name' => array(
						   'title'=> lang("collection_name"),
						   'type' => 'textfield',
						   'name' => 'collection_name',
						   'id' => 'collection_name',
						   'value' => $name,
						   'db_field' => 'collection_name',
						   'required' => 'yes',
						   'invalid_err' => lang("collect_name_er")
						   ),
			
			'desc' => array(
							'title' => lang("collection_description"),
							'type' => 'textarea',
							'name' => 'collection_description',
							'id' => 'colleciton_desciption',
							'value' => $description,
							'db_field' => 'collection_description',
							'required' => 'yes',
							'invalid_err' => lang("collect_descp_er")
							),
			'tags' => array(
							'title' => lang("collection_tags"),
							'type' => 'textfield',
							'name' => 'collection_tags',
							'id' => 'collection_tags',
							'value' => cleanForm(genTags($tags)),
							'hint_2' => lang("collect_tag_hint"),
							'db_field' => 'collection_tags',
							'required' => 'yes',
							'invalid_err' => lang("collect_tag_er"),
							'validate_function' => 'genTags'
							),
							
			'cat' => array(
						   'title' => lang("collect_category"),
						   'type' => 'checkbox',
						   'name' => 'category[]',
						   'id' => 'category',
						   'value' => array('category',$cat_array),
						   'db_field' => 'category',
						   'required' => 'yes',
						   'validate_function' => 'validate_group_category',
						   'invalid_err' => lang('collect_cat_er'),
						   'display_function' => 'convert_to_categories',
						   'category_type' => 'collection'
						   ),
						   
			'type' => array(
							'title' => lang("collect_type"),
							'type' => 'dropdown',
							'name' => 'type',
							'id' => 'type',
							'value' => $this->types,
							'db_field' => 'type',
							'required' => 'yes'
							)						   														   					   
		);
		
		return $reqFileds;	
	}
	
	/**
	 * Function used to load collections optional fields
	 */
	function load_other_fields($default=NULL)
	{
		if($default=NULL)
			$default = $_POST;
			
		$broadcast = $default['broadcast'];
		$allow_comments = $default['allow_comments'];
		$public_upload = $default['public_upload'];
		
		$other_fields = array
		(
			'broadcast' => array(
								'title' => lang("vdo_br_opt"),
								'type' => 'radiobutton',
								'name' => 'broadcast',
								'id' => 'broadcast',
								'value' => array("public"=>lang("collect_borad_pub"),"private"=>lang("collect_broad_pri")),
								'checked' => $broadcast,
								'db_field' => 'broadcast',
								'required' => 'no',
								'display_function' => 'display_sharing_opt'
								),
			'allow_comments' => array(
									 'title' => lang("comments"),
									 'type' => 'radiobutton',
									 'name' => 'allow_comments',
									 'id' => 'allow_comments',
									 'value' => array("yes"=>lang("vdo_allow_comm"),"no"=>lang("vdo_dallow_comm")),
									 'checked' => $allow_comments,
									 'db_field', 'allow_comments',
									 'required' => 'no',
									 'display_function' => 'display_sharing_opt'
									  ),
			'public_upload' => array(
									'title' => lang("collect_allow_public_up"),
									'type' => 'radiobutton',
									'id' => 'public_upload',
									'name' => 'public_upload',
									'value' => array("yes"=>lang("collect_pub_up_allow"),"no"=>lang("collect_pub_up_dallow")),
									'checked' => $public_upload,
									'db_field' => 'public_upload',
									'required' => 'no',
									'display_function' => 'display_sharing_opt',
									)									  								
		);
		
		return $other_fields;	
	}
	
	/**
	 * Function used to validate form fields
	 */
	function validate_form_fields($array=NULL)
	{
		if($array=NULL)
			$array = $_POST;
			
		if(is_array($_FILES))
			$array = array_merge($_FILES,$array);
				
		$reqFileds = $this->load_required_fields($array);
		$otherFields = $this->load_other_fields($array);
		
		$collection_fields = array_merge($reqFileds,$otherFields);
		
		validate_cb_form($collection_fields,$array);	
	}
	
	/**
	 * Function used to create collections
	 */
	function create_collection($array=NULL)
	{
		global $db, $userquery;
		
		if($array=NULL)
			$array = $_POST;
			
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
			
		$this->validate_form_fields($array);
		
		if(!error())
		{
			$fields = $this->load_required_fields($array);
			$collection_fields = array_merge($fields,$this->load_other_fields($array));
			
			if(count($this->custom_collection_fields) > 0)
				$collection_fields = array_merge($collection_fields,$this->custom_collection_fields);
				
			foreach($collection_fields as $field)
			{
				$name = formObj::rmBrackets($field['name']);
				$val = $array[$name];
				
				if($field['use_func_val'])
					$val = $field['validate_function']($val);
				
				
				if(!empty($field['db_field']))
					$query_field[] = $field['db_field'];
				
				if(is_array($val))
				{
					$new_val = '';
					foreach($val as $v)
					{
						$new_val .= "#".$v."# ";
					}
					$val = $new_val;
				}
				if(!$field['clean_func'] || (!function_exists($field['clean_func']) && !is_array($field['clean_func'])))
					$val = mysql_clean($val);
				else
					$val = apply_func($field['clean_func'],sql_free('|no_mc|'.$val));
				
				if(!empty($field['db_field']))
					$query_val[] = $val;	
			}
			
			// date_added
			$query_field[] = "date_added";
			$query_val[] = NOW();
			
			// user
			$query_field[] = "userid";
			if($array['userid'])
				$query_val[] = $array['userid'];
			else
				$query_val[] = userid();
				
			// active
			$query_field[] = "active";
			$query_val[] = "yes";
			
			$db->insert(tbl($this->section_tbl),$query_field,$query_val);
			e(lang("collect_added_msg"),"m");	
		}
	}
	
	/**
	 * Function used to add item in collection
	 */
	function add_collection_item($objID,$cid,$type)
	{
		global $db;
		if($array=NULL)
			$array = $_POST;
		
		if($this->collection_exists($cid))
		{
			if(!userid())
				e("you_not_logged_in");
			elseif(!$this->object_exists($objID,$type))	
				e("object_does_exists");
			elseif($this->object_in_collection($objID,$cid,$type))
				e("object_exists_collection");
			else
			{
				$flds = array("collection_id","object_id","type","userid","date_added");
				$vls = array($cid,$objID,$type,userid(),NOW());
				$db->insert($this->items,$flds,$vls);	
			}
		} else {
			e(lang("collect_not_exist"));	
		}
	}
}

?>