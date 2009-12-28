<?php

/**
 * Installed Written by Arslan Hassan
 * @ Software : ClipBucket v2
 * @ license : CBLA
 * @ since : 2512-2009
 * @ author: Arslan Hassan
 */
 
error_reporting(E_ALL ^E_NOTICE ^E_DEPRECATED);
include("../includes/clipbucket.php");
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
	case 1:
	default:
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
		$subdirs = array('conversion_queue','logs','original','temp','thumbs','videos');
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
				
				copy("install.loc",SCRIPT_DIR.'/files/install.loc');

			}
		}
	}
	
	break;
	case "already_installed":
	{
		$step = 'ai';
	}
}

include("steps/body.php");

?>