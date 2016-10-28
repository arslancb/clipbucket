<?php
/**
 * This file contains discipline class
 */ 


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
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 * 
	 * @see video.class.php/init_search() function. 
	 */
	function init_search() {
		$this->search = new cbsearch;
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
		$this->search->has_user_id = true;
	
		/**
		 * Setting up the sorting thing
		 */
	
		$sorting	= 	array(
				'date_added'=> lang("date_added"),
				'views'		=> lang("views"),
				'comments'  => lang("comments"),
				'rating' 	=> lang("rating"),
				'favorites'	=> lang("favorites")
		);
	
		$this->search->sorting	= array(
				'date_added'=> " date_added DESC",
				'views'		=> " views DESC",
				'comments'  => " comments_count DESC ",
				'rating' 	=> " rating DESC",
				'favorites'	=> " favorites DeSC"
		);
		/**
		 * Setting Up The Search Fields
		*/
			
		$default = $_GET;
		if(is_array($default['category']))
			$cat_array = array($default['category']);
		$uploaded = $default['datemargin'];
		$sort = $default['sort'];
	
		//$this->search->search_type['videos'] = array('title'=>lang('videos'));
		$this->search->results_per_page = config('videos_items_search_page');
	
		$fields = array(
				'query'	=> array(
						'title'=> lang('keywords'),
						'type'=> 'textfield',
						'name'=> 'query',
						'id'=> 'query',
						'value'=>cleanForm($default['query'])
				),
				'category'	=>  array(
						'title'		=> lang('vdo_cat'),
						'type'		=> 'checkbox',
						'name'		=> 'category[]',
						'id'		=> 'category',
						'value'		=> array('category',$cat_array),
				),
				'uploaded'	=>  array(
						'title'		=> lang('uploaded'),
						'type'		=> 'dropdown',
						'name'		=> 'datemargin',
						'id'		=> 'datemargin',
						'value'		=> $this->search->date_margins(),
						'checked'	=> $uploaded,
				),
				'sort'		=> array(
						'title'		=> lang('sort_by'),
						'type'		=> 'dropdown',
						'name'		=> 'sort',
						'value'		=> $sorting,
						'checked'	=> $sort
				)
		);
	
		$this->search->search_type['videos']['fields'] = $fields;
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