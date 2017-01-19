<?php
 
	/**
	* Plugin Name: HelloWorld
	* Description: Demonstrates to developers how ClipBucket plugins work
	* Author: Saqib Razzaq
	* ClipBucket Version: 2.8.1
	* Plugin Version: Version of plugin
	* @since October 3rd, 2016
	* Github: https://github.com/saqirzzq
	*/

	/*
	* This will be the base name of your plugin. It is stored
	* in constant for ease of access and to ensure it is always
	* same and doesn't get modified by anything. It will allow
	* you to dynamically handle base path and it won't matter
	* what users  name plugin's folder. Your code will always work
	* using this constant will give folder name of your plugin
	*/

	define("helloWorld_BASE",basename(dirname(__FILE__)));

	/*
	* Uses helloWorld_BASE constant to create full base path
	* for your plugin. This path will come in handy when you 
	* are trying to include external libraries, other PHP and 
	* html templates
	*/

	define("helloWorld_DIR",PLUG_DIR.'/'.helloWorld_BASE);

	/*
	* Directory constant is quite handy but you cannot use it include
	* CSS and JS or images. Hence, you need to define a constant for
	* get URL of plugin via which you can access plugin files such as
	* images, CSS and javascript
	*/

	define("helloWorld_URL",PLUG_URL.'/'.helloWorld_BASE);

	/*
	* It is strongly recomended that you use a totally different folder
	* for handling admin area section of ClipBucket's plugin because that
	* makes it much easier to handle files and results in clean overall
	* directory structure

	* This constant will provide full path to admin files and can be used 
	* to include them
	*/

	define("helloWorld_ADMIN_DIR", helloWorld_DIR.'/admin');

	/*
	* Again, we define a constant for fetching CSS and js etc files
	*/

	define("helloWorld_ADMIN_URL", helloWorld_URL.'/admin');


	/*
	* ClipBucket doesn't limit you to create one specific function and then
	* call everything from there even though we recomend that because it makes
	* things easier and much simpler to track. Here we create our main function
	* to display a hello world message to users
	*/

	function helloWorld() {
		global $userquery;
		if (!empty($userquery->username)) {
			$user = $userquery->username;
		} else {
			$user = "";
		}

		$message = "Say hello to ClipBucket! Have fun ".$user." while browsing our website";
		echo '<alert alert-info>'.$message.'</alert>';
	}

	/*
	* It creates a menu in admin area with given details. You can use any PHP file
	* to handle these pages. Just add PHP file path and do actions like you do when
	* a file is executed directly and rest will be handled by ClipBucket
	*/

	add_admin_menu('Menu Main Name','Submenu Name','file_to_execute.php', 'path_dir_where_file_exists');

	/*
	* ClipBucket uses anchors for making it easier to call functions wherever you need 
	* inside your templates. Below we register an anchor to access above helloWorld() function
	* inside html templates
	*/

	register_anchor_function('helloWorld','helloWorld');

?>