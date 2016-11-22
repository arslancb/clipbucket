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
	 	// First search with all sconnected search engine.
		foreach ($this->searchObjects as $obj){
			$obj->search->key=$this->key;
			// Remove each search engine limit to get all results and set the limit later
			$obj->search->limit="";
			// Concatenate all search results in one
			$results= array_merge($results,$obj->search->search());
	 		$this->total_results+= $obj->search->total_results;
	 	}
	 	if (count($results)>0){
	 		// Put all results ids in a table in order to remove duplicated results
	 		$videoids="";
	 		foreach ($results as $res)
	 			$videoids.=$res["videoid"].",";
 			$videoids=substr($videoids,0,-1);
 			
 			if ($this->limit)
 				$limit=" LIMIT ".$this->limit;
 			global $db;
	 		// Get result without duplicate. Data is orderd and only one page is returned 
	 		$results=$db->_select("SELECT * from ".tbl("video")." WHERE `videoid` IN (".$videoids.") ORDER BY `datecreated` DESC ".$limit);

	 		// Get the total number of results in order to paginate 
	 		$cnt=$db->_select("SELECT COUNT(*) from ".tbl("video")." WHERE `videoid` IN (".$videoids.")");
	 		foreach ($cnt[0] as $key=>$val){
	 			$this->total_results =$val;
	 		}
	 		
	 		// Reverse the resutl because it's reversed again in search-result.php then the result is in right order in the page.
	 		$results=array_reverse($results);
 		
	 	}
	 	return $results;
	}
}

?>