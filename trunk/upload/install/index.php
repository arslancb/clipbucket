<?php

/**
 * Installed Written by Arslan Hassan
 * @ Software : ClipBucket v2
 * @ license : CBLA
 * @ since : 2512-2009
 * @ author: Arslan Hassan
 */
 
error_reporting(E_ALL ^E_NOTICE ^E_DEPRECATED);
include("config.php");
include("checkup.php");

if(!defined("ClipBucket"))
{
 define("ClipBucket","ClipBucket - Open Source Media Sharing Script by Arslan Hassan");
 define("VERSION",NULL);
 define("STATE",NULL);
 define("REV",NULL);
 define("RELEASED",NULL);
 define("AUTHORS","ARSLAN HASSAN");
}

define('SCRIPT_URL',str_replace('/install','',GetServerURL()));
if(!defined('SCRIPT_DIR'))
{
	define('SCRIPT_DIR',str_replace('/install','',GetBaseDir()));
}


$errors = array();
$msgs = array();

$step = $_POST['step'];

if(file_exists(SCRIPT_DIR.'/files/install.loc'))
	$step = 'already_installed';
	
switch($step)
{
	case "0":
	default:
	{
		$step = 0;
	}
	break;
	case 1:
	{
		$step = 1;
	}
	break;
	case 2:
	{
		$step = 2;
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
		
		//Checking includes Directory
		if(!is_writeable("../includes"))
			$errors[] = '"/includes" directory is not writeable - Please changes its permission to 0777';
		else
			$msgs[] = '"/includes" directory is writeable';
	}
	break;
	case 3:
	{
		unset($errors);
		if(isset($_POST['check_db_connection']))
		{
			$connect = @mysql_connect(post('host'),post('dbuser'),post('dbpass'));
			if(!$connect)
				$errors[] = "Unabele to connect to datbase server : ".mysql_error();
			else
			{
				$db = @mysql_select_db(post('dbname'));
				if(!$db)
					$errors[] = "Unable to connect select databse  : ".mysql_error();
				else
					$msgs[] = "Connected to database successfully";
			}
		}
		
		$step = 3;
		
	}
	break;
	
	case 4:
	{
		$step = 4;
		
		$connect = @mysql_connect(post('host'),post('dbuser'),post('dbpass'));
		if(!$connect)
			$errors[] = "Unabele to connect to datbase server : ".mysql_error();
		else
		{
			$db = @mysql_select_db(post('dbname'));
			if(!$db)
				$errors[] = "Unable to connect select databse  : ".mysql_error();
			else
			{
				$dbconnect = 

'<?php
	/**
	* @Software : ClipBucket
	* @License : CBLA
	* @version :ClipBucket v2
	*/

	$BDTYPE = "mysql";
	//Database Host
	$DBHOST = "'.post('host').'";
	//Database Name
	$DBNAME = "'.post('dbname').'";
	//Database Username
	$DBUSER = "'.post('dbuser').'";
	//Database Password
	$DBPASS = "'.post('dbpass').'";

	require \'adodb/adodb.inc.php\';

	$db = ADONewConnection($BDTYPE);
	$db->debug = false;
	$db->charpage = \'cp_utf8\';
	$db->charset = \'utf8\';
	if(!$db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME))
	{
	exit($db->ErrorMsg());
	}
	$db->Connect($DBHOST, $DBUSER, $DBPASS, $DBNAME);
	
?>';
				$fp = fopen('../includes/dbconnect.php', 'w');
				fwrite($fp, $dbconnect);
				fclose($fp);		
				
				require '../includes/adodb/adodb.inc.php';

				$db = ADONewConnection('mysql');
				$db->debug = false;
				$db->Connect(post('host'), post('dbuser'), post('dbpass'), post('dbname'));
				
				$templine = '';
				$lines = file("cb_v2.sql");
				foreach ($lines as $line_num => $line)
				{
					if (substr($line, 0, 2) != '--' && $line != '') 
					{
						$templine .= $line;
						if (substr(trim($line), -1, 1) == ';') 
						{
							$db->Execute($templine);
							$templine = '';
						}
					}
				}
				

				$db->update("config",array("value"),array(SCRIPT_URL)," name='baseurl'");
				$db->update("config",array("value"),array(SCRIPT_DIR)," name='basedir'");
				$db->update("config",array("value"),array(RELEASED)," name='date_released'");
				$db->update("config",array("value"),array(now())," name='date_updated'");
				$db->update("config",array("value"),array(now())," name='date_installed'");
				
				copy("install.loc",SCRIPT_DIR.'/files/install.loc');
				copy("clipbucket.php",SCRIPT_DIR."/includes/clipbucket.php");

			}
		}
		
	}
	
	break;
	case "already_installed":
	{
		$step = 'ai';
	}
	
	break;
	case "update_0":
	{
		$step = 'update_0';
		if(file_exists("./../includes/dbconnect.php"))
		{
			include("./../includes/dbconnect.php");
			$msgs[] = "Connected to database";
		}else
		{
			$errors[] = "Unable to connect to find dbconnect.php :(, Please check your ./includes/dbconnect.php";
		}
		
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
		
		//Checking install Directory
		if(!is_writeable("../install"))
			$errors[] = '"/install" directory is not writeable - Please changes its permission to 0777';
		else
			$msgs[] = '"/install" directory is writeable';
		
		//Checking includes Directory
		if(!is_writeable("../includes"))
			$errors[] = '"/includes" directory is not writeable - Please changes its permission to 0777';
		else
			$msgs[] = '"/includes" directory is writeable';
			
			//Checking includes Directory
		if(!is_writeable("../includes/clipbucket.php"))
			$errors[] = '"/includes/clipbucket.php" file is not writeable - Please changes its permission to 0777';
		else
			$msgs[] = '"/includes/clipbucket.php" file is writeable';
	}
	break;
	case "update_1":
	{
		$version_arrays = 
		array('2.0.0','2.0.1','2.0.2');
		
		//Checking What sql files need to be called....
		
		include("./../includes/dbconnect.php");
		$step = 'update_1';
		//Checking for the update file
		$dbfile = "cb_v".the_version()."_".VERSION.".sql";
		$lines = file($dbfile);
		foreach ($lines as $line_num => $line)
		{
			if (substr($line, 0, 2) != '--' && $line != '') 
			{
				$templine .= $line;
				if (substr(trim($line), -1, 1) == ';') 
				{
					$db->Execute($templine);
					$templine = '';
				}
			}
		}
		
		//Special Updates for v2.0.1 or less
		if(the_version()=='2.0.1' || the_version()=='2')
		{
			//update cbhash(a general code of clipbucket that does nothing but tells clipbucket who it actually is)
			$db->update("config",array("value"),array("")," name='date_released'");
		}
		
		$db->update("config",array("value"),array(RELEASED)," name='date_released'");
		$db->update("config",array("value"),array(now())," name='date_updated'");
		$db->update("config",array("value"),array(VERSION)," name='version'");
		$db->update("config",array("value"),array(STATE)," name='type'");
		
		copy("install.loc",SCRIPT_DIR.'/files/install.loc');
		unlink(SCRIPT_DIR."/includes/clipbucket.php");
			copy("clipbucket.php",SCRIPT_DIR."/includes/clipbucket.php");
		
	}
	
}

include("steps/body.php");

?>