<?php
require "extend_search_class.php";
/**
 * This File contains a class that extends CBVideo in order to replace the CBvideo vaviable called search 
 * by an instance of extend-search class. This class also add search in video 'description' field by default
 */
class extend_video extends CBvideo {
	/**_____________________________________
	 * init
	 * _____________________________________
	 *Call the parent init function and replace the global $CBucket variable stored in $Cbucket->search_types['videos']
	 *by the global variable "cbvidext" which is an instance of this class.
	 *
	 */	
	function init() {
		global $Cbucket;
		parent::init();
		$Cbucket->search_types['videos'] = "cbvidext";
	}
	
	/**_____________________________________
	 * cloneValues
	 * _____________________________________
	 *Make a pseudo clone of an object to an other. This method is used to copy all attribute of a source object
	 *to a destination one. Th current use case is copying attribute to an object of a derived class 
	 *of the source object's class
	 *
	 *input $srcObj : The source object
	 *		$dstObj : The destination object
	 *
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

	/**_____________________________________
	 * init_search
	 * _____________________________________
	 *This method initilize th instance of extend_video
	 */	
	function init_search(){
		
		//temporary add of speakers tables in this plugin
		$this->reqTbls[]='speaker';
		$this->reqTbls[]='speakerfunction';
		$this->reqTbls[]='video_speaker';
		$this->reqTblsJoin[]=array('table1'=>'speaker', 'field1'=>'id','table2'=>'speakerfunction','field2'=>'speaker_id');
		$this->reqTblsJoin[]=array('table1'=>'speakerfunction', 'field1'=>'id','table2'=>'video_speaker','field2'=>'speakerfunction_id');
		$this->reqTblsJoin[]=array('table1'=>'video_speaker', 'field1'=>'video_id','table2'=>'video','field2'=>'videoid');
		
		parent::init_search();
		$search=new extend_search();
		$this->cloneValues($this->search,$search);
		$this->search=$search;
		$this->search->reqTbls=$this->reqTbls;
		$this->search->reqTblsJoin=$this->reqTblsJoin;
		//var_dump(get_object_vars($this->search));
		$this->search->columns[]=array('field'=>'description','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR');
		
		//temporary add of speakers tables in this plugin
		$this->search->columns[]=array('table'=>'speaker', 'field'=>'firstname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR');
		$this->search->columns[]=array('table'=>'speaker', 'field'=>'lastname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR');
	}
	
}
?>