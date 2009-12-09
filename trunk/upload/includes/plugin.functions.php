<?php

/**
 * this sperate file 
 * has all functions that are used
 * to create plugins
 */
 
 
	/**
	 * FUNCTION USED TO REGISTER ACTIONS THAT ARE TO APPLIED
	 * ON COMMENTS , TITLE, DESCRIPTIONS etc
	 */
	function register_action($name,$type=NULL)
	{
		global $Cbucket;
		if(is_array($name))
		{
			foreach($name as $key => $naam)
				if(is_array($naam))
				{
					foreach($naam as $name)
					{
						$Cbucket->actionList[$name][] = $key;
					}
				}else{

					$Cbucket->actionList[$naam][] = $key;
				}
		}elseif($type!=NULL){
			$Cbucket->actionList[$type][] = $name;
		}
	}
	
	
	
	
	
	/**
	* FUNCTION USED TO CREATE ANCHOR PLACEMENT
	* these are the placement where we can add plugin's or widget's code,
	* e.g if we want to display a new WYSIWYG box before comment text area 
	* we will create anchor before text area as {ANCHOR place='before_compose_box'}
	* code will be written in plugin file and its place will point 'before_compose_box'
	* then our function will get all the code for this placement and will display it
	* @param : array(Ad Code, LIMIT);
	*/
	function ANCHOR($params,&$Smarty)
	{
		global $Cbucket;
		//Getting List of codes to display at this anchor
		$codes = $Cbucket->get_anchor_codes($params['place']);
		if(!empty($codes))
		{
			if(is_array($codes))
			{
				foreach($codes as $code)
				{
					echo $code;
				}
			}else{
				echo $codes;
			}
		}
		
		//Getting list of function that will be performed while calling achor
		$funcs = $Cbucket->get_anchor_function_list($params['place']);

		if(!empty($funcs))
		{
			if(is_array($funcs))
			{
				foreach($funcs as $func)
				{
					if(function_exists($func))
					{
						if($params['data'])
							$func($params['data']);
						else
							$func();
					}
				}
			}else{
				$funcs();
			}
		}
	}
	
	/**
	* FUNCTION USED TO REGISTER ANCHORS
	* before_comments etc.. see complete list on http://docs.clip-bucket.com
	*/
	function register_anchor($name,$type=NULL)
	{
		global $Cbucket;
		if(is_array($name))
		{
			foreach($name as $key => $naam)
				if(is_array($naam))
				{
					foreach($naam as $name)
					{
						$Cbucket->anchorList[$name][] = $key;
					}
				}else{

					$Cbucket->anchorList[$naam][] = $key;
				}
		}elseif($type!=NULL){
			$Cbucket->anchorList[$type][] = $name;
		}
	}
	
	
	
	/**
	* FUNCTION USED TO REGISTER FUNCTION
	* If you want to perform some function on 
	* some place, you can simple register function that will be execute where anchor points are
	* placed
	*/
	function register_anchor_function($name,$type=NULL)
	{
		global $Cbucket;
		if(is_array($name))
		{
			foreach($name as $key => $naam)
				if(is_array($naam))
				{
					foreach($naam as $name)
					{
						$Cbucket->anchor_function_list[$name][] = $key;
					}
				}else{

					$Cbucket->anchor_function_list[$naam][] = $key;
				}
		}elseif($type!=NULL){
			$Cbucket->anchor_function_list[$type][] = $name;
		}
	}
	
	
	 /**
	  * Function used to add items in admin menu
	  * This function will insert new item in admin menu
	  * under given header, if the header is not available 
	  * it will create one, ( Header means titles ie 'Plugins' 'Videos' etc)
	  * @param STRING $header - Could be Plugin , Videos, Users , please check 
	  * http://docs.clip-bucket.com. for reference
	  * @param STRING name 
	  * @param STRING link
	  * That will add new item in admin menu
	  */
	 function add_admin_menu($header='Tool Box',$name,$link)
	 {
		 global $Cbucket;
		 //Gett Menu
		 $menu = $Cbucket->AdminMenu;
		 //Add New Meny
		 $menu[$header][$name] = $link;
		 $Cbucket->AdminMenu = $menu;
	 }
	 
	 
	 
	 
	/**
	 * Function used to add custom upload fields
	 * In this you will provide an array that has a complete
	 * details of the field such as 'name',validate_func etc
	 * please check docs.clip-bucket.com for "how to add custom upload field"
	 */
	function register_custom_upload_field($array)
	{
		global $Upload;
		$name = key($array);
		if(is_array($array) && !empty($array[$name]['name']))
		{
			foreach($array as $key => $arr)
				$Upload->custom_upload_fields[$key] = $arr;
		}
	}
	
	/**
	 * Function used to add custom form fields
	 * In this you will provide an array that has a complete
	 * details of the field such as 'name',validate_func etc
	 * please check docs.clip-bucket.com for "how to add custom form field"
	 */
	function register_custom_form_field($array)
	{
		global $Upload;
		$name = key($array);
		if(is_array($array) && !empty($array[$name]['name']))
		{
			foreach($array as $key => $arr)
				$Upload->custom_form_fields[$key] = $arr;
		}
	}
	
	
	/**
	 * Function used to add custom signup form fields
	 * In this you will provide an array that has a complete
	 * details of the field such as 'name',validate_func etc
	 * please check docs.clip-bucket.com for "how to add custom signup field"
	 */
	function register_signup_field($array)
	{
		global $userquery;
		$name = key($array);
		if(is_array($array) && !empty($array[$name]['name']))
		{
			foreach($array as $key => $arr)
				$userquery->custom_signup_fields[$key] = $arr;
		}
	}
	
	
	
	
	/**
	  * Function used to add actions that will be performed
	  * when video is uploaded
	  * @param Function name
	  */
	 function register_after_video_upload_action($func)
	 {
		 global $Upload;
		 $Upload->actions_after_video_upload[] = $func;
	 }
	 
	  /**
	  * Function used to add actions that will be performed
	  * when video is going to play, it will check which player to use
	  * what type to use and what to do
	  * @param Function name
	  */
	 function register_actions_play_video($func)
	 {
		 global $Cbucket;
		 $Cbucket->actions_play_video[] = $func;
	 }
	 
	 
	 
	 /**
	  * Function used to add links in admin area
	  */
	 function add_admin_link($array)
	 {
		 $area = $array['area'];
		 $title = $array['title'];
		 $link = $array['link'];
	 }
	 
	 
	 /**
	  * function use to register function that will be 
	  * called while deleting a video
	  */
	 function register_action_remove_video($func)
	 {
		 global $cbvid;
		 $cbvid->video_delete_functions[] = $func;
	 }
	 
	/**
	 * Function used to display comment rating
	*/
	function comment_rating($input)
	{
		if($input<0)
			return '<font color="#ed0000">'.$input.'</font>';
		elseif($input>0)
			return '<font color="#006600">+'.$input.'</font>';
		else
			return $input;
	}
	
	
	/**
	 * Function use to register security captchas for clipbucket
	 */
	function register_cb_captcha($func,$ver_func,$show_field=true)
	{
		global $Cbucket;
		$Cbucket->captchas[] = array('load_function'=>$func,'validate_function'=>$ver_func,'show_field'=>$show_field);
	}
?>