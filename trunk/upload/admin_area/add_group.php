<?php

require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();

// Collecting Users List
$userdata = $db->select("users","*");

// Creating Group if button is pressed
	if(isset($_POST['create_group'])) {
		$groups->createGroups($_POST);	
	}
	
	// Assigning Variables
	assign('users',$userdata);
	assign('category',$groups->get_categories());

template_files('add_group.html');
display_it();

?>