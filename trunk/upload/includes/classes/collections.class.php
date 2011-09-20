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
	var $collect_thumb_width = 160;
	var $collect_thumb_height = 120;
	var $collect_small_thumb_width = 120;
	var $collect_small_thumb_height = 90;
	var $items = 'collection_items'; // ITEMS TABLE
	var $types = ''; // TYPES OF COLLECTIONS
	var $user_links = '';
	var $custom_collection_fields = array();
	var $collection_delete_functions = array();
	var $action = '';
	var $share_variables;
	
	/**
	 * Setting variables of different thing which will
	 * help makes this class reusble for very object
	 */
	var $objTable = 'photos';
	var $objType = 'p';
	var $objName = 'Photo';
	var $objClass = 'cbphoto';
	var $objFunction = 'photo_exists';
	var $objFieldID = 'photo_id';
	
	
	/**
	 * Constructor function to set values of tables
	 */
	function Collections()
	{
		$this->cat_tbl = "collection_categories";
		$this->section_tbl = "collections";
		$this->types = array('videos' => lang("Videos"),'photos' => lang("Photos"));
		ksort($this->types);
		$this->setting_up_collections();
		$this->init_actions();

	}
	
	/**
	 *	 Settings up Action Class
	 */
	function init_actions()
	{
		$this->action = new cbactions();
		$this->action->init();	 // Setting up reporting excuses
		$this->action->type = 'cl';
		$this->action->name = 'collection';
		$this->action->obj_class = 'cbcollection';
		$this->action->check_func = 'collection_exists';
		$this->action->type_tbl = "collections";
		$this->action->type_id_field = 'collection_id';	
	} 
	
	/**
	 * Setting links up in my account
	 */
	function setting_up_collections()
	{
		global $userquery,$Cbucket;
		$per = $userquery->get_user_level(userid());
		// Adding My Account Links	
		if(isSectionEnabled('collections'))
		$userquery->user_account[lang('Collections')] = array(
											lang('add_new_collection') => "manage_collections.php?mode=add_new",
											lang('manage_collections') => "manage_collections.php",
											lang('manage_favorite_collections') => "manage_collections.php?mode=favorite"
											);
		
		// Adding Search Type
		if(isSectionEnabled('collections'))
		$Cbucket->search_types['collections'] = "cbcollection";
		
		// Adding Collection links in Admin Area
		if($per['collection_moderation'] == "yes")
		$Cbucket->AdminMenu['Collections'] = array(
													lang('Manage Collections')=>'collection_manager.php',
													lang('Manage Categories')=>'collection_category.php',
													lang('Flagged Collections')=>'flagged_collections.php');
		

		// Adding Collection links in Cbucket Class
		$Cbucket->links['collections'] = array('collections.php','collections/');
		$Cbucket->links['manage_collections'] = array('manage_collections.php','manage_collections.php');
		$Cbucket->links['edit_collection'] = array('manage_collections.php?mode=edit_collection&amp;cid=',
												   'manage_collections.php?mode=edit_collection&amp;cid=');
		$Cbucket->links['manage_items'] = array('manage_collections.php?mode=manage_items&amp;cid=%s&amp;type=%s',
												'manage_collections.php?mode=manage_items&amp;cid=%s&amp;type=%s');
		$Cbucket->links['user_collections'] = array('user_collections.php?mode=uploaded&user=','user_collections.php?mode=uploaded&user=');
		$Cbucket->links['user_fav_collections'] = array('user_collections.php?mode=favorite&user=','user_collections.php?mode=favorite&user=');										
												
																																													
	}
		
	/**
	 * Initiatting Search
	 */
	function init_search()
	{
		$this->search = new cbsearch;
		$this->search->db_tbl = "collections";
		$this->search->columns = array(
			array("field"=>"collection_name","type"=>"LIKE","var"=>"%{KEY}%"),
			array("field"=>"collection_tags","type"=>"LIKE","var"=>"%{KEY}%","op"=>"OR")
		);
		$this->search->match_fields = array("collection_name","collection_tags");
		$this->search->cat_tbl = $this->cat_tbl;
		
		$this->search->display_template = LAYOUT.'/blocks/collection.html';
		$this->search->template_var = 'collection';
		$this->search->has_user_id = true;
			
		$sorting	= 	array(
						'date_added'=> lang("date_added"),
						'views'		=> lang("views"),
						'total_comments'  => lang("comments"),
						'total_objects' 	=> lang("Items")
						);
								
		$this->search->sorting	= array(
						'date_added'=> " date_added DESC",
						'views'		=> " views DESC",
						'total_comments'  => " total_comments DESC ",
						'total_objects' 	=> " total_objects DESC"
						);
						
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		$uploaded = $default['datemargin'];
		$sort = $default['sort'];
		
		$this->search->search_type['collections'] = array('title'=>lang('collections'));
		$this->search->results_per_page = config('videos_items_search_page');
		
		$fields = array(
		'query'	=> array(
						'title'=> lang('keywords'),
						'type'=> 'textfield',
						'name'=> 'query',
						'id'=> 'query',
						'value'=>cleanForm($default['query'])
						),
		'category'	=>  array(
						'title'		=> lang('category'),
						'type'		=> 'checkbox',
						'name'		=> 'category[]',
						'id'		=> 'category',
						'value'		=> array('category',$cat_array),
						'category_type'	=> 'collections'
						),
		'uploaded'	=>  array(
						'title'		=> lang('uploaded'),
						'type'		=> 'dropdown',
						'name'		=> 'datemargin',
						'id'		=> 'datemargin',
						'value'		=> $this->search->date_margins(),
						'checked'	=> $uploaded,
						),
		'sort'		=> array(
						'title'		=> lang('sort_by'),
						'type'		=> 'dropdown',
						'name'		=> 'sort',
						'value'		=> $sorting,
						'checked'	=> $sort
						)
		);

		$this->search->search_type['collections']['fields'] = $fields;											
	}
	
	/**
	 * Function used to set-up sharing
	 */
	function set_share_mail($data)
	{
		$this->share_variables = array(
				'{name}' => $data['collection_name'],
				'{description}' => $data['collection_description'],
				'{type}' => $data['type'],
				'{total_items}' => $data['total_objects'],
				'{collection_link}' => $this->collection_links($data,'view'),
				'{collection_thumb}' => $this->get_thumb($data,'small',TRUE)
			);
		$this->action->share_template_name = 'collection_share_template';
		$this->action->val_array = $this->share_variables;			
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
	function get_collection($id,$cond=NULL)
	{
		global $db;
		$result = $db->select(tbl($this->section_tbl).",".tbl("users"),
		"".tbl($this->section_tbl).".*,".tbl('users').".userid,".tbl('users').".username",
		" ".tbl($this->section_tbl).".collection_id = $id AND ".tbl($this->section_tbl).".userid = ".tbl('users').".userid $cond");
		//echo $db->db_query;
		if($result)
			return $result[0];
		else
			return false;		
	}
	
	function is_viewable($cid)
	{
		global $userquery;
		
		$c = $this->get_collection($cid);
		if(empty($c))
		{
			e(lang('collection_not_exists'));
			return false;	
		} elseif($c['active'] == 'no') {
			e(lang('collection_not_active'));
			if(!has_access('admin_access',TRUE))
				return false;
			else
				return true;		
		} elseif($c['broadcast'] == 'private' && !$userquery->is_confirmed_friend($c['userid'],userid())
				&& $c['userid']!=userid() && !has_access('admin_access',TRUE))
		{
			e(lang('collection_is_private'));
			return false;
		} else {
			return true;	
		}
	}
	
	/**
	 * Function used to get collections
	 */
	function get_collections($p=NULL)
	{
		global $db;
		
		$limit = $p['limit'];
		$order = $p['order'];	
		$cond = "";
		
		if(!has_access('admin_access',TRUE) && $p['user'] != userid())
			$cond .= " ".tbl('collections.active')." = 'yes'";
		elseif($p['user'] == userid())
			$cond .= " ".tbl('collections.active')." = 'yes'";	
		else
		{
			if($p['active'])
			{
				$cond .= " ".tbl('collections.active')." = '".$p['active']."'";
			}
			
			if($p['broadcast'])
			{
				if($cond != '')
					$cond .= " AND ";
				$cond .= " ".tbl('collections.broadcast')." = '".$p['broadcast']."'";		
			}
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
					if($count > 1)
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
			$cond .= " ".cbsearch::date_margin("date_added",$p['date_span']);	
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
		}
			
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
		
		

		/** Get only with those who have items **/
		if($p['has_items'] )
		{
			if($cond != '')
				$cond .= " AND ";
			$cond .= " ".tbl('collections.total_objects')." >= '1'";		
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
			if($cond != "")
				$cond .= " AND ";
			$result =   $db->select(tbl("collections,users"),
						tbl("collections.*,users.userid,users.username"),
						$cond.tbl("collections.userid")." = ".tbl("users.userid"),$limit,$order);
									
			//echo $db->db_query;	
						
		}
		
		if($p['count_only'])
		{
			return $result = $db->count(tbl("collections"),"collection_id",$cond);
			//echo $db->db_query;	
		}
		
		if($p['assign'])
			assign($p['assign'],$result);
		else
			return $result;	
		
	}
	
	/**
	 * Function used to get collection items
	 */
	function get_collection_items($id,$order=NULL,$limit=NULL)
	{
		global $db;
		$result = $db->select(tbl($this->items),"*"," collection_id = $id",$limit,$order);
		if($result)
			return $result;
		else
			return false;		
	}
	
	/**
	 * Function used to get next / previous collection item
	 */
	function get_next_prev_item($ci_id,$cid,$item="prev",$limit=1,$check_only=false)
	{
		global $db;
		$iTbl = tbl($this->items);
		$oTbl = tbl($this->objTable);
		$uTbl = tbl('users');
		$tbls = $iTbl.",".$oTbl.",".$uTbl;
		
		if($item == "prev")
		{
			$op = ">";
			$order = '';
		}
		elseif($item == "next")
		{
			$op = "<";
			$order = $iTbl.".ci_id DESC";
		}
		elseif($item == NULL)
		{
			$op = "=";
			$order = '';
		}
		
		$cond = " $iTbl.collection_id = $cid AND $iTbl.ci_id $op $ci_id AND $iTbl.object_id = $oTbl.".$this->objFieldID." AND $oTbl.userid = $uTbl.userid";
		if(!$check_only)
		{	
			$result = $db->select($tbls,"$iTbl.*,$oTbl.*,$uTbl.username", $cond,$limit,$order);
			
			// Result was empty. Checking if we were going backwards, So bring last item
			if(empty($result) && $item == "prev")
			{
				$order = $iTbl.".ci_id ASC";
				$op = "<";
				$result = $db->select($tbls,"$iTbl.*,$oTbl.*,$uTbl.username", " $iTbl.collection_id = $cid AND $iTbl.ci_id $op $ci_id AND $iTbl.object_id = $oTbl.".$this->objFieldID." AND $oTbl.userid = $uTbl.userid",$limit,$order);
			}
			
			// Result was empty. Checking if we were going fowards, So bring first item
			if(empty($result) && $item == "next")
			{
				$order = $iTbl.".ci_id DESC";
				$op = ">";
				$result = $db->select($tbls,"$iTbl.*,$oTbl.*,$uTbl.username", " $iTbl.collection_id = $cid AND $iTbl.ci_id $op $ci_id AND $iTbl.object_id = $oTbl.".$this->objFieldID." AND $oTbl.userid = $uTbl.userid",$limit,$order);	
			}
		}
		
		if($check_only)
		{	
			$result = $db->count($iTbl.",".$oTbl,"$iTbl.ci_id", " $iTbl.collection_id = $cid AND $iTbl.ci_id $op $ci_id AND $iTbl.object_id = $oTbl.".$this->objFieldID,$limit,$order);
		}
		
		//echo $db->db_query;
		if($result)
			return $result;
		else
			return false;			
		
	}
	
	/**
	 * Function used to set cookie on moving
	 * forward or backward
	 */
	function set_item_cookie($value)
	{
		if(isset($_COOKIE['current_item']))
			unset($_COOKIE['current_item']);
		
		setcookie('current_item',$value,time()+240);	
	}
	
	/**
	 * Function used to get collection items with details
	 */
	function get_collection_items_with_details($id,$order=NULL,$limit=NULL,$count_only=FALSE)
	{
		global $db;
		$itemsTbl = tbl($this->items);
		$objTbl = tbl($this->objTable);
		$tables = $itemsTbl.",".$objTbl.",".tbl("users");
		
		if(!$count_only)
		{
			$result = $db->select($tables,"$itemsTbl.ci_id,$itemsTbl.collection_id,$objTbl.*,".tbl('users').".username"," $itemsTbl.collection_id = '$id' AND $itemsTbl.object_id = $objTbl.".$this->objFieldID." AND $objTbl.userid = ".tbl('users').".userid",$limit,$order);
			//echo $db->db_query;
		} else {
			$result = $db->count($itemsTbl,"ci_id"," collection_id = $id");	
		}
		
		if($result)
		{
			return $result;	
		}
		else
			return false;	
	}
	
	/**
	 * Function used to get collection items with
	 * specific fields
	 */
	function get_collection_item_fields($cid,$objID,$fields)
	{
		global $db;
		$result = $db->select(tbl($this->items),$fields," object_id = $objID AND collection_id = $cid");
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
		if($default==NULL)
			$default = $_POST;
			
		$name = $default['collection_name'];
		$description = $default['collection_description'];
		$tags = $default['collection_tags'];
		$type = $default['type'];
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
						   'value' => cleanForm($name),
						   'db_field' => 'collection_name',
						   'required' => 'yes',
						   'invalid_err' => lang("collect_name_er")
						   ),
			
			'desc' => array(
							'title' => lang("collection_description"),
							'type' => 'textarea',
							'name' => 'collection_description',
							'id' => 'colleciton_desciption',
							'value' => cleanForm($description),
							'db_field' => 'collection_description',
							'required' => 'yes',
							'anchor_before' => 'before_desc_compose_box',
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
						   'validate_function' => 'validate_collection_category',
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
							'required' => 'yes',
							'checked' => $type
							)						   														   					   
		);
		
		return $reqFileds;	
	}
	
	/**
	 * Function used to load collections optional fields
	 */
	function load_other_fields($default=NULL)
	{
		if($default==NULL)
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
								'validate_function'=>'yes_or_no',
								'display_function' => 'display_sharing_opt',
								'default_value'=>'yes'
								),
			'comments' => array(
									'title' => lang("comments"),
									'type' => 'radiobutton',
									'id' => 'allow_comments',
									'name' => 'allow_comments',
									'value' => array("yes"=>lang("vdo_allow_comm"),"no"=>lang("vdo_dallow_comm")),
									'checked' => $allow_comments,
									'db_field' => 'allow_comments',
									'required' => 'no',
									'validate_function'=>'yes_or_no',
									'display_function' => 'display_sharing_opt',
									'default_value'=>'yes'
									),
			'public_upload' => array(
									'title' => lang("collect_allow_public_up"),
									'type' => 'radiobutton',
									'id' => 'public_upload',
									'name' => 'public_upload',
									'value' => array("no"=>lang("collect_pub_up_dallow"),"yes"=>lang("collect_pub_up_allow")),
									'checked' => $public_upload,
									'db_field' => 'public_upload',
									'required' => 'no',
									'validate_function'=>'yes_or_no',
									'display_function' => 'display_sharing_opt',
									'default_value'=>'no'
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
	 * Function used to validate collection category
	 * @param input array
	 */	
	function validate_collection_category($array=NULL)
	{
		if($array==NULL)
			$array = $_POST['category'];
		if(count($array)==0)
			return false;
		else
		{
			
			foreach($array as $arr)
			{
				if($this->category_exists($arr))
					$new_array[] = $arr;
			}
		}
		if(count($new_array)==0)
		{
			e(lang('vdo_cat_err3'));
			return false;
		}
		/*elseif(count($new_array)>ALLOWED_GROUP_CATEGORIES)
		{
			e(sprintf(lang('vdo_cat_err2'),ALLOWED_GROUP_CATEGORIES));
			return false;
		}*/
			
		return true;
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
					$val = ($val);
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
				$query_val[] = $userid = $array['userid'];
			else
				$query_val[] =  $userid = userid();
				
			// active
			$query_field[] = "active";
			$query_val[] = "yes";

			$insert_id = $db->insert(tbl($this->section_tbl),$query_field,$query_val);
			
			addFeed(array('action'=>'add_collection','object_id' => $insert_id,'object'=>'collection'));
			
			//Incrementing usr collection
			$db->update(tbl("users"),array("total_collections"),array("|f|total_collections+1")," userid='".$userid."'");
			
			e(lang("collect_added_msg"),"m");
			return $insert_id;	
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
	function add_collection_item($objID,$cid)
	{
		global $db;
		if($array==NULL)
			$array = $_POST;
		
		if($this->collection_exists($cid))
		{
			if(!userid())
				e(lang("you_not_logged_in"));
			elseif(!$this->object_exists($objID))	
				e(sprintf(lang("object_does_not_exists"),$this->objName));
			elseif($this->object_in_collection($objID,$cid))
				e(sprintf(lang("object_exists_collection"),$this->objName));
			else
			{
				$flds = array("collection_id","object_id","type","userid","date_added");
				$vls = array($cid,$objID,$this->objType,userid(),NOW());
				$db->insert(tbl($this->items),$flds,$vls);
				$db->update(tbl($this->section_tbl),array("total_objects"),array("|f|total_objects+1")," collection_id = $cid");
				e(sprintf(lang("item_added_in_collection"),$this->objName),"m");	
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
			if(count($result[0]) > 2)
				return $result[0];
			else
				return $result[0][$field];	
		} else
			return false;
	}
	
	/**
	 * Function used to check if user collection owner
	 */
	function is_collection_owner($cdetails,$userid=NULL)
	{
		if($userid==NULL)
			$userid = userid();
			
		if(!is_array($cdetails))
			$details = $this->get_collection($cdetails);
		else
			$details = $cdetails;
			
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
		global $db,$eh;
		$collection = $this->get_collection($cid);
		if(empty($collection))
			e(lang("collection_not_exists"));
		elseif($collection['userid'] != userid() && !has_access('admin_access',true))
			e(lang("cant_perform_action_collect"));
		else
		{
			$del_funcs = $this->collection_delete_functions;
			if(is_array($del_funcs) && !empty($del_funcs))
			{
				foreach($del_funcs as $func)
				{
					if(function_exists($func))
						$func($collection);	
				}
			}
			
			$db->delete(tbl($this->items),array("collection_id"),array($cid));
			$this->delete_thumbs($cid);
			$db->delete(tbl($this->section_tbl),array("collection_id"),array($cid));
						
			//Decrementing users total collection
			$db->update(tbl("users"),array("total_collections"),array("|f|total_collections-1")," userid='".$cid."'");
			//Removing video Comments
			$db->delete(tbl("comments"),array("type","type_id"),array("cl",$cid));
			//Removing video From Favortes
			$db->delete(tbl("favorites"),array("type","id"),array("cl",$cid));
			$eh->flush();
			e(lang("collection_deleted"),"m");	
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
			e(lang("collection_not_exists"));
		elseif($collection['userid'] != userid() && !has_access('admin_access',true))
			e(lang("cant_perform_action_collect"));
		else {
			$db->delete(tbl($this->items),array("collection_id"),array($cid));
			$db->update(tbl($this->section_tbl),array("total_objects"),array($this->count_items($cid))," collection_id = $cid");			
			e(lang("collect_items_deleted"),"m");	
		}
	}
	
	/**
	 * Function used to delete collection items
	 */
	function remove_item($id,$cid)
	{
		global $db;
				
		if($this->collection_exists($cid))
		{
			if(!userid())
				e(lang("you_not_logged_in"));
			elseif(!$this->object_in_collection($id,$cid))
				e(sprintf(lang("object_not_in_collect"),$this->objName));
			elseif(!$this->is_collection_owner($cid) && !has_access('admin_access',true))
				e(lang("cant_perform_action_collect"));
			else
			{
				$db->execute("DELETE FROM ".tbl($this->items)." WHERE object_id = $id AND collection_id = $cid");
				$db->update(tbl($this->section_tbl),array("total_objects"),array("|f|total_objects-1")," collection_id = $cid");
				e(sprintf(lang("collect_item_removed"),$this->objName),"m");	
			}
		} else {
			e(lang('collect_not_exists'));
			return false;	
		}
	}
	
	/**
	 * Function used to count collection items
	 */
	function count_items($cid)
	{
		global $db;
		$count = $db->count($this->items,"ci_id"," collection_id = $cid");	
		if($count)
			return $count;
		else
			return 0;	
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
		global $imgObj,$cbphoto;
		$file_ext = strtolower(getext($file['name']));
		$exts = array("jpg","gif","jpeg","png");
		
		foreach($exts as $ext)
		{
			if($ext == $file_ext)
			{
				$thumb = COLLECT_THUMBS_DIR."/".$cid.".".$ext;
				$sThumb = COLLECT_THUMBS_DIR."/".$cid."-small.".$ext;
				foreach($exts as $un_ext)
					if(file_exists(COLLECT_THUMBS_DIR."/".$cid.".".$un_ext) && file_exists(COLLECT_THUMBS_DIR."/".$cid."-small.".$un_ext))
					{
						unlink(COLLECT_THUMBS_DIR."/".$cid.".".$un_ext); 
						unlink(COLLECT_THUMBS_DIR."/".$cid."-small.".$un_ext);
					}
				move_uploaded_file($file['tmp_name'],$thumb);
				if(!$imgObj->ValidateImage($thumb,$ext))
					e("pic_upload_vali_err");
				else
				{
					$imgObj->createThumb($thumb,$thumb,$this->collect_thumb_width,$ext,$this->collect_thumb_height);
					$imgObj->createThumb($thumb,$sThumb,$this->collect_small_thumb_width,$ext,$this->collect_small_thumb_height);	
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
		
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
			
		$this->validate_form_fields($array);
		$cid = $array['collection_id'];
		
		if(!error())
		{
			$reqFields = $this->load_required_fields($array);
			$otherFields = $this->load_other_fields($array);
			
			$collection_fields = array_merge($reqFields,$otherFields);
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
					$val = ($val);
				else
					$val = apply_func($field['clean_func'],sql_free('|no_mc|'.$val));
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}
			
			if(has_access('admin_access',TRUE))
			{
				
				if(!empty($array['total_comments']))
				{
					$total_comments = $array['total_comments'];
					if(!is_numeric($total_comments) || $total_comments<0)
						$total_comments = 0;
						
					$query_field[] = "total_comments";
					$query_val[] = $total_comments;	
				}
				
				if(!empty($array['total_objects']))
				{
					$tobj = $array['total_objects'];
					if(!is_numeric($tobj) || $tobj<0)
						$tobj = 0;
					$query_field[] = "total_objects";
					$query_val[] = $tobj;	
				}
			}
		}
		
		if(!error())
		{
			if(!userid())
				e(lang("you_not_logged_in"));
			elseif(!$this->collection_exists($cid))
				e(lang("collect_not_exist"));
			elseif(!$this->is_collection_owner($cid,userid()) && !has_access('admin_access',TRUE))
				e(lang("cant_edit_collection"));
			else
			{
				$db->update(tbl($this->section_tbl),$query_field,$query_val," collection_id = $cid");
				e(lang("collection_updated"),"m");
				
				if(!empty($array['collection_thumb']['tmp_name']))
					$this->upload_thumb($cid,$array['collection_thumb']);	
			}
		}
	}
	
	/**
	 * Function used get default thumb
	 */
	function get_default_thumb($size=NULL)
	{
		if($size=="small" && file_exists(TEMPLATEDIR."/images/thumbs/collection_thumb-small.png"))
		{
			return TEMPLATEDIR."/images/thumbs/collection_thumb-small.png";	
		} elseif(!$size && file_exists(TEMPLATEDIR."/images/thumbs/collection_thumb.png")) {
			return TEMPLATEDIR."/images/thumbs/collection_thumb.png";	
		} else {
			if($size == "small")
				$thumb = COLLECT_THUMBS_URL."/no_thumb-small.png";
			else
				$thumb = COLLECT_THUMBS_URL."/no_thumb.png";
				
			return $thumb;			
		}
	}
	
	/**
	 * Function used get collection thumb
	 */
	function get_thumb($cdetails,$size=NULL,$return_c_thumb=false)
	{
		
		if(is_numeric($cdetails))
		{
			$cdetails = $this->get_collection($cdetails);
			$cid = $cdetails['collection_id'];	
		} else
			$cid = $cdetails['collection_id'];
				
		$exts = array("jpg","png","gif","jpeg");
					
		if($return_c_thumb)
		{
			foreach($exts as $ext)
			{
				if($size=="small")
					$s = "-small";
				if(file_exists(COLLECT_THUMBS_DIR."/".$cid.$s.".".$ext))
					return COLLECT_THUMBS_URL."/".$cid.$s.".".$ext;	
			}
		} else {
			
			$item = $this->get_collection_items($cid,'ci_id DESC',1);
			$type = $item[0]['type'];
			switch($type)
			{
				case "v":
				{
					global $cbvideo;
					$thumb = get_thumb($cbvideo->get_video_details($item[0]['object_id']));						
				}
				break;
				
				case "p":
				{
					global $cbphoto;
					$thumb = $cbphoto->get_image_file($cbphoto->get_photo($item[0]['object_id']));	
				}
			}
			
			if($thumb)
				return $thumb;
			else
			{
				foreach($exts as $ext)
				{
					if($size=="small")
						$s = "-small";
					if(file_exists(COLLECT_THUMBS_DIR."/".$cid.$s.".".$ext))
						return COLLECT_THUMBS_URL."/".$cid.$s.".".$ext;	
				}				
			}
		}
		
		return $this->get_default_thumb($size);
	}
	
	
	/**
	 * Used to display collection voterts details.
	 * User who rated, how many stars and when user rated
	 */
	function collection_voters($id,$return_array=FALSE,$show_all=FALSE)
	{
		global $json;
		$c= $this->get_collection($id);
		if((!empty($c) && $c['userid'] == userid()) || $show_all === TRUE)
		{
			global $userquery;
			$voters = $c['voters'];
			if(phpversion() < "5.2.0")
				$voters = $json->json_decode($voters,TRUE);
			else
				$voters = json_decode($voters,TRUE);
				
			if(!empty($voters))	
			{
				if($return_array)
					return $voters;
				else
				{
					foreach($voters as $id=>$details)
					{
						$username = get_username($id);
						$output = "<li id='user".$id.$c['collection_id']."' class='PhotoRatingStats'>";
						$output .= "<a href='".$userquery->profile_link($id)."'>$username</a>";
						$output .= " rated <strong>". $details['rate']/2 ."</strong> stars <small>(";
						$output  .= niceTime($details['time']).")</small>";
						$output .= "</li>";
						echo $output;		
					}	
				}
			}
		} else
			return false;
	}
	
	
	/**
	 * Used to get current rating
	 */
	function current_rating($id)
	{
		global $db;
		$result = $db->select(tbl('collections'),'allow_rating,rating,rated_by,voters,userid'," collection_id = ".$id."");
		if($result)
			return $result[0];
		else
			return false;				
	}
	
	
	/**
	 * Used to rate photo
	 */
	function rate_collection($id,$rating)
	{
		global $db,$json;
		
		if(!is_numeric($rating) || $rating <= 9)
			$rating = 0;
		if($rating >= 10)
			$rating = 10;
			
		$c_rating = $this->current_rating($id);
		$voters   = $c_rating['voters'];
		
		$new_rate = $c_rating['rating'];
		$rated_by = $c_rating['rated_by'];
		
		if(phpversion < '5.2.0')
			$voters = $json->json_decode($voters,TRUE);
		else
			$voters = json_decode($voters,TRUE);

		if(!empty($voters))
			$already_voted = array_key_exists(userid(),$voters);
		
		

		if(!userid())
			e(lang("please_login_to_rate"));
		elseif(userid()==$c_rating['userid'] && !config('own_collection_rating'))
			e(lang("you_cannot_rate_own_collection"));
		elseif(!empty($already_voted))
			e(lang("you_hv_already_rated_photo"));
		elseif($c_rating['allow_rating'] == 'no' || !config('collection_rating'))
			e(lang("collection_rating_not_allowed"));
		else
		{
			$voters[userid()] = array('rate'=>$rating,'time'=>NOW());
			if(phpversion < '5.2.0')
				$voters = $json->json_encode($voters);
			else
				$voters = json_encode($voters);
					
			$t = $c_rating['rated_by'] * $c_rating['rating'];
			$rated_by = $c_rating['rated_by'] + 1;
			$new_rate = ($t + $rating) / $rated_by;
			$db->update(tbl('collections'),array('rating','rated_by','voters'),
			array("$new_rate","$rated_by","|no_mc|$voters"),
			" collection_id = ".$id."");
			$userDetails = array(
				"object_id"	=>	$id,
				"type"	=>	"collection",
				"time"	=>	now(),
				"rating"	=>	$rating,
				"userid"	=>	userid(),
				"username"	=>	username()
			);	
			/* Updating user details */		
			update_user_voted($userDetails);			
			e(lang("thnx_for_voting"),"m");			
		}
	
		$return = array("rating"=>$new_rate,"rated_by"=>$rated_by,'total'=>10,"id"=>$id,"type"=>"collection","disable"=>"disabled");
		return $return;	
	}
	
	/**
	 * Function used generate collection link
	 */
	function collection_rating($cid,$type)
	{	
		switch($type)
		{
			case "videos":
			case "v":
			{
				global $cbvideo;
				$items = $cbvideo->collection->get_collection_items_with_details($cid);
				$total_rating = '';
				if(!empty($items))
				{
					foreach($items as $item)
					{
						$total_rating += $item['rating'];
						if(!empty($item['rated_by']) && $item['rated_by'] != 0)
							$voters[] = $item['rated_by'];	
					}
				}
			}
			break;
			
			case "photos":
			case "p":
			{
				global $cbphoto;
				$items = $cbphoto->collection->get_collection_items_with_details($cid);
				$total_rating = '';
				if(!empty($items))
				{
					foreach($items as $item)
					{
						$total_rating += $item['rating'];
						if(!empty($item['rated_by']) && $item['rated_by'] != 0)
							$voters[] = $item['rated_by'];	
					}
				}
			}
			break;
		}
		$total_voters = count($voters);
		if(!empty($total_rating) && $total_voters != 0)
		{
			$collect_rating = $total_rating / $total_voters;
			return round($collect_rating,2);	
		}
	}
	
	/**
	 * Function used to add comment 
	 */
	function add_comment($comment,$obj_id,$reply_to=NULL,$force_name_email=false)
	{
		global $myquery,$db;
		
		$collection = $this->get_collection($obj_id);
		if(!$collection)
			e(lang("collect_not_exist"));
		else
		{
			$obj_owner = $this->get_collection_field($collection,"userid");
			$cl_link = $this->collection_links($collection,'vc');
			$comment = $myquery->add_comment($comment,$obj_id,$reply_to,'cl',$obj_owner,$cl_link,$force_name_email);
			//echo $comment;
			if($comment)
			{
				$log_array = array
				(
				 'success'=>'yes',
				 'details'=> "comment on a collection",
				 'action_obj_id' => $obj_id,
				 'action_done_id' => $comment,
				);
				insert_log('collection_comment',$log_array);
				
				$this->update_total_comments($obj_id);	
			}
			return $comment;
		}
	}
	
	/**
	 * Function used to update total comments of collection 
	 */
	function update_total_comments($cid)
	{
		global $db;
		$count = $db->count(tbl("comments"),"comment_id"," type = 'cl' AND type_id = '$cid'");
		$db->update(tbl($this->section_tbl),array("total_comments","last_commented"),array($count,now())," collection_id = '$cid'");	
	}
	
	/**
	 * Function used return collection links
	 */
	function collection_links($details,$type=NULL)
	{		
		if(is_array($details))
		{
			if(empty($details['collection_id']))
				return BASEURL;
			else
				$cdetails = $details;		
		} else {
			if(is_numeric($details))
				$cdetails = $this->get_collection($details);
			else
				return BASEURL;		
		}
		
		if(!empty($cdetails))
		{
			if($type == NULL || $type == "main")
			{
				if(SEO == 'yes')
					return BASEURL."/collections";
				else
					return 	BASEURL."/collections.php";	
			}
			elseif($type == "vc" || $type == "view_collection" ||$type == "view")
			{
				if(SEO == 'yes')
					return BASEURL."/collection/".$cdetails['collection_id']."/".$cdetails['type']."/".SEO(($cdetails['collection_name']))."";	
				else
					return BASEURL."/view_collection.php?cid=".$cdetails['collection_id']."&amp;type=".$cdetails['type']; 
			} elseif($type == "vi" || $type == "view_item" ||$type == "item") {
				//$item_type = $this->get_collection_field($cdetails['collection_id'],'type');
				if($cdetails['videoid'])
					$item_type = 'videos';
				else
					$item_type = 'photos';
				switch($item_type)
					{
						case "videos":
						case "v":
						{
							if(SEO == "yes")
								return BASEURL."/item/".$item_type."/".$details['collection_id']."/".$details['videokey']."/".SEO(clean(str_replace(' ','-',$details['title'])));
							else
								return BASEURL."/view_item.php?item=".$details['videokey']."&amp;type=".$item_type."&amp;collection=".$details['collection_id'];
						}
						break;
						
						case "photos":
						case "p":
						{
							if(SEO == "yes")
								return BASEURL."/item/".$item_type."/".$details['collection_id']."/".$details['photo_key']."/".SEO(clean(str_replace(' ','-',$details['photo_title'])));
							else
								return BASEURL."/view_item.php?item=".$details['photo_key']."&amp;type=".$item_type."&amp;collection=".$details['collection_id'];	
						}
						break;
					}
			} elseif($type == 'load_more' || $type == 'more_items' || $type='moreItems') {
				if(empty($cdetails['page_no']))
					$cdetails['page_no'] = 2;
					
				if(SEO == 'yes')
					return "?cid=".$cdetails['collection_id']."&amp;type=".$cdetails['type']."&amp;page=".$cdetails['page_no'];
				else
					return 	"?cid=".$cdetails['collection_id']."&amp;type=".$cdetails['type']."&amp;page=".$cdetails['page_no'];
			}
		} else {
			return BASEURL;	
		}
	}
	
	/**
	 *	Used to update counts
	 */
	function update_collection_counts($id,$amount,$op)
	{
		global $db;
		$db->update(tbl("collections"),array("total_objects"),array("|f|total_objects$op$amount")," collection_id = $id");	
	}
	
	/**
	 *	Used to change collection of product
	 */
	function change_collection($new,$obj,$old=NULL)
	{
		global $db;
		
		/* THIS MEANS OBJECT IS ORPHAN MOST PROBABLY AND HOPEFULLY - PHOTO 
		   NOW WE WILL ADD $OBJ TO $NEW */
		if($old == 0 || $old == NULL)
		{
			$this->add_collection_item($obj,$new);
		} else {
			$update = $db->update(tbl($this->items),array('collection_id'),array($new)," collection_id = $old AND type = '".$this->objType."' AND object_id = $obj");
			
			if(!empty($update))
			{
				$this->update_collection_counts($new,1,'+');
				$this->update_collection_counts($old,1,'-');
			}
		}
	}
	
	/**
	 * Sorting links for collection
	 */
	function sorting_links()
	{
		if(!isset($_GET['sort']))
			$_GET['sort'] = 'most_recent';	
		
		$array = array
			('most_recent' 	=> lang('recent'),
			 'most_viewed'	=> lang('viewed'),
			 'featured'		=> lang('featured'),
			 'most_items'	=> lang('Most Items'),
			 'most_commented'	=> lang('commented'),
			 );
		return $array;	 	
	}
	
	/**
	 * Used to perform actions on collection
	 */
	function collection_actions($action,$cid)
	{
		global $db;
		switch($action)
		{
			case "activate":
			case "activation":
			case "ac":
			{
				$db->update(tbl($this->section_tbl),array("active"),array("yes")," collection_id = $cid");
				e(lang("collection_activated"),"m");
			}
			break;
			
			case "deactivate":
			case "deactivation":
			case "dac":
			{
				$db->update(tbl($this->section_tbl),array("active"),array("no")," collection_id = $cid");
				e(lang("collection_deactivated"),"m");
			}
			break;
			
			case "make_feature":
			case "featured":
			case "mcf":
			{
				$db->update(tbl($this->section_tbl),array("featured"),array("yes")," collection_id = $cid");
				e(lang("collection_featured"),"m");
			}
			break;
			
			case "make_unfeature":
			case "unfeatured":
			case "mcuf":
			{
				$db->update(tbl($this->section_tbl),array("featured"),array("no")," collection_id = $cid");
				e(lang("collection_unfeatured"),"m");
			}
			break;
			
			default:
			{
				header("location:".BASEURL);	
			}
		}
	}
	
	/**
	 * Function used to get collection from its Item ID and type
	 * only get collections of logged in user
	 * @param : OBJ_ID
	 * @param : OBJ_Type
	 * @return : Object
	 */
	function getCollectionFromItem($objId,$type=NULL)
	{
		global $db;
		if(!$type)
			$type = $this->objType;
		$userid=userid();
		$results = $db->select(tbl('collections,collection_items'),'*',
		tbl("collections.collection_id")." = ".tbl("collection_items.collection_id")." AND "
		.tbl("collection_items.type='".$type."'")." AND ".tbl("collections.userid='".$userid."'")." AND "
		.tbl("collections.active='yes'")." AND ".tbl("collection_items.object_id='".$objId."'"));
		
		if($db->num_rows>0)
			$assign = $results;
		else
			$assign = false;
			
		return $assign;	
		
	}
	
	/**
	 * Function used to remove item from collections
	 * and decrement collection item count
	 * @param : itemID
	 * @param : type
	 */
	function deleteItemFromCollections($objId,$type=NULL)
	{
		global $db,$cbvid;
		if(!$type)
			$type = $this->objType;

			$db->update(tbl('collections,collection_items'),array('total_objects'),array('|f|total_objects -1'),
			tbl("collections.collection_id")." = ".tbl("collection_items.collection_id")." AND "
			.tbl("collection_items.type='".$type."'")."  AND ".tbl("collection_items.object_id='".$objId."'"));
			
			
			$db->execute("DELETE FROM ".tbl('collection_items')." WHERE "
			.("type='".$type."'")."  AND ".("object_id='".$objId."'"));
	}
	
}

?>