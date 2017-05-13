<?php

// Global Object $linkquery is used in the plugin
$linkquery = new Link();
$Smarty->assign_by_ref('linkquery', $linkquery);


/**
 * Contains all actions that can affect the external link plugin 
 */
class Link extends CBCategory{
	private $basic_fields = array();
	
	/**
	 * Constructor for linkquery's instances
	 */
	function Link()	{
		global $cb_columns;
		$basic_fields = array('id', 'title','url');
		$cb_columns->object( 'external_links' )->register_columns( $basic_fields );
		$basic_fields = array('id', 'video_id','link_id');
		$cb_columns->object( 'video_links' )->register_columns( $basic_fields );
	}

	/**
	 *Function used to add a new external links 
	 *
	 * @param array $array 
	 * 	a dictionnary that contains all fields for a link. $_POST is used if empty
	 * @return int|bool
	 * 	link's id if exists , otherwise false
	 */
	function addLink($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validateFormFields($array);
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
	
	/**
	 * Function used to update a links 
	 *
	 * @param array $array 
	 * 		a dictionnary that contains all fields for a link. $_POST is used if empty
	 * @return int|bool
	 * 		link's id if exists , otherwise false
	 */
	function updateLink($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validateFormFields($array);
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
	
	/**
	 * Function used to test if an external link already exists 
	 * 
	 * @param array $array 
	 * 		a dictionnary that contains fields for a link. $_POST is used if empty
	 * @return bool
	 * 		true if link exists , otherwise false
	 */
	function searchLink($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validateFormFields($array,false);
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
	
	/**
	 * Function used to get external links 
	 *
	 * @param array $params 
	 * 		a dictionary containing information about the requested links
	 * 		<ul>
	 * 			<li>$params['limit'] is for pagination (ie '0.100')</li>
	 *			<li>$params['order'] is for ordering</li>
	 *			<li>$params['cond'] is the "where" condition of the sql request</li>
	 * 			<li>$params['count_only'] used only if we want to retrive number of links</li>
	 * 			<li>$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template</li>
	 *		</ul>
	 * @return int|array
	 * 		the number of links if $params['count_only'] is set otherwise an array of all specified Link objects
	 */
	function getLinks($params=NULL)	{
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

	/**
	 * Function used to get links for a specific video
	 *
	 * @param array $params 
	 * 		a dictionary containing information about the requested links
	 * 		<ul>
	 * 			<li>$params['limit'] is for pagination (ie '0.100')</li>
	 *			<li>$params['order'] is for ordering</li>
	 *			<li>$params['selected'] if =="yes" returns documents linked to the video, 
	 *									if =="no" returns documents not linked to the video</li>
	 *			<li>$params['videoid'] is the video's id</li>
	 *			<li>$params['cond'] is the "where" condition of the sql request</li>
	 * 			<li>$params['count_only'] used only if we want to retrive number of links</li>
	 * 			<li>$params['assign'] if defined, is used to assign the result to the parameter for use in the HTML template</li>
	 *		</ul>

	 * @return int|array
	 * 		the number of links if $params['count_only'] is set otherwise an array of all specified Link objects
	 */
	function getLinkForVideo($params=NULL){
		global $db;
		global $cb_columns;
		$limit = $params['limit'];
		$order = $params['order'];
		$cond = "";
		if($params['selected']=='yes' && $params['videoid']) {// return only links that are linked to the specified video
			if($cond!='')
				$cond .= ' AND ';
				$cond .= "  links.id IN (SELECT links2.id as id2 FROM ".tbl('links')." AS links2 LEFT JOIN "
						.tbl('video_links')." AS video_links2 ON links2.id=video_links2.link_id
					 		WHERE video_id=".$params['videoid'].')';
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
			);
			$query = " SELECT ".tbl_fields($fields)." FROM ".tbl('links')." AS links"; 
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
	
	/**
	 * Test if link's id exists or not 
	 *
	 * @param int $id 
	 * 		the link's id
	 * @return bool	
	 * 		true if link exists otherwise false
	 */
	function linkExists($id){
		global $db;
		$result = $db->count(tbl('links'),"id"," id='".$id."'");
		return ($result>0);
	}
	
	
	/**
	 * Function used to get link details using it's id 
	 *
	 * @param int $id 
	 *		Link's id
	 * @return array|bool 
	 * 		a dictionary containing each fields for a link, false if no link found
	 */
	function getLinkDetails($id=NULL)	{
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
	
	/**
	 * Remove link from the database. 
	 * if the link is associated to a video, then nothing is done, just an error message appears.
	 *
	 * @param int $id
	 * 		the id of the link to be deleted 
	 */
	function deleteLink($id) {
		global $db;
		if($this->linkExists($id)) {
			$udetails = $this->getLinkDetails($id);
				$test2=$db->execute("DELETE FROM ".tbl("links")." WHERE id='$id'");
				if (!$test2)
					e(lang("cant_del_linked_link_msg")." id=".$id,"e");
				else
					e(lang("link_del_msg")." id=".$id,"m");
		}else{
			e(lang("link_does_not_exist"));
		}
	}
	
	/**
	 * Associate an external link to video 
	 *
	 * @param int $id 
	 * 		link's id
	 * @param int $videoid 
	 * 		the video's id
	 */
	function linkLink($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_links'),'*',"link_id=".$id.	" and video_id=".$videoid);
		if ($cnt==0)
			$db->insert(tbl('video_links'), array('video_id','link_id'), array(mysql_clean($videoid),mysql_clean($id)));
	}

	/**
	 * Remove associate between an external link and a video
	 *
	 * @param int $id
	 * 		link's id
	 * @param int $videoid
	 * 		the video's id
	 */
	function unlinkLink($id,$videoid) {
		global $db;
		$cnt= $db->count(tbl('video_links'),'*',"link_id=".$id.	" and video_id=".$videoid);
		if ($cnt>0)
			$db->execute("DELETE FROM ".tbl("video_links")." WHERE video_id='$videoid' AND link_id='$id'");
	}
	
	/**
	 * Remove associate between any external link and a video 
	 *
	 * @param int $videoid 
	 * 		the video's id
	 */
	function unlinkAllLinks($videoid) {
		global $db;
		$cnt= $db->count(tbl('video_links'),'*',"video_id=".$videoid);
		if ($cnt>0){
			$db->execute("DELETE FROM ".tbl("video_links")." WHERE video_id='$videoid' ");
			e(lang("links_have_been_disconected"),'m');
		}
	}
	
	/**
 	 * Create initial array for link fields 
 	 * 
	 * @param array $input 
	 * 		a dictionary with external link's informations (if null $_POST is used)
	 * @param bool $strict
	 * 		if true then field is requiered in the data form. Default value is true.
	 * @return array
	 * 		Fields for the administration page of the plugin. 
	 * 		Fields are ('title','url'). For each field this will tell
	 * 		<br/>array(
	 *      <br/>title [text that will represents the field]
	 *      <br/>type [One of the following values : textfield, password,texarea, checkbox,radiobutton, dropbox]
	 *      <br/>name [name of the fields, input NAME attribute]
	 *      <br/>id [id of the fields, input ID attribute]
	 *      <br/>value [value of the fields, input VALUE attribute]
	 *      <br/>size
	 *      <br/>class [CSS class of the field]
	 *      <br/>label
	 *      <br/>extra_tags [Extra tags added as is to the field]
	 *      <br/>hint_1 [hint before field]
	 *      <br/>hint_2 [hint after field]
	 *      <br/>anchor_before [anchor before field]
	 *      <br/>anchor_after [anchor after field]
	 *      <br/>)
 	 */
	function loadLinkFields($input=NULL,$strict=true) {
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

	
	/**
	 * Validate external link's administration form fields (Add and Edit forms) 
	 *
 	 * @param array $input
 	 * 		a dictionary with external link's informations (if null $_POST is used)
	 *	@param array $strict
	 *		if trus then field is requiered in the data form. Default value is true
	 * @return bool
	 * 		true if the form is valid otherwise false
	 * @see loadLinkFields for more information about $array content
	 */
	function validateFormFields($array=NULL,$strict=true) {
		$fields= $this->loadLinkFields($array,$strict);
		if($array==NULL)
			$array = $_POST;
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
	
		validate_cb_form($fields,$array);
	}
	
	
}

?>