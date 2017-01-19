<?php
/**
 * Very basic error handler
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
	
	public static function flush_error() {
		$this->error_list = '';
	}
	  
	/**
	* Functio nused to add message_list
	*/

	public static function add_message($message=NULL,$id=NULL) {
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
	
	public static function flush_msg() {
		$this->message_list = '';
	}
	
	/**
	* Function used to flush warning
	*/

	public static function flush_warning() {
		$this->warning_list = '';
	}
	
	/**
	* Function used to flush , both message and error
	*/
	
	public static function flush() {
		$this->flush_msg();
		$this->flush_error();
		$this->flush_warning();
	}
	
	/**
	* Function used to add error or either message using simple
	* and small object
	* @param : message, @param :type,@param:id
	*/

	function e($message=NULL,$type='e',$id=NULL) {
		
		switch($type) {
			case 'm':
			case 1:
			case 'msg':
			case 'message':
			$this->add_message($message,$id);
			break;
			
			case 'e':
			case 'err':
			case 'error':
				$this->add_error($message,$id);
			break;
			
			case 'w':
			case 2:
			case 'war':
			case 'warning':
				$this->add_warning($message,$id);
			break;
			
			default:
				$this->error_list($message,$id);
			break;
		}
		
		return $message;
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