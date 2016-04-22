<?php
/**
 * @ Author Arslan Hassan, Fawaz Tahir
 * @ License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 * @ Class : Menu Handler Class
 * @ date : 31 December 2010
 * @ Version : v2.0.91
 * @ Description: This class will help in handling Menus of Clipbucket
 */

class MenuHandler
{
	var $headMenu = array();
	var $fileExt = '.menu';
	var $displayTypes = array();
	
	
	function MenuHandler()
	{					
		$this->setDisplayTypes();								
	}
	
	function setDisplayTypes()
	{
		$displayTypes = array('global' => 'Global',
							  'registered' => 'Registered Users',
							  'guest' => 'Guest Users');
		if(count($this->displayTypes) > 0)
			$displayTypes = array_merge($displayTypes,$this->displayTypes);
			
		$this->displayTypes = $displayTypes;		
	}
	
	function createHeadMenu($params=NULL)
	{
		$this->headMenu[strtolower(lang("menu_home"))] = array('name'=>lang("menu_home"),
												     'link'=>BASEURL,
												     'this'=>'home',
												     'display_type' => 'global');
		$this->headMenu[strtolower(lang("videos"))] = array('name'=>lang("videos"),
												     'link'=>cblink(array("name"=>"videos")),
												     'this'=>'videos',
												     'display_type' => 'global');
		//pr($this->headMenu,TRUE);											 													   
		return $this->headMenu;													   	
	}
	
	function createHeadFile()
	{
		$current_menu = $this->createHeadMenu();
		$file = BASEDIR.'/includes/templates/'.$this->headFile;
		if(file_exists($file))
		{
			$finalArray = array();
			foreach($current_menu as $menu)
			{
				$finalArray[] = $menu;	
			}
			$fo = fopen(BASEDIR.'/includes/templates/'.$this->headFile,'w+');
			fwrite($fo,json_encode($finalArray));
			fclose($fo);
			return true;
		} else {
			e(sprintf(lang("file_not_exists"),$this->headFile));
			return false;	
		}
	}
	
	function addNewTab($params=NULL)
	{
		if($params == NULL)
			$params = $_POST;
		
		if(empty($params['name']))
			e(lang("tab_name_is_empty"));
		if(!$this->validURL($params['link_name']))
			return false;	
			
		if(!error())
		{
			$menuName = $params['name'];
			$cbLink = cblink(array('name'=>strtolower($params['link_name'])));
			if($cbLink)
				$fullURL = $cbLink;
			elseif($params['noBASEURL'])
				$fullURL = $params['link_name'];
			else
			{
				$fullURL = BASEURL."/".$params['link_name'];	
			}
			
			$resultArray = array("name"=>$menuName,"link"=>$fullURL,"this"=>strtolower($menuName));
			return $resultArray;
		}
	}
	
	function validURL($url)
	{
		// Following Pattern is by DraconianDevil. Link: http://RegExr.com?2sf02
		//$pattren = '/(((http|ftp|https):\/\/)|www\.)[\w\-_]+(\.[\w\-_]+)+([\w\-\.,@?^=%&:/~\+#!]*[\w\-\@?^=%&/~\+#])?/g';
		if(empty($url))
		{
			e(lang("tab_url_is_empty"));
		}
	}
	
	function getMenus()
	{
		$files = glob(BASEDIR."/includes/templates/*.menu");
		if(!empty($files))
		{
			foreach($files as $file)
			{
				$file_parts = explode("/",$file);
				$name = $file_parts[count($file_parts)-1];
				$fileName[] = $name;
			}
			
			return $fileName;
		} else {
			return false;	
		}
	}
	
	function getMenuName($name)
	{
		if(empty($name))
			e(lang("menu_name_is_empty"));
		else
		{
			$parts = explode(".",$name);
			return $parts[0];	
		}
	}
	
	function getTabs($name)
	{
		if(empty($name))
			e(lang("name_is_empty"));
		elseif(!file_exists(BASEDIR."/includes/templates/".$name.$this->fileExt))
		{
			e(lang("menu_does_not_exist"));	
		} else {
			$content = file_get_contents(BASEDIR."/includes/templates/".$name.$this->fileExt);
			$content = json_decode($content,TRUE);
			return $content;	
		}
	}
	
	function getMenu($name)
	{
		if(empty($name))
			return false;
		else
		{
			if(file_exists(BASEDIR."/includes/templates/".$name.$this->fileExt))
			{
				return $name;
			}
			else
				e(lang("menu_does_not_exist"));		
		}
	}
	
	function updateMenu($oldName,$newName)
	{
		if(empty($newName))
			e(lang("menu_name_is_empty"));
		elseif(!file_exists(BASEDIR."/includes/templates/".$oldName.$this->fileExt))
			e(lang("menu_does_not_exist"));
		else
		{
			$file = BASEDIR."/includes/templates/".$oldName.$this->fileExt;
			rename($file,BASEDIR."/includes/templates/".strtolower(SEO($newName)).$this->fileExt);
			e(lang("menu_updated")." <a href='menu_manager.php'>Go Back</a>","m");	
		}
	}
	
	function createNewMenu($params=NULL)
	{
		if($params == NULL)
			$params = $_POST;
			
		if(empty($params['menuName']))
		{
			e("menu_name_is_empty");
			return false;
		} else {
			$name = $params['menuName'];
			$file = BASEDIR."/includes/templates/".strtolower(SEO($name)).$this->fileExt;
			if(file_exists($file))
				e("Menu with <strong>".$name."</strong> name already exists");
			else
			{
				$fopen = fopen($file,'w+');
				if($fopen)
					e(lang("New Menu Added"),"m");
					fclose($fopen);	
			}
		}
	}
			
}
?>