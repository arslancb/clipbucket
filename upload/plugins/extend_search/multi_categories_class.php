<?php
require "multi_search_class.php";
/**
 * This File contains a class that extends CBCategory in order to extend the research of video in other classes than video itself.
 * It may be used to find video by discipline, video by speaker...
 */
class MultiCategories extends CBvideo {
	var $searchObjects=[];
	var $search="";
	function addSearchObject($search){
		$this->searchObjects[]=$search;
	}
	
	/**
	 * Initialize the MultiCategories objetcs
	 * 
	 * Call the parent init function and replace the global $CBucket variable stored in $Cbucket->search_types['videos']
	 * by the global variable "cbvidext" which is an instance of this class.
	 *
	 */	
	function init() {
		global $Cbucket;
		parent::init();
		$Cbucket->search_types['multisearch'] = "multicategories";
	}
	
	/**
	 * Clone an object
	 * 
	 * Make a pseudo clone of an object to an other. This method is used to copy all attribute of a source object
	 * to a destination one. The current use case is copying attribute to an object of a derived class 
	 * of the source object's class
	 *
	 * @param object $srcObj
	 * 		The source object
	 * @param object $dstObj
	 * 		The destination object
	 */	
	function cloneValues($srcObj , $dstObj){
		foreach (get_object_vars($srcObj) as $key => $val){
			$dstObj->{$key}=$srcObj->{$key};
		}
	}

	/**
	 * This method initilize the search engine for this class
	 */	
	function init_search(){
		parent::init_search();
		$search=new MultiSearch();
		$this->cloneValues($this->search,$search);
		$this->search=$search;
		foreach ($this->searchObjects as $obj){
			global ${$obj};
			${$obj}->init_search();
			$this->search->addSearchObject($obj);
		}
	}
	
}
?>