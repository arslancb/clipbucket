<?php
/**
 * Very basic error handler
 */
 

class EH extends ClipBucket
{
	var $error_list = array();
	var $message_list = array();
	var $warning_list = array();
	
	/**
	 * A CONSTRUCTOR
	 */
	function error_handler()
	{
		
	}
	
	/**
	 * Function used to add new Error
	 */
	 
	function add_error($message=NULL,$id=NULL)
	{
		global $ignore_cb_errors;
		//if id is set, error will be generated from error message list
		if(!$ignore_cb_errors)
		$this->error_list[] = $message;
	}

	
	/**
	 * Function usd to add new warning
	 */
	function add_warning($message=NULL,$id=NULL)
	{
		$this->warning_list[] = $message;
	}
	
	/**
	 * Function used to get error list
	 */
	 
	 function error_list()
	 { 
	 	return $this->error_list;
	 }
	 
	 /**
	  * Function used to flush errors
	  */
	  function flush_error()
	  {
		  $this->error_list = '';
	  }
	  
	/**
	 * Functio nused to add message_list
	 */
	function add_message($message=NULL,$id=NULL)
	{
		global $ignore_cb_errors;
		//if id is set, error will be generated from error message list
		if(!$ignore_cb_errors)
		$this->message_list[] = $message;
	}
	   
	/**
	 * Function used to get message list
	 */
	function message_list()
	{
		return $this->message_list;
	}
	
	/**
	 * Function used to flush message
	 */
	function flush_msg()
	{
		$this->message_list = '';
	}
	
	/**
	 * Function used to flush warning
	 */
	function flush_warning()
	{
		$this->warning_list = '';
	}
	
	/**
	 * Function used to flush , both message and error
	 */
	function flush()
	{
		$this->flush_msg();
		$this->flush_error();
		$this->flush_warning();
	}
	
	/**
	 * Function used to add error or either message using simple
	 * and small object
	 * @param : message, @param :type,@param:id
	 */
	function e($message=NULL,$type='e',$id=NULL)
	{
	
		switch($type)
		{
			case 'm':
			case 1:
			case 'msg':
			case 'message':
			$this->add_message($message,$id);
			break;
			
			case 'e':
			case 'err':
			case 'error':
			default:
			$this->add_error($message,$id);
			break;
			
			case 'w':
			case 2:
			case 'war':
			case 'warning':
			{
				$this->add_warning($message,$id);
			}
			break;
			
			default:
			$this->error_list($message,$id);
			break;
		}
		
		return $message;
	}
	
}


?>