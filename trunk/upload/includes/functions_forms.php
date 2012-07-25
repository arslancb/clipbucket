<?php
        

        /**
	 * ClipBucket Form Validator
	 * this function controls the whole logic of how to operate input
	 * validate it, generate proper error
	 */
	function validate_cb_form($input,$array)
	{
		
		if(is_array($input))
		foreach($input as $field)
		{
			$field['name'] = formObj::rmBrackets($field['name']);
			
			//pr($field);
			$title = $field['title'];
			$val = $array[$field['name']];
			$req = $field['required'];
			$invalid_err =  $field['invalid_err'];
			$function_error_msg = $field['function_error_msg'];
			if(is_string($val))
			{
				if(!isUTF8($val))
					$val = utf8_decode($val);
				$length = strlen($val);
			}
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
                                        
				}
				
				if(!is_valid_syntax($field['syntax_type'],$val))
				{
					if(!empty($invalid_err))
						e($invalid_err);
				}
				if(isset($max_len))
				{
					if($length > $max_len || $length < $min_len)
					e(sprintf(lang('please_enter_val_bw_min_max'),
							  $title,$min_len,$field['max_length']));
				}
				if(function_exists($field['db_value_check_func']))
				{

					$db_val_result = $field['db_value_check_func']($val);
					if($db_val_result != $field['db_value_exists'])
						if(!empty($field['db_value_err']))
							e($field['db_value_err']);
						elseif(!empty($invalid_err))
							e($invalid_err);
				}
				if($field['relative_type']!='')
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
		}
	}
        
        
        
        
        /**
	 * Function used to get value from $_GET
	 */
	function get_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_GET[$val]);
		else
			return $_GET[$val];
	}function get($val){ return get_form_val($val); }
	
	/**
	 * Function used to get value form $_POST
	 */
	function post_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_POST[$val]);
		else
			$_POST[$val];
	}
	
	
	/**
	 * Function used to get value from $_REQUEST
	 */
	function request_form_val($val,$filter=false)
	{
		if($filter)
			return form_val($_REQUEST[$val]);
		else
			$_REQUEST[$val];
	}
        
        
	/**
	 * Function used to get Regular Expression from database
	 * @param : code
	 */
	function get_re($code)
	{
		global $db;
		$results = $db->select(tbl("validation_re"),"*"," re_code='$code'");
		if($db->num_rows>0)
		{
			return $results[0]['re_syntax'];
		}else{
			return false;
		}
	}
	function get_regular_expression($code)
	{
		return get_re($code); 
	}
	
	/**
	 * Function used to check weather input is valid or not
	 * based on preg_match
	 */
	function check_re($syntax,$text)
	{
		preg_match('/'.$syntax.'/i',$text,$matches);
		if(!empty($matches[0]))
		{
			return true;
		}else{
			return false;
		}
	}
	function check_regular_expression($code,$text)
	{
		return check_re($code,$text); 
	}
	
	/**
	 * Function used to check field directly
	 */
	function validate_field($code,$text)
	{
		$syntax =  get_re($code);
		if(empty($syntax))
			return true;
		return check_regular_expression($syntax,$text);
	}
	
	function is_valid_syntax($code,$text)
	{
		if(DEVELOPMENT_MODE && DEV_INGNORE_SYNTAX)
			return true;
		return validate_field($code,$text);
	}
	
	/**
	 * Function used to apply function on a value
	 */
	function is_valid_value($func,$val)
	{
		if(!function_exists($func))
			return true;
		elseif(!$func($val))
			return false;
		else
			return true;
	}
	
	function apply_func($func,$val)
	{
		if(is_array($func))
		{
			foreach($func as $f)
				if(function_exists($f))
					$val = $f($val);
		}else{
			$val = $func($val);
		}
		return $val;
	}
	
	/**
	 * Function used to validate YES or NO input
	 */
	function yes_or_no($input,$return=yes)
	{
		$input = strtolower($input);
		if($input!=yes && $input !=no)
			return $return;
		else
			return $input;
	}
        
        
        
        /**
	 * Function used to load captcha field
	 */
	function get_captcha()
	{
		global $Cbucket;
		if(count($Cbucket->captchas)>0)
		{
			return $Cbucket->captchas[0];
		}else
			return false;
	}
	
	/**
	 * Function used to load captcha
	 */
	define("GLOBAL_CB_CAPTCHA","cb_captcha");
	function load_captcha($params)
	{
		global $total_captchas_loaded;
		switch($params['load'])
		{
			case 'function':
			{
				if($total_captchas_loaded!=0)
					$total_captchas_loaded = $total_captchas_loaded+1;
				else
					$total_captchas_loaded = 1;
				$_COOKIE['total_captchas_loaded'] = $total_captchas_loaded;
				if(function_exists($params['captcha']['load_function']))
					return $params['captcha']['load_function']().'<input name="cb_captcha_enabled" type="hidden" id="cb_captcha_enabled" value="yes" />';
			}
			break;
			case 'field':
			{
				echo '<input type="text" '.$params['field_params'].' name="'.GLOBAL_CB_CAPTCHA.'" />';
			}
			break;
			
		}
	}
        
        
        /**
	 * Function used to verify captcha
	 */
	function verify_captcha()
	{
		$var = post('cb_captcha_enabled');
		if($var=='yes')
		{
			$captcha = get_captcha();
			$val = $captcha['validate_function'](post(GLOBAL_CB_CAPTCHA));
			return $val;
		}else
			return true;
	}
        
        
        /**
         * Cleans form value
         * @param STRING $string
         * @return STRING
         */
	function cleanForm($string)
	{
		if(is_string($string))
			$string = htmlspecialchars($string);
		if(get_magic_quotes_gpc())
			if(!is_array($string))
			$string = stripslashes($string);			
		return $string;
	}
	function form_val($string){return cleanForm($string); }
        
        
        
        /**
         *
         * @param type $tag
         * @return type 
         */
	function isValidtag($tag)
	{
            $disallow_array = array
            ('of','is','no','on','off','a','the','why','how','what','in');
            if(!in_array($tag,$disallow_array) && strlen($tag)>2)
                    return true;
            else
                    return false;
	}
        
        
        
        /**
	 * Function used to give output in proper form 
	 */
	function input_value($params)
	{
		$input = $params['input'];
		$value = $input['value'];
		
		if($input['value_field']=='checked')
			$value = $input['checked'];
			
		if($input['return_checked'])
			return $input['checked'];
			
		if(function_exists($input['display_function']))
			return $input['display_function']($value);
		elseif($input['type']=='dropdown')
		{
			if($input['checked'])
				return $value[$input['checked']];
			else
				return $value[0];
		}else
			return $input['value'];
	}
        
        
        
        /**
	 * Function used to get post var
	 */
	function post($var)
	{
		return $_POST[$var];
	}
?>