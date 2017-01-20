<?php

/**
* File : Error Handler class
* Description : Class used for handling errors inside ClipBucket. This provides an easy to track and debug
* interface for developers not only check validation of certain actions, but also helps in
* debugging issues whenever they arise. It is strongly suggested to use this class to throw
* errors. Afterall, its it for making your life easier
* @since : ClipBucket 2
* @author : Arslan Hassan, Saqib Razzaq
* @modified : { January 19th, 2017 } { Saqib Razzaq } { Added developer related functions, documented }
*/
 

class errorhandler extends ClipBucket {

	public $error_list = array();
	public $message_list = array();
	public $warning_list = array(); 
	/**
	* Function used to add new Error
	*/

	private function add_error($message=NULL,$id=NULL) {
		global $ignore_cb_errors;
		//if id is set, error will be generated from error message list
		if(!$ignore_cb_errors)
			$this->error_list[] = $message;
			$this->error_list['all_errors']['user_error']['critical_priority'][] = $message;
	}

	
	/**
	* Function usd to add new warning
	*/
	
	private function add_warning($message=NULL,$id=NULL) {
		$this->warning_list[] = $message;
		$this->user_errors['medium_priority'][] = $message;
		$this->error_list['all_errors']['user_error']['medium_priority'][] = $message;
	}
	
	/**
	* Function used to get error list
	*/
	 
	public function error_list() { 
	 	global $developer_errors;
	 	if ($developer_errors) {
	 		return $this->error_list;
	 	} else {
	 		$error_list = array();
	 		foreach ($this->error_list as $key => $error) {
	 			if (!is_array($error)) {
	 				$error_list[] = $error;
	 			}
	 		}
	 		return $error_list;
	 	}
	 }
	 
	/**
	* Function used to flush errors
	*/
	
	public function flush_error() {
		$this->error_list = '';
	}
	  
	/**
	* Functio nused to add message_list
	*/

	public function add_message($message=NULL,$id=NULL) {
		global $ignore_cb_errors;
		//if id is set, error will be generated from error message list
		if(!$ignore_cb_errors)
			$this->message_list[] = $message;
			$this->user_errors['lower_priority'][] = $message;
	}
	   
	/**
	* Function used to get message list
	*/

	public function message_list() {
		return $this->message_list;
	}
	
	/**
	* Function used to flush message
	*/
	
	public function flush_msg() {
		$this->message_list = '';
	}
	
	/**
	* Function used to flush warning
	*/

	public function flush_warning() {
		$this->warning_list = '';
	}
	
	/**
	* Function used to flush , both messages and error
	*/
	
	public function flush() {
		$this->flush_msg();
		$this->flush_error();
		$this->flush_warning();
	}
	
	/**
	* Function for throwing errors that users can see
	* @param : { string } { $message } { error message to throw }
	* @param : { string } { $type } { type of error message e.g m : message, e : error, w : warning }
	* @author : Arslan Hassan
	* 
	* @return : { array } { $this->error_list } { an array of all currently logged errors }
	*/

	function e($message = NULL, $type ='e') {
		
		switch($type) {
			case 'm':
			case 1:
			case 'msg':
			case 'message':
			$this->add_message($message);
			break;
			
			case 'e':
			case 'err':
			case 'error':
				$this->add_error($message);
			break;
			
			case 'w':
			case 2:
			case 'war':
			case 'warning':
				$this->add_warning($message);
			break;
			
			default:
				$this->error_list($message);
			break;
		}
		
		return $this->error_list;
	}

	/**
	* Adds a new error in list of all errors
	* @param : { mixed } { $error } { error to be listed }
	* @param : { string } { $state } { state of error e.g critical_priority }
	* @param : { string } { $type } { type of error e.g user_errors or developer_errors }
	* @author : Saqib Razzaq
	* @since : 19th January, 2017
	*/

	private function addAll($error, $state, $type) {
		return $this->error_list['all_errors'][$type][$state][] = $error;
	}

	/**
	* Handles developer related errors to ease up debugging process
	* @param : { mixed } { $error } { error to be listed }
	* @param : { string } { $state } { state for message e.g m : medium, l : low, c : critical }
	* @author : Saqib Razzaq
	* @since : 19th January, 2017
	*/

	public function deverr($error, $state = 'm') {
		global $developer_errors;		
		switch ($state) {
			case 'l':
				$state = 'lower_priority';
				break;
			case 'c':
				$state = 'critical_priority';
				break;
			
			default:
				$state = 'medium_priority';
				break;
		}

		if (!$developer_errors) {
			$this->addAll($error, $state, 'developer_errors');
		} else {
			$thrown_error = array();
			$back_traced = debug_backtrace();
			$calling_sect = $back_traced[0];
			$calling_file_path = $calling_sect['file'];
			$calling_file = basename($calling_file_path);
			$calling_line = $calling_sect['line'];

			$thrown_error['message'] = $error;
			$thrown_error['file_path'] = $calling_file_path;
			$thrown_error['file_name'] = $calling_file;
			$thrown_error['file_line'] = $calling_line;
			
			$this->addAll($thrown_error, $state, 'developer_errors');
			
		}
	}
	
}


?>