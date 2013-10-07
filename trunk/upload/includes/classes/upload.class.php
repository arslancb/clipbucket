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
	var $custom_form_fields_groups = array() ; //Groups of custom fields
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
			}elseif(userid() && !has_access('allow_video_upload',true,true))
				return false;
			
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
		
		//Adding Video Feed
		addFeed(array('action' => 'upload_video','object_id' => $insert_id,'object'=>'video'));;
		return $insert_id;
				
	}
	
	/**
	 * Function used to get available name for video thumb
	 * @param FILE_Name
	 */
	function get_available_file_num($file_name,$big=false)
	{
		//Starting from 1
		$code = 1;
		if($big)
			$big = "big-";
		while(1)
		{
			$path = THUMBS_DIR.'/'.$file_name.'-'.$big.$code.'.';
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
				$big_file_path = THUMBS_DIR.'/'.$file_name.'-big-'.$file_num.'.'.$ext;
				move_uploaded_file($file['tmp_name'][$key],$file_path);
				
				$imgObj->CreateThumb($file_path,$big_file_path,config('big_thumb_width'),$ext,config('big_thumb_height'),false);
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
							 'required'=>'yes',
							 'anchor_after'=>'after_desc_compose_box',
							 
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
		
		//Checking weather to enabled or disable password field
		$video_pass_disable = 'disabled="disabled"  ';
		$video_user_disable = 'disabled="disabled"  ';
		
		if($broadcast=='unlisted')
			$video_pass_disable = "";
		elseif($broadcast=='private')
			$video_user_disable = '';
			
		$uploadFormOptionFieldsArray = array
		(
		 'broadcast'=> array('title'=>lang('vdo_br_opt'),
							 'type'=>'radiobutton',
							 'name'=>'broadcast',
							 'id'=>'broadcast',
							 'value'=>array('public'=>lang('vdo_br_opt1'),'private'=>lang('vdo_br_opt2')
							 ,'unlisted'=>lang('vdo_broadcast_unlisted'),'logged'=>lang("logged_users_only")),
							 'checked'=>$broadcast,
							 'db_field'=>'broadcast',
							 'required'=>'no',
							 'validate_function'=>'yes_or_no',
							 'display_function'=>'display_sharing_opt',
							 'default_value'=>'public',
							 'extra_tags' => 
							 	' onClick="
									$(\'#video_password\').attr(\'disabled\',\'disabled\');
									$(\'#video_users\').attr(\'disabled\',\'disabled\');
									if($(this).val()==\'unlisted\') 
										$(\'#video_password\').attr(\'disabled\',\'\')
									else if($(this).val()==\'private\') 
										$(\'#video_users\').attr(\'disabled\',\'\')

									" '
							 ),
		 
		 'video_password'=> array
		 					('title'=>lang('video_password'),
							 'type'=>'password',
							 'name'=>'video_password',
							 'id'=>'video_password',
							 'value'=> $default['video_password'],
							 'db_field'=>'video_password',
							 'required'=>'no',
							 'extra_tags' => " $video_pass_disable ",
							 'hint_2'=> lang('set_video_password')
							  ),
		 'video_users' => array('title'=>lang('video_users'),
							 'type'=>'textarea',
							 'name'=>'video_users',
							 'id'=>'video_users',
							 'value'=> $default['video_users'],
							 'db_field'=>'video_users',
							 'required'=>'no',
							 'extra_tags' => " $video_user_disable ",
							 'hint_2'=> lang('specify_video_users'),
							 'validate_function' => 'video_users',
							 'use_func_val'=>true
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
			if(!file_exists(TEMP_DIR.'/'.$file) || !$name)
				return false;
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
	function load_custom_form_fields($data,$group_based=false)
	{
		if(!$group_based)
		{
			$array = $this->custom_form_fields;
			foreach($array as $key => $fields)
			{
					if($data[$fields['db_field']])
						$value = $data[$fields['db_field']];
					elseif($data[$fields['name']])
						$value = $data[$fields['name']];
						
						
					if($fields['type']=='radiobutton' || 
					   $fields['type']=='checkbox' ||
					   $fields['type']=='dropdown')
					$fields['checked'] = $value;
					else
					$fields['value'] = $value;
					
						
					$new_array[$key] = $fields;
			}
			return $new_array;
		}else
		{
			return $array = $this->custom_form_fields_groups;
		}
	}
	
	
	/**
	 * function used to upload user avatar and or background
	 */
	function upload_user_file($type='a',$file,$uid)
	{
		global $db,$userquery,$cbphoto,$imgObj;
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
								$cbphoto->CreateThumb($file_path,$file_path,$ext,AVATAR_SIZE,AVATAR_SIZE);
								$cbphoto->CreateThumb($file_path,$small_size,$ext,AVATAR_SMALL_SIZE,AVATAR_SMALL_SIZE);
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
	
	
	
	/**
	 * load_video_fields
	 * 
	 * @param $input default values for all videos
	 * @return array of video fields
	 *
	 * Function used to load Video fields
	 * in clipbucket v2.5 , video fields are loaded in form of groups arrays
	 * each group has it name and fields wrapped in array 
	 * and that array will be part of video fields
	 */
	function load_video_fields($input)
	{
		$fields = array
		(
			array
			(
				'group_name' => lang('required_fields'),
				'group_id'	=> 'required_fields',
				'fields'	=> $this->loadRequiredFields($input),
			),
			array
			(
				'group_name' => lang('vdo_share_opt'),
				'group_id'	=> 'sharing_fields',
				'fields'	=> $this->loadOptionFields($input),
			),
			array
			(
				'group_name' => lang('date_recorded_location'),
				'group_id'	=> 'date_location_fields',
				'fields'	=> $this->loadLocationFields($input),
			)
		);
		
		//Adding Custom Fields
		$custom_fields = $this->load_custom_form_fields($input,false);
		
		if($custom_fields)
		{
			$more_fields_group = 
			array
			(
				'group_name' => lang('more_fields'),
				'group_id'	=> 'custom_fields',
				'fields'	=> $custom_fields,
			);
		}
		
		//Adding Custom Fields With Groups
		$custom_fields_with_group = $this->load_custom_form_fields($input,true);
		
		//Finaling putting them together in their main array called $fields
		if($custom_fields_with_group)
		{
			$custFieldGroups = $custom_fields_with_group;
		
			foreach($custFieldGroups as $gKey => $fieldGroup)
			{
				
				foreach($fieldGroup['fields'] as $mainKey => $nField)
				{
					$updatedNewFields[$mainKey] = $nField;
					if($input[$nField['db_field']])
						$value = $input[$nField['db_field']];
					elseif($input[$nField['name']])
						$value = $input[$nField['name']];
						
					if($nField['type']=='radiobutton' || 
					   $nField['type']=='checkbox' ||
					   $nField['type']=='dropdown')
					$updatedNewFields[$mainKey]['checked'] = $value;
					else
					$updatedNewFields[$mainKey]['value'] = $value;
									
			
				}
				
				$fieldGroup['fields'] = $updatedNewFields;
				
				$group_id = $fieldGroup['group_id'];
				
				foreach($fields as $key => $field)
				{ 
					
					if($field['group_id'] == $group_id)
					{
						$inputFields = $field['fields'];
						//Setting field values
						$newFields = $fieldGroup['fields'];
						
						
						
						$mergeField = array_merge($inputFields,$newFields);
						
						
						//Finally Updating array
						$newGroupArray =
						array
						(
							'group_name' => $field['group_name'],
							'group_id' => $field['group_id'],
							'fields' => $mergeField,
						);
						
						$fields[$key] = $newGroupArray;
						
						$matched = true;
						break;
					}else
						$matched = false;
				}
				
				if(!$matched)
					$fields[] = $fieldGroup;
				
			}
		}
		
		if($more_fields_group)
			$fields[] = $more_fields_group;
				
		return $fields;
	}
	
	function isTime($time)
	{
		preg_match("/(([0-9]?[0-9]{1}):)?([0-5]{1}[0-9]{1}):([0-5]{1}[0-9]{1})/",$time,$match);
		if(!empty($match[0]))
			return ($match[0]);
		else
			return false;		
	}	
	
	function time_to_sec($time) {
		if(!$this->isTime($time))
			e(lang("Format of time is not right."));
		else
		{		 
			$hours = substr($time, 0, -6); 
			$minutes = substr($time, -5, 2); 
			$seconds = substr($time, -2); 
			return $hours * 3600 + $minutes * 60 + $seconds; 
		}
	}
}	
?>