<?php

	//Checking Files Permissions
	if(!is_files_writeable())
		$errors[] = '"/files" directory is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/files" directory is writeable';
	
	//checking for sub dirs
	$subdirs = array('conversion_queue','logs','original','temp','thumbs','videos','mass_uploads','mass_uploads');
	foreach($subdirs as $subdir)
	{
		if(!is_files_writeable($subdir))
			$errors[] = '"/files/'.$subdir.'" directory is not writeable - Please changes its permission to 0777';
		else
			$msgs[] = '"/files/'.$subdir.'" directory is writeable';
	}

	if(!is_writeable("../files/temp/install.me"))
		$errors[] = '"/files/temp/install.me" is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/files/temp/install.me" is writeable';

	//Checking Images
	if(!is_images_writeable())
		$errors[] = '"/images" directory is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/images" directory is writeable';
	//checking for sub dirs
	$subdirs = array('avatars','backgrounds','category_thumbs','groups_thumbs');
	
	foreach($subdirs as $subdir)
	{
		if(!is_images_writeable($subdir))
			$errors[] = '"/images/'.$subdir.'" directory is not writeable - Please changes its permission to 0777';
		else
			$msgs[] = '"/images/'.$subdir.'" directory is writeable';
	}
	
	//Checking Cache Directory
	if(!is_writeable("../cache"))
		$errors[] = '"/cache" directory is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/cache" directory is writeable';
		
	//Checking install Directory
	if(!is_writeable("../install"))
		$errors[] = '"/install" directory is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/install" directory is writeable';

//	//Checking includes Directory
//	if(!is_writeable("../includes/clipbucket.php"))
//		$errors[] = '"/includes/clipbucket.php" is not writeable - Please changes its permission to 0777';
//	else
//		$msgs[] = '"/includes/clipbucket.php" is writeable';
//	
//	//Checking includes Directory
//	if(!is_writeable("../includes/dbconnect.php"))
//		$errors[] = '"/includes/dbconnect.php" is not writeable - Please changes its permission to 0777';
//	else
//		$msgs[] = '"/includes/dbconnect.php" is writeable';

	//Checking includes Directory
	if(!is_writeable("../includes"))
		$errors[] = '"/includes" directory is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/includes" directory is writeable';
			
?>