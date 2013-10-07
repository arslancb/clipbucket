<?php

class CBTemplate {

	/**
	 * Function used to set Smarty Functions
	 */
   function CBTemplate() {
        global $Smarty;
        if (!isset($Smarty)) {
            $Smarty = new Smarty;
        }
    }

    function create() {
        global $Smarty;
        $Smarty = new Smarty();
        $Smarty->compile_check = true;
        $Smarty->debugging = false;
        $Smarty->template_dir = BASEDIR."/styles";
        $Smarty->compile_dir  = BASEDIR."/cache";

        return true;
    }
    
    function setCompileDir($dir_name) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        $Smarty->compile_dir = $dir_name;
    }

    function setType($type) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        $Smarty->type = $type;
    }

    function assign($var, $value) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        $Smarty->assign($var, $value);
    }

    function setTplDir($dir_name = null) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        if (!$dir_name) {
            $Smarty->template_dir = BASEDIR."/styles/clipbucketblue";
        } else {
            $Smarty->template_dir = $dir_name;
        }
    }

    function setModule($module) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        $Smarty->theme = $module;
        $Smarty->type  = "module";
    }

    function setTheme($theme) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        $Smarty->template_dir = BASEDIR."/styles/" . $theme;
        $Smarty->compile_dir  = BASEDIR."/styles/" . $theme;
        $Smarty->theme        = $theme;
        $Smarty->type         = "theme";
    }

    function getTplDir() {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        return $Smarty->template_dir;
    }

    function display($filename) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        $Smarty->display($filename);
    }

    function fetch($filename) {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        return $Smarty->fetch($filename);
    }
    
    function getVars() {
        global $Smarty;
        if (!isset($Smarty)) {
            CBTemplate::create();
        }
        return $Smarty->get_template_vars();
    }
	
	/**
	 * Function used to get available templates
	 */
	function get_templates()
	{
		$dir = STYLES_DIR;
		//Scaning Dir
		$dirs = scandir($dir);
		foreach($dirs as $tpl)
		{
			if(substr($tpl,0,1)!='.')
				$tpl_dirs[] = $tpl;
		}
		//Now Checking for template template.xml
		$tpls = array();
		foreach($tpl_dirs as $tpl_dir)
		{
			$tpl_details = CBTemplate::get_template_details($tpl_dir);
			
			if($tpl_details && $tpl_details['name']!='')
				$tpls[$tpl_details['name']] = $tpl_details;
		}
		
		return $tpls;
	}
	function gettemplates()
	{
		return $this->get_templates();
	}
	
	function get_template_details($temp,$file='template.xml')
	{
		$file = STYLES_DIR.'/'.$temp.'/template.xml';
		if(file_exists($file))
		{
			$content = file_get_contents($file);
			preg_match('/<name>(.*)<\/name>/',$content,$name);
			preg_match('/<author>(.*)<\/author>/',$content,$author);
			preg_match('/<version>(.*)<\/version>/',$content,$version);
			preg_match('/<released>(.*)<\/released>/',$content,$released);
			preg_match('/<description>(.*)<\/description>/',$content,$description);
			preg_match('/<website title="(.*)">(.*)<\/website>/',$content,$website_arr);
			
			$name = $name[1];
			$author = $author[1];
			$version = $version[1];
			$released = $released[1];
			$description = $description[1];

			$website = array('title'=>$website_arr[1],'link'=>$website_arr[2]);
			
			//Now Create array
			$template_details = array
			('name'=>$name,
			 'author'=>$author,
			 'version'=>$version,
			 'released'=>$released,
			 'description'=>$description,
			 'website'=>$website,
			 'dir'=>$temp,
			 'path'=>TEMPLATEFOLDER.'/'.$temp
			 );
			
			return $template_details;
		}else
			return false;
	}
	
	/**
	 * Function used to get template thumb
	 */
	function get_preview_thumb($template)
	{
		$path = TEMPLATEFOLDER.'/'.$template.'/images/preview.';
		$exts = array('png','jpg','gif');
		$thumb_path = BASEURL.'/images/icons/no_thumb_template.png';
		foreach($exts as $ext)
		{
			$file = BASEDIR.'/'.$path.$ext;
			if(file_exists($file))
			{
				$thumb_path = BASEURL.'/'.$path.$ext;
				break;
			}
		}
		
		return $thumb_path;		
	}
	
	/**
	 * Function used to get any template
	 */
	function get_any_template()
	{
		$templates = $this->get_templates();
		if(is_array($templates))
		{
			foreach($templates as $template)
			{
				if(!empty($template['name']))
					return $template['dir'];
			}
			return false;
		}else
			return false;
	}
	
	/**
	 * Function used to check weather given template is ClipBucket Template or not
	 * It will read Template XML file
	 */
	function is_template($folder)
	{
		return $this->get_template_details($folder);
	}
	
	
	/**
	 * Function used to get list of template file frrom its layout and styles folder
	 */
	function get_template_files($template,$type=NULL)
	{
		switch($type)
		{
			case "layout":
			default:
			{
				$style_dir = STYLES_DIR."/$template/layout/";
				$files_patt = $style_dir."*.html";
				$files = glob($files_patt);
				/**
				 * All Files IN Layout Folder
				 */
				$new_files = array();
				foreach($files as $file)
				{
					$new_files[] = str_replace($style_dir,'',$file);
				}
				
				/**
				 * Now Reading Blocks Folder
				 */
				$blocks = $style_dir.'blocks/';
				$file_patt = $blocks.'*.html';
				$files = glob($file_patt);
				foreach($files as $file)
				{
					$new_files['blocks'][] = str_replace($blocks,'',$file);
				}
				
				/**
				 * Reading Folders Under Blocks
				 */
				//$blocks_dirs = glob($blocks.'*',GLOB_ONLYDIR);
//				foreach($blocks_dirs as $dir)
//				{
//					$dir_name = str_replace($blocks,'',$dir);
//					
//					/**
//					 * Now Reading Files under them and saving in array
//					 */
//					$sub_dir = $blocks.$dir_name.'/';
//					$file_patt = $sub_dir.'*.html';
//					$files = glob($file_patt);
//					foreach($files as $file)
//					{
//						$new_files['blocks'][$dir_name][] = str_replace($sub_dir,'',$file);
//					}
//				}
				return $new_files;
			}
			break;
			case "theme":
			{
				$style_dir = STYLES_DIR."/$template/theme/";
				$files_patt = $style_dir."*.css";
				$files = glob($files_patt);
				/**
				 * All Files IN CSS Folder
				 */
				$new_files = array();
				foreach($files as $file)
				{
					$new_files[] = str_replace($style_dir,'',$file);
				}
				
				return $new_files;
			}
		}
	}
}

?>