<?php
// Global Object $videoGrouping is used in the plugin
require_once PLUG_DIR.'/extend_search/extend_video_class.php';

$videoGrouping = new VideoGrouping();
$Smarty->assign_by_ref('videoGrouping', $videoGrouping);

/**
 * Contains actions that can affect the video grouping plugin 
 */
class VideoGrouping extends CBCategory{

	/**
	 * Constructor for videoGrouping's instances
	 */
	function videoGrouping()	{
		$this->init();
	}
	
	/**
	 * Call the parent init function and set a new search engine for videoGrouping into the global $CBucket variable
	 */	
	function init() {
		global $Cbucket;
		$Cbucket->search_types['videogrouping'] = "videoGrouping";
		$Cbucket->configs['videogroupingSection']='yes';
	}
	
	/**
	 * Add a new groupingType
	 *
	 * @param array $array
	 * 		a dictionnary that contains all fields for a groupingType. $_POST is used if empty
	 * @return int|bool
	 * 		groupingType's id if exists , otherwise false
	 */
	function addGroupingType($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$name=mysql_clean($array['groupingTypeName']);
		$inthumb=0;
		if(!empty($array['groupingTypeInThumb'])) $inthumb = 1;
		$inmenu=0;
		if(!empty($array['groupingTypeInMenu'])) $inmenu = 1;
		$cond=" name = '$name'";
		$res=$db->select(tbl('vdogrouping_type'),'id',$cond,false,false,false);
		// test groupingType unicity
		if (count($res)>0){
			e(lang("grouping_type_already_exists"));
			return false;
		}
		else {
			// insert groupingType
			$db->insert(tbl('vdogrouping_type'), array('name','in_thumb','in_menu'), array($name,$inthumb,$inmenu));
			$res=$db->select(tbl('vdogrouping_type'),'id',$cond,false,false,false);
			$id=$res[0]['id'];
			return $id;
		}
	}
	
	/**
	 * get all groupingTypes informations
	 * 
	 * @param bool $inMenu
	 * 		If true only in_menu groupingTypes are returned. Default value is false.
	 * @return array
	 * 		An array containing all groupingTypes sorted by name
	 */
	function getAllGroupingTypes($inMenu=false){
		global $db;
		$query="SELECT * FROM ".tbl("vdogrouping_type");
		if ($inMenu===true)
			$query.=" WHERE in_menu=1";
		$query.= " ORDER BY name ASC";
		return $db->_select($query);
	}
	
	/**
	 * get one groupingType details using it's id
	 *
	 * @param int $id
	 *		GroupingType's id
	 * @return array|bool
	 * 		a dictionary containing each fields for a GroupingType, false if no GroupingType found
	 */
	function getGroupingType($id=NULL)	{
		global $db;
		$query = "SELECT * FROM ".tbl('vdogrouping_type');
		$query .= " WHERE id = '$id'";
		$result = select($query);
		if ($result) {
			$details = $result[0];
			return $details;
		}
		return false;
	}
	
	/**
	 * Function used to update a groupingType
	 *
	 * @param array $array
	 * 		a dictionnary that contains all fields for a groupingType. $_POST is used if empty
	 * @return int|bool
	 * 		groupingType's id if exists , otherwise false
	 */
	function updateGroupingType($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$name=mysql_clean($array['groupingTypeName']);
		$inthumb=0;
		if(!empty($array['groupingTypeInThumb'])) $inthumb = 1;
		$inmenu=0;
		if(!empty($array['groupingTypeInThumb'])) $inthumb = 1;
		$id=mysql_clean($array['grouptypeid']);
		$cond=" name = '$name' ";
		$res=$db->select(tbl('vdogrouping_type'),'id',$cond,false,false,false);
		// test groupingType unicity
		if (count($res)>0 && $res[0]["id"]!=$id){
			e(lang("grouping_type_already_exists"));
			return false;
		}
		else {
			// update groupingType 
			$db->update(tbl('vdogrouping_type'), array('name','in_thumb','in_menu'), array($name,$inthumb,$inmenu),"id='$id'");
			return $id;
		}
	}

	/**
	 * Check if groupingType exists or not
	 *
	 * @param int $id
	 * 		the groupingType's id
	 * @return bool
	 * 		true if groupingType exists otherwise false
	 */
	function groupingTypeExists($id){
		global $db;
		$result = $db->count(tbl('vdogrouping_type'),"id"," id='".$id."'");
		return ($result>0);
	}
	
	/**
	 * Remove groupingType from the database.
	 * if the groupingType is associated to a videoGrouping, then nothing is done, just an error message appears.
	 *
	 * @param int $id
	 * 		the id of the groupingType to be deleted
	 */
	function deleteGroupingType($id) {
		global $db;
		if($this->groupingTypeExists($id)) {
			$udetails = $this->getGroupingType($id);
			$test2=$db->execute("DELETE FROM ".tbl("vdogrouping_type")." WHERE id='$id'");
			if (!$test2)
				e(lang("cant_del_linked_groupingtype_msg")." id=".$id,"e");
			else
				e(lang("groupingtype_deleted")." id=".$id,"m");
		}else{
			e(lang("groupingtype_does_not_exist"));
		}
	}
	
	/**
	 * Add a new video grouping
	 *
	 * @param array $array
	 * 		a dictionnary that contains all fields for a video grouping. 
	 * @param array|NULL $fileInfos
	 * 		a dictionnary providing information about one uploaded image if not NULL.
	 * 		The content of this dictionnary comes from $_FILES variable when uploading a file
	 * @return int|bool
	 * 		grouping's id if exists , otherwise false
	 */
	function addGrouping($array,$fileInfos){
		global $db;
		$name=mysql_clean($array['groupingName']);
		$cond=" name = '$name'";
		$res=$db->select(tbl('vdogrouping'),'id',$cond,false,false,false);
		// test groupingType unicity
		if (count($res)>0){
			e(lang("grouping_already_exists"));
			return false;
		}
		else {
			$file_name = $fileInfos['name'];     	//The file name like it is on the user's disk (ie: my_icon.png)
			$file_type = $fileInfos['type'];     	//The file mime type (ie: image/png)
			$file_size = $fileInfos['size'];     	//The file size in bytes.
			$file_tmpname = $fileInfos['tmp_name']; //The address in the temporary folder where the file was uploader
			$file_error = $fileInfos['error'];    	//The error code (used to know if the file was correctly uploaded)
			$max_size = mysql_clean($array['MAX_FILE_SIZE']);
			$replace_file = mysql_clean($array['replace_thumb']);
			$existing_file = mysql_clean($array['existing_thumb']);
			$groupingTypeId= mysql_clean($array["groupingType"]);
			$nom = md5(uniqid(rand(), true)); //randomize name
			$desc=mysql_clean($array['groupingDesc']);
			$in_menu = 0;
			if(!empty($array['groupingInMenu'])) $in_menu = 1;
			$color = $array['groupingColor'];
			if ($replace_file == "replace") {  //if checkbox "replace thumb" is checked
				if ($file_error === 0) {  //file > 0kb
					if ($file_size < $max_size) { //file < form max size
						if(move_uploaded_file($file_tmpname, VIDEO_GROUPING_UPLOAD."/".$nom.$file_name)){  //moving file from tmp folder to thumbs folder
							if(!empty($array['groupingName'])){
								$result=$db->insert(tbl('vdogrouping'),array("grouping_type_id","name","description","in_menu","color","thumb_url"),array($groupingTypeId, $name,$desc,$in_menu,$color,$nom.$file_name));
								if ($result) {
									$msg = e(lang("grouping_added"),'m');   //success
									$res=$db->select(tbl('vdogrouping_type'),'id',$cond,false,false,false);
									$id=$res[0]['id'];
									return $id;									}
							}
							else $msg = e(lang("name_is_required"),'e'); 						//error
						}
						else $msg = e(lang("unable_to_copy_file"),'e');
					}
					else $msg = e(lang("file_is_too_big"),'e');
				}
				else $msg = e(lang("upload_failed"),'e');
			}
			else {
				if(!empty($array['groupingName'])){
					$result=$db->insert(tbl('vdogrouping'),array("grouping_type_id","name","description","in_menu","color"),array($groupingTypeId,$name,$desc,$in_menu,$color));
					if ($result){
						$msg = e(lang("grouping_added"),'m'); //success
						$res=$db->select(tbl('vdogrouping_type'),'id',$cond,false,false,false);
						$id=$res[0]['id'];
						return $id;
					}
				}
				else
					$msg = e(lang("name_is_required"),'e'); //error
			}
			return false;
		}
	}
	
	/**
	 * get an array of all grouping
	 *
	 * @return array
	 * 		An array of all grouping, including for each grouping the associated groupingType name
	 */
	function getAllGroupings ()	{
		global $db;
		return $db->_select("SELECT g.id,g.name,g.grouping_type_id,g.name,g.place,g.description,g.in_menu,g.color,
				g.thumb_url,gt.name as grouping_type_name FROM ".tbl("vdogrouping") ." AS g ,".tbl("vdogrouping_type")." AS gt
				WHERE g.grouping_type_id = gt.id ORDER BY gt.name,g.place,g.name ASC");
	}
	
	/**
	 * get an array of all grouping of a specified type
	 *
	 * @param int $id
	 * 		the grouping type id
	 * @param bool $in_menu
	 * 		if true only the grouping which are tagged as in_menu will be returned, otherwise all grouping will be returned
	 *
	 * @return array
	 * 		An array of all grouping
	 */
	function getGroupingsOfType ($id, $in_menu=false)	{
		global $db;
		$query="SELECT * FROM ".tbl("vdogrouping") ." WHERE grouping_type_id = ".$id;
		if ($in_menu)
			$query.=" AND in_menu=1 ";
		$query.=" ORDER BY place,name ASC";
		return $db->_select($query);
	}
	
	/**
	 * get the number of all grouping of a specified type
	 *
	 * @param int $id
	 * 		the grouping type id
	 * @param bool $in_menu
	 * 		if true only the grouping which are tagged as in_menu will be returned, otherwise all grouping will be returned
	 *
	 * @return bool|int
	 * 		the number of grouping correspondinf to the specified parameters
	 */
	function countGroupingsOfType ($id, $in_menu=false)	{
		global $db;
		$cond="grouping_type_id = ".$id;
		if ($in_menu)
			$cond.=" AND in_menu=1 ";
		return $db->count(tbl("vdogrouping"),"*",$cond);
	}
	
	
	/**
	 * Get all groupings speficied by the $param attribute
	 *
	 * @param array $params
	 * 		a dictionary containing information about the requested groupings
	 *		<ul>
	 *			<li>$params['limit'] is for pagination (ie '0.100')</li>
	 *			<li>$params['order'] is for ordering</li>
	 *			<li>$params['cond'] is the "where" condition of the sql request</li>
	 * 			<li>$params['count_only'] used only if we want to retrive number of groupings</li>
	 * 			<li>$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template</li>
	 * 		</ul>
	 * @return int|array
	 * 		the number of groupings if $params['count_only'] is set otherwise an array of all specified grouping objects
	 */
	function getGroupings($params=NULL)	{
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['cond']) {
			if($cond!='')
				$cond .= ' AND ';
				$cond .= " ".$params['cond']." ";
		}
		if(!$params['count_only']) {
			$query="SELECT g.id,g.name,g.grouping_type_id,g.name,g.place,g.description,g.in_menu,g.color,
				g.thumb_url,gt.name as grouping_type_name FROM ".tbl("vdogrouping") ." AS g ,".tbl("vdogrouping_type")." AS gt
				WHERE g.grouping_type_id = gt.id ";
			
			if ($cond)
				$query .= ' AND '.$cond; // the "WHERE" statement is defined in the lines above
			if ($order)
				$query .= " ORDER BY ".$order;
			else
				$query .= " ORDER BY gt.name,g.place,g.name ASC";
			if ($limit)
				$query .= " LIMIT  ".$limit;
			$result = $db->_select($query);
		}
		if($params['count_only']){
			$result = $db->count(tbl('vdogrouping')." AS vdogrouping ",'id',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
			return $result;
	}
	
	
	
	/**
	 * Get the specified grouping
	 *
	 * @param int $id
	 * 		The grouping id
	 * @return array
	 * 		An array containing the grouping fields
	 */
	function getGrouping($id){
		global $db;
		$id=mysql_clean($id);
		$data = $db->_select("SELECT * FROM ".tbl("vdogrouping")." WHERE id = $id");
		return $data[0];
	}
	
	/**
	 * Get the number of videos linked to the specified grouping
	 *
	 * @param int $id 
	 * 		The grouping id
	 * @return int
	 * 		The number of videos of this grouping
	 */
	function countVideoInGrouping ($id)	{
		global $db;
		$id=mysql_clean($id);
		$query="SELECT COUNT(`videoid`) FROM ".tbl('video')." AS  v ";
		$query.="LEFT JOIN  ".tbl('video_grouping')." AS vg ON v.videoid=vg.video_id ";
		$query.="LEFT JOIN  ".tbl('vdogrouping')." AS g ON g.id=vg.vdogrouping_id WHERE g.id=".$id;
		$result=$db->_select($query);
		return $result[0]['COUNT(`videoid`)'];
	}
	
	/**
	 * Get the videos in the specified grouping
	 * 
	 * @param int $id
	 * 		The grouping id
	 * @return array
	 * 		An array containing all videos ids linked with this grouping
	 */
	function getVideoOfGrouping ($id)	{
		global $db;
		$id=mysql_clean($id);
		$query="SELECT `videoid` FROM ".tbl('video')." AS  v ";
		$query.="LEFT JOIN  ".tbl('video_grouping')." AS vg ON v.videoid=vg.video_id ";
		$query.="LEFT JOIN  ".tbl('vdogrouping')." AS g ON g.id=vg.vdogrouping_id WHERE g.id=".$id;
		$result=$db->_select($query);
		return $result;
	}

	/**
	 * Remove the grouping if not used
	 * 
	 * An alert message is sent if any video islinked to this grouping
	 *
	 * @param int $id
	 * 		The grouping id
	 */
	function deleteGrouping($id){	
		global $db;
		$id=mysql_clean($id);
		$grp = $this->getGrouping($id);
		$error=false;
		$nb=$this->countVideoInGrouping($id);
		if ($nb>0) {
			$error=true;
			$vids = $this->getVideoOfGrouping($id);
			$videolist="";
			foreach ($vids as $vid){
				$videolist.=$vid["videoid"].", ";
			}
			e($nb." ".lang("videos_use_the_grouping")." '".$grp["name"]."' ".
					lang("please_modify_the_following_videos_before_deleting")." : ".$videolist,'w');
		}
		if (!$error){
			if($grp['thumb_url'] != "default.png"){
				unlink(VIDEO_GROUPING_UPLOAD."/".$grp['thumb_url']);
			}
			$db->delete(tbl('vdogrouping'),array('id'),array($id));
			e(lang("grouping_deleted_succesfully").' : '.$grp['id'],'m');
		}
	}
	
	/**
	 * Change the "in menu" status of the specified grouping
	 * 
	 * Grouping with "in menu" status set to true will be displayed 
	 * in the front end main menu of the corresponding grouping type.
	 * 
	 * @param int $id
	 * 		The grouping id
	 * @param bool $flagInMenu
	 * 		the new value of the "in menu" status
	 */
	function setInMenu($id,$flagInMenu){
		global $db;
		$id=mysql_clean($id);
		$var = $flagInMenu?1:0;
		$db->Execute("UPDATE ".tbl('vdogrouping')." SET in_menu = $var WHERE id = $id");
	}
	
	/**
	 * Update grouping order according to the $array values
	 * 
	 * This order is used to show in_menu groupings in a user predifined order
	 * 
	 * @param array $array
	 * 		A dictionnary that contains for each grouping id it's order.$this. 
	 * 		The keys of the dictionnary are grouping ids and the value the new order.
	 */
	function reorder($array){
		global $db;
		foreach ($array as $key=>$value){
			$db->Execute("UPDATE ".tbl('vdogrouping')." SET place = ".$value." WHERE id = ".$key." ");
		}
	}
	
	/**
	 * Update the specified video grouping
	 *
	 * @param array $array
	 * 		a dictionnary that contains all fields for the video grouping to be modified. $_POST is used if empty
	 * @param array|NULL $fileInfos
	 * 		a dictionnary providing information about one uploaded image if not NULL.
	 * 		The content of this dictionnary comes from $_FILES variable when uploading a file
	 * @return int|bool
	 * 		grouping's id if exists , otherwise false
	 */
	function updateGrouping($array=NULL,$fileInfos){
		global $db;
		$name=mysql_clean($array['groupingName']);
		$cond=" name = '$name'";
		$res=$db->select(tbl('vdogrouping'),'id',$cond,false,false,false);
		// test groupingType unicity
		if (count($res)>0 && $res[0]["id"]!=$array["groupingId"]){
			e(lang("grouping_already_exists"));
			return false;
		}
		else {
			$file_name = $fileInfos['name'];     	//The file name like it is on the user's disk (ie: my_icon.png)
			$file_type = $fileInfos['type'];     	//The file mime type (ie: image/png)
			$file_size = $fileInfos['size'];     	//The file size in bytes.
			$file_tmpname = $fileInfos['tmp_name']; //The address in the temporary folder where the file was uploader
			$file_error = $fileInfos['error'];    	//The error code (used to know if the file was correctly uploaded)
			$max_size = mysql_clean($array['MAX_FILE_SIZE']);
			$replace_file = mysql_clean($array['replace_thumb']);
			$existing_file = mysql_clean($array['existing_thumb']);
			$groupingTypeId= mysql_clean($array["groupingType"]);
			$nom = md5(uniqid(rand(), true)); //randomize name
			$desc=mysql_clean($array['groupingDesc']);
			$in_menu = 0;
			if(!empty($array['groupingInMenu'])) $in_menu = 1;
			$color = $array['groupingColor'];
			if ($replace_file == "replace") {  //if checkbox "replace thumb" is checked
				if($existing_file != "default.png"){
					unlink(VIDEO_GROUPING_UPLOAD."/".$existing_file);
				}
				if ($file_error == 0) {  //file > 0kb
					if ($file_size < $max_size) { //file < form max size
						if(move_uploaded_file($file_tmpname, VIDEO_GROUPING_UPLOAD."/".$nom.$file_name)){  //moving file from tmp folder to thumbs folder
							if(!empty($array['groupingName']) && !empty($array['groupingId'])){
								$db->Execute("UPDATE ".tbl('vdogrouping')." SET grouping_type_id = '".$groupingTypeId."' ,name = '".$name."', description = '".$desc."', color = '".$color."', in_menu = '".$in_menu."', thumb_url = '".$nom.$file_name."' WHERE id = ".$array['groupingId']." ");
								if ($result) {
									$msg = e(lang("grouping_updated"),'m');   //success
									$res=$db->select(tbl('vdogrouping_type'),'id',$cond,false,false,false);
									$id=$res[0]['id'];
									return $id;									}
							}
							else $msg = e(lang("name_is_required"),'e'); 						//error
						}
						else $msg = e(lang("unable_to_copy_file"),'e');
					}
					else $msg = e(lang("file_is_too_big"),'e');
				}
				else $msg = e(lang("upload_failed"),'e');
			}
			else {
				if(!empty($array['groupingName']) && !empty($array['groupingId'])){
					$db->Execute("UPDATE ".tbl('vdogrouping')." SET grouping_type_id = '".$groupingTypeId."' ,name = '".$name."', description = '".$desc."', color = '".$color."', in_menu = '".$in_menu."' WHERE id = ".$_POST['groupingId']." ");
					$msg = e(lang("grouping_updated"),'m'); //success
					$res=$db->select(tbl('vdogrouping_type'),'id',$cond,false,false,false);
					$id=$res[0]['id'];
					return $id;
				}
				else
					$msg = e(lang("name_is_required"),'e'); //error
			}
			return false;
		}
	}
	
	/**
	 * Get groupings relatively to a specific video
	 *
	 * Depending on the $params['selected'] value it get all groupings linked to th video or all groupings non linked to the video
	 *
	 * @param array $params
	 * 		a dictionary containing information about the requested groupings
	 *		<ul>
	 *			<li>$params['limit'] is for pagination (ie '0.100')</li>
	 *			<li>$params['order'] is for ordering</li>
	 *			<li>$params['selected'] if =="yes" returns groupings linked to the video,
	 *									if =="no" returns groupings not linked to the video</li>
	 *			<li>$params['videoid'] is the video's id</li>
	 *			<li>$params['cond'] is the "where" condition of the sql request</li>
	 * 			<li>$params['count_only'] used only if we want to retrive number of groupings</li>
	 * 			<li>$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template</li>
	 * 		</ul>
	 * @return int|array
	 * 		the number of groupings if $params['count_only'] is set otherwise an array of all specified groupings objects
	 */
	function getGroupingForVideo($params=NULL){
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['selected']=='yes' && $params['videoid']) {// return only groupings that are linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
				$cond .= "  vdogrouping.id IN (SELECT vdogrouping2.id as id2 FROM ".tbl('vdogrouping')." AS vdogrouping2 LEFT JOIN "
						.tbl('video_grouping')." AS video_grouping2 ON vdogrouping2.id=video_grouping2.vdogrouping_id
					 	WHERE video_id=".$params['videoid'].')';
				}
		if($params['selected']=='no' && $params['videoid']) {// return only groupings that are not linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
			else
				$cond .= "  vdogrouping.id NOT IN (SELECT vdogrouping2.id as id2 FROM ".tbl('vdogrouping')." AS vdogrouping2 LEFT JOIN "
						.tbl('video_grouping')." AS video_grouping2 ON vdogrouping2.id=video_grouping2.vdogrouping_id
					 	WHERE video_id=".$params['videoid'].')';
		}
		if($params['cond']) {
			if($cond!='')
				$cond .= ' AND ';
				$cond .= " ".$params['cond']." ";
		}
	
	
		if(!$params['count_only']) {

			$fields = array(
					'vdogrouping' => array('*'),
					'vdogrouping_type' => array('name'),
				);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('vdogrouping')." AS vdogrouping  LEFT JOIN ".
				tbl('vdogrouping_type')." AS vdogrouping_type ON vdogrouping_type.id=vdogrouping.grouping_type_id ";
					// add alias on video_grouping.id to avoid any conflict between vdogrouping.id and video_grouping.id
					$query = str_replace(' vdogrouping_type.name',' vdogrouping_type.name as vdogroupingtype_name',$query);
						
					if ($cond)
						$query .= " WHERE ".$cond;
					if ($order)
						$query .= " ORDER BY ".$order;
					else 
						$query .= " ORDER BY vdogrouping_type.name,vdogrouping.place,vdogrouping.name ";
					if ($limit)
						$query .= " LIMIT  ".$limit;
					$result = $db->_select($query);
		}
		if($params['count_only']){
			$result = $db->count(tbl('vdogrouping')." AS vdogrouping ",'*',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
			return $result;
	
	}
	
	/**
	 * Associate a grouping to video
	 *
	 * @param int $id
	 * 		the grouping id
	 * @param int $videoid
	 * 		the video's id
	 */
	function linkGrouping($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_grouping'),'*',"vdogrouping_id=".$id.	" and video_id=".$videoid);
		if ($cnt==0)
			$db->insert(tbl('video_grouping'), array('video_id','vdogrouping_id'), array(mysql_clean($videoid),mysql_clean($id)));
	}
	
	/**
	 * Remove associate between a grouping and a video
	 *
	 * @param int $id
	 * 		the grouping id
	 * @param int $videoid
	 * 		the video's id
	 */
	function unlinkGrouping($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_grouping'),'*',"vdogrouping_id=".$id.	" and video_id=".$videoid);
		if ($cnt>0)
			$db->execute("DELETE FROM ".tbl("video_grouping")." WHERE video_id='$videoid' AND vdogrouping_id='$id'");
	}
	
	
	/**
	 * Return all groupings (with their type) linked to a video
	 *
	 * @param int $vid 
	 * 		the video id
	 * return array
	 * 		an array containing all groupings fields and groupingtype fields
	 */
	function getGroupingOfVideo($vid){
		global $db;
		$fields = array(
				'vdogrouping' => array('id', 'grouping_type_id','name','place','description','in_menu','color','thumb_url'),
				'vdogrouping_type' => array('name','in_thumb','in_menu'),
		);
		$tblvdogrouping=tbl('vdogrouping');
		$tblvideo_grouping=tbl('video_grouping');
		$tblvdogrouping_type=tbl('vdogrouping_type');
		$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('vdogrouping')." AS vdogrouping LEFT JOIN "
				.tbl('vdogrouping_type')." AS vdogrouping_type ON vdogrouping_type.id=vdogrouping.grouping_type_id"
				." LEFT JOIN ".tbl('video_grouping')." AS video_grouping ON video_grouping.vdogrouping_id=vdogrouping.id"
				." WHERE video_grouping.video_id=".$vid;
		// add alias on video_grouping.name to avoid any conflict between vdogrouping.name and video_grouping.name
				$query = str_replace(' vdogrouping_type.name',' vdogrouping_type.name AS vdogroupingtype_name',$query);
				$query = str_replace(' vdogrouping_type.in_menu',' vdogrouping_type.name AS vdogroupingtype_inmenu',$query);
				
		$result = $db->_select($query);
		return $result;
	}
	
	/**
	 * Array of strings that contains all requiered table names for the search request (for a video search).
	 *
	 * This variable can be extended extrernally
	 */
	var $reqTbls=array('video','users','vdogrouping','vdogrouping_type', 'video_grouping');
	
	/**
	 * Array that contains all requiered table and fields fo a sql join  (for a video search)
	 *
	 * each value of this table is an array like :
	 * array('table1'=>'table1_name'.'field1' => 'field1_name', 'table2'=>'table2_name'.'field2' => 'field2_name')
	 *
	 * This variable can be extended extrernally
	 */
	var $reqTblsJoin=array(
			array('table1'=>'users', 'field1'=>'userid','table2'=>'video','field2'=>'userid'),
			array('table1'=>'video', 'field1'=>'videoid','table2'=>'video_grouping','field2'=>'video_id'),
			array('table1'=>'vdogrouping', 'field1'=>'id','table2'=>'video_grouping','field2'=>'vdogrouping_id'),
			array('table1'=>'vdogrouping', 'field1'=>'grouping_type_id','table2'=>'vdogrouping_type','field2'=>'id'),
	);
	
	/**
	 * String used to declare all necessary fields the search request should return.  (for a video)
	 * This string have to contain fields in which we are searching data 
	 * in order to make a post treatment for requests that contains single quotes 
	 * 
	 */
	var $reqFields="video.*,vdogrouping.name,users.userid,users.username";

	/**
	 * Initialize search object for videos
	 * 
	 * This function initialize the search engine in order to retrieve videos from a specific groupingType
	 *
	 * @see init_search() function.
	 */
	function initSearchVideoFromGrouping() {
		$this->search = new ExtendSearch();

		// When calling this function int assume that the $_GET['gtype'] contains the groupingType Id
		$gtype=$_GET['gtype'];
		$gt=$this->getGroupingType($gtype);
		$this->search->search_type['videogrouping'] = array('title'=>$gt['name']);

		$this->search->db_tbl = "video";
		$this->search->columns =array(
				array('table' => 'vdogrouping', 'field'=>'name','type'=>'LIKE','var'=>'%{KEY}%'),
				array('field'=>'broadcast','type'=>'!=','var'=>'unlisted','op'=>'AND','value'=>'static'),
				array('field'=>'status','type'=>'=','var'=>'Successful','op'=>'AND','value'=>'static')
		);
		//Add a specific column to reduce the search to only this goupingType
		$this->search->columns[]= array('table' => 'vdogrouping_type', 'field'=>'id','type'=>'LIKE','var'=>$gtype);

		//commit this line so that videos search can be applied to %like% instead of whole word search
		//$this->search->use_match_method = true;
		$this->search->match_fields = array("vdogrouping");
		
		///$this->search->cat_tbl = $this->cat_tbl;
		
		$this->search->display_template = LAYOUT.'/blocks/video.html';
		$this->search->template_var = 'video';
		
		/**
		 * Setting up the sorting thing
		 */
		
		$this->search->sorting	= array(
				'date_added'=> " date_added DESC",
				'datecreated'=> " datecreated DESC",
				'views'		=> " views DESC",
				'comments'  => " comments_count DESC ",
				'rating' 	=> " rating DESC",
				'favorites'	=> " favorites DeSC"
		);
		$this->search->sort_by = 'datecreated';
		
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);
		
		//set tables for this plugin in extended search plugin
		$this->search->reqTbls=$this->reqTbls;
		//set tables associations for this plugin in extended search plugin
		$this->search->reqTblsJoin =$this->reqTblsJoin;
		//set return fields  for this plugin in extended search plugin
		$this->search->searchFields=$this->reqFields;
			
		$this->search->results_per_page = config('videos_items_search_page');
	}
	
	/**
	 * Initialize search object for grouping
	 * 
	 * This function initialize the search engine in order to retrieve groupings from a specific groupingType
	 *
	 * @see init_search() function.
	 */
	function initSearchGrouping() {
		$this->search = new ExtendSearch();
		$this->search->search_type['videogrouping'] = array('title'=>$gt['name']);
		$this->search->db_tbl="vdogrouping_type";
		$this->search->columns =array(
				array('table' => 'vdogrouping_type', 'field'=>'name','type'=>'LIKE','var'=>'%{KEY}%'),
		);
		
		$this->search->display_template = LAYOUT.'/blocks/grouping.html';
		$this->search->template_var = 'vdogrouping';
		$this->search->sorting	= array('name'=> " name ASC");
		$this->search->sort_by = 'datecreated';
		
		//set tables for this plugin in extended search plugin
		$this->search->reqTbls=array('vdogrouping','vdogrouping_type');
		//set tables associations for this plugin in extended search plugin
		$this->search->reqTblsJoin = array(array('table1'=>'vdogrouping', 'field1'=>'grouping_type_id','table2'=>'vdogrouping_type','field2'=>'id'));
		//set return fields  for this plugin in extended search plugin
		$this->search->searchFields="vdogrouping.*";
			
		$this->search->results_per_page = config('videos_items_search_page');
	}
	
	/**
	 * Initialize search object for video section
	 * 
	 * Depending on the $_GET variable it call one of the 2 following function : 
	 * initSearchVideoFromGrouping or initSearchGrouping
	 *
	 * @see video.class.php/init_search() function.
	 */
	function init_search() {
		if (empty($_GET['gtype'])) 
			$this->initSearchGrouping();
		else 
			$this->initSearchVideoFromGrouping();
	}

}

?>