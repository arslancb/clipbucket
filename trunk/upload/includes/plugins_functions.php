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
		return $description;
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
		global $cbpm;
		$array = $array['pm'];
		echo $array['message_content'];
		$cbpm->parse_attachments($array['message_attachments']);
	}


?>