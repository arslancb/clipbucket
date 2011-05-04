<?php


	/**
	 * This file contains Smarty modifiers that will be applied in templates
	 * You just need to define a function that can be used ClipBucket
	 * and then you have to assign that function accordingly
	 * For more information please visit http://docs.clip-bucket.com/
	 * Author : Arslan Hassan
	 * ClipBucket v 2.0
	 */
 

	/**
	* Function used to modify comment, if there is any plugin installed
	* @param : comment
	*/
	function comment($comment)
	{
		global $Cbucket;
		$comment = nl2br($comment);
		//Getting List of comment functions
		$func_list = $Cbucket->getFunctionList('comment');
		//Applying Function
		if(count($func_list)>0)
		{
			foreach($func_list as $func)
			{
				$comment = $func($comment);
			}
		}
		return $comment;
	}


	/**
	* Function used to modify description, if there is any plugin installed
	* @param : description
	*/
	function description($description)
	{
		global $Cbucket;
		//Getting List of comment functions
		$func_list = $Cbucket->getFunctionList('description');
		//Applying Function
		if(count($func_list)>0)
		{
			foreach($func_list as $func)
			{
				$description = $func($description);
			}
		}
		return nl2br($description);
	}
	
	
	/**
	* Function used to modify title of video , channel or any object except website, 
	* if there is any plugin installed
	* @param : title
	*/
	function title($title)
	{
		global $Cbucket;
		//Getting List of comment functions
		$func_list = $Cbucket->getFunctionList('title');
		//Applying Function
		if(is_array($func_list))
		foreach($func_list as $func)
		{
			$title = $func($title);
		}
		return $title;
	}
	
	
	/**
	 * Function used to display Private Message
	 */
	function private_message($array)
	{
		global $cbpm,$Cbucket;
		$array = $array['pm'];
		$message = $array['message_content'];
		$func_list = $Cbucket->getFunctionList('private_message');
		
		//Applying Function
		if(is_array($func_list))
		foreach($func_list as $func)
		{
			if(function_exists($func))
				$message = $func($message);
		}
		echo $message;
		$cbpm->parse_attachments($array['message_attachments']);
	}
	
	
	
	/**
	 * Function used to turn tags into links
	 */
	function tags($input,$type,$sep=', ')
	{
		//Exploding using comma
		$tags = explode(',',$input);
		$count = 1;
		$total = count($tags);
		$new_tags = '';
		foreach($tags as $tag)
		{
			$params = array('name'=>'tag','tag'=>trim($tag),'type'=>$type);
			$new_tags .= '<a href="'.cblink($params).'">'.$tag.'</a>';
			if($count<$total)
				$new_tags .= $sep;
			$count++;
		}
		
		return $new_tags;
	}
	
	
	/**
	 * Function used to turn db category into links
	 */
	function categories($input,$type,$sep=',',$object_name=null)
	{
		global $cbvideo;
		switch($type)
		{
			case 'video':
			//default:
			$obj = $cbvideo;
			break;
			case 'group':
			case 'groups':
			{
				global $cbgroup;
				$obj = $cbgroup;
			}
			break;
			case 'user':
			case 'users':
			{
				global $userquery;
				$obj = $userquery;
			}
			break;
			case 'collection':
			case 'collections':
			{
				global $cbcollection;
				$obj = $cbcollection;
			}
			break;
			
			default:
			{
				global ${$object_name};
				$obj = ${$object_name};
			}
		}
		
		preg_match_all('/#([0-9]+)#/',$input,$m);
		$cat_array = array($m[1]);
		$cat_array = $cat_array[0];

		$count = 1;
		$total = count($cat_array);
		$cats = '';
		foreach($cat_array as $cat)
		{
			$cat_details = $obj->get_category($cat);
			
			$params = array('name'=>'category_search','category'=>$cat_details['category_id'],'type'=>$type);
					
			$cats .= '<a href="'.category_link($cat_details,$type).'">'.$cat_details['category_name'].'</a>';
			if($count<$total)
				$cats .= $sep;
			$count++;
		}
		
		return $cats;
	}


	/**
	 * Function used to display page
	 */
	function page($content)
	{
		global $Cbucket;
		//Getting List of comment functions
		$func_list = $Cbucket->getFunctionList('page');
		//Applying Function
		if(is_array($func_list))
		foreach($func_list as $func)
		{
			$content = $func($content);
		}
		return $content;
	}

?>