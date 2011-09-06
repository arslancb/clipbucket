<?php


	$versions = array('1','2.0.4','2.0.5','2.0.6'
	,'2.0.7','2.0.8','2.0.9','2.0.91','2.1','2.2','2.3','2.4','2.4.5','2.5','2.5.1','2.6');
		
	/**
	 * Function used to upgrade ClipBucket
	 */
	function is_upgradeable()
	{		
		global $versions;
		//checking for Installed ClipBucket file 
		if(file_exists("../includes/clipbucket.php"))
		{
			$contents = file_get_contents("../includes/clipbucket.php");
			if($contents)
			{
				preg_match("/define\(\"VERSION\",\"([\d.]+)\"\);/i",$contents,$matches);
			}
			$version = $matches[1];
		}
				
		if(in_array(@$version,$versions) && $version < VERSION)
			return $version;
		else
			return false;
	}
	
	/**
	 * ClipBucket v2.1 Installaer
	 */
 
	 //Checking if clipbucket is upgradable
	 $upgrade = is_upgradeable();
	 
?>