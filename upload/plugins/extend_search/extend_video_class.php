<?php
require_once "extend_search_class.php";
/**
 * This File contains a class that extends CBVideo in order to replace the CBvideo vaviable called search 
 * by an instance of extend-search class. This class also add search in video 'description' field by default
 */
class ExtendVideo extends CBvideo {
	/**
	 * Initialize the ExtendVideo objetcs
	 * 
	 * Call the parent init function and replace the global $CBucket variable stored in $Cbucket->search_types['videos']
	 * by the global variable "cbvidext" which is an instance of this class.
	 *
	 */	
	function init() {
		global $Cbucket;
		parent::init();
		$Cbucket->search_types['videos'] = "cbvidext";
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
	 * Array of strings that contains all requiered table names for the search request.
	 *  
	 * This variable can be extended extrernally
	 */ 
	var $reqTbls=array('video','users');	

	/** 
	 * Array that contains all requiered table and fields fo a sql join 
	 * each value of this table is an array like :
	 * array('table1'=>'table1_name'.'field1' => 'field1_name', 'table2'=>'table2_name'.'field2' => 'field2_name')
	 *  
	 * This variable can be extended extrernally
	 */
	var $reqTblsJoin=array(array('table1'=>'users', 'field1'=>'userid','table2'=>'video','field2'=>'userid'));

	/**
	 * String used to declare all necessary fields the search request should return.
	 * This string have to contain fields in which we are searching data
	 * in order to make a post treatment for requests that contains single quotes
	 *
	 */
	var $reqFields="video.*,users.userid,users.username";
	
	/**
	 * This method initilize the search engine for this class
	 */	
	function init_search(){
		parent::init_search();
		$search=new ExtendSearch();
		$this->cloneValues($this->search,$search);
		$this->search=$search;
		//set default in extended search plugin (must be overwritten)
		$this->search->reqTbls=$this->reqTbls;
		//set default tables associations in extended search plugin (must be overwritten)
		$this->search->reqTblsJoin=$this->reqTblsJoin;
		//set default return fields (must be overwritten)
		$this->search->searchFields=$this->reqFields;
		$this->search->search_type['videos'] = array('title'=>lang('videos'));
		//var_dump(get_object_vars($this->search));
		$this->search->columns =array(
				array('field'=>'title','type'=>'LIKE','var'=>'%{KEY}%'),
				array('field'=>'tags','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
				array('field'=>'broadcast','type'=>'!=','var'=>'unlisted','op'=>'AND','value'=>'static'),
				array('field'=>'status','type'=>'=','var'=>'Successful','op'=>'AND','value'=>'static'),
				array('field'=>'description','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR')
		);
		$this->search->sort_by = 'datecreated';
		/**
		 * Setting up the sorting thing
		 */
		
		$this->search->sorting	= array(
				'date_added'=> " date_added DESC",
				'datecreated'=> " datecreated DESC",
				'most_recent'=> " datecreated DESC",
				'views'		=> " views DESC",
				'comments'  => " comments_count DESC ",
				'rating' 	=> " rating DESC",
				'favorites'	=> " favorites DeSC"
		);
		
	}
	
}
?>