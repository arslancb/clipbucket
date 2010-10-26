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
	var $user_links = '';
	var $custom_collection_fields = array();
	
	/**
	 * Setting variables of different thing which will
	 * help makes this class reusble for very object
	 */
	var $objTable = 'pictures';
	var $objType = 'p';
	var $objClass = 'cbpicture';
	var $objFunction = 'picture_exists';
	
	
	/**
	 * Constructor function to set values of tables
	 */
	function Collections()
	{
		$this->cat_tbl = "collection_categories";
		$this->section_tbl = "collections";
		$this->types = array('v' => lang("videos"),'p' => lang("pictures"));
		$this->set_user_links();
	}
	
	/**
	 * Setting links up in my account
	 */
	function set_user_links()
	{
		global $userquery;
		$links = array();	
		$links[lang('Collections')] = array(
											lang('Add New Collection') => "manage_collections.php?mode=add_new",
											lang('Manage Collections') => "manage_collections.php"
											);
		$userquery->user_account = $links;									  	
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
	 * Function used to check if object exists
	 * This is a replica of actions.class, exists function
	 */	
	function object_exists($id)
	{
		$obj = $this->objClass;
		global ${$obj};
		$obj = ${$obj};
		$func = $this->objFunction;
		return $obj->{$func}($id);
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
	 * Function used to get collections
	 */
	function get_collections($params=NULL)
	{
		global $db;
		$params = $p;
		
		$limit = $p['limit'];
		$order = $p['order'];
		$sort  = $p['sort'];
		
		if($order && $sort)
			$sorting = $order." ".$sort;
		elseif($order && !$sort)
			$sorting = $order." ASC";
		elseif(!$order && $sort)
			$sorting = "date_added ".$sort;		
		
		$cond = "";
		
		if(!has_access('admin_access',TRUE))
			$cond .= " active='yes'";
		else
		{
			if($p['active'])
				$cond .= " ".tbl('collections.active')." = '".$p['active']."'";
				
			if($p['broadcast'])
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ".tbl('collections.broadcast')." = '".$p['broadcast']."'";		
			}
			
			if($p['category'])
			{
				$get_all = false;
				if(!is_array($p['category']))
					if(strtolower($p['category']) == 'all')
						$get_all = true;
				
				if(!$get_all)
				{
					if($cond != '')
						$cond .= " AND ";
						
					$cond .= "(";
					if(!is_array($p['category']))
						$cats = explode(',',$p['category']);
					else
						$cats = $p['category'];
					$count = 0;
					
					foreach($cats as $cat)
					{
						$count++;
						if($count > 0)
							$cond .= " OR ";
						$cond .= " ".tbl('collections.category')." LIKE '%#$cat#%'";	
					}
					$cond .= ")";		
				}
			}
			
			if($p['date_span'])
			{
				if($cond!='')
					$cond .= ' AND ';
				$cond .= " ".cbsearch::date_margin("date_added",$params['date_span']);	
			}
			
			if($p['user'])
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ".tbl('collections.userid')." = '".$p['user']."'";		
			}
			
			if($p['type'])
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ".tbl('collections.type')." = '".$p['type']."'";		
			} else
				$cond .= " ".tbl('collections.type')." = 'p'";
				
			if($p['featured'])
			{
				if($cond != '')	
					$cond .= " AND ";
				$cond .= " ".tbl('collections.featured')." = '".$p['featured']."'";	
			}
			
			if($p['public_upload'])
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ".tbl('collections.public_upload')." = '".$p['public_upload']."'";	
			}
			
			if($p['exclude'])
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ".tbl('collections.collection_id')." <> '".$p['exclude']."'";		
			}
			
			if($p['cid'])
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ".tbl('collections.collection_id')." = '".$p['cid']."'";		
			}
			
			$title_tag = '';
			
			if($p['name'])
			{
				$title_tag .= " ".tbl('collections.collection_name')." LIKE '%".$p['name']."%'";	
			}
			
			if($p['tags'])
			{
				$tags = explode(",",$p['tags']);
				if(count($tags)>0)
				{
					if($title_tag != '')
						$title_tag .= " OR ";
					$total = count($tags);
					$loop = 1;
					foreach($tags as $tag)
					{
						$title_tag .= " ".tbl('collections.collection_tags')." LIKE '%$tag%'";
						if($loop<$total)
							$title_tag .= " OR ";
						$loop++;		
					}
				} else {
					if($title_tag != '')
						$title_tag .= " OR ";
					$title_tag .= " ".tbl('collections.collection_tags')." LIKE '%".$p['tags']."%'";		
				}
			}
			
			if($title_tag != "")
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ($title_tag) ";		
			}
			
			if(!$p['count_only'])
			{
				$result =   $db->select(tbl("collections,users"),
							tbl("collections.*,users.userid,users.username"),
							$cond." AND ".tbl("collections.userid")." = ".tbl("users.userid"),$limit,$sorting);	
			}
			
			if($p['count_only'])
			{
				$result = $db->count(tbl("collections"),"collection_id",$cond);	
			}
			
			if($p['assign'])
				assign($p['assign'],$result);
			else
				return $result;	
		}
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
	function load_required_fields($default=NULL)
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
						   'category_type' => 'collections'
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
		$reqFileds = $this->load_required_fields($array);
		
		if($array==NULL)
			$array = $_POST;
			
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
				
		
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
		
		if($array==NULL)
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
	 * Function used to get collection owner
	 */
	function get_collection_owner($cid)
	{
		global $db;
		$user_tbl = tbl("users");
		$result = $db->select(tbl($this->section_tbl.",users"),tbl($this->section_tbl).".*,$user_tbl.userid,$user_tbl.username"," collection_id = $cid AND ".tbl($this->section_tbl).".userid = $user_tbl.userid");
		if($db->num_rows > 0)
			return $result[0]['userid'];
		else
			return false;	
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
			elseif(!$this->object_exists($objID))	
				e("object_does_exists");
			elseif($this->object_in_collection($objID,$cid))
				e("object_exists_collection");
			else
			{
				$flds = array("collection_id","object_id","type","userid","date_added");
				$vls = array($cid,$objID,$type,userid(),NOW());
				$db->insert($this->items,$flds,$vls);
				$db->update(tbl($this->section_tbl),array("total_objects"),array("total_objects+1"));	
			}
		} else {
			e(lang("collect_not_exist"));	
		}
	}
	
	/**
	 * Function used to check if object exists in collection
	 */
	function object_in_collection($id,$cid)
	{
		global $db;
		$result = $db->select(tbl($this->items),"*"," object_id = $id AND collection_id = $cid");
		if($result)
			return $result[0];
		else
			return false;		
	}
	
	/**
	 * Function used to get collection field vlaue
	 */
	function get_collection_field($cid,$field=NULL)
	{
		global $db;
		if($field==NULL)
			$field = "*";
		else
			$field = $field;
			
		if(is_array($cid))
			$id = $cid['collection_id'];
		else
			$id = $cid;
			
		$result = $db->select(tbl($this->section_tbl),$field," collection_id = $id");
		if($result)
		{
			if(count($result[0]) > 0)
				return $result[0];
			else
				return $result[0][$field];	
		} else
			return false;
	}
	
	/**
	 * Function used to check if user collection owner
	 */
	function is_collection_owner($cid,$userid=NULL)
	{
		if($userid==NULL)
			$userid = userid();
			
		if(!is_array($cid))
			$details = $this->get_collection($cid);
		else
			$details = $cid;
			
		if($details['userid'] == $userid)
			return true;
		else
			return false;				
	}
	
	/**
	 * Function used to delete collection
	 */
	function delete_collection($cid)
	{
		global $db;
		$collection = $db->get_collection($cid);
		if(!$collection)
			e("collection_not_exists");
		elseif($collection['userid'] != userid() || !has_access('admin_access',true))
			e("cant_perform_action_collect");
		else
		{
			$this->delete_thubms($cid);
			$this->delete_collection_items($cid);
			$db->delete(tbl($this->section_tbl),array("collection_id"),array($cid));
			e("collection_deleted","m");	
		}
	}
	
	/**
	 * Function used to delete collection items
	 */
	function delete_collection_items($cid)
	{
		global $db;
		$collection = $this->get_collection($id);
		if(!$collection)
			e("collection_not_exists");
		elseif($collection['userid'] != userid() || !has_access('admin_access',true))
			e("cant_perform_action_collect");
		else {
			$db->delete(tbl($this->items),array("collection_id"),array($cid));
			e("collect_items_deleted","m");	
		}
	}
		
	/**
	 * Function used to delete collection preview
	 */
	function delete_thumbs($cid)
	{
		$glob = glob(COLLECT_THUMBS_DIR."/$cid*.jpg");
		if($glob)
		{
			foreach($glob as $file)
			{
				if(file_exists($file))
					unlink($file);
			}
		} else {
			return false;	
		}
	}
	
	/**
	 * Function used to create collection preview
	 */
	function upload_thumb($cid,$file)
	{
		global $imgObj;
		$file_ext = strtolower(getext($file['name']));
		$exts = array("jpg","gif","jpeg","png");
		
		foreach($exts as $ext)
		{
			if($ext == $file_ext)
			{
				$thumb = COLLECT_THUMB_DIR."/".$cid.".".$ext;
				$sThumb = COLLECT_THUMB_DIR."/".$cid."-small.".$ext;
				if(file_exists($thumb) && file_exists($sThumb))
					unlink($thumb); unlink($sThumb);
					
				move_uploaded_file($file['tmp_name'],$thumb);
				if(!$imgObj->ValidateImage($thumb,$ext))
					e("pic_upload_vali_err");
				else
				{
					$imgObj->CreateThumb($file,$thumb,$this->collect_thumb_width,$ext,$this->collect_thumb_height,true);
					$imgObj->CreateThumb($file,$sThumb,$this->collect_thumb_width,$ext,$this->collect_thumb_height,true);	
				}
			}
		}
	}
	/**
	 * Function used to create collection preview
	 */
	function update_collection($array=NULL)
	{
		global $db;
		
		if($array=NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
			
		$this->validate_form_fields($array);
		$cid = $array['collection_id'];
		
		if(!error())
		{
			$reqFields = $this->load_required_fields($array);
			$otherFields = $this->load_other_fields($array);
			
			$collection_fields = array_merge($regFields,$otherFields);
			if($this->custom_collection_fields > 0)
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
			
			if(has_access('admin_access',TRUE))
			{
				if(!empty($array['featured']))
				{
					$query_field[] = "featured";
					$query_val[] = $array['featured'];	
				}
				
				if(!empty($array['total_comments']))
				{
					$query_field[] = "total_comments";
					$query_val[] = $array['total_comments'];	
				}
				
				if(!empty($array['total_objects']))
				{
					$query_field[] = "total_objects";
					$query_val[] = $array['total_objects'];	
				}
			}
		}
		
		if(!error())
		{
			if(!userid())
				e("you_not_logged_in");
			elseif(!$this->collection_exists($cid))
				e("collect_not_exist");
			elseif(!$this->is_collection_owner($cid,userid()))
				e("cant_edit_collection");
			else
			{
				$db->update(tbl($this->section_tbl),$query_field,$query_val," collection_id = $id");
				e("collection_updated","m");
				
				if(!empty($array['collection_thumb']['tmp_name']))
					$this->upload_thumb($cid,$array['colleciton_thumb']);	
			}
		}
	}
}

?>