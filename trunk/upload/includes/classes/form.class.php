<?php

/**
* THIS CLASS IS USED TO CREATE DYNAMIC FORMS
* @AUTHOR : ARSLAN HASSAN <arslan@labguru.com, arslan@clip-bucket.com>
* @LINK : http://arslan.labguru.com/ - http://clip-bucket.com/
* @LICENSE : CBLA
* @DATE : Feb 21 2009
* @Version : 1.2
* @CB Version : v2
* @Class : formObj
*/

class formObj 
{
	
	/**
	* FUNCTION USED TO CREATE TEXT FIELD
	*/
	function createField($field)
	{
		$field['sep'] = $field['sep'] ? $field['sep'] : '<br>';
		
		switch($field['type'])
		{
			case 'textfield':
			case 'password':
			case 'textarea':
			$fields=$this->createTextfield($field);
			break;
			case 'checkbox':
			$fields=$this->createCheckBox($field);
			break;
			case 'radiobutton':
			$fields=$this->createRadioButton($field);
			break;
			case 'dropdown':
			$fields=$this->createDropDown($field);
			break;

		}
		return $fields;
	}
	
	
	/**
	* FUNCTION USED TO CREATE TEXT FIELD
	* @param name
	* @param id
	* @param value
	* @param class
	* @param extra_tags
	* @param label
	*/
	function createTextfield($field)
	{
		//Starting Text Field
		if($field['type']=='textfield')
			$textField = '<input type="text"';
		if($field['type']=='password')
			$textField = '<input type="password"';
		elseif($field['type']=='textarea')
			$textField = '<textarea';			
		if(!empty($field['name']))
			$textField .= ' name="'.$field['name'].'" ';
		if(!empty($field['id']))
			$textField .= ' id="'.$field['id'].'" ';
		if(!empty($field['class']))
			$textField .= ' class="'.$field['class'].'" ';
		if(!empty($field['size']))
		{
			if($$field['type']=='textfield' ||$field['type']=='password')
			$textField .= ' size="'.$field['size'].'" ';
			else
			$textField .= ' cols="'.$field['size'].'" ';
		}
		if(!empty($field['extra_tags']))
			$textField .= ' '.$field['extra_tags'].' ';
		
		if(!empty($field['value']))
		{
			if($field['type']=='textfield' ||$field['type']=='password')
				$textField .= ' value="'.$field['value'].'" ';
			
		}
		
		if($field['type']=='textarea')
				$textField .= '>'.$field['value'];
				
		//Finishing It
		if($field['type']=='textfield' ||$field['type']=='password')
			$textField .= ' >';
		elseif($field['type']=='textarea')
			$textField .= '</textarea>';
		
		//Checking Label
		if(!empty($field['label']))
		$formTextField = '<label>'.$field['label'].$textField.'</label>';
		else
		$formTextField = $textField;
		
		return $formTextField;
		
	}
	
	
	/**
	 * FUNCTION USED TO CREATE CHECK BOXES 
	 * @param name
	 * @param id
	 * @param value = array('value'=>'name')
	 * @param class
	 * @param extra_tags
	 * @param label
	 */
	
	function createCheckBox($field)
	{
		//First Checking if value is CATEGORY
		if($field['value'][0]=='category')
		{
			$values_array = $field['value'][1][0];
			$field['value'] = '';
			//Generate Category list
			$type = $field['type'] ? $field['type'] : 'video';
			$catArray = getCategoryList();
			foreach ($catArray as $cat)
			{
				$field['value'][$cat['category_id']] = $cat['category_name'];
			}
		}
		$arrayName = $this->rmBrackets($field['name']);
		//Creating Fields

		foreach($field['value'] as $key => $value)
		{
			if(is_array($values_array))
			{
				foreach($values_array as $cat_val)
				{
					if ($cat_val == $key || $field['checked']=='checked')
					{
						$checked = ' checked ';
						break;
					}else{
						$checked = '  ';
					}
				}
			}
			echo '<input name="'.$field['name'].'" type="checkbox" value="'.$key.'" '.$checked.' '.$field['extra_tags'].'>'.$value	;
			echo $field['sep'];
		}
	}
	
	
	/**
	* FUNCTION USED TO CREATE RADIO Button
	* @param name
	* @param id
	* @param value = array('value'=>'name')
	* @param class
	* @param extra_tags
	* @param label
	*/
	function createRadioButton($field)
	{
		//Creating Fields
		$count = 0;
		$sep = $field['sep'];
		$arrayName = $this->rmBrackets($field['name']);
		foreach($field['value'] as $key => $value)
		{
			if(!empty($_POST[$arrayName]) || !empty($field['checked']))
			{
				if ($_POST[$arrayName] == $key || $field['checked'] == $key)
				{
					$checked = ' checked ';
				}else{
					$checked = '  ';
				}
			}else{
				if($count==0)
					$checked = ' checked ';
				else
					$checked = '';
				$count++;
			}
			echo '<input name="'.$field['name'].'" type="radio" value="'.$key.'" '.$checked.' '.$field['extra_tags'].'>'.$value	;
			echo $sep;
		}
	}
	
	/**
	* FUNCTION USED TO REMOVE BRACKET FROM FROM FIELD NAME IF IT IS AN ARRAY
	* @param name with brackets
	* return name without brackets
	*/
	function rmBrackets($string)
	{
		$string = preg_replace('/\[\]/','',$string);
		return $string;
	}
	
	
	/**
	* FUNCTION USED TO CREATEA DROPDOWN MENU
	* @param name
	* @param id
	* @param value = array('value'=>'name')
	* @param class
	* @param extra_tags
	* @param label
	*/
	
	function createDropDown($field)
	{
		global $LANG;
		$ddFieldStart = '<select name="'.$field['name'].'" id="'.$field['id'].'" class="'.$field['class'].'">';
		$arrayName = $this->rmBrackets($field['name']);
		foreach($field['value'] as $key => $value)
		{
			if(!empty($_POST[$arrayName]) || !empty($field['checked']))
			{
				if ($_POST[$arrayName] == $key || $field['checked']== $key)
				{
					$checked = ' selected="selected" ';
				}else{
					$checked = '  ';
				}
			}else{
				if($count==0)
					$checked = ' selected="selected" ';
				else
					$checked = '';
				$count++;
			}
			$fieldOpts .='<option value="'.$key.'" '.$checked.' '.$field['extra_tags'].'>'.$value.'</option>';
		}
		$ddFieldEnd = '</select>';
		echo $ddFieldStart.$fieldOpts.$ddFieldEnd;
	}
	
	
	
	/**
	 * Form Validator
	 * This function used to valid form fields
	 */
	function validate_form($field,$method,$syntax=NULL)
	{
		switch($method)
		{
			case 'username':
			$syntax = get_re('username');
		}
			
	}
}




?>