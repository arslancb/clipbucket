<?php

/**
 * Author : Arslan Hassan
 * Script : ClipBucket v2
 * LIcense : CBLA
 *
 *
 * Class : Video
 * Used to perform function swith videos
 * -- history
 * all function that were in my_query
 * has been transfered here
 * however thhey will still work from there
 * too
 */
 
 

class CBvideo extends CBCategory
{
	/**
	 * __Constructor of CBVideo
	 */	
	function CBvideo()
	{
		$this->cat_tbl = 'video_categories';
		$this->section_tbl = 'video';
	}
	
	
	/**
	 * Function used to check weather video exists or not
	 * @param VID or VKEY
	 */
	function video_exists($vid)
	{
		return $this->get_video($vid);
	}
	function exists($vid){return $this->video_exists($vid);}
	function videoexists($vid){return $this->video_exists($vid);}
	
	
	/**
	 * Function used to get video data
	 */
	function get_video($vid)
	{
		global $db;
		$results = $db->select("video","*"," videoid='$vid' OR videokey='$vid'");
		if($db->num_rows>0)
		{
			return $results[0];
		}else{
			return false;
		}
	}
	function getvideo($vid){return $this->get_video($vid);}
	function get_video_data($vid){return $this->get_video($vid);}
	function getvideodata($vid){return $this->get_video($vid);}
	function get_video_details($vid){return $this->get_video($vid);}
	function getvideodetails($vid){return $this->get_video($vid);}
	
	
	/**
	 * Function used to perform several actions with a video
	 */
	function action($case,$vid)
	{
		global $db;
		if(!$this->exists($vid))
			return false;
		//Lets just check weathter video exists or not
		switch($case)
		{
			//Activating a video
			case 'activate':
			case 'av':
			case 'a':
			{
				$db->update("video",array('active'),array('yes')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_act_msg"),m);
			}
			break;
			
			//Deactivating a video
			case "deactivate":
			case "dav":
			case "d":
			{
				$db->update("video",array('active'),array('no')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_act_msg1"),m);
			}
			break;
			
			//Featuring Video
			case "feature":
			case "featured":
			case "f":
			{
				$db->update("video",array('featured'),array('yes')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_vdo_fr_msg"),m);
			}
			break;
			
			
			//Unfeatured video
			case "unfeature":
			case "unfeatured":
			case "uf":
			{
				$db->update("video",array('featured'),array('no')," videoid='$vid' OR videokey = '$vid' ");
				e(lang("class_fr_msg1"),m);
			}
			break;
		}
	}
	
	
	
	/**
	 * Function used to update video
	 */
	function update_video()
	{
		global $eh,$Cbucket,$db,$Upload;

		$Upload->validate_video_upload_form(NULL,TRUE);
		
		if(empty($eh->error_list))
		{
			$required_fields = $Upload->loadRequiredFields($array);
			$location_fields = $Upload->loadLocationFields($array);
			$option_fields = $Upload->loadOptionFields($array);
			
			$upload_fields = array_merge($required_fields,$location_fields,$option_fields);
			
			//Adding Custom Upload Fields
			if(count($Upload->custom_upload_fields)>0)
				$upload_fields = array_merge($upload_fields,$Upload->custom_upload_fields);
			//Adding Custom Form Fields
			if(count($Upload->custom_form_fields)>0)
				$upload_fields = array_merge($upload_fields,$Upload->custom_form_fields);
			
			$array = $_POST;
			$vid = $array['videoid'];

			if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
		
			foreach($upload_fields as $field)
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
				if(!$field['clean_func'] || (!apply_func($field['clean_func'],$val) && !is_array($field['clean_func'])))
					$val = mysql_clean($val);
				else
					$val = apply_func($field['clean_func'],$val);
				
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}		
			
			#$query = "INSERT INTO video (";
			$total_fields = count($query_field);
			
			//Adding Fields to query
			$i = 0;
			
			/*for($key=0;$key<$total_fields;$key++)
			{
				$query .= query_field[$key]." = '".$query_val[$key]."'" ;
				if($key<$total_fields-1)
				$query .= ',';
			}*/
			
			
			if(!userid())
			{
				e("You are not logged in");
			}elseif(!$this->video_exists($vid)){
				e("Video deos not exist");
			}else{
				$db->update('video',$query_field,$query_val," videoid='$vid'");
				e("Video details have been updated",m);
			}
			
		}
	}
	
}


?>