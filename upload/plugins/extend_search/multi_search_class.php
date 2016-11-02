<?php
/**
 * This File contains a class that extends cbsearch in order to be able to search a video using multiple instance of cbsearch or cbsearch children.
 * It agreagate all result in one
 */
class MultiSearch extends cbsearch {
	var $searchObjects=[];	
	
	function addSearchObject($obj){
		global ${$obj};
		$this->searchObjects[]=${$obj};
	}
	
	/**
	 * Run the database search request. 
	 * @todo nedd to be odified to take paging in account
	 */
	 function search(){
	 	$results=[];
	 	$this->total_results=0;
	 	// Concatenate all search results in one 
		foreach ($this->searchObjects as $obj){
			$obj->search->key=$this->key;
	 		$results= array_merge($results,$obj->search->search());
	 		$this->total_results+= $obj->search->total_results;
	 	}
	 	// remove duplicate results
	 	if (count($results)>0){
	 		$videoids="";
	 		foreach ($results as $res)
	 			$videoids.=$res["videoid"].",";
 			$videoids=substr($videoids,0,-1);
	 		global $db;
	 		$results=$db->_select("SELECT * from ".tbl("video")." WHERE `videoid` IN (".$videoids.")");
	 	}
	 	return $results;
	}
}

?>