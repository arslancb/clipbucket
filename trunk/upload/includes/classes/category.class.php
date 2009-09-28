<?php

/**
 * @Author : Arslan Hassan <arslan@clip-bucket.com>
 * This class is used to create 
 * and manage categories
 * its an abstract class
 * it will be used in custom plugins or built-in plugins
 * sections also use category system like videos, groups , channels etc
 *
 * this abstract class has some rules
 * each section's category column should be named as "category"
 * each section's category table must have same columns as video_categories
 */



abstract class CBCategory
{
	var $cat_tbl = ''; //Name of category Table
	var $section_tbl = ''; //Name of table that related to $cat_tbl
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
		$results = $db->select($this->cat_tbl,"*"," category_id='$cid' ");
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
		$results = $db->select($this->cat_tbl,"*"," category_name='$name' ");
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
		$name = mysql_clean($array['name']);
		$desc = mysql_clean($array['desc']);
		$default = mysql_clean($array['default']);
		
		if($this->get_cat_by_name($name))
		{
			e(lang("add_cat_erro"));
			
		}elseif(empty($name))
		{
			e(lang("add_cat_no_name_err"));
		}else{
			$cid = $db->insert($this->cat_tbl,
						array("category_name","category_desc","date_added"),
						array($name,$desc,now())
						);
			$cid = $db->insert_id();
			if($default=='yes' || !$this->get_default_category())
				$this->make_default_category($cid);
			e(lang("cat_add_msg"),m);
			
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
			$db->update($this->cat_tbl,array("isdefault"),array("no")," isdefault='yes' ");
			$db->update($this->cat_tbl,array("isdefault"),array("yes")," category_id='$cid' ");
			e(lang("cat_set_default_ok"),m);
		}else
			e(lang("cat_exist_error"));
	}
	
	
	/**
	 * Function used to get list of categories
	 */
	function get_categories()
	{
		global $db;
		$select = $db->select($this->cat_tbl,"*");
		return $select;
	}
	
	
	/**
	 * Function used to count total number of categoies
	 */
	function total_categories()
	{
		global $db;
		return  $db->count($this->cat_tbl,"*");
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
			//Moving all contents to default category
			$this->change_category($cid);
			//Removing Category
			$db->execute("DELETE FROM ".$this->cat_tbl." WHERE category_id='$cid'");
			e(lang("class_cat_del_msg"),m);
		}
	
	}
	
	/**
	 * Functon used to get dafault categry
	 */
	function get_default_category()
	{
		global $db;
		$results = $db->select($this->cat_tbl,"*"," isdefault='yes' ");
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
	function change_category($from,$to=NULL,$check_multiple=false)
	{
		global $db;
		if(!$this->category_exists($to))
			$to = $this->get_default_cid();
		$db->execute("UPDATE ".$this->section_tbl." SET category = replace(category,'#".$from."#','#".$to."#') WHERE category LIKE '%#".$from."#%'");
		$db->execute("UPDATE ".$this->section_tbl." SET category = replace(category,'#".$to."# #".$to."#','#".$to."#') WHERE category LIKE '%#".$to."#%'");
	}
	
	
	/**
	 * Function used to edit category
	 * submit values and it will update category
	 */
	function update_category($array)
	{
		global $db;
		$name = mysql_clean($array['name']);
		$desc = mysql_clean($array['desc']);
		$default = mysql_clean($array['default']);
		
		$cur_name = mysql_clean($array['cur_name']);
		$cid = mysql_clean($array['cid']);
		
		
		if($this->get_cat_by_name($name) && $cur_name !=$name )
		{
			e(lang("add_cat_erro"));
			
		}elseif(empty($name))
		{
			e(lang("add_cat_no_name_err"));
		}else{
			$db->update($this->cat_tbl,
						array("category_name","category_desc"),
						array($name,$desc),
						" category_id='$cid' "
						);
			if($default=='yes' || !$this->get_default_category())
				$this->make_default_category($cid);
			e(lang("cat_update_msg"),m);
			
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
						e(lang("pic_upload_vali_err"));
					else
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
	
}

?>