<?php
/*
 * This file contains speakerquery class and some usefull functions used in this plugin
 */ 


// Global Object $speakerquery is used in the plugin
$disciplinequery = new disciplinequery();
$Smarty->assign_by_ref('disciplinequery', $disciplinequery);

/**_____________________________________________________
 * Class disciplinequery
 * _____________________________________________________
 *Contains actions that can affect the discipline's plugin 
 */
class disciplinequery extends CBCategory{

	/**_____________________________________
	 * disciplinequery
	 * _____________________________________
	 *Constructor for disciplinequery's instances
	 */
	function disciplinequery()	{
		$this->init();
	}
	
	/**_____________________________________
	 * init
	 * _____________________________________
	 *Call the parent init function and set a new search engine for disciplines into the global $CBucket variable
	 *
	 */	
	function init() {
		global $Cbucket;
		$Cbucket->search_types['disciplines'] = "disciplinequery";
		$Cbucket->configs['disciplinesSection']='yes';
	}

	/**_____________________________________
	 * init_search
	 * _____________________________________
	 * Function used to use to initialize search object for video section
	 * op=>operator (AND OR)
	 * SEEALSO: made from video class init_search funrtion. 
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
	
	/**_____________________________________
	 * discipline_count
	 * _____________________________________
	 *return the number of disciplines
	 */
	function discipline_count ()	{
		global $db;
		return $db->count(tbl('disciplines'),'id');
	}
	
	/**_____________________________________
	 * count_video_of_discipline
	 * _____________________________________
	 *return the number of videos of this discipline
	 */
	function count_video_of_discipline ($did)	{
		global $db;
		return $db->count(tbl('video'),'videoid',"discipline=$did");
	}
	
	/**_____________________________________
	 * get_video_of_discipline
	 * _____________________________________
	 *return an array containing all videos ids of this discipline
	 */
	function get_video_of_discipline ($did)	{
		global $db;
		return $db->select(tbl('video'),'videoid',"discipline=$did");
	}
	
	/**_____________________________________
	 * get_all_disciplines
	 * _____________________________________
	 *return a table of all disciplines
	 */
	function get_all_disciplines ()	{
		global $db;
		return $db->select(tbl('disciplines'),'*',false,false,"discipline_order");
	}

	/**_____________________________________
	 * get_all_disciplines_for_menu
	 * _____________________________________
	 *return a table of all disciplines with menu visibility enabled
	 */
	function get_all_disciplines_for_menu ()	{
		global $db;
		return $db->_select("SELECT * FROM ".tbl("disciplines")." WHERE in_menu = 1 ORDER BY discipline_order ASC");
	}
	
	/**_____________________________________
	 * get_discipline
	 * _____________________________________
	 * Return the discipline  corresponding to the id in parameter
	 *
	 * input $vid : the discipline id
	 * output : an arraycontaining the discipline's fields
	 */
	function get_discipline($did){
		global $db;
		$alias = $db->_select("SELECT * FROM ".tbl("disciplines")." WHERE id = $did");
		return $alias;
	}
	
	/**_____________________________________
	 * get_discipline_of_video
	 * _____________________________________
	 * Return the discipline  of a video
	 *
	 * input $vid : the video id
	 * output : an arraycontaining the discipline's fields
	 */
	function get_discipline_of_video($vid){
		global $db;
		$alias = $db->_select("SELECT *
							   FROM ".tbl("disciplines")." AS d, ".tbl("video")." AS v
							   WHERE d.id = v.discipline
							   AND v.videoid=$vid");
		return $alias;
	}
	
	/**_____________________________________
	 * set_discipline
	 * _____________________________________
	 *Change the discipline id for a video
	 *
	 *input $vid : the id of the video affected
	 *input $did : the discipline id to be stored in the video 
	 */
	function set_discipline($vid, $did){
		global $db;
		$db->update(tbl('video'), array('discipline'), array($did),"videoid='$vid'");
	}
	
}

?>