<?php
/**
 * ClipBucket v2.1 Installat Ajax
 */
 
 include("functions.php");
 
 $mode = $_POST['mode'];
 
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

?>