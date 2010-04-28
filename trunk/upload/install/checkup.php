<?php

/**
 * Function : Checks all requirments for ClipBucket
 * Author : Frank White, Arslan Hassan
 * Software : CLipBucket
 * Since : 25-12-2009
 */
 
 	define("BASEDIR","..");
	function is_files_writeable($subdir=NULL)
    {
		if(!$subdir)
		{
			if(!is_writable(BASEDIR.'/files'))
			{
				return false;
			}else
				return true;
		}else
		{
			if(!is_writable(BASEDIR.'/files/'.$subdir))
			{
				return false;
			}else
				return true;
		}
    }

    function is_images_writeable($subdir=NULL)
    {
		if(!$subdir)
		{
			if(!is_writable(BASEDIR.'/images'))
			{
				return false;
			}else
				return true;
		}else
		{
			if(!is_writable(BASEDIR.'/images/'.$subdir))
			{
				return false;
			}else
				return true;
		}
    }

    function FilterArray($value)
    {
		if(preg_match('/share/i', $value) || preg_match('/:/', $value) ||
		preg_match('/etc/', $value)|| preg_match('/lib/', $value) ||
		preg_match('/include/', $value) || preg_match('/src/', $value)
		|| preg_match('/man/', $value))
		{
		    return false;
		}
		return true;
    }

    function LocCheck($bin)
    {
	    $new        = array();
		$check      = whereis($bin);
		if($check)
		{
		    $check      = explode(' ',$check);
		    $filtered   = array_filter($check,'FilterArray');
		    $filtered   = array_merge((array)$new, (array)$filtered);
		    return $filtered;
		}
		$check  = array('Not Installed');
		return $check;
    }


    function is_exec_enabled()
    {
		$safe_mode          = ini_get('safe_mode');
		$safe_mode_execdir  = ini_get('safe_mode_execdir');
		if(!empty($safe_mode_execdir))
		{
			return false;
		}
        else
        {
		    return true;
        }
    }


    function check_upload_size_valid($mb,$value)
    {
		$check  = ini_get($value);
		if(preg_match('/M/i', $check))
		{
			$check = str_replace('M','',$check);
			if($mb > $check)
			{
				return 1;
			}
		}
		elseif(preg_match('/G/i', $check))
		{
			$check = str_replace('G','',$check);
			$check = $check * 1024;
			if($mb > $check)
			{
				return 1;
			}
		}
		elseif(preg_match('/K/i', $check))
		{
			$mb = $mb * 8192;
			$check = str_replace('K','',$check);
			if($mb > $check)
			{
				return 1;
			}
		}
		else
		{
			$mb = $mb * 1048576;
			if($mb > $check)
			{
				return 1;
			}
		}
    }
	
	
	function the_version()
	{
		$thefile = '../includes/clipbucket.php';
		if(file_exists('../includes/clipbucket.php'))
		{
			$file = file_get_contents($thefile);
			//Get Version
			preg_match("/define\(\"VERSION\",\"(.*)\"\)/",$file,$matches);
			$version = $matches[1];
			return $version;

		}else
		{
			$last_ver = upgrade_able();
			if($last_ver)
				return $last_ver['version'];
			return false;
		}
	}
	
	/**
	 * Function used to check weather current ClipBucket is updateable or not
	 */
	function update_able()
	{
		$thefile = '../includes/clipbucket.php';
		if(file_exists('../includes/clipbucket.php'))
		{
			$version = the_version();
			if($version && VERSION > $version)
				return true;
			else
				return false;
		}else
			return false;
	}
	
	/**
	 * Function used to check weather ClipBucket is updateable or not
	 */
	function upgrade_able()
	{
		$dbconnect = "../includes/dbconnect.php";
		//checking for $dbconnect
		if(file_exists($dbconnect))
		{
			include($dbconnect);
			if($db)
			{
				if(defined("TABLE_PREFIX"))
					$tbpre= TABLE_PREFIX;
				//Checking weather there is any evidence of prior installation or not
				$query = mysql_query("SELECT * FROM ".$tbpre."config WHERE name='version' ");
				@$data = mysql_fetch_array($query);
				$version = substr($data['value'],0,3);
				if($version=='1.7')
				{
					$array['version'] = $version;
					$array['host'] = $DBHOST;
					$array['dbname'] = $DBNAME;
					$array['dbuser'] = $DBUSER;
					$array['dbpass'] = $DBPASS ;
					return $array;
				}
			}
		}
		
		return false;		
	}
?>