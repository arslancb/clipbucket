<?php

/**
 * @Author  : Fawaz Tahir
 * @Software : Community Bucket
 * @License : CBLA
 * @Since : 31 OCT 2009
 */

class Groups extends CBCategory {
	var $gp_thumb_width = '120';
	var $gp_thumb_height = '90';
	var $gp_tbl = '';

	/**
	 * Constructor function to set values of tables
	 */
	function Groups() {
		$this->cat_tbl = 'group_categories';
		$this->gp_tbl =  'groups';
	}
	
	/**
	 * Function used to check if the provided URL is taken or not
	 * @param = $url { URL of group provided by user }
	 */		
	function GroupUrl($url) {
		global $db;
		$result = $db->select($this->gp_tbl,"*"," group_url='$url'");
		if($db->num_rows>0) {
			return $result[0];	
		} else{
			return false;	
		}
	}
	

	/**
	 * Function used to get all the data of groups 
	 */
	function get_groups() {
		global $db;
		$getgp = $db->select($this->gp_tbl,"*");
		return $getgp;
	}
	
	/**
	 * Function used to list groups 
	 */
	function list_groups() {
		global $db;
		$getgp = $db->select($this->gp_tbl,"*","active='yes' AND (group_type='0' OR group_type='1')");
		return $getgp;
	}
	
	/**
	 * Function used to make user a member of group 
	 * @param = $userid { ID of user who is going to Join Group }
	 * @param = $username { Username of user who is going to Join Group }
	 * @param = $gpid { ID of group which is being joined }
	 */
	function join_group($gpid,$ownerid=NULL,$userid=NULL) {
		global $db;
		
		//Check if user is already a member
		if($this->joined_group($userid,$gpid)) {
			e(lang('You are already a member of this group'));	
		} else {
			$db->insert("group_members",
						array("group_id","ownerid","userid","date_added"),
						array($gpid,$ownerid,$userid,now()));
			
			//Count total members
			$total_members = $this->total_members($gpid);
			
			//Update Stats
			$db->update("group_stats",
						array("total_members"),
						array($total_members),
						"group_id='$gpid'");
			
			e(lang('You have successfully joined group.'),'m');
		}
	}

	/**
	 * Function used to check whether user is already a member or not 
	 * @param = $user { User to check }
	 * @param = $gpid { ID of group in which we will check }
	 */
	function joined_group($user,$gpid) {
		global $db;
		$data = $db->select("group_members","*","group_id='$gpid' AND userid='$user'");
		if($db->num_rows>0) {
			return true;	
		} else {
			return false;	
		}
	}

	/**
	 * Function used to get all the data of specific group by ID
	 * @param = $gpid { ID of the group we want to edit }
	 */	
	function group_details($gpid) {
		global $db;
		$gp_details = $db->select($this->gp_tbl,"*","group_id='$gpid'");
		if($db->num_rows>0) {
			return $gp_details[0];
		} else{
			return false;	
		}
	}
	
	/**
	 * Function used to get all the data of specific group by URL
	 * @param = $gpurl { URL of the group we want to edit }
	 */	
	function group_details_url($gpurl) {
		global $db;
		$gp_details = $db->select($this->gp_tbl,"*","group_url='$gpurl'");
		if($db->num_rows>0) {
			return $gp_details[0];
		} else{
			return false;	
		}
	}
	
	/**
	 * Function used to check if group is active or unactive 
	 * @param = $gpid { ID of the group we want to check }
	 */
	function gp_chk_act($gpid) {
		global $db;
		$data = $db->select($this->gp_tbl,"*","group_id='$gpid' AND active='yes'");
		if($db->num_rows>0) {
			return true;	
		} else {
			return false;	
		}
	}

	
	/**
	 * Function used to count total number of groups.
	 */
	function total_groups() {
		global $db;
		$totalgp = $db->count($this->gp_tbl,"*");
		return $totalgp;
	}
	
	/**
	 * Function used to count total number of members in a group.
	 * @param = $gpid { ID of group whose members are going to be counted }
	 */
	function total_members($gpid) {
		global $db;
		$totalmem = $db->count("group_members","*","group_id='$gpid'");
		return $totalmem[0];
	}
	
	/**
	 * Function used to get group stats
	 * @param = $gpid { ID of group whose members are going to be counted }
	 */
	function group_stats($gpid) {
		global $db;
		$stats = $db->select("group_stats","*","group_id='$gpid'");
		return $stats[0];
	}
	
	/**
	 * Function used to get default image for group.
	 */
	function get_default_thumb() {
		$this->get_default_thumb = 'no_thumb.png';
		return $this->get_default_thumb;
	}
	
	/**
	 * Function used to get group thumb.
	 */
	function get_gp_thumb($gp_img) {
		
		if(empty($gp_img)) {
			return $this->get_default_thumb();
		} else{
			return $gp_img;
		}
	}
	
	/**
	 * Function used to create new groups
	 */
	function createGroups($array,$user=false) {
		global $db;
		if($user) {
			$owner = $user;
		} else {
			$owner = $array['users'];
		}
		$title = $array['title'];
		$description = $array['description'];
		$category = $array['category'];
		$gptype = $array['gptype'];
		$tags = $array['tags'];
		$gpurl = $array['gp_url'];
		
		// Validating all the fields
		
		if(empty($title)) {
			e(lang('We need your group title. Enter Group Title'));	
		} elseif(empty($description)) {
			e(lang("We need Description for a group. Please Enter Description."));	
		} elseif(empty($tags)) {
			e(lang("Please Enter Tags, so other can find your group."));	
		} elseif(empty($category)) {
			e(lang("Please Select a category."));	
		} elseif(empty($owner) && $user == false) {
			e(lang("Please select the Owner of Group."));	
		} elseif(empty($gpurl)) {
			e(lang('Please enter a URL for your Group.'));
		} elseif($this->GroupUrl($gpurl)) {
			e(lang("This url is already taken."));
		} elseif(!preg_match("/^([a-z0-9-_\.]*?)$/",$gpurl)) {
			e(lang("Invalid Format of your Group URL."));
		}else{
			$gpin = $db->insert($this->gp_tbl,
						array('group_name,group_description,group_category,group_tags,group_url,group_owner,group_type,date_created'),
						array($title,$description,$category,$tags,$gpurl,$owner,$gptype,now())
						);
			$gpid = $db->insert_id(); // used to get ID freshly inserted data.
			
			//Insert stats in group_stats table.
			$db->insert("group_stats",array("group_id,total_members,total_topics"),array($gpid,"0","0"));
			
			//Owner will join his group automaically
				$this->join_group($gpid,$owner);
			
			// Creating group thumbnail, if provided
			if(!empty($_FILES['gpThumb']['tmp_name'])) {
				$this->create_group_image($gpid,$_FILES['gpThumb']);
			}
			
			e(lang('Group Created'),'m');
		}
			
	}
	
	/**
	 * Function used to delete the old group image, if provided new one.
	 */
	function update_gp_image($gpid) {
		$path = GP_THUMB_DIR."/".$gpid;
		$extents = array('png','jpg','gif');
		$img = array();
		$i = 0;
		foreach ($extents as $ext) {
			$img[] = $path.".".$ext;
		}
		while($i < count($img)) {
			 if(file_exists($img[$i])) {
				unlink($img[$i]); 
			 }
			$i++;
		}
	}
	
	/**
	 * Function used to edit/update group
	 */
	function edit_group($array) {
		global $db;
		$nowner = mysql_clean($array['users']);
		$owner = mysql_clean($array['owner']);
		$title = mysql_clean($array['title']);
		$description = mysql_clean($array['description']);
		$tags = mysql_clean($array['tags']);
		$category = mysql_clean($array['category']);
		$ngpurl = mysql_clean($array['gp_url']);
		$gpurl = mysql_clean($array['gp_url']);
		$gpid = mysql_clean($array['gpid']);
		$gptype = mysql_clean($array['gptype']);
		
		// Validating all the fields
		
		if(empty($title)) {
			e(lang('We need your group title. Enter Group Title'));	
		} elseif(empty($description)) {
			e(lang("We need description for your group. Please Enter Description."));	
		} elseif(empty($tags)) {
			e(lang("Please Enter Tags, so other can find your group."));	
		} elseif(empty($category)) {
			e(lang("Please Select a category."));	
		} elseif(empty($gpurl)) {
			e(lang('Please enter a URL for your Group.'));
		} elseif($this->GroupUrl($ngpurl) && $ngpurl != $gpurl) {
			e(lang("This url is already taken."));
		} else {
			
			//Change owner of group if admin changed them
			if($nowner != $owner) {
				$this->change_owner($gpid,$owner,$nowner);	
			}
			
			$db->update($this->gp_tbl,
						array("group_name","group_description","group_category","group_tags","group_url","group_type","group_owner"),
						array($title,$description,$category,$tags,$ngpurl,$gptype,$nowner),
						" group_id='$gpid'");
			
			// Updating group thumbnail, if provided
			if(!empty($_FILES['gpThumb']['tmp_name'])) {
				// Used to delete previous image of groups. Saving Space.
				$this->update_gp_image($gpid);
				$this->create_group_image($gpid,$_FILES['gpThumb']);
			}
			
			e(lang('Group is updated.','m'));
		}

	}
	
	/**
	 * Function used to delete group
	 * @param = $gpid {ID of group which you want to delete }
	 */
	function delete_group($gpid) {
		global $db;
		if($this->group_details($gpid)) {
			$db->execute("DELETE FROM ".$this->gp_tbl." WHERE group_id='$gpid'");
			e(lang('Group is deleted.','m'));	
		} else {
			e(lang('Group does not exist.'));	
		}
	}
	
	/**
	 * Function used to change group owner in group_members table if change from Admin Area.
	 * @param = $gpid { ID of group which will become deactive }
	 */
	function change_owner($gpid,$owner,$nowner) {
			global $db;
			$db->update("group_members",array("ownerid"),array($nowner)," group_id='$gpid'");
	}

	/**
	 * Function used to make group Deactive.
	 * @param = $gpid { ID of group which will become deactive }
	 * @param = $multi { false means your are deactivating single group, true means deactivating multiple groups }
	 */
	function unactive_gp($gpid,$multi=false) {
		global $db;		
		//Check if group exists
		if(!$this->group_details($gpid)) {
			e(lang("Either this group has been deleted or never existed from the start."));	
		}
		// If Multiple Groups are getting active, don't display message here.
		if($multi==true) {
				$db->update($this->gp_tbl,array('active'),array('no'),"group_id='$gpid'");
		}
		
		// If $multi == false, display message here
		if($multi==false) {
			if($this->gp_chk_act($gpid)) {
				$db->update($this->gp_tbl,array('active'),array('no'),"group_id='$gpid'");
				e(lang("Group is deactivate now.",'m'));
			}
		}
	}
	
	/**
	 * Function used to make group Active.
	 * @param = $gpid { ID of group which will become active }
	 * @param = $multi { false means your are activating single group, true means activating multiple groups }
	 */
	function active_gp($gpid,$multi=false) {
		global $db;		
		//Check if group exists
		if(!$this->group_details($gpid)) {
			e(lang("Either this group has been deleted or never existed from the start."));	
		}
		// If Multiple Groups are getting active, don't display message here.
		if($multi==true) {
				$db->update($this->gp_tbl,array('active'),array('yes'),"group_id='$gpid'");
		}
		
		// If $multi == false, display message here
		if($multi==false) {
			if(!$this->gp_chk_act($gpid)) {
				$db->update($this->gp_tbl,array('active'),array('yes'),"group_id='$gpid'");
				e(lang("Group is Activate now.",'m'));
			}
		}
	}
	
	/**
	 * Function used to create group thumbnail
	 * @param = $gpid {ID of group for which thumbnail is being created }
	 * @param = $file { Source of image file $_FILES }
	 */
	function create_group_image($gpid,$file) {
			global $imgObj,$db;
			$ext = strtolower(getext($file['name']));
			if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif") {
				$thumb_name = $gpid.'.'.$ext;
				$path = GP_THUMB_DIR.'/'.$thumb_name;
				move_uploaded_file($file['tmp_name'],$path);
				
				if(!$imgObj->ValidateImage($path,$ext)) 
					e(lang('Please Upload a valid with JPG OR GIF OR PNG Image.'));	
				else
				{
					$imgObj->CreateThumb($path,$path,$this->gp_thumb_width,$ext,$this->gp_thumb_width,true);
					$db->update($this->gp_tbl,array('group_image'),array($thumb_name),"group_id='$gpid'");
				}
			}
	}
}
?>