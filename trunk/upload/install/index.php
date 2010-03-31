<?php

/**
 * Installed Written by Arslan Hassan
 * @ Software : ClipBucket v2
 * @ license : CBLA
 * @ since : 2512-2009
 * @ author: Arslan Hassan
 */
 
define('DEBUG_LEVEL', 1);
switch(DEBUG_LEVEL)
{
    case 0:
        error_reporting(0);
        ini_set('display_errors', '0');
    case 1:
        error_reporting(E_ALL);
        ini_set('display_errors', '1');
    default:
        if(phpversion() >= '5.3.0')
        {
            error_reporting(E_ALL ^E_NOTICE ^E_DEPRECATED);
            ini_set('display_errors', '1');
        }
        else
        {
            error_reporting(E_ALL ^E_NOTICE);
            ini_set('display_errors', '1');
        }
}
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

if(file_exists(SCRIPT_DIR.'/files/install.lock'))
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
		include("perms_check.php");
	}
	break;
	case 3:
	{
		unset($errors);
		if(isset($_POST['check_db_connection']))
		{
			$connect = @mysql_connect(post('host'),post('dbuser'),post('dbpass'));
			if(!$connect)
				$errors[] = "Unable to connect to database server : ".mysql_error();
			else
			{
				$db = @mysql_select_db(post('dbname'));
				if(!$db)
					$errors[] = "Unable to connect select database  : ".mysql_error();
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
				
		$prefix = post("prefix");
		if(!$prefix || empty($prefix))
			$prefix = "cb_";

		$connect = @mysql_connect(post('host'),post('dbuser'),post('dbpass'));
		if(!$connect)
			$errors[] = "Unable to connect to database server : ".mysql_error();
		else
		{
			$db = @mysql_select_db(post('dbname'));
			if(!$db)
				$errors[] = "Unable to select database  : ".mysql_error();
			else
			{
				$dbconnect = file_get_contents('dbconnect.php');
                $dbconnect = str_replace('_DB_HOST_', $_POST['host'], $dbconnect);
                $dbconnect = str_replace('_DB_NAME_', $_POST['dbname'], $dbconnect);
                $dbconnect = str_replace('_DB_USER_', $_POST['dbuser'], $dbconnect);
                $dbconnect = str_replace('_DB_PASS_', $_POST['dbpass'], $dbconnect);
                $dbconnect = str_replace('_TABLE_PREFIX_', $prefix, $dbconnect);

                file_put_contents(SCRIPT_DIR.'/includes/dbconnect.php',$dbconnect);
				
				require '../includes/adodb/adodb.inc.php';
				require '../includes/classes/category.class.php';
				require '../includes/classes/user.class.php';

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
							$templine = preg_replace("/{tbl_prefix}/",$prefix,$templine);
							$db->Execute($templine);
							$templine = '';
						}
					}
				}
				

				$db->update($prefix."config",array("value"),array(SCRIPT_URL)," name='baseurl'");
				$db->update($prefix."config",array("value"),array(SCRIPT_DIR)," name='basedir'");
				$db->update($prefix."config",array("value"),array(RELEASED)," name='date_released'");
				$db->update($prefix."config",array("value"),array(now())," name='date_updated'");
				$db->update($prefix."config",array("value"),array(now())," name='date_installed'");
				$db->update($prefix."config",array("value"),array(VERSION)," name='version'");
				$db->update($prefix."config",array("value"),array(STATE)," name='type'");
				
				$userquery = new userquery();
				$sess_key = $userquery->create_session_key($_COOKIE['PHPSESSID'],'admin');
				$sess_code = $userquery->create_session_code();
				
				$query_field[] = "doj";
				$query_val[] = now();
				
				$query_field[] = "user_session_key";
				$query_val[] = $sess_key;
				
				$query_field[] = "user_session_code";
				$query_val[] = $sess_code;
			
				$db->update($prefix."users",$query_field,$query_val," username='admin' ");

                file_put_contents(SCRIPT_DIR.'/files/install.lock',time());
                file_put_contents(SCRIPT_DIR.'/includes/clipbucket.php',file_get_contents('clipbucket.php'));
                unlink(SCRIPT_DIR.'/files/temp/install.me');

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
		
	//Checking includes Directory
	if(!is_writeable("../includes/dbconnect.php"))
		$errors[] = '"/includes/dbconnect.php" file is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/includes/dbconnect.php" file is writeable';
		
	//Checking includes Directory
	if(!is_writeable("../includes/clipbucket.php"))
		$errors[] = '"/includes/clipbucket.php" file is not writeable - Please changes its permission to 0777';
	else
		$msgs[] = '"/includes/clipbucket.php" file is writeable';
		
		
		include("perms_check.php");
	}
	break;
	case "update_1":
	{
		$version_arrays = 
		array('2.0.0','2.0.1','2.0.2');
		
		//Checking What sql files need to be called....
		
		$prefix = post("prefix");
		if(!$prefix || empty($prefix))
			$prefix = "cb_";
			
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
					$templine = preg_replace("/{tbl_prefix}/",$prefix,$templine);
					$db->Execute($templine);
					$templine = '';
				}
			}
		}
		
		//Special Updates for v2.0.1 or less
		if(the_version()=='2.0.1' || the_version()=='2')
		{
			//Creating User Sessions and keys
			$query = mysql_query("SELECT * FROM ".$prefix."users WHERE userid <> '1' ");
			while($data = mysql_fetch_array($query))
			{
				$sess_code = rand(10000,99999);
				$newkey = $_COOKIE['PHPSESSID'].RandomString(10);
				$sess_key = md5($newkey);
				mysql_query("UPDATE ".$prefix."users SET user_session_key='$sess_key'
							, user_session_code ='$sess_code' WHERE userid='".$data['userid']."'");
			}
		}

		//Rewriting Database File
		if(the_version()<'2.0.6')
		{
						//update cbhash(a general code of clipbucket that does nothing but tells clipbucket who it actually is)
			$db->update($prefix."config",array("value"),array("PGRpdiBhbGlnbj0iY2VudGVyIj48IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPg0KUG93ZXJlZCBieSA8YSBocmVmPSJodHRwOi8vY2xpcC1idWNrZXQuY29tLyI+Q2xpcEJ1Y2tldDwvYT4gJXMgfCA8YSBocmVmPSJodHRwOi8vY2xpcC1idWNrZXQuY29tL2Fyc2xhbi1oYXNzYW4iPkFyc2xhbiBIYXNzYW48L2E+DQo8IS0tIERvIG5vdCByZW1vdmUgdGhpcyBjb3B5cmlnaHQgbm90aWNlIC0tPjwvZGl2Pg==")," name='cbhash'");


			$dbconnect = file_get_contents('dbconnect.php');
            $dbconnect = str_replace('_DB_HOST_', $_POST['host'], $dbconnect);
            $dbconnect = str_replace('_DB_NAME_', $_POST['dbname'], $dbconnect);
            $dbconnect = str_replace('_DB_USER_', $_POST['dbuser'], $dbconnect);
            $dbconnect = str_replace('_DB_PASS_', $_POST['dbpass'], $dbconnect);
            $dbconnect = str_replace('_TABLE_PREFIX_', $prefix, $dbconnect);

            file_put_contents(SCRIPT_DIR.'/includes/dbconnect.php',$dbconnect);

		}
		
		$prefix = TABLE_PREFIX;
		
		//ClipBucket Stats Fix
		if(the_version()<'2.0.5')
		{
			$results = $db->select($prefix."stats","*");
			if(is_array($results))
			foreach($results as $result)
			{
				$vid_stats = $result['video_stats'];
				$user_stats = $result['user_stats'];
				$group_stats = $result['group_stats'];
				
				if(substr($vid_stats,0,7)=='|no_mc|')
					$vid_stats = substr($vid_stats,7,strlen($vid_stats));
					
				if(substr($user_stats,0,7)=='|no_mc|')
					$user_stats = substr($user_stats,7,strlen($user_stats));
					
				if(substr($group_stats,0,7)=='|no_mc|')
					$group_stats = substr($group_stats,7,strlen($group_stats));
					
				$db->update($prefix."stats",array('video_stats','user_stats','group_stats'),
										array("|no_mc|".$vid_stats,"|no_mc|".$user_stats,"|no_mc|".$group_stats)," stat_id  ='".$result['stat_id']."' ");
					
			}
		}

		
		$db->update($prefix."config",array("value"),array(RELEASED)," name='date_released'");
		$db->update($prefix."config",array("value"),array(now())," name='date_updated'");
		$db->update($prefix."config",array("value"),array(VERSION)," name='version'");
		$db->update($prefix."config",array("value"),array(STATE)," name='type'");
		
        file_put_contents(SCRIPT_DIR.'/files/install.lock',time());
        file_put_contents(SCRIPT_DIR.'/includes/clipbucket.php',file_get_contents('clipbucket.php'));
		
	}
	break;
	//Upgrading
	case "upgrade_0":
	{
		$step = 'upgrade_0';
		include("perms_check.php");
		//Checking dbconnect.php Directory
		if(!is_writeable("../includes/dbconnect.php"))
			$errors[] = '"/includes/dbconnect.php" file is not writeable - Please changes its permission to 0777';
		else
			$msgs[] = '"/includes/dbconnect.php" file is writeable';
		
	}	
	break;
	case "upgrade_1":
	{
		$step = 'upgrade_1';
		if(!isset($_POST['check_db_connection']))
		{
			$array = upgrade_able();
			$_POST = $array;
		}else
		{
			$connect = @mysql_connect(post('host'),post('dbuser'),post('dbpass'));
			if(!$connect)
				$errors[] = "Unabele to connect to database server : ".mysql_error();
			else
			{
				$db = @mysql_select_db(post('dbname'));
				if(!$db)
					$errors[] = "Unable to connect select databse  : ".mysql_error();
				else
					$msgs[] = "Connected to database successfully";
			}
		}
	}
	break;
	case "upgrade_2";
	{
		//Re Write Database File
		$step = 'upgrade_2';
		require '../includes/adodb/adodb.inc.php';

		$db = ADONewConnection('mysql');
		$db->debug = false;
		$db->Connect(post('host'), post('dbuser'), post('dbpass'), post('dbname'));


		//Checking What sql files need to be called....
		$prefix = post("prefix");
		if(!$prefix || empty($prefix))
			$prefix = "cb_";


        $dbconnect = file_get_contents('dbconnect.php');
        $dbconnect = str_replace('_DB_HOST_', $_POST['host'], $dbconnect);
        $dbconnect = str_replace('_DB_NAME_', $_POST['dbname'], $dbconnect);
        $dbconnect = str_replace('_DB_USER_', $_POST['dbuser'], $dbconnect);
        $dbconnect = str_replace('_DB_PASS_', $_POST['dbpass'], $dbconnect);
        $dbconnect = str_replace('_TABLE_PREFIX_', $prefix, $dbconnect);

        file_put_contents(SCRIPT_DIR.'/includes/dbconnect.php',$dbconnect);
				
		$templine = '';
		$lines = file("cb_v2.sql");
		foreach ($lines as $line_num => $line)
		{
			if (substr($line, 0, 2) != '--' && $line != '') 
			{
				$templine .= $line;
				if (substr(trim($line), -1, 1) == ';') 
				{
					$templine = preg_replace("/{tbl_prefix}/",$prefix,$templine);
					$db->Execute($templine);
					$templine = '';
				}
			}
		}
		
		$db->update($prefix."config",array("value"),array(SCRIPT_URL)," name='baseurl'");
		$db->update($prefix."config",array("value"),array(SCRIPT_DIR)," name='basedir'");
		$db->update($prefix."config",array("value"),array(RELEASED)," name='date_released'");
		$db->update($prefix."config",array("value"),array(now())," name='date_updated'");
		$db->update($prefix."config",array("value"),array(now())," name='date_installed'");
		$db->update($prefix."config",array("value"),array(VERSION)," name='version'");
		$db->update($prefix."config",array("value"),array(STATE)," name='type'");

		$msgs[] = "Database has been imported successfully";
		
	}
	break;
}

include("steps/body.php");

?>