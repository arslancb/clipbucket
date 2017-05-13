<?php
/**
 * This file contains discipline class
 */ 
require_once PLUG_DIR.'/extend_search/extend_video_class.php';


// Global Object $speakerquery is used in the plugin
$disciplinequery = new Discipline();
$Smarty->assign_by_ref('disciplinequery', $disciplinequery);

/**
 * Class containing actions that can affect the discipline's plugin 
 */
class Discipline extends CBCategory{

	/**
	 * Constructor for discipline's instances
	 */
	function Discipline()	{
		$this->init();
	}
	
	/**
	 * Call the parent init function and set a new search engine for disciplines into the global $CBucket variable
	 *
	 */	
	function init() {
		global $Cbucket;
		$Cbucket->search_types['disciplines'] = "disciplinequery";
		$Cbucket->configs['disciplinesSection']='yes';
	}

	/**
	 * Array of strings that contains all requiered table names for the search request.
	 *
	 * This variable can be extended extrernally
	 */
	var $reqTbls=array('video','users','disciplines');
	
	/**
	 * Array that contains all requiered table and fields fo a sql join
	 *
	 * each value of this table is an array like :
	 * array('table1'=>'table1_name'.'field1' => 'field1_name', 'table2'=>'table2_name'.'field2' => 'field2_name')
	 *
	 * This variable can be extended extrernally
	 */
	var $reqTblsJoin=array(
			array('table1'=>'users', 'field1'=>'userid','table2'=>'video','field2'=>'userid'),
			array('table1'=>'disciplines', 'field1'=>'id','table2'=>'video','field2'=>'discipline'));

	/**
	 * String used to declare all necessary fields the search request should return.
	 * This string have to contain fields in which we are searching data
	 * in order to make a post treatment for requests that contains single quotes
	 *
	 */
	var $reqFields="video.*,disciplines.name,users.userid,users.username";
	
	/**
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 * 
	 * @see video.class.php/init_search() function. 
	 */
	function init_search() {
		$this->search = new ExtendSearch();
		$this->search->db_tbl = "video";
		$this->search->columns =array(
				array('field'=>'discipline','type'=>'LIKE','var'=>'%{KEY}%'),
				array('field'=>'broadcast','type'=>'!=','var'=>'unlisted','op'=>'AND','value'=>'static'),
				array('field'=>'status','type'=>'=','var'=>'Successful','op'=>'AND','value'=>'static')
		);
		//commit this line so that videos search can be applied to %like% instead of whole word search
		//$this->search->use_match_method = true;
		$this->search->match_fields = array("discipline");
	
		///$this->search->cat_tbl = $this->cat_tbl;
	
		$this->search->display_template = LAYOUT.'/blocks/video.html';
		$this->search->template_var = 'video';
	
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
			
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);
		$uploaded = $default['datemargin'];
		$this->search->sort_by = 'datecreated';
		
		$this->search->search_type['disciplines'] = array('title'=>lang('discipline'));
		//set tables for this plugin in extended search plugin
		$this->search->reqTbls=$this->reqTbls;
		//set tables associations for this plugin in extended search plugin
		$this->search->reqTblsJoin =$this->reqTblsJoin;
		//set return fields  for this plugin in extended search plugin
		$this->search->searchFields=$this->reqFields;
		
		$this->search->results_per_page = config('videos_items_search_page');
	}
	
	/**
	 * Count the number of disciplines
	 * @return 
	 * 		the number of disciplines
	 */
	function disciplineCount ()	{
		global $db;
		return $db->count(tbl('disciplines'),'id');
	}
	
	/**
	 * Count the number of video in @author franck
	 * 
	 * @param int $did
	 * 		the discipline id
	 * @return 
	 * 		the number of videos of this discipline
	 */
	function countVideoOfDiscipline ($did)	{
		global $db;
		return $db->count(tbl('video'),'videoid',"discipline=$did");
	}
	
	/**
	 * Get a the list of videos ids of a discipline
	 * 
	 * @param int $did
	 * 		the discipline id
	 * @return array 
	 * 		array containing all videos ids of this discipline
	 */
	function getVideosOfDiscipline ($did)	{
		global $db;
		return $db->select(tbl('video'),'videoid',"discipline=$did");
	}
	
	/**
	 * Get all disciplines
	 *
	 * @return array 
	 * 		array containing all disciplines
	 */
	function getAllDisciplines ()	{
		global $db;
		return $db->select(tbl('disciplines'),'*',false,false,"discipline_order");
	}

	/**
	 * Get all disciplines that have their in_menu flag set to 1
	 * 
	 * @return array
	 * 		array of all disciplines with menu visibility enabled
	 */
	function getAllDisciplinesForMenu ()	{
		global $db;
		return $db->_select("SELECT * FROM ".tbl("disciplines")." WHERE in_menu = 1 ORDER BY discipline_order ASC");
	}
	
	/**
	 * Get a discipline specified by it's id
	 * 
	 * @param int $did
	 * 		th discipline's id
	 * @return array
	 * 		array containing the discipline's fields
	 */
	function getDiscipline($did){
		global $db;
		$alias = $db->_select("SELECT * FROM ".tbl("disciplines")." WHERE id = $did");
		return $alias;
	}
	
	/**
	 * Get the discipline of a specified video id
	 *
	 * @param int $vid 
	 * 		the video id
	 * @return array
	 * 		array containing the discipline's fields of this video
	 */
	function getDisciplineOfVideo($vid){
		global $db;
		$alias = $db->_select("SELECT *
							   FROM ".tbl("disciplines")." AS d, ".tbl("video")." AS v
							   WHERE d.id = v.discipline
							   AND v.videoid=$vid");
		return $alias;
	}
	
	/**
	 * Change the discipline of a video
	 *
	 * @param int $vid 
	 * 		the id of the video affected
	 * @paramint $did 
	 * 	the new discipline's id for this video 
	 */
	function setDiscipline($vid, $did){
		global $db;
		$db->update(tbl('video'), array('discipline'), array($did),"videoid='$vid'");
	}
	
}

?>