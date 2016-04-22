<?php
	
	/**
	* File: Common Ajax
	* Description: ClipBucket default ajax file has gone too big. Time to make things a little cleaner
	* @since: 4th April, 2016, ClipBucket 2.8.1 
	* @author: Saqib Razzaq
	* @modified: 8th April, 2016
	*/

	require '../includes/config.inc.php';
	if (isset($_POST['mode'])) {
		$mode = $_POST['mode'];
		global $db;
		switch ($mode) {
			case 'emailExists':
				$email = $_POST['email'];
			    $check = $db->select(tbl('users'),"email"," email='$email'");
			    if (!$check) {
			    	echo "NO";
			    }
				break;

			case 'userExists':
				$username = $_POST['username'];
			    $check = $db->select(tbl('users'),"username"," username='$username'");
			    if (!$check) {
			    	echo "NO";
			    }
				break;
			
			default:
				# code...
				break;
		}
	}
?>