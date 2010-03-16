<?php
/**
 **************************************************************************************************
 This Source File Is Written For ClipBucket, Please Read its End User License First and Agree its
 Terms of use at http://www.opensource.org/licenses/attribution.php
 **************************************************************************************************
 Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
 **************************************************************************************************
 **/
 
//Setting Class For Modules

class Modules
{

	//Used To Get Details Of Modules
	function GetModuleDetails($module){
	$query = mysql_query("SELECT * FROM modules WHERE module_name = '".$module."'");
	$data = mysql_fetch_array($query);
	return $data;
	}
	
	//Function Used To Check Weather Module Name Is Valid or Not
	function isValidModule($name){
      $pattern = "^^[_a-z0-9-]+$";
      if (eregi($pattern, $name)){
         return true;
      	}else {
         return false;
      }   
   }
	
	//Function Used To Activate Moodule
	
	function ActivateModule($module){
		$data = $this->GetModuleDetails($module);
		if(!empty($data['module_name'])){
		mysql_query("UPDATE modules SET active = '".yes."' WHERE module_name = '".$module."'");
		$msg = "Module Has Been Activated";
		}else{
		$msg = "Module Doesnt Exist";
		}
	return $msg ;
	}
	
	//Function Used To DeActivate Moodule
	
	function DeActivateModule($module){
		$data = $this->GetModuleDetails($module);
		if(!empty($data['module_name'])){
		mysql_query("UPDATE modules SET active = '".no."' WHERE module_name = '".$module."'");
		$msg = "Module Has Been DeActivated";
		}else{
		$msg = "Module Doesnt Exist";
		}
	return $msg;
	}
	
	//Function Used To Delete Module
	function DeleteModule($module){
	$data = $this->GetModuleDetails($module);
		if(!empty($data['module_name'])){
		mysql_query("DELETE FROM modules WHERE module_name = '".$module."'");
		$msg = "Module Has Been Deleted";
		}else{
		$msg = "Sorry File Doesnt Exist";
		}
	}
	
	//Function Used To Add Module
	function AddModule(){
	$name = substr($_POST['module_file'], 0, strrpos($_POST['module_file'], '.instructions'));
	$file = mysql_clean($_POST['module_file']);
	$active = mysql_clean($_POST['active']);
	
	if(empty($name)){
		$msg[] = "Module Name Is Empty";
		}elseif(!$this->isValidModule($name)){
		$msg[] = "Module Name Is Not Valid";
		}
	$data = $this->GetModuleDetails($name);
	if(!empty($data['module_name'])){
	$msg[] = "Module Already Exists";
	}
	if(empty($file)){
	$msg[] = "No File Selected";
	}
	if(!file_exists(MODULEDIR.'/'.$file)){
	$msg[] = "Specified File Does not Exists";
	}
		if(empty($msg)){	
		mysql_query("INSERT INTO modules(module_name,module_file,active)VALUES('".$name."','".$file."','".$active."')");
		$msg="Module Has Been Added";
		}
	return $msg;
	}
	
}

$Modules = new Modules();

	//Ading and Displaying Module
	
	$moduleQuery = @mysql_query("SELECT * FROM modules  WHERE active ='yes'");
	while($moduleData = @mysql_fetch_array($moduleQuery)){
		$module = $Modules->GetModuleDetails($moduleData['module_name']);
		if($module['active'] == 'yes'){
			include(MODULEDIR.'/'.$moduleData['module_file']);
			$module_list[] = $module;
		}
	}
	
	$Cbucket->moduleList = $module_list;
	
?>