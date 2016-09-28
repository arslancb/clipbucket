<?php
/*
 * This file contains linkquery class and some usefull functions used in this plugin
 */ 


// Global Object $linkquery is used in the plugin
$linkquery = new linkquery();
$Smarty->assign_by_ref('linkquery', $linkquery);


/**_____________________________________________________
 * Class linkquery
 * _____________________________________________________
 *Contains all actions that can affect the external link plugin 
 */
class linkquery extends CBCategory{
	private $basic_fields = array();
	
	/**_____________________________________
	 * linkquery
	 * _____________________________________
	 *Constructor for linkquery's instances
	 */
	function linkquery()	{
		global $cb_columns;
		$basic_fields = array('id', 'title','url');
		$cb_columns->object( 'external_links' )->register_columns( $basic_fields );
		$basic_fields = array('id', 'video_id','link_id');
		$cb_columns->object( 'video_links' )->register_columns( $basic_fields );
	}

	/**_____________________________________
	 * add_link
	 * ____________________________________
	 *Function used to add a new external links 
	 *
	 *input $array : a dictionnary that contains all fields for a link. $_POST is used if empty
	 * output : return link's id if exists , otherwise false
	 */
	function add_link($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array);
		if(!error()) {
			$title=mysql_clean($array['title']);
			$url=mysql_clean($array['url']);
			$req=" title = '$title' AND url='$url'";
			$res=$db->select(tbl('links'),'id',$req,false,false,false);
			// test link's unicity
			if (count($res)>0){
				e(lang("link_already_exists"));
				return false;
			}
			else {
				// insert link
				$db->insert(tbl('links'), array('title','url'), array($title,$url));
				$res=$db->select(tbl('links'),'id',$req,false,false,false);
				$id=$res[0]['id'];
				return $id;		
			}
		}
	}
	
	/**_____________________________________
	 * update_link
	 * ____________________________________
	 *Function used to update a links 
	 *
	 *input $array : a dictionnary that contains all fields for a link. $_POST is used if empty
	 * output : return link's id if exists , otherwise false
	 */
	function update_link($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array);
		if(!error()) {
			$title=mysql_clean($array['title']);
			$url=mysql_clean($array['url']);
			$linkid=mysql_clean($array['linkid']);
			$req=" title = '$title' AND url='$url'";
			$res=$db->select(tbl('links'),'id',$req,false,false,false);
			// test link's unicity
			if (count($res)>0){
				e(lang("link_already_exists"));
				return false;
			}
			else {
				// update links
				$db->update(tbl('links'), array('title','url'), array($title,$url),"id='$linkid'");
				return $linkid;		
			}
		}
	}
	
	/**_____________________________________
	 * search_link
	 * ____________________________________
	 *Function used to test if an external link already exists 
	 * 
	 *input $array : a dictionnary that contains fields for a link. $_POST is used if empty
	 * output : return true if link exists , otherwise false
	 */
	function search_link($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validate_form_fields($array,false);
		if(!error()) {
			$title=$array['title'];
			$req=" title like '%".title."%'";
			$res=$db->select(tbl('links'),'*',$req,false,false,false);
			// test link's unicity
			if (count($res)>0){
				$s="";
				for ($i=0; $i<count($res); $i++)
					$s=$s.$res[$i]['title'].', ';
				e(lang("link_already_exists")." : ".$s,"w");
				return true;
			}
			else {
				e(lang("link_does_not_exist"),"m");
				return false;
			}
		}
	}
	
	/**_____________________________________
	 * get_links
	 * ____________________________________
	 *Function used to get external links 
	 *
	 *input $params : is a dictionary containing information about the requested links
	 *				$params['limit'] is for pagination (ie '0.100')
	 *				$params['order'] is for ordering
	 *				$params['cond'] is the "where" condition of the sql request
	 * 			$params['count_only'] used only if we want to retrive number of links
	 * 			$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template
	 * output : return specified links
	 */
	function get_links($params=NULL)	{
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
			$fields = array('link' => $cb_columns->object('external_links')->get_columns(),);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('links')." AS link ";
			if ($cond) 
				$query .= " WHERE ".$cond;
			if ($order)
				$query .= " ORDER BY ".$order;
			if ($limit)
				$query .= " LIMIT  ".$limit;
			//$result = $db->select(tbl('users'),'*',$cond,$limit,$order);
			$result = select( $query );
		}
		if($params['count_only']){
			$result = $db->count(tbl('links')." AS link ",'id',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
	}

	/**_____________________________________
	 * get_link_for_video
	 * ____________________________________
	 *Function used to get links for a specific video
	 *
	 *input $params : is a dictionary containing information about the requested links
	 *				$params['limit'] is for pagination (ie '0.100')
	 *				$params['order'] is for ordering
	 *				($params['selected'] if =="yes" returns external links linked to the video
	 *										 if =="no" returns external links not linked to the video
	 *				$params['videoid'] is the video's id
	 *				$params['cond'] is the "where" condition of the sql request
	 * 			$params['count_only'] used only if we want to retrive number of links
	 * 			$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template
	 * output : return related links
	 */
	function get_link_for_video($params=NULL){
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['selected']=='yes' && $params['videoid']) {// return only links that are linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " video_id = '".$params['videoid']."' ";
		}
		if($params['selected']=='no' && $params['videoid']) {// return only links that are not linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
			else 
				$cond .= "  links.id NOT IN (SELECT links2.id as id2 FROM ".tbl('links')." AS links2 LEFT JOIN " 
					 .tbl('video_links')." AS video_links2 ON links2.id=video_links2.link_id 
					 		WHERE video_id=".$params['videoid'].')'; 
		}
		if($params['cond']) {
			if($cond!='')
				$cond .= ' AND ';
			$cond .= " ".$params['cond']." ";
		}
	
	
		if(!$params['count_only']) {
			$fields = array(
					'links' => $cb_columns->object('external_links')->get_columns(),
					'video_links' => $cb_columns->object('video_links')->get_columns(),
			);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('links')." AS links LEFT JOIN " 
					 .tbl('video_links')." AS video_links ON links.id=video_links.link_id";
			// add alias on video_links.id to avoid any conflict between links.id and video_links.id
			$query = str_replace(' video_links.id',' video_links.id as vid',$query);
				
			if ($cond) 
				$query .= " WHERE ".$cond;
			if ($order)
				$query .= " ORDER BY ".$order;
			if ($limit)
				$query .= " LIMIT  ".$limit;
			$result = select( $query );
		}
		if($params['count_only']){
			$result = $db->count(tbl('links')." AS links ",'*',$cond);
		}
		if($params['assign'])
			assign($params['assign'],$result);
		return $result;
		
	}
	
	
	/**_____________________________________
	 * link_exists
	 * ____________________________________
	 *Test if link's id exists or not 
	 *
	 *input $id : is the link's id
	 *output : true if link exists otherwise false
	 */
	function link_exists($id){
		global $db;
		$result = $db->count(tbl('links'),"id"," id='".$id."'");
		return ($result>0);
	}
	
	
	/**_____________________________________
	 * get_link_details
	 * ____________________________________
	 *Function used to get link details using it's id 
	 *
	 *input $id : link's id
	 *output : a dictionary containig each fields for a link, false if no link found
	 */
	function get_link_details($id=NULL)	{
		global $db;
		$fields = tbl_fields(array('links' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('links');
		$query .= " WHERE links.id = '$id'";
		$result = select($query);
		Assign('link', $result);
		if ($result) {
			$details = $result[0];
			return $details;
		}
		return false;
	}
	
	/**_____________________________________
	 * delete_link
	 * ____________________________________
	 *Remove link from the database. 
	 *TODO : if the link is associated to a video, then nothing is done, just an error message appears.
	 *input $id : the id of the link to be deleted 
	 */
	function delete_link($id) {
		global $db;
		if($this->link_exists($id)) {
			$udetails = $this->get_link_details($id);
				$test2=$db->execute("DELETE FROM ".tbl("links")." WHERE id='$id'");
				if (!$test2)
					e(lang("cant_del_linked_link_msg")." id=".$id,"e");
				else
					e(lang("link_del_msg")." id=".$id,"m");
		}else{
			e(lang("link_does_not_exist"));
		}
	}
	
	/**_____________________________________
	 * link_link
	 * ____________________________________
	 *Associate an external link to video 
	 *
	 *input $id : link's id
	 *			$videoid : the video's id
	 */
	function link_link($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_links'),'*',"link_id=".$id.	" and video_id=".$videoid);
		if ($cnt==0)
			$db->insert(tbl('video_links'), array('video_id','link_id'), array(mysql_clean($videoid),mysql_clean($id)));
	}

	/**_____________________________________
 	 * unlink_link
 	 * ____________________________________
	 *Remove associate between an external link and a video 
	 *
	 *input $id : link's id
	 *			$videoid : the video's id
	 */
	function unlink_link($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_links'),'*',"link_id=".$id.	" and video_id=".$videoid);
		if ($cnt>0)
			$db->execute("DELETE FROM ".tbl("video_links")." WHERE video_id='$videoid' AND link_id='$id'");
	}
	
	/**_____________________________________
 	 * load_links_fields
 	 * ____________________________________
 	 *Create initial array for link fields 
	 * this will tell
	 * array(
	 *       title [text that will represents the field]
	 *       type [One of the following values : textfield, password,texarea, checkbox,radiobutton, dropbox]
	 *       name [name of the fields, input NAME attribute]
	 *       id [id of the fields, input ID attribute]
	 *       value [value of the fields, input VALUE attribute]
	 *       size
	 *       class [CSS class of the field]
	 *       label
	 *       extra_tags [Extra tags added as is to the field]
	 *       hint_1 [hint before field]
	 *       hint_2 [hint after field]
	 *       anchor_before [anchor before field]
	 *       anchor_after [anchor after field]
	 *      )
	 *
 	 *input $input : a dictionary with link's informations (if null $_POST is used)
	 *		$strict : if trus then field is requiered in the data form
 	 *output : Fields for the administration page of the plugin
 	 */
	function load_links_fields($input=NULL,$strict=true) {
		global $LANG,$Cbucket;
		$default = array();
		if(isset($input))
			$default = $input;
		if(empty($default))
			$default = $_POST;
	
		$title = (isset($default['title'])) ? $default['title'] : "";
		$url = (isset($default['url'])) ? $default['url'] : "";

		$my_fields = array (
				'title' => array(
						'title'=> lang('title'),
						'type'=> "textfield",
						'name'=> "title",
						'id'=> "title",
						'value'=> $title,
						'db_field'=>'title',
						'required'=>($strict) ? 'yes' : 'no',
						//'hint_1'=> lang('user_allowed_format'),
						//'hint_2'=> lang('user_allowed_format'),
						// 'syntax_type'=> 'username',
						//'validate_function'=> 'username_check',
						//'function_error_msg' => lang('user_contains_disallow_err'),
						//'db_value_check_func'=> 'user_exists',
						//'db_value_exists'=>false,
						//'db_value_err'=>lang('usr_uname_err2'),
						//'min_length'	=> config('min_username'),
						//'max_length' => config('max_username'),
				),
				'url' => array(
						'title'=> lang('url'),
						'type'=> "textfield",
						'name'=> "url",
						'id'=> "url",
						'value'=> $url,
						'db_field'=>'url',
						'required'=>($strict) ? 'yes' : 'no',
				),
		);
		return $my_fields;
	}

	
	/**_____________________________________
	 * validate_form_fields
	 * ____________________________________
	 *Validate external link's administion form fields (Add and Edit forms) 
	 *
 	 *input $input : a dictionary with external link's informations (if null $_POST is used)
	 *		$strict : if trus then field is requiered in the data form
	 *output : true if the form is valid, otherwise false
	 */
	function validate_form_fields($array=NULL,$strict=true) {
		$fields= $this->load_links_fields($array,$strict);
		if($array==NULL)
			$array = $_POST;
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
	
		validate_cb_form($fields,$array);
	}
	
	
}

?>