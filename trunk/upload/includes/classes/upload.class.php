<?php
/**
 * @Software : ClipBucket
 * @Function : Handles all queries regarding ClipBucket Actions
 * @Author : Arslan Hassan
 * @Since : 15 December, 2007
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 *
 * Name : Upload
 * This class can be edited for own purpose..
 * it handles all uploading that is done on ClipBucket or with ClipBucket
 */
 
 
class Upload{
 
 	var $custom_form_fields = array();  //Step 1 of Uploading
	var $custom_upload_fields = array(); //Step 2 of Uploading
	var $actions_after_video_upload = array('activate_video_with_file');
	
 	/**
	 * Function used to vlidate upload form fields
	 */
	function validate_video_upload_form($array=NULL,$is_upload=FALSE){
		global $LANG;
		
		//First Load All Fields in an array
		$required_fields = $this->loadRequiredFields($array);
		$location_fields = $this->loadLocationFields($array);
		$date_fields = $this->loadDateForm('','/',TRUE);
		$option_fields = $this->loadOptionFields($array);
		
		if($array==NULL)
			$array = $_POST;
		
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);

		//Mergin Array
		$upload_fields = array_merge($required_fields,$location_fields,$option_fields);
		
		//Adding Custom Upload Fields
			if(count($this->custom_upload_fields)>0 && $is_upload)
				$upload_fields = array_merge($upload_fields,$this->custom_upload_fields);
			//Adding Custom Form Fields
			if(count($this->custom_form_fields)>0)
				$upload_fields = array_merge($upload_fields,$this->custom_form_fields);
				
		//Now Validating Each Field 1 by 1
		/*foreach($upload_fields as $field)
		{
			$field['name'] = formObj::rmBrackets($field['name']);
			
			//pr($field);
			$title = $field['title'];
			$val = $array[$field['name']];
			$req = $field['required'];
			$invalid_err =  $field['invalid_err'];
			$function_error_msg = $field['function_error_msg'];
			if(is_string($val))
			$length = strlen($val);
			$min_len = $field['min_length'];
			$min_len = $min_len ? $min_len : 0;
			$max_len = $field['max_length'] ;
			$rel_val = $array[$field['relative_to']];
			
			if(empty($invalid_err))
				$invalid_err =  sprintf("Invalid '%s'",$title);
			if(is_array($array[$field['name']]))
				$invalid_err = '';
				
			//Checking if its required or not
			if($req == 'yes')
			{
				if(empty($val) && !is_array($array[$field['name']]))
				{
					e($invalid_err);
					$block = true;
				}else{
					$block = false;
				}
			}

			$funct_err = is_valid_value($field['validate_function'],$val);
			if($block!=true)
			{
				
				//Checking Syntax
				if(!$funct_err)
				{
					if(!empty($function_error_msg))
						e($function_error_msg);
					elseif(!empty($invalid_err))
						e($invalid_err);
				}elseif(!is_valid_syntax($field['syntax_type'],$val))
				{
					if(!empty($invalid_err))
						e($invalid_err);
				}
				elseif(isset($max_len))
				{
					if($length > $max_len || $length < $min_len)
					e(sprintf(" please enter '%s' value between '%s' and '%s'",
							  $title,$field['min_length'],$field['max_length']));
				}elseif(function_exists($field['db_value_check_func']))
				{
					$db_val_result = $field['db_value_check_func']($val);
					if($db_val_result != $field['db_value_exists'])
						if(!empty($field['db_value_err']))
							e($field['db_value_err']);
						elseif(!empty($invalid_err))
							e($invalid_err);
				}elseif($field['relative_type']!='')
				{
					switch($field['relative_type'])
					{
						case 'exact':
						{
							if($rel_val != $val)
							{
								if(!empty($field['relative_err']))
									e($field['relative_err']);
								elseif(!empty($invalid_err))
									e($invalid_err);
							}
						}
						break;
					}
				}
			}	
		}*/
		validate_cb_form($upload_fields,$array);
		
	}
	function ValidateUploadForm()
	{
		return validate_video_upload_form();
	}
	
	function UploadProcess($array=NULL)
	{
		return $this->submit_upload($array);
	}
	
	function submit_upload($array=NULL)
	{
		global $eh,$Cbucket,$db,$userquery;
		
		if(!$array)
			$array = $_POST;
			
		$this->validate_video_upload_form($array,TRUE);
		
		if(empty($eh->error_list))
		{
			$required_fields = $this->loadRequiredFields($array);
			$location_fields = $this->loadLocationFields($array);
			$option_fields = $this->loadOptionFields($array);
			
			$upload_fields = array_merge($required_fields,$location_fields,$option_fields);
			//Adding Custom Upload Fields
			if(count($this->custom_upload_fields)>0)
				$upload_fields = array_merge($upload_fields,$this->custom_upload_fields);
			//Adding Custom Form Fields
			if(count($this->custom_form_fields)>0)
				$upload_fields = array_merge($upload_fields,$this->custom_form_fields);
			
			$userid = userid();
			
			if(!userid() && has_access('allow_video_upload',true,false))
			{
				$userid = $userquery->get_anonymous_user();
				//$userid = $user['userid'];
			}
			
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
						$val = apply_func($field['clean_func'],sql_free($val));
				
				if(empty($val) && !empty($field['default_value']))
					$val = $field['default_value'];
					
				if(!empty($field['db_field']))
				$query_val[] = $val;
				
			}
			
			//Adding Video Code
			$query_field[] = "file_name";
			$file_name = mysql_clean($array['file_name']);
			$query_val[] = $file_name;
			
			//ADding Video Key
			$query_field[] = "videokey";
			$query_val[] = $this->video_keygen();
			
			
			//Userid
			$query_field[] = "userid";
			
			if(!$array['userid'])
				$query_val[] = $userid;
			else
				$query_val[] = $array['userid'];
			
			
			//Upload Ip
			$query_field[] = "uploader_ip";
			$query_val[] = $_SERVER['REMOTE_ADDR'];
			
			$activation = ACTIVATION;
			
			//Setting Activation Option
			if($activation == 0){
				$active = 'yes';
			}else{
				$active = 'no';
			}
			$query_field[] = "active";
			$query_val[] = $active;
			
			$query_field[] = "date_added";
			$query_val[] = now();
			
			$query = "INSERT INTO ".tbl("video")." (";
			$total_fields = count($query_field);
			
			//Adding Fields to query
			$i = 0;
			foreach($query_field as $qfield)
			{
				$i++;
				$query .= $qfield;
				if($i<$total_fields)
				$query .= ',';
			}
			
			$query .= ") VALUES (";
			
			
			$i = 0;
			//Adding Fields Values to query
			foreach($query_val as $qval)
			{
				$i++;
				$query .= "'$qval'";
				if($i<$total_fields)
				$query .= ',';
			}
			
			//Finalzing Query
			$query .= ")";
			
			//exit($query);
			
			if(!userid() && !has_access('allow_video_upload',false,false))
			{
				e(lang("you_not_logged_in"));
				//exit();
			}else{
								
				$insert_id = file_name_exists($file_name);
				if(!$insert_id)
				{				
					$db->Execute($query);
					$insert_id = $db->insert_id();
					
					//loggin Upload
					$log_array = array
					(
					 'success'=>'yes',
					 'action_obj_id' => $insert_id,
					 'userid' => $userid,
					 'details'=> "uploaded a video");
					insert_log('upload_video',$log_array);
					
					$db->update(tbl("users"),array("total_videos"),array("|f|total_videos+1")," userid='".$userid."'");
				}
			}
			
		}
		
		return $insert_id;
				
	}

		/*//FUNCTION USED TO CLEAN EMBED CODE
		function CleanEmbedCode($code){
			$code		    = trim($code);
			$embed_code     = str_replace('<script', '', $code);
			$embed_code 	= preg_replace('/<a(.*)<\/a>/','',$embed_code);
			
			if ( !stristr($embed_code, '<embed') && !stristr($embed_code, '<object')  && !stristr($embed_code, '<div') ) 
			{
				$embed_code     = NULL;
			}
			if ( stristr($embed_code, '<a') || stristr($embed_code, '<img') || stristr($embed_code, '<iframe') ) {
				$embed_code     = NULL;
			}
			return $embed_code;
		}
		
		//FUNCTION USED TO VALIDATE EMBED CODE
	 	function ValidateEmbedForm(){
			global $LANG;
			$embed_code		    = $this->CleanEmbedCode($_POST['embed_code']);
			
			if(empty($embed_code))
				$msg[] = e(lang('vdo_embed_code_wrong'));
			$seconds			= mysql_clean($_POST['seconds']);
			$minutes			= mysql_clean($_POST['minutes']);
			
			if(strlen($seconds)<2) $seconds = '0'.$seconds;
			if(empty($seconds) || !is_numeric($seconds))
				$msg[] = e(lang('vdo_seconds_err'));
			
			if(strlen($minutes)<2) $minutes = '0'.$minutes;
			if(empty($minutes) || !is_numeric($minutes))
				$msg[] = e(lang('vdo_mins_err'));
			
			//Getting Thumb
			if(empty($msg)){
				$file 		= $_FILES['thumb_upload']['name'];
				$ext 		= GetExt($file);
				$image = new ResizeImage();
				if($image->ValidateImage($_FILES['thumb_upload']['tmp_name'],$ext)){
					$file_1 = BASEDIR.'/files/thumbs/'.GetThumb($_POST['flvname'],1,FALSE);
					$file_2 = BASEDIR.'/files/thumbs/'.GetThumb($_POST['flvname'],2,FALSE);
					$file_3 = BASEDIR.'/files/thumbs/'.GetThumb($_POST['flvname'],3,FALSE);
					move_uploaded_file($_FILES['thumb_upload']['tmp_name'],$file_1);
					$image->CreateThumb($file_1,$file_1,THUMB_WIDTH,$ext,THUMB_HEIGHT,false);
					copy($file_1,$file_2);
					copy($file_1,$file_3);
				}else{
				$msg[] = e(lang('vdo_thumb_up_err'));
				}
			}
			
			if(empty($msg)){
			return 'OK';
			}else{
			return $msg;
			}
		}*/
	
	
	
	/**
	 * Function used to get available name for video thumb
	 * @param FILE_Name
	 */
	function get_available_file_num($file_name)
	{
		//Starting from 1
		$code = 1;
		while(1)
		{
			$path = THUMBS_DIR.'/'.$file_name.'-'.$code.'.';
			if(!file_exists($path.'jpg') && !file_exists($path.'png') && !file_exists($path.'gif'))
				break;
			else
				$code = $code + 1;
		}
		
		return $code;
	}
	
	
	function upload_thumb($file_name,$file_array,$key=0)
	{
		global $imgObj,$LANG;
		$file = $file_array;
		if(!empty($file['name'][$key]))
		{
			$file_num = $this->get_available_file_num($file_name);
			$ext = getExt($file['name'][$key]);
			if($imgObj->ValidateImage($file['tmp_name'][$key],$ext))
			{
				$file_path = THUMBS_DIR.'/'.$file_name.'-'.$file_num.'.'.$ext;
				move_uploaded_file($file['tmp_name'][$key],$file_path);
				$imgObj->CreateThumb($file_path,$file_path,THUMB_WIDTH,$ext,THUMB_HEIGHT,false);
				e(lang('upload_vid_thumb_msg'),'m');
			}	
		}
	}
	
	/**
	 * Function used to upload big thumb
	 * @param FILE_NAME
	 * @param $_FILES array name
	 */	
	function upload_big_thumb($file_name,$file_array) {
		global $imgObj,$LANG;
		$file = $file_array;
		$ext = getExt($file['name']);
		$bigThumbWidth = config('big_thumb_width');
		$bigThumbHeight = config('big_thumb_height');
		
		if($imgObj->ValidateImage($file['tmp_name'],$ext))
		{
			$path = THUMBS_DIR.'/'.$file_name.'-big.'.$ext;
			move_uploaded_file($file['tmp_name'],$path);
			$imgObj->CreateThumb($path,$path,$bigThumbWidth,$ext,$bigThumbHeight,false);
			e(lang('Video big thumb uploaded'),'m');	
		}
	}
	
	/**
	 * Function used to upload video thumbs
	 * @param FILE_NAME
	 * @param $_FILES array name
	 */
	
	function upload_thumbs($file_name,$file_array)
	{
		global $LANG;
		if(count($file_array[name])>1)
		{
			for($i=0;$i<count($file_array['name']);$i++)
			{
				$this->upload_thumb($file_name,$file_array,$i);
			}
			e(lang('upload_vid_thumbs_msg'),'m');
		}else{
			$file = $file_array;
			$this->upload_thumb($file_name,$file);
		}
	}
	
	function UploadThumb($flv,$thumbid){
		$file = $_FILES["upload_thumb_$thumbid"]['tmp_name'];
		$ext = GetExt($_FILES["upload_thumb_$thumbid"]['name']);
		if(!empty($file) && $ext =='jpg'){
			$image = new ResizeImage();
			if($image->ValidateImage($file,$ext)){
				$thumb = BASEDIR.'/files/thumbs/'.GetThumb($flv,$thumbid);
				move_uploaded_file($file,$thumb);
				$image->CreateThumb($thumb,$thumb,THUMB_WIDTH,$ext,THUMB_HEIGHT,false);
				return true;
			}else{
				return false;
			}	
		}else{
			return false;
		}
		
	}
		
		
	
	/**
	* FUNCTION USED TO LOAD UPLOAD FORM REQUIRED FIELDS
	* title [Text Field]
	* description [Text Area]
	* tags [Text Field]
	* categories [Check Box]
	*/
	
	function loadRequiredFields($default=NULL)
	{
		global $LANG;		
		if($default == NULL)
			$default = $_POST;

		$title = $default['title'];
		$desc = $default['description'];

		if(is_array($default['category']))
			$cat_array = array($default['category']);		
		else
		{
			preg_match_all('/#([0-9]+)#/',$default['category'],$m);
			$cat_array = array($m[1]);
		}
		
		$tags = $default['tags'];
		
		$uploadFormRequiredFieldsArray = array
		(
		/**
		 * this function will create initial array for fields
		 * this will tell 
		 * array(
		 *       title [text that will represents the field]
		 *       type [type of field, either radio button, textfield or text area]
		 *       name [name of the fields, input NAME attribute]
		 *       id [id of the fields, input ID attribute]       
		 *       value [value of the fields, input VALUE attribute]
		 *       id [name of the fields, input NAME attribute]
		 *       size
		 *       class
		 *       label
		 *       extra_params
		 *       hint_1 [hint before field]
		 *       hint_2 [hint after field]
		 *       anchor_before [before after field]
		 *       anchor_after [anchor after field]
		 *      )
		 */
		 
		 'title'	=> array('title'=> lang('vdo_title'),
							 'type'=> 'textfield',
							 'name'=> 'title',
							 'id'=> 'title',
							 'value'=>  cleanForm($title),
							 'size'=>'45',
							 'db_field'=>'title',
							 'required'=>'yes',
							 'min_length' => config("video_min_title"),
							 'max_length'=>config("video_max_title")

							 ),
		 'desc'		=> array('title'=> lang('vdo_desc'),
							 'type'=> 'textarea',
							 'name'=> 'description',
							 'id'=> 'desc',
							 'value'=> cleanForm($desc),
							 'size'=>'35',
							 'extra_params'=>' rows="4"',
							 'db_field'=>'description',
							 'required'=>'yes'
							 
							 ),
		 'cat'		=> array('title'=> lang('vdo_cat'),
							 'type'=> 'checkbox',
							 'name'=> 'category[]',
							 'id'=> 'category',
							 'value'=> array('category',$cat_array),
							 'hint_1'=>  sprintf(lang('vdo_cat_msg'),ALLOWED_VDO_CATS),
							 'db_field'=>'category',
							 'required'=>'yes',
							 'validate_function'=>'validate_vid_category',
							 'invalid_err'=>lang('vdo_cat_err3'),
							 'display_function' => 'convert_to_categories'

							 
							 ),
		 'tags'		=> array('title'=> lang('tag_title'),
							 'type'=> 'textfield',
							 'name'=> 'tags',
							 'id'=> 'tags',
							 'value'=> cleanForm(genTags($tags)),
							 'hint_1'=> '',
							 'hint_2'=>  lang('vdo_tags_msg'),
							 'db_field'=>'tags',
							 'required'=>'yes',
							 'validate_function'=>'genTags'	
							 ),
		 );
		//Setting Anchors
		$uploadFormRequiredFieldsArray['desc']['anchor_before'] = 'before_desc_compose_box';
		
		//Setting Sizes
		return $uploadFormRequiredFieldsArray;
	}
	
	/**
	* FUNCTION USED TO LOAD FORM OPTION FIELDS
	* broadacast [Radio Button]
	* embedding [Radio Button]
	* rating [Radio Button]
	* comments [Radio Button]
	* comments rating [Radio Button]
	*/
	function loadOptionFields($default=NULL)
	{
		global $LANG,$uploadFormOptionFieldsArray;
		
		
		if($default == NULL)
			$default = $_POST;
			
		$broadcast = $default['broadcast'] ? $default['broadcast'] : 'public';
		$comments = $default['allow_comments'] ? $default['allow_comments'] : 'yes';
		$comment_voting = $default['comment_voting'] ? $default['comment_voting'] : 'yes';
		$rating = $default['allow_rating'] ? $default['allow_rating'] : 'yes';
		$embedding = $default['allow_embedding'] ? $default['allow_embedding'] : 'yes';
		
		$uploadFormOptionFieldsArray = array
		(
		 'broadcast'=> array('title'=>lang('vdo_br_opt'),
							 'type'=>'radiobutton',
							 'name'=>'broadcast',
							 'id'=>'broadcast',
							 'value'=>array('public'=>lang('vdo_br_opt1'),'private'=>lang('vdo_br_opt2')),
							 'checked'=>$broadcast,
							 'db_field'=>'broadcast',
							 'required'=>'no',
							 'validate_function'=>'yes_or_no',
							 'display_function'=>'display_sharing_opt',
							 'default_value'=>'public',
							 ),
		 'comments'=> array('title'=>lang('comments'),
							'type'=> 'radiobutton',
							'name'=>'allow_comments',
							'id'=>'comments',
							'value'=> array('yes'=>lang('vdo_allow_comm'),'no'=>lang('vdo_dallow_comm')),
							'checked'=> $comments,
							'db_field'=>'allow_comments',
							'required'=>'no',
							'validate_function'=>'yes_or_no',
							'display_function'=>'display_sharing_opt',
							'default_value'=>'yes',
							 ),
		 'commentsvote'=> array('title'=>lang('vdo_comm_vote'),
							 'type'=>'radiobutton',
							 'name'=>'comment_voting',
							 'id'=>'comment_voting',
							 'value'=>array('yes'=>lang('vdo_allow_comm').' Voting','no'=>lang('vdo_dallow_comm').' Voting'),
							 'checked'=>$comment_voting,
							 'db_field'=>'comment_voting',
							 'required'=>'no',
							 'validate_function'=>'yes_or_no',
							 'display_function'=>'display_sharing_opt',
							 'default_value'=>'yes',
							 ),
		 'rating'=> array('title'=>lang('ratings'),
							 'type'=>'radiobutton',
							 'name'=>'allow_rating',
							 'id'=>'rating',
							'value'=> array('yes'=>lang('vdo_allow_rating'),'no'=>lang('vdo_dallow_ratig')),
							'checked'=>$rating,
							'db_field'=>'allow_rating',
							'required'=>'no',
							'validate_function'=>'yes_or_no',
							'display_function'=>'display_sharing_opt',
							'default_value'=>'yes',
							 ),
		 'embedding'=> array('title'=>lang('vdo_embedding'),
							'type'=> 'radiobutton',
							'name'=> 'allow_embedding',
							'id'=> 'embedding',
							'value'=> array('yes'=>lang('vdo_embed_opt1'),'no'=>lang('vdo_embed_opt2')),
							'checked'=> $embedding,
							'db_field'=>'allow_embedding',
							'required'=>'no',
							'validate_function'=>'yes_or_no',
							'display_function'=>'display_sharing_opt',
							'default_value'=>'yes',
							 ),
		 );
		return $uploadFormOptionFieldsArray;
	}
	
	/**
	* FUNCTION USED TO LOAD DATE AND LOCATION OPTION OF UPLOAD FORM
	* - day - month - year
	* - country
	* - city
	*/
	function loadLocationFields($default=NULL)
	{
		global $LANG,$LocationFieldsArray,$CBucket;
		
		if($default == NULL)
			$default = $_POST;
		
		$dcountry = $default['country'];
		$location = $default['location'];
		$date_recorded = $default['datecreated'];
		$date_recorded =  $date_recorded ? date(config("date_format"),strtotime($date_recorded)) : date(config("date_format"),time());
		
		$country_array = array("");

		$country_array = @array_merge($country_array,ClipBucket::get_countries());
		
		$LocationFieldsArray = array
		(
		 'country'=> array('title'=>lang('country'),
							'type'=> 'dropdown',
							'name'=> 'country',
							'id'=> 'country',
							'value'=> $country_array,
							'checked'=> $dcountry,
							'db_field'=>'country',
							'required'=>'no',
							'default_value'=>'',
							
							 ),
		 'location'=> array('title'=>lang('location'),
							 'type'=>'textfield',
							'name'=> 'location',
							'id'=> 'location',
							'value'=> $location,
							'hint_2'=> lang('vdo_add_eg'),
							'db_field'=>'location',
							'required'=>'no',
							'default_value'=>'',
							 ),
		 'date_recorded'	=> array(
						 'title' => 'Date Recorded',
						 'type' => 'textfield',
						 'name' => 'datecreated',
						 'id' => 'datecreated',
						 'class'=>'date_field',
						 'anchor_after' => 'date_picker',
						 'value'=> $date_recorded,
						 'db_field'=>'datecreated',
						 'required'=>'no',
						 'default_value'=>'',
						 'use_func_val' => true,
						 'validate_function' => 'datecreated',
						 'hint_2' => config("date_format"),
						 )
		 );
		return $LocationFieldsArray;
	}
	
	/**
	* FUNCTION USED TO DISPLAY DATE FORM
	*/
	function loadDateForm($date=NULL,$sep='/',$bg_process=FALSE)
	{
		global $LANG,$formObj;
		$month_array = array(''=>'--');
		$day_array = array(''=>'--');
		$year_array = array(''=>'----');
		for($i=1;$i<13;$i++) $month_array[$i] = $i;
		for($i=1;$i<32;$i++) $day_array[$i] = $i;
		for($i=date("Y",time());$i>1900;$i--) $year_array[$i] = $i;
		
		if($date['value']==NULL)
		{
			$d_month = $_POST['month'];
			$d_day = $_POST['day'];
			$d_year = $_POST['year'];
		}else{
			$d_month = date("m",strtotime($date));
			$d_day = date("d",strtotime($date));
			$d_year = date("Y",strtotime($date));
		}
		if(!$bg_process)
		{
			echo $formObj->createField('dropdown','month','',$month_array,NULL,NULL,NULL,NULL,$d_month); 
			echo $sep;
			echo $formObj->createField('dropdown','day','',$day_array,NULL,NULL,NULL,NULL,$d_day);
			echo $sep;
			echo $formObj->createField('dropdown','year','',$year_array,NULL,NULL,NULL,NULL,$d_year); 
			echo lang('vdo_for_date');
		}
		
	}
	
	
	
	/**
	 * Function used to load upload form fields
	 * it will load all the values that are submited in the upload form
	 * after validation
	 */
	function load_post_fields()
	{
		
		$required_fields = $this->loadRequiredFields($array);
		$location_fields = $this->loadLocationFields($array);
		$option_fields = $this->loadOptionFields($array);
		$upload_fields = array_merge($required_fields,$location_fields,$option_fields);
		if(count($this->custom_form_fields)>0)
				$upload_fields = array_merge($upload_fields,$this->custom_form_fields);
				
		foreach($upload_fields as $field)
		{
			$name = formObj::rmBrackets($field['name']);
			$val = $_POST[$name];
			if(!is_array($val))
			{
				$val = cleanForm($_POST[$name]);
				echo '<input type="hidden" name="'.$name.'" value="'.$val .'">';
			}else{
				$loop = count($val);
				for($i=0;$i<$loop;$i++)
				{
					$val = $_POST[$name][$i];
					$val = cleanForm($_POST[$name][$i]);
					echo '<input type="hidden" name="'.$name.'[]" value="'.$val.'">';
				}
			}
		}
	}


	/**
	 * Function used to add files in conversion queue
	 */
	function add_conversion_queue($file)
	{
		global $Cbucket,$db;
		$tmp_ext = $Cbucket->temp_exts;
		
		$count = 1;
		while(1)
		{
			$exists = 'no';
			foreach($tmp_ext as $exts)
			{
				
				if(file_exists(TEMP_DIR.'/' .getName($file).'.'.$exts))
				{
					$exists = 'yes';
					break;
				}
			}
			
			if($exists !='yes')
			break;
			
			$new_file = getName($file).'-'.$count.'.'.strtolower(getExt($file));
			rename(TEMP_DIR.'/'.$file,TEMP_DIR.'/'.$new_file);
			$file = $new_file;
			
			$count++;
			if($count>50)
			break;
		}
	
		//Checking file existsi or not
		if(file_exists(TEMP_DIR.'/'.$file))
		{
			$ext = mysql_clean(strtolower(getExt($file)));
			$name = mysql_clean(getName($file));
			//Get Temp Ext
			$tmp_ext = mysql_clean($tmp_ext[rand(0,count($tmp_ext)-1)]);
			//Creating New File Name
			$new_file = $name.'.'.$tmp_ext;
			//Renaming File for security purpose
			rename(TEMP_DIR.'/'.$file,TEMP_DIR.'/'.$new_file);
			//Adding Details to database
			$db->Execute("INSERT INTO ".tbl("conversion_queue")." (cqueue_name,cqueue_ext,cqueue_tmp_ext,date_added)
							VALUES ('".$name."','".$ext."','".$tmp_ext."','".NOW()."') ");
			return $db->insert_id;
		}else{
			return false;
		}
	}
	
	
	/**
	 * Video Key Gen
	 * * it is use to generate video key
	 */
	function video_keygen()
	{
		global $db;
		
		$char_list = "ABDGHKMNORSUXWY";
		$char_list .= "123456789";
		while(1)
		{
			$vkey = '';
			srand((double)microtime()*1000000);
			for($i = 0; $i < 12; $i++)
			{
			$vkey .= substr($char_list,(rand()%(strlen($char_list))), 1);
			}
			
			if(!vkey_exists($vkey))
			break;
		}
		
		return $vkey;
	}
	
	
	
	
	/**
	 * Function used to load upload form
	 */
	function load_upload_options()
	{
		global $Cbucket,$Smarty;
		$opt_list = $Cbucket->upload_opt_list;
		
		foreach($opt_list as $opt)
		{
			$Smarty->register_function($opt['load_func'],$opt['load_func']);
		}
		
		return $opt_list;
	}
	
	
	
	/**
	 * Function used to perform some actions , after video is upload
	 * @param Videoid
	 */
	function do_after_video_upload($vid)
	{
		foreach($this->actions_after_video_upload as $funcs)
		{
			if(function_exists($funcs))
				$funcs($vid);
		}
	}
	
	
	/**
	 * Function used to load custom upload fields
	 */
	function load_custom_upload_fields($data,$ck_display_admin=FALSE,$ck_display_user=FALSE)
	{
		$array = $this->custom_upload_fields;
		foreach($array as $key => $fields)
		{
			$ok = 'yes';
			if($ck_display_admin)
			{
				if($fields['display_admin'] == 'no_display')
					$ok = 'no';
			}
			
			if($ok=='yes')
			{
				if(!$fields['value'])
					$fields['value'] = $data[$fields['db_field']];
				$new_array[$key] = $fields;
			}
		}
		
		return $new_array;
	}
	
	
	/**
	 * Function used to load custom form fields
	 */
	function load_custom_form_fields($data)
	{
		$array = $this->custom_form_fields;
		foreach($array as $key => $fields)
		{
				if(!$fields['value'])
					$fields['value'] = $data[$fields['db_field']];
				$new_array[$key] = $fields;
		}
		
		return $new_array;
	}
	
	
	/**
	 * function used to upload user avatar and or background
	 */
	function upload_user_file($type='a',$file,$uid)
	{
		global $db,$userquery,$imgObj;
		$avatar_dir = BASEDIR.'/images/avatars/';
		$bg_dir		= BASEDIR.'/images/backgrounds/';
		
		if($userquery->user_exists($uid))
		{
			switch($type)
			{
				case 'a':
				case 'avatar':
				{
					
					if($file['size']/1024 > config('max_profile_pic_size'))
						e(sprintf(lang('file_size_exceeds'),config('max_profile_pic_size')));
					elseif(file_exists($file['tmp_name']))
					{
						$ext = getext($file['name']);
						$file_name = $uid.'.'.$ext;
						$file_path = $avatar_dir.$file_name;
						if(move_uploaded_file($file['tmp_name'],$file_path))
						{
							if(!$imgObj->ValidateImage($file_path,$ext))
							{
								e(lang("Invalid file type"));
								@unlink($file_path);
							}else{
								$small_size = $avatar_dir.$uid.'-small.'.$ext;
								$imgObj->CreateThumb($file_path,$file_path,AVATAR_SIZE,$ext);
								$imgObj->CreateThumb($file_path,$small_size,AVATAR_SMALL_SIZE,$ext);
							}
						}else{
							e(lang("class_error_occured"));
						}
					}
				}
				break;
				case 'b':
				case 'bg':
				case 'background':
				{
					if($file['size']/1024 > config('max_bg_size'))
						e(sprintf(lang('file_size_exceeds'),config('max_bg_size')));
					elseif(file_exists($file['tmp_name']))
					{
						$ext = getext($file['name']);
						$file_name = $uid.'.'.$ext;
						$file_path = $bg_dir.$file_name;
						if(move_uploaded_file($file['tmp_name'],$file_path))
						{
							if(!$imgObj->ValidateImage($file_path,$ext))
							{
								e(lang("Invalid file type"));
								@unlink($file_path);
							}else{
								$imgObj->CreateThumb($file_path,$file_path,BG_SIZE,$ext);
							}
						}else{
							e(lang("An error occured While Uploading File!"));
						}
					}
				}
				break;
			}
			return $file_name;
		}else
			e(lang('user_doesnt_exist'));
	}
	
	
	/** 
	 * Function used to upload website logo
	 * @param logo_file
	 * @return $file_name.'.'.$ext;
	 */
	function upload_website_logo($file)
	{
		global $imgObj,$LANG;

		if(!empty($file['name']))
		{
			//$file_num = $this->get_available_file_num($file_name);
			$ext = getExt($file['name']);
			$file_name = 'plaery-logo';
			if($imgObj->ValidateImage($file['tmp_name'],$ext))
			{
				$file_path = BASEDIR.'/images/'.$file_name.'.'.$ext;
				if(file_exists($file_path))
					if(!unlink($file_path))
					{
						e("Unable to remove '$file_path' , please chmod it to 0777");
						return false;
					}
						
				move_uploaded_file($file['tmp_name'],$file_path);
				//$imgObj->CreateThumb($file_path,$file_path,200,$ext,200,false);
				e("Logo has been uploaded",'m');
				return $file_name.'.'.$ext;
			}else
				e("Invalid Image file");
		}
		return false;
	}

}	
?>