<?php

	/**
	* Lists all of custom fields by name
	* @return : { array } { $flds } { an array with fields }
	*/

	function custom_fields_list() {
		global $db;
		$raw_flds = $db->select(tbl("custom_fields"),'custom_field_name','custom_field_list_id != 0');
		$flds = array();
		foreach ($raw_flds as $key => $name) {
			$flds[] = $name['custom_field_name'];
		}
		return $flds;
	}

	function push_custom_field($data = false) {
		global $db;
		if (!is_array($data)) {
			$data = $_POST;
		}

		$name = str_replace(" ", "_", $data['fieldname']);
		$label = $data['fieldtitle'];
		$type = $data['type'];
		$df_val = $data['default_value'];
		$ptype = $data['section_type'];
		$flds = array('custom_field_name','custom_field_title','custom_field_type','custom_field_value','custom_field_ptype');
		$vals = array($name, $label, $type, $df_val, $ptype);
		$insert_id = $db->insert(tbl("custom_fields"), $flds, $vals);
		if ($insert_id) {
			if ($ptype == 'video') {
				$table = 'video';
			} else {
				$table = 'users';
			}
			$db->Execute("ALTER TABLE ".tbl($table)." ADD `cfld_".$name."` varchar(255) NOT NULL");
			return $insert_id;
		} else {
			return false;
		}
	}

	function pull_custom_fields($type = false, $id = false) {
		global $db;
		if ($type) {
			$cond = "custom_field_ptype = '$type'";
		}
		if ($id) {			
			$cond = "custom_field_list_id = '$id'";
			if ($type) {
				$cond .= " AND custom_field_ptype = '$type'";
			}
		}
		$raw_pull = $db->select(tbl("custom_fields"),"custom_field_list_id,custom_field_title,custom_field_type,custom_field_ptype,custom_field_name,custom_field_value,custom_field_required",$cond);
		if (is_array($raw_pull)) {
			return $raw_pull;
		} else {
			return false;
		}
	}

	function update_cstm_field($data = false, $id = false) {
		global $db;
		if (!is_array($data)) {
			$data = $_POST;
		}
		if (!$id) {
			$id = $_GET['custom_edit'];
		}
		$name = $data['field_name'];
		$label = $data['field_title'];
		$db_field = $data['db_field'];
		$type = $data['field_type'];

		$flds = array('custom_field_name', 'custom_field_title', 'custom_field_db_field', 'custom_field_type');
		$vals = array($name, $label, $db_field, $type);
		$db->update(tbl("custom_fields"), $flds, $vals, "custom_field_list_id = '$id'");
	}

	function delete_custom_field($fid) {
        global $db;
        $file_data = pull_custom_fields(false, $fid);
        $type = $file_data[0]['custom_field_ptype'];
        $name = $file_data[0]['custom_field_name'];
        if ($type == 'video') {
        	$table = 'video';
        } else {
        	$table = 'users';
        }
  		$db->Execute("ALTER TABLE ".tbl($table)." DROP `cfld_".$name."` varchar(255) NOT NULL");
  		$db->delete(tbl('custom_fields'),array('custom_field_list_id'),array($fid));
  	}

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

?>