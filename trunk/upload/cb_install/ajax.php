<?php
define("THIS_PAGE","cb_install");
include('clipbucket.php');

/**
 * ClipBucket v2.1 Installat Ajax
 */
 $mode = $_POST['mode'];
 
 if($mode!='finish_upgrade')
 include("functions.php");
 include("upgradeable.php");
 
 
 if($mode=='dataimport')
 {
	 $result = array();
	 
	 $dbhost = $_POST['dbhost'];
	 $dbpass = $_POST['dbpass'];
	 $dbuser = $_POST['dbuser'];
	 $dbname = $_POST['dbname'];
	 
	 $cnnct = @mysql_connect($dbhost,$dbuser,$dbpass);
	 
	if(!$cnnct)
	 	$result['err'] = "<span class='alert'>Unable to connect to mysql : ".mysql_error().'</span>';
	else
	{
		$dbselect = @mysql_select_db($dbname,$cnnct);
		if(!$dbselect)
			$result['err'] = "<span class='alert'>Unable to select database : ".mysql_error().'</span>';
	}
	echo json_encode($result);
 }
 
 
 if($mode=='register')
 {
	 $version = @curl_version();
	 if($version)
	 {
	    $website = $_POST['website'];
		$email = $_POST['email'];
		$ch =  curl_init('http://clip-bucket.com/register.php');
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
		curl_setopt($ch,CURLOPT_POST,true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,array('website'=>$website,'email'=>$email));
		$data = curl_exec($ch);
		echo $data;
	 }
 }
 
 if($mode=='adminsettings')
 {
	 $dbhost = $_POST['dbhost'];
	 $dbpass = $_POST['dbpass'];
	 $dbuser = $_POST['dbuser'];
	 $dbname = $_POST['dbname'];
	 $dbprefix = $_POST['dbprefix'];
	 
	 $cnnct = @mysql_connect($dbhost,$dbuser,$dbpass);
	  
	if(!$cnnct)
	 	$result['err'] = "<span class='alert'>Unable to connect to mysql : ".mysql_error().'</span>';
	else
	{
		$dbselect = @mysql_select_db($dbname,$cnnct);
		if(!$dbselect)
			$result['err'] = "<span class='alert'>Unable to select database : ".mysql_error().'</span>';
	}
	
	if(@$result['err'])
	{
		exit(json_encode($result));
	}
	 
	 $step = $_POST['step'];
	 $files = array
	 (
		 'structure' 		=> 'structure.sql',
		 'configs'			=> 'configs.sql',
		 'ads_placements'	=> 'ads_placements.sql',
		 'countries'		=> 'countries.sql',
		 'email_templates'	=> 'email_templates.sql',
		 'pages'			=> 'pages.sql',
		 'user_levels'		=> 'user_levels.sql'
	 );	 
	 
	 $next = false;
	 if(array_key_exists($step,$files) && $step)
	 {
		 $total = count($files);
		 $count = 0;
		 foreach($files as $key => $file)
		 {
			 $count++;
			 if($next)
			 {
				 $next = $key;
				 break;
			 }
			 if($key==$step)
			 {
				$current = $step;
				if($count<$total)
				$next = true;
			 }
		 }
		 
		 if(!$next)
		 {
		 	 $next = 'add_categories';
			 $next_msg = 'Creating categories';
		 }
		 
		 if($current)
		 {
			$lines = file(BASEDIR."/cb_install/sql/".$files[$current]);
			foreach ($lines as $line_num => $line)
			{
				if (substr($line, 0, 2) != '--' && $line != '') 
				{
					@$templine .= $line;
					if (substr(trim($line), -1, 1) == ';') 
					{
						@$templine = preg_replace("/{tbl_prefix}/",$dbprefix,$templine);
						mysql_query($templine);
						$templine = '';
					}
				}
			}
		 }
			
		 $return = array();
		 $return['msg'] = '<div class="ok green">'.$files[$current].' has been imported successfully</div>';
		 
		 if(@$files[$next])
		 $return['status'] = 'importing '.$files[$next];
		 else
		 $return['status'] = $next_msg;
		 
		 $return['step'] = $next;
	 }else
	 {
		 switch($step)
		 {
			 case 'add_categories':
			 {
				$lines = file(BASEDIR."/cb_install/sql/categories.sql");
				foreach ($lines as $line_num => $line)
				{
					if (substr($line, 0, 2) != '--' && $line != '') 
					{
						@$templine .= $line;
						if (substr(trim($line), -1, 1) == ';') 
						{
							@$templine = preg_replace("/{tbl_prefix}/",$dbprefix,$templine);
							mysql_query($templine);
							$templine = '';
						}
					}
				}
				 $return['msg'] = '<div class="ok green">Videos, Users, Groups and Collections Categories have been created</div>';
				 $return['status'] = 'adding admin account..';
			 	 $return['step'] = 'add_admin';
			 }
			 break;
			 
			 case "add_admin":
			 {
				
				 
				$lines = file(BASEDIR."/cb_install/sql/add_admin.sql");
				foreach ($lines as $line_num => $line)
				{
					if (substr($line, 0, 2) != '--' && $line != '') 
					{
						@$templine .= $line;
						if (substr(trim($line), -1, 1) == ';') 
						{
							@$templine = preg_replace("/{tbl_prefix}/",$dbprefix,$templine);
							mysql_query($templine);
							$templine = '';
						}
					}
				}
				 $return['msg'] = '<div class="ok green">Admin account has been created</div>';
				 $return['status'] = 'Creating config files...';
			 	 $return['step'] = 'create_files';
			 }
			 break;
			 
			 case "create_files":
			 {
				 mysql_close($cnnct);
				 $dbconnect = file_get_contents(BASEDIR.'/cb_install/dbconnect.php');
                 $dbconnect = str_replace('_DB_HOST_', $dbhost, $dbconnect);
                 $dbconnect = str_replace('_DB_NAME_', $dbname, $dbconnect);
                 $dbconnect = str_replace('_DB_USER_', $dbuser, $dbconnect);
                 $dbconnect = str_replace('_DB_PASS_', $dbpass, $dbconnect);
                 $dbconnect = str_replace('_TABLE_PREFIX_', $dbprefix, $dbconnect);

                 $fp = fopen(BASEDIR.'/includes/dbconnect.php', 'w');
				 fwrite($fp, $dbconnect);
				 fclose($fp);
				 	
				 copy(BASEDIR."/cb_install/clipbucket.php",BASEDIR."/includes/clipbucket.php");
							
				 $return['msg'] = '<div class="ok green">DBconnect and Clipbucket files have been created</div>';
				 $return['status'] = 'forwarding you to admin settings..';
			 	 $return['step'] = 'forward';
			 }
			 break;			  
		 }
		
	 }
	 
	 echo json_encode($return);
 }
 
 if($mode=='finish_upgrade')
 {
	 chdir("..");
	 $configIncluded = true;
	 require_once 'includes/config.inc.php';
	 chdir("cb_install");
	 include("functions.php");
	 $files = getUpgradeFiles();
	 
	 if($files)
	 {
		 $step = $_POST['step'];
		 if($step=='upgrade')
		 	$index = 0;
		 else
		 	$index = $step;
		
		$total = count($files);
		
		if($index >= $total)
		{
			$return['msg'] = '<div class="ok green">Upgrade clipbucket</div>';
			$return['status'] = 'finalizing upgrade...';
			$return['step'] = 'forward';
		}
		
		if($index+1 >= $total)
			$next = 'forward';
		else
			$next = $index+1;
		
		if($next=='forward')
			$status = 'finalizing upgrade...';
		 else
		 	$status = 'Importing upgrade_'.$files[$next].'.sql';
		 
		 $sqlfile = BASEDIR."/cb_install/sql/upgrade_".$files[$index].".sql";
		 if(file_exists($sqlfile))
		 {
			 
			 $lines = file($sqlfile);
			 foreach ($lines as $line_num => $line)
			 {
				if (substr($line, 0, 2) != '--' && $line != '') 
				{
					@$templine .= $line;
					if (substr(trim($line), -1, 1) == ';') 
					{
						@$templine = preg_replace("/{tbl_prefix}/",TABLE_PREFIX,$templine);
						$templine;
						mysql_query($templine);
						$templine = '';
					}
				}
			 }
		 }
		 
		 //There were problems with email templates with version lower than 2.4
		 //therefore we are dumping all existing email templates and re-import them
		 if($upgrade<'2.4.5')
		 {
			 mysql_query('TRUNCATE '.TABLE_PREFIX.'email_templates');
			 //Dumping
			 $sqlfile = BASEDIR."/cb_install/sql/email_templates.sql";
			 if(file_exists($sqlfile))
			 {
				 
				 $lines = file($sqlfile);
				 foreach ($lines as $line_num => $line)
				 {
					if (substr($line, 0, 2) != '--' && $line != '') 
					{
						@$templine .= $line;
						if (substr(trim($line), -1, 1) == ';') 
						{
							@$templine = preg_replace("/{tbl_prefix}/",TABLE_PREFIX,$templine);
							$templine;
							mysql_query($templine);
							$templine = '';
						}
					}
				 }
			 }
		 }
		 //Dumping finished
		 
		 $return['msg'] = '<div class="ok green">upgrade_'.$files[$index].'.sql has been imported</div>';
		 $return['status'] = $status;
		 $return['step'] = $next;
	 
	 }else
	 {
		$return['msg'] = '<div class="ok green">Upgrade clipbucket</div>';
		$return['status'] = 'finalizing upgrade...';
		$return['step'] = 'forward';
	 }	
	 
	 echo json_encode($return);
 }

?>