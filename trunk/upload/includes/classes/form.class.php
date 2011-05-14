<?php

/**
* THIS CLASS IS USED TO CREATE DYNAMIC FORMS
* @AUTHOR : ARSLAN HASSAN <arslan@labguru.com, arslan@clip-bucket.com>
* @LINK : http://arslan.labguru.com/ - http://clip-bucket.com/
* @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
* @DATE : Feb 21 2009
* @Version : 1.2
* @CB Version : v2
* @Class : formObj
*/

class formObj 
{
	
	var $multi_cat_id =  0;
	
	/**
	* FUNCTION USED TO CREATE TEXT FIELD
	*/
	function createField($field,$multi=FALSE)
	{
		$field['sep'] = $field['sep'] ? $field['sep'] : '<br>';
		
		switch($field['type'])
		{
			case 'textfield':
			case 'password':
			case 'textarea':
				$fields=$this->createTextfield($field,$multi);
			break;
			case 'checkbox':
			$fields=$this->createCheckBox($field,$multi);
			break;
			case 'radiobutton':
			$fields=$this->createRadioButton($field,$multi);
			break;
			case 'dropdown':
			$fields=$this->createDropDown($field,$multi);
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
	function createTextfield($field,$multi=FALSE)
	{

		//Starting Text Field
		if($field['type']=='textfield')
			$textField = '<input type="text"';
		if($field['type']=='password')
			$textField = '<input type="password"';
		elseif($field['type']=='textarea')
			$textField = '<textarea';			
		if(!empty($field['name']))
		{
			if(!$multi)
				$textField .= ' name="'.$field['name'].'" ';
			else
				$textField .= ' name="'.$field['name'].'[]" ';
		}
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
		if(!empty($field['rows']) && $field['type']=='textarea')
		{
			$textField .= ' rows="'.$field['rows'].'" ';
		}
		
		if(!empty($field['extra_tags']))
			$textField .= ' '.$field['extra_tags'].' ';
		
		if(!empty($field['value']))
		{
			if($field['type']=='textfield' ||$field['type']=='password')
				$textField .= ' value="'.(htmlspecialchars_decode($field['value'])).'" ';
			
		}
		
		if($field['type']=='textarea')
				$textField .= '>'.htmlspecialchars_decode($field['value']);
				
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
	function createCheckBox($field,$multi=FALSE)
	{
		//First Checking if value is CATEGORY
		if($field['value'][0]=='category')
		{
			$values_array = $field['value'][1][0];
			//$field['value'] = '';
			//Generate Category list
			$type = $field['category_type'] ? $field['category_type'] : 'video';
			
			$catArray = getCategoryList(array("type"=>$type));
			
			if(is_array($catArray))
			{
				$this->multi_cat_id = $this->multi_cat_id + 1;
				$params['categories'] = $catArray;
				$params['field'] = $field;
				if(config('show_collapsed_checkboxes') == 1)
					$params['collapsed'] = true;	
				$this->listCategoryCheckBox($params,$multi);
				return false;
			}else
				return "There is no category to select";
			
			
		}
		$arrayName = $this->rmBrackets($field['name']);
		//Creating Fields
		
		if($multi)
		{
			global $multi_cat_id;
			@$multi_cat_id++;
		}
		
		$count=0;
		foreach($field['value'] as $key => $value)
		{
			$count++;
			
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
			
			if(!$multi)
				$field_name = $field['name'];
			else
			{
				$field_name = $field['name'];
				$field_name = $this->rmBrackets($field_name);
				$field_name = $field_name.$multi_cat_id.'[]';
			}
			
			if(!empty($field['id']))
				$field_id = ' id="'.$field['id'].'" ';
			
			if($count>0)
			echo $field['sep'];
			echo '<label><input name="'.$field_name.'" type="checkbox" value="'.$key.'" '.$field_id.' '.$checked.' '.$field['extra_tags'].'>'.$value.'</label>'	;			
		}
	}
	
	function listCategoryCheckBoxCollapsed($in,$multi)
	{
		$cats = $in['categories'];
		$field = $in['field'];
		$rand = (rand(0,100000));
		if($field['sep'] == "<br/>")
			$field['sep'] = "";
			
		if(!$multi)
			$fieldName = $field['name'];
		else
		{
			$fieldName = $field['name'];
			$fieldName = $this->rmBrackets($fieldName);
			$fieldName = $fieldName.$multi_cat_id.'[]';
		}
		$display = "none";
		$values = $field['value'][1][0];
		$Values = array();
		if(!empty($values))
			foreach($values as $val)
				$Values[] = "|".$val."|";
	
		if($cats)
		{
			$output = "";
			foreach($cats as $cat)
			{
				$checked = "";
				if(in_array("|".$cat['category_id']."|",$Values))
					$checked = 'checked';
				echo "<div class='uploadCategoryCheckBlock' style='position:relative'>";
				echo $field['sep'];
				echo '<label><input name="'.$fieldName.'" type="checkbox" value="'.$cat['category_id'].'" '.$field_id.'
				 '.$checked.' '.$field['extra_tags'].'>'.$cat['category_name'].'</label>';
				 if($cat['children'])
				 {
				 		echo "<span id='".$cat['category_id']."_toggler' alt='".$cat['category_id']."_".$rand."' class='CategoryToggler CheckBoxCategoryToggler ".$display."' style='display:block;' onclick='toggleCategory(this);'>&nbsp;</span>";
							$childField = $field;
							$childField['sep'] = $field['sep'].str_repeat('&nbsp;',5); 
						echo "<div id='".$cat['category_id']."_".$rand."' class='sub_categories sub_categories_checkbox' style='display:".$display."'>";
							echo 	$this->listCategoryCheckBoxCollapsed(array('categories'=>$cat['children'],'field'=>$childField,'children_indent'=>true),$multi);
						echo "</div>"; 
				 }
				 echo "</div>";
			}
		}
	}
	
	//Creating checkbox with indent for cateogry childs
	function listCategoryCheckBox($in,$multi)
	{
		$cats = $in['categories'];
		$field = $in['field'];
		//$in['collapsed'] = true;
		$collapsed = $in['collapsed'];
		if($collapsed)
			return $this->listCategoryCheckBoxCollapsed($in,$multi);
		//setting up the field name
		if(!$multi)
			$field_name = $field['name'];
		else
		{
			$field_name = $field['name'];
			$field_name = $this->rmBrackets($field_name);
			$field_name = $field_name.$this->multi_cat_id.'[]';
		}
		
		//Setting up the values
		$values = $field['value'][1][0];
		$newVals = array();
		
		if(!empty($values))
			foreach($values as $val)
				$newVals[] = '|'.$val.'|';
		if($cats)
		{
			foreach($cats as $cat)
			{
				$checked = '';
				//checking value
				if(in_array('|'.$cat['category_id'].'|',$newVals))
					$checked = 'checked';
				echo $field['sep'];
				echo '<label><input name="'.$field_name.'" type="checkbox" value="'.$cat['category_id'].'" '.$field_id.'
				 '.$checked.' '.$field['extra_tags'].'>'.$cat['category_name'].'</label>'	;
				 if($cat['children'])
				 {
					$childField = $field;
					$childField['sep'] = $field['sep'].str_repeat('&nbsp;',5);
				 	$this->listCategoryCheckBox(array('categories'=>$cat['children'],'field'=>$childField,'children_indent'=>true),$multi);
				 }
			}
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
	function createRadioButton($field,$multi=FALSE)
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
			if(!empty($field['id']))
				$field_id = ' id="'.$field['id'].'" ';
			
			if(!$multi)
				$field_name = $field['name'];
			else
				$field_name = $field['name'].'[]';
				
			echo '<label><input name="'.$field_name .'" type="radio" value="'.$key.'" '.$field_id.' '.$checked.' '.$field['extra_tags'].'>'.$value.'</label>'	;
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
	
	function createDropDown($field,$multi=FALSE)
	{
		global $LANG;
		
		//First Checking if value is CATEGORY
		if($field['value'][0]=='category')
		{
			$values_array = $field['value'][1][0];
			$field['value'] = '';
			//Generate Category list
			$type = $field['type'] ? $field['type'] : 'video';
			$catArray = getCategoryList(array("type"=>$field['category_type']));
			foreach ($catArray as $cat)
			{
				$field['value'][$cat['category_id']] = $cat['category_name'];
			}
		}
		
		if(!$multi)
			$field_name = $field['name'];
		else
			$field_name = $field['name'].'[]';
		
		$ddFieldStart = '<select name="'.$field_name.'" id="'.$field['id'].'" class="'.$field['class'].'">';
		$arrayName = $this->rmBrackets($field['name']);
		
		if(is_array($field['value']))
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