<?php

/**
 * @Author : Arslan Hassan <arslan@clip-bucket.com>
 * This class is used to create 
 * and manage categories
 * its an abstract class
 * it will be used in custom plugins or built-in sections
 * sections like videos, groups , channels etc use this category system
 *
 * this abstract class has some rules
 * each section's category column should be named as "category"
 * each section's category table must have same columns as video_categories
 */



abstract class CBCategory
{
	var $cat_tbl = ''; //Name of category Table
	var $section_tbl = ''; //Name of table that related to $cat_tbl
	var $use_sub_cats = FALSE; // Set to true if you using Sub-Cateogires
	var $cat_thumb_height = '125';
	var $cat_thumb_width = '125';
	var $default_thumb = 'no_thumb.jpg';
	
	/**
	 * Function used to check weather category exists or not
	 */
	function category_exists($cid)
	{
		global $db;
		return $this->get_category($cid); 
	}
	
	
	/**
	 * Function used to get category details
	 */
	function get_category($cid)
	{
		global $db;
		$results = $db->select(tbl($this->cat_tbl),"*"," category_id='$cid'");
		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to get category by name
	 */
	function get_cat_by_name($name)
	{
		global $db;
		$results = $db->select(tbl($this->cat_tbl),"*"," category_name='$name' ");
		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}
	
	
	/**
	 * Function used to add new category
	 */
	function add_category($array)
	{
		global $db;
		$name = ($array['name']);
		$desc = ($array['desc']);
		$default = mysql_clean($array['default']);
		
		$flds = array("category_name","category_desc","date_added");
		$values = array($name,$desc,now());
		
		if(!empty($this->use_sub_cats))
		{
			$parent_id = mysql_clean($array['parent_cat']);
			$flds[] = "parent_id";
			$values[] = $parent_id;	
		}
		
		if($this->get_cat_by_name($name))
		{
			e(lang("add_cat_erro"));
			
		}elseif(empty($name))
		{
			e(lang("add_cat_no_name_err"));
		}else{
			$cid = $db->insert(tbl($this->cat_tbl),$flds,$values);
						
			$cid = $db->insert_id();
			if($default=='yes' || !$this->get_default_category())
				$this->make_default_category($cid);
			e(lang("cat_add_msg"),'m');
			
			//Uploading thumb
			if(!empty($_FILES['cat_thumb']['tmp_name']))
				$this->add_category_thumb($cid,$_FILES['cat_thumb']);
		}
		
	}
	
	
	
	/**
	 * Function used to make category as default
	 */
	function make_default_category($cid)
	{
		global $db;
		if($this->category_exists($cid))
		{
			$db->update(tbl($this->cat_tbl),array("isdefault"),array("no")," isdefault='yes' ");
			$db->update(tbl($this->cat_tbl),array("isdefault"),array("yes")," category_id='$cid' ");
			e(lang("cat_set_default_ok"),'m');
		}else
			e(lang("cat_exist_error"));
	}
	
	
	/**
	 * Function used to get list of categories
	 */
	function get_categories()
	{
		global $db;
		$select = $db->select(tbl($this->cat_tbl),"*",NULL,NULL," category_order ASC");
		return $select;
	}
	
	function getCbCategories($params)
	{
		global $db; 
		$params['use_sub_cats'] = $params['use_sub_cats'] ? $params['use_sub_cats'] : "yes";
		if($this->use_sub_cats && config('use_subs') == 1 && $params['use_sub_cats'] == "yes" && 
		   ($params['type'] == "videos" || $params['type'] == "video" || $params['type'] == "v"))
		{
			$cond = " parent_id = 0";
			$subCategories = TRUE;	
		} else 
			$cond = NULL;
		$orderby = $params['orderby'] = $params['orderby'] ? $params['orderby'] : "category_order";
		$order = $params['order'] = $params['order'] ? $params['order'] : "ASC";
		$limit = $params['limit'] = $params['limit'] ? (is_numeric($params['limit']) ? $params['limit'] : NULL) : NULL;
		
		
		$categories = $db->select(tbl($this->cat_tbl),"*",$cond,$limit," $orderby $order");

		$finalArray = array();
		if($params['with_all'])
			$finalArray[] = array("category_id"=>"all","category_name"=>lang("cat_all"));
		
		foreach($categories as $cat)
		{
			$finalArray[$cat['category_id']] = $cat;	
			if($subCategories === TRUE && $this->is_parent($cat['category_id']))
				$finalArray[$cat['category_id']]['children'] = $this->getCbSubCategories($cat['category_id'],$params);
		}
		
		return $finalArray;
	}
	
	function getCbSubCategories($category_id,$params)
	{
		global $db;
		if(empty($category_id))
			return false;
		{
			$orderby = $params['orderby']; $order = $params['order'];
			$finalOrder = $orderby.' '.$order;
			if($params['limit_sub'])
			{
				if(is_numeric($params['limit_sub']))
					$limit = $params['limit_sub'];
				elseif($params['limit_sub'] == "parent")
					$limit = $params['limit'];
				else
					$limit = NULL;
			}
			if($params['sub_order'])
				$finalOrder = $params['sub_order'];
			$subCats = $db->select(tbl($this->cat_tbl),"*"," parent_id = '$category_id'",$limit," $finalOrder");
			
			if($subCats)
			{
				$subArray = array();
				foreach($subCats as $subCat)
				{
					$subArray[$subCat['category_id']] = $subCat;
					if($this->is_parent($subCat['category_id']))
					{
						$subArray[$subCat['category_id']]['children'] = $this->getCbSubCategories($subCat['category_id'],$params);
					}
				}
				return $subArray;
			}
		}
	}
	
	function displayListCategories($catArray,$params)
	{
		$html = '';
		foreach($catArray as $catID=>$cat)
		{
			if($_GET['cat'] == $cat['category_id'] || (empty($_GET['cat']) && $cat['category_id'] == 'all'))
				$selected = " selected ";
			else
				$selected = "";
			if($params['class'])
				$class = $params['class'];		
			$html .= "<li class='cbCategoryItem ".$class.$selected."'";
			if($params['id'])
				$html .= " id = '".$params['id']."'";					
			$html .= "><a href='".cblink(array("name"=>"category","data"=>$cat,"type"=>$params['type']))."'>".$cat['category_name']."</a>";
			if($cat['children'])
			{
				$html .= "<ul id='".$cat['category_id']."_categories' class='sub_categories'>";
				$html .= $this->displayListCategories($cat['children'],$params);
				$html .= "</ul>";
			}
			$html .= "</li>";
		}
		
		return $html;	
	}
	
	function displayOptions($catArray,$params,$spacer="")
	{
		foreach($catArray as $catID=>$cat)
		{
			if($_GET['cat'] == $cat['category_id'] || ($params['selected'] && $params['selected'] == $cat['category_id']))
				$selected = " selected=selected";
			else
				$selected = "";
			if($params['value'] == "link") 
				$value = cblink(array("name"=>"category","data"=>$cat,"type"=>$params['type'])); else $value = $cat['category_id'];
			$html .= "<option value='$value' $selected>";
			$html .= $spacer.$cat['category_name'];
			$html .= "</option>";
			if($cat['children'])
			{
				$html .= $this->displayOptions($cat['children'],$params,$spacer.($params['spacer']?$params['spacer']:"- "));	
			}
		}
		
		return $html;
	}
	
	function displayDropdownCategory($catArray,$params)
	{
		$html = '';
		if($params['name']) $name = $params['name']; else $name = "cat";
		if($params['id']) $id = $params['id']; else $id = "cat";
		if($params['class']) $class = $params['class']; else $class = "cbSelectCat";
		
		$html .= "<select name='$name' id='$id' class='$class'>";
		if($params['blank_option'])
			$html .= "<option value='0'>None</option>";
		$html .= $this->displayOptions($catArray,$params);
		$html .= "</select>";
		return $html;
	}
	
	function displayCollpasedListCateogry($catArray,$params)
	{
		$html = '';
		
		foreach($catArray as $catID=>$cat)
		{
			if($_GET['cat'] == $catID || (empty($_GET['cat']) && $cat['category_id'] == 'all'))
				$selected =  "selected"; 
			else
				$selected = "";

				
						
			if($params['class'])
				$class = $params['class'];		
			$html .= "<li class='cbCategoryItem ".$class.$selected."'";
			if($params['id'])
				$html .= " id = '".$params['id']."'";					
			$html .= ">";
			$id  = '"#'.$catID.'_categories"';
			if(array_key_exists($catID,$_COOKIE))
			{
				$property = $_COOKIE[$catID];
				if($property == "expanded")$display = "block";
				if($property == "collapsed")$display = "none";		
			} else {
				$display = "none";	
			}	
			if($cat['children'])		
				$html .= "<span id='".$cat['category_id']."_toggler' alt='".$cat['category_id']."_categories' class='CategoryToggler ".$display."' onclick='toggleCategory(this);'>&nbsp;</span>";
							
			$html .= "<a href='".cblink(array("name"=>"category","data"=>$cat,"type"=>$params['type']))."'>".$cat['category_name']."</a>";
			

			
			if($cat['children'])
			{
				

				$html .= "<ul id='".$catID."_categories' class='sub_categories' style='display:".($display)."'>";
				$html .= $this->displayCollpasedListCateogry($cat['children'],$params);
				$html .= "</ul>";
			}
			$html .= "</li>";			
		}
		
		return $html;				
	}
	
	function displayOutput($CatArray,$params)
	{
		$output = $params['output'];
		if(is_array($CatArray))
		{
			switch($output)
			{
				case "list": case "li":
				default:
				{
					if($params['list_style'] == "" || $params['list_style'] == "simple")
						$html = $this->displayListCategories($CatArray,$params);
					if($params['list_style'] == "collapsed")
						$html = $this->displayCollpasedListCateogry($CatArray,$params);	
				}
				break;
				
				case "dropdown": case "option":
				{
					$html = $this->displayDropdownCategory($CatArray,$params);	
				}
				break;
				
				case "checkbox": case "check_box":
				{
					
				}
				break;
			}
			return $html;
		} else 
			return false;
	}
	
	function cbCategories($params=NULL)
	{
		$p = $params;
		$p['type'] = $p['type'] ? $p['type'] : 'video';  
		$p['echo'] = $p['echo'] ? $p['echo'] : FALSE; 
		$p['with_all'] = $p['with_all'] ? $p['with_all'] : FALSE;

		{
			$categories = $this->getCbCategories($p);
			
			if($categories)
			{
				if($p['echo'] == TRUE)
				{
					$html = $this->displayOutput($categories,$p);			
					if($p['assign'])
						assign($p['assign'],$html);
					else
						echo $html;
				} else {
					if($p['assign'])
						assign($p['assign'],$categories);
					else
						return $categories;	
				}
			} else 
				return false;
		}
	}
	
	/**
	 * Function used to list of categories
	 */
	function cb_list_categories($type,$with_all=false)
	{
		global $db;
		if($type == 'video' || $type == 'vid' || $type == 'v')
			$cond = " parent_id = 0";
		else
			$cond = NULL;	
		
		//Getting List of categories
		$cats = $db->select(tbl($this->cat_tbl),"*",$cond,NULL," category_order ASC");
		
		if($with_all)
			array_unshift($cats,array("category_id"=>"all","category_name"=>"All"));
				
		$html = '';
		for($i=0;$i<count($cats);$i++)
		{
			if($_GET['cat'] == $cats[$i]['category_id'] || (empty($_GET['cat']) && $cats[$i]['category_id'] == 'all'))
				$selected = "selected";
			else
				$selected = "";
				
			$html .= "<li class='".$selected."'>";
			$html .= "<a href='".category_link($cats[$i],$type)."' title='".$cats[$i]['category_name']."'>".$cats[$i]['category_name']."</a>";
			if($this->is_parent($cats[$i]['category_id']))
			{
				$html .= $this->cb_list_subs($cats[$i]['category_id'],$type);
			}
			$html .= "</li>";
		}
		return $html;
		
	}
	
	function cb_list_subs($cid,$type)
	{
		global $db;
		$html = "";
		$query = mysql_query("SELECT * FROM ".tbl($this->cat_tbl)." WHERE parent_id = $cid");

		if(!empty($query))
		{
			
			$html .= "<ul id='".$cid."_subs' class='sub_categories'>";
			while($result = mysql_fetch_array($query))
			{	
				if($_GET['cat'] == $result['category_id'])
					$selected = "selected";
				else
					$selected = "";
					
				$html .= "<li class='".$selected."'>";
				$html .= "<a href='".category_link($result,$type)."' title='".$result['category_name']."'>".$result['category_name']."</a>";
				if($this->is_parent($result['category_id']))
				{
					$html .= $this->cb_list_subs($result['category_id'],$type);
				}
				$html .= "</li>";
			}
			
			$html .= "</ul>";
		}
		
		return $html;
	}
	
	
	/**
	 * Function used to count total number of categoies
	 */
	function total_categories()
	{
		global $db;
		return  $db->count(tbl($this->cat_tbl),"*");
	}
	
	
	/**
	 * Function used to delete category
	 */
	function delete_category($cid)
	{
		global $db;
		$cat_details = $this->category_exists($cid);
		if(!$cat_details)
			e(lang("cat_exist_error"));
			
		//CHecking if category is default or not
		elseif($cat_details['isdefault'] == 'yes')
			e(lang("cat_default_err"));
		else{
			
			$pcat = $this->has_parent($cid,true);
			
			//Checking if category is both parent and child
			if($pcat && $this->is_parent($cid))
			{
				$to = $pcat[0]['category_id'];
				$has_child = TRUE;
			}
			elseif($pcat && !$this->is_parent($cid)) //Checking if category is only child
			{
				$to = $pcat[0]['category_id'];
				$has_child = TRUE;
			}
			elseif(!$pcat && $this->is_parent($cid)) //Checking if category is only parent
			{
				$to = NULL;
				$has_child = NULL;
				$db->update(tbl($this->cat_tbl),array('parent_id'),array('0')," parent_id = $cid");
			}
				
			//Moving all contents to parent OR default category									
			$this->change_category($cid,$to,$has_child);
			
			//Removing Category
			$db->execute("DELETE FROM ".tbl($this->cat_tbl)." WHERE category_id='$cid'");
			e(lang("class_cat_del_msg"),'m');
		}
	
	}
	
	/**
	 * Functon used to get dafault categry
	 */
	function get_default_category()
	{
		global $db;
		$results = $db->select(tbl($this->cat_tbl),"*"," isdefault='yes' ");
		if($db->num_rows>0)
			return $results[0];
		else
			return false;
	}
	
	/**
	 * Function used to get default category ID
	 */
	function get_default_cid()
	{
		$default = $this->get_default_category();
		return $default['category_id'];
	}
	
	
	
	/**
	 * Function used to move contents from one section to other
	 */
	function change_category($from,$to=NULL,$has_child=NULL,$check_multiple=false)
	{
		global $db;
		
		if(!$this->category_exists($to))
			$to = $this->get_default_cid();
		
		if($has_child) {
			$db->update(tbl($this->cat_tbl),array('parent_id'),array($to)," parent_id = $from");
		}
		
		$db->execute("UPDATE ".tbl($this->section_tbl)." SET category = replace(category,'#".$from."#','#".$to."#') WHERE category LIKE '%#".$from."#%'");
		
		$db->execute("UPDATE ".tbl($this->section_tbl)." SET category = replace(category,'#".$to."# #".$to."#','#".$to."#') WHERE category LIKE '%#".$to."#%'");
	}
	
	
	/**
	 * Function used to edit category
	 * submit values and it will update category
	 */
	function update_category($array)
	{
		global $db;
		$name = ($array['name']);
		$desc = ($array['desc']);
		$default = mysql_clean($array['default']);
		$pcat = mysql_clean($array['parent_cat']);
		
		$flds = array("category_name","category_desc");
		$values = array($name,$desc);
		
		$cur_name = mysql_clean($array['cur_name']);
		$cid = mysql_clean($array['cid']);
		
		if(!empty($this->use_sub_cats))
		{
			$flds[] = "parent_id";
			$values[] = $pcat;	
		}
		
		if($this->get_cat_by_name($name) && $cur_name !=$name )
		{
			e(lang("add_cat_erro"));
			
		}elseif(empty($name)){
			e(lang("add_cat_no_name_err"));
		} elseif($pcat == $cid){
			e(lang("You can not make category parent of itself"));
		}else{
			$db->update(tbl($this->cat_tbl),$flds,$values," category_id='$cid' ");
			
			if($default=='yes' || !$this->get_default_category())
				$this->make_default_category($cid);
			e(lang("cat_update_msg"),'m');
			
			//Uploading thumb
			if(!empty($_FILES['cat_thumb']['tmp_name']))
				$this->add_category_thumb($cid,$_FILES['cat_thumb']);
		}
	}
	
	
	/**
	 * Function used to add category thumbnail
	 * @param $Cid and Array
	 */
	function add_category_thumb($cid,$file)
	{
		global $imgObj;
		if($this->category_exists($cid))
		{
			//Checking for category thumbs direcotry
			if(isset($this->thumb_dir))
				$dir = $this->thumb_dir;
			else
				$dir = $this->section_tbl;
			
			//Checking File Extension
			$ext = strtolower(getext($file['name']));
			
			if($ext=='jpg' || $ext =='png' || $ext=='gif')
			{
				$dir_path = CAT_THUMB_DIR.'/'.$dir;
				if(!is_dir($dir_path))
					@mkdir($dir_path,0777);
					
				if(is_dir($dir_path))	
				{
					$path = $dir_path.'/'.$cid.'.'.$ext;
					
					//Removing File if already exists
					if(file_exists($path))
						unlink($path);
					move_uploaded_file($file['tmp_name'],$path);
					
					//Now checking if file is really an image
					if(!@$imgObj->ValidateImage($path,$ext))
					{
						e(lang("pic_upload_vali_err"));
						unlink($path);
					}else
					{
						$imgObj->CreateThumb($path,$path,$this->cat_thumb_width,$ext,$this->cat_thumb_height,true);
					}
				}else{
					e(lang("cat_dir_make_err"));
				}
			}else{
				e(lang("cat_img_error"));
			}
		}
	}
	
	
	/**
	 * Function used to get category thumb
	 */
	function get_cat_thumb($cat_details)
	{
		//Checking for category thumbs direcotry
		if(isset($this->thumb_dir))
			$dir = $this->thumb_dir;
		else
			$dir = $this->section_tbl;
		
		$cid = $cat_details['category_id'];
		$path = CAT_THUMB_DIR.'/'.$dir.'/'.$cid.'.';
		$exts = array('jpg','png','gif');
		
		$file_exists = false;
		foreach($exts as $ext)
		{
			$cur_ext = $ext;
			if(file_exists($path.$ext))
			{
				$file_exists = true;
				break;
			}
		}
		
		if($file_exists)
			return CAT_THUMB_URL.'/'.$dir.'/'.$cid.'.'.$ext;
		else
			return $this->default_thumb();
	}
	function get_category_thumb($i)
	{
		return $this->get_cat_thumb($i);
	}
	
	/**
	 * function used to return default thumb
	 */
	function default_thumb()
	{
		if(empty($this->default_thumb))
			$this->default_thumb = 'no_thumb.jpg';
		return CAT_THUMB_URL.'/'.$this->default_thumb;
	}
	
	/**
	 * Function used to update category id
	 */
	function update_cat_order($id,$order)
	{
		global $db;
		$cat = $this->category_exists($id);
		if(!$cat)
			e(lang("cat_exist_error"));
		else
		{
			if(!is_numeric($order) || $order <1)
				$order = 1;
			$db->update(tbl($this->cat_tbl),array("category_order"),array($order)," category_id='".$id."'");
		}
	}
	
	/**
	 * Function used get parent cateogry
	 */
	 function get_parent_category($pid)
	 {
		global $db;
		$result = $db->select(tbl($this->cat_tbl),"*"," category_id = $pid");
		if($db->num_rows>0)
			return $result;
		else
			return false;
	 }
	 
	/**
	 * Function used to check category is parent or not
	 */	
	 function is_parent($cid)
	 {
		 global $db;
		 $result = $db->count(tbl($this->cat_tbl),"category_id"," parent_id = $cid");
		 
		 if($result > 0)
		 	return true;
		 else
		 	return false;	
	 }
	 
	/**
	 * Function used to check wheather category has parent or not
	 */	
	 function has_parent($cid,$return_parent=false)
	 {
		 global $db;
		 $result = $db->select(tbl($this->cat_tbl),"*"," category_id = $cid AND parent_id != 0");
		 
		 if($result > 0) {
		 	if($return_parent)
			{
				$pid = $this->get_parent_category($result[0]['parent_id']);
				return $pid;				
			} else {
				return true;
			}
		 } else {
		 	return false;
		}
	 }

	/**
	 * Function used to get parent categories
	 */	
	function get_parents($count=false) {
		global $db;
		
		if($count) {
			$result = $db->count(tbl($this->cat_tbl),"*"," parent_id = 0");
		} else {	
			$result = $db->select(tbl($this->cat_tbl),"*"," parent_id = 0");
		}
		
		return $result;
	}
	
	/**
	 * Function used to list categories in admin area
	 * with indention
	 */
	function admin_area_cats($selected)
	{
		global $db;
		$html = '';
		$pcats = $this->get_parents();
		
		if(!empty($pcats))
		{
			foreach($pcats as $key=>$pcat)
			{
				if($selected == $pcat['category_id'])
					$select = "selected='selected'";
				else
					$select = NULL;
					
				$html .= "<option value='".$pcat['category_id']."' $select>";
				$html .= $pcat['category_name'];
				$html .= "</option>";
				if($this->is_parent($pcat['category_id']))
					$html .= $this->get_sub_subs($pcat['category_id'],$selected);
			}
			
			return $html;
		}
	}
	
	 /**
	 * Function used to get child categories
	 */	
	 function get_sub_categories($cid)
	 {
		 global $db;
		 $result = $db->select(tbl($this->cat_tbl),"*"," parent_id = $cid");
		 //echo $db->db_query;
		 if($result > 0){
		 	return $result;		
	 	}else{
		 	return false;
	 	}
	 }
	 
	/**
	 * Function used to get child child categories
	*/
	function get_sub_subs($cid,$selected,$space="&nbsp; - ")
	{
		global $db;
		$html = '';
		$subs = $this->get_sub_categories($cid);
		if(!empty($subs))
		{
			foreach($subs as $sub_key=>$sub)
			{
				if($selected == $sub['category_id'])
					$select = "selected='selected'";
				else
					$select = NULL;
					
				$html .= "<option value='".$sub['category_id']."' $select>";
				$html .= $space.$sub['category_name'];
				$html .= "</option>";
				if($this->is_parent($sub['category_id']))
					$html .= $this->get_sub_subs($sub['category_id'],$selected,$space." - ");
			}
			return $html;
		}
	}

	function get_category_field($cid,$field)
	{
		global $db;
		$result = $db->select(tbl($this->cat_tbl),"$field"," category_id = $cid");
		//echo $db->db_query;
		if($result)
			return $result[0][$field];
		else
			return false;
	}
	
	
	
	
}

?>