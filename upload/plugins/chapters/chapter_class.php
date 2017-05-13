<?php
/*
 * This file contains Chapter class
 */ 
require_once PLUG_DIR.'/extend_search/extend_video_class.php';

// Global Object $speakerquery is used in the plugin
$chapter = new Chapter();
$Smarty->assign_by_ref('chapter', $chapter);


/**
 * Class Containing actions for the speaker plugin 
 */
class Chapter extends CBCategory{
	private $basic_fields = array();
	
	/**
	 * Constructor for chapters's instances
	 */
	function Chapter()	{
		global $cb_columns;
		$basic_fields = array('id', 'time','title', 'videoid');
		$cb_columns->object( 'chapters' )->register_columns( $basic_fields );
	}

	/**
	 * Function used to test if a speakers exists.
	 * This function is testing the existance of firstname & lastname fields
	 * 
	 * @param array $array
	 * 		a dictionnary that contains fields for a speaker. $_POST is used if empty
	 * @return bool
	 * 		true if speaker exists , otherwise false
	 */
	function searchChapter($array=NULL){
		global $db;
		if($array==NULL)
			$array = $_POST;
		$this->validateFormFields($array,false);
		if(!error()) {
			$firstname=$array['firstname'];
			$lastname=$array['lastname'];
			$req=" firstname like '%".$firstname."%' AND lastname like '%".$lastname."%'";
			$res=$db->select(tbl('speaker'),'*',$req,false,false,false);
			// test speaker's unicity
			if (count($res)>0){
				$s="";
				for ($i=0; $i<count($res); $i++)
					$s=$s.$res[$i]['firstname'].' '.$res[$i]['lastname'].', ';
				e(lang("speaker_already_exists")." : ".$s,"w");
				return true;
			}
			else {
				e(lang("speaker_does_not_exist"),"m");
				return false;
			}
		}
	}
	
	/**
	 * Get all chapters of the specified video id 
	 *
	 * @param array $vid 
	 *		the video id
	 * @return array
	 * 		an array of all specified chapters objects
	 */
	function getChapters($vid)	{
		$query = " SELECT * FROM ".tbl('chapters')." WHERE `videoid`='".$vid."' ORDER BY `time`";
		$result = select( $query );
		return $result;
	}

	/**
	 * Get the html text for displaying the edit fields of one chapter
	 *
	 * @param int $index
	 *		the chapter index (each index must be diferent becasue used to generate some html ids)
	 * @param int $vid
	 * 		the video id for which we want to generate chapters
	 * @param array $flds
	 * 		an array containing the fields values containing default values for each field
	 * @return string
	 * 		an HTML string containing the chapter's fields
	 */
	function generateHTMLFields($index, $vid, $flds){
		global $formObj;
		
		$output='<div class="form-group" id="extra_'.$index.'"><input type="hidden" id="chid_'.$index.'" value="'.$flds["id"].'"/>';
		
		foreach ($this->loadChapterFields($vid) as $field){
			$output.='<div class="'.$field["class"].'">';
			$output.='<label for="'.$field["id"].'_'.$index.'">'.$field["title"].'</label><br>';
			if ($field["hint_1"])
				$output.=$field["hint_1"]."<br>";
				$field["class"]="form-control";
				$field["value"]=$flds[$field['id']];
				$field['id']=$field['id'].'_'.$index;
				$output.=$formObj->createField($field,TRUE);
				if ($field["hint_2"])
					$output.=$field["hint_2"]."<br>";
					$output.='</div>';
		
		}
		$output.='<div title="' . lang('delete') . '" class="btn btn-link btn-sm" onclick="deleteMe(\'' . $index . '\')">';
		$output.='<br><span class="glyphicon glyphicon-remove color-red"></span><span class="sr-only">'.lang('delete').'</span></div>';
		$output.='</div>';
		return $output;
	}
	
	/**
	 * get the HTML of all chapters fields for a specific video orderded by timecode
	 * @param int $video
	 * 		the video id
	 * @return string
	 * 		The HTML string containing all chapters fields
	 */
	function generateHTMLChapters($video){
		$chapters=$this->getChapters($video);
		$output="";
		for ($i=0; $i<count($chapters); $i++){
			$chapters[$i]["chtime"]=$chapters[$i]["time"];
			if ($chapters[$i]["time"]=="0"){
				$chapters[$i]["time"]="0.0";
			}
			$chapters[$i]["chtitle"]=$chapters[$i]["title"];
			$output.=$this->generateHTMLFields($i, $video, $chapters[$i]);
		}
		Assign ("number",count($chapters));
		return $output;
	}
	

	/**
	 * Save a vtt file containing all chapters for the selected video.
	 * This is used to generate navigation into the video inside the videojs player.
	 *
	 * @param array $vid
	 *		the video id
	 */
	function saveVTT($vid)	{
		$query = " SELECT * FROM ".tbl('video')." WHERE `videoid`='".$vid."'";
		$respons = select( $query );
		$query = " SELECT * FROM ".tbl('chapters')." WHERE `videoid`='".$vid."' ORDER BY `time`";
		$result = select( $query );
		$filename=$respons[0]["file_name"];
		$fileDirectory=$respons[0]['file_directory'];
		$dstFullpath=dirname(__FILE__)."/../../files/videos/".$fileDirectory."/track_".$filename.'.vtt';
		// if no chapters then delete vtt file
		if (count($result)==0){
			unlink($dstFullpath);
		}
		else {
			$output="WEBVTT\n\n";
			for ($i=0; $i<count($result); $i++){
				$output.="chapter_".$i."\n";
				$start=$result[$i]['time'];
				$dec=($start-intval($start))*1000;
				$start=sprintf('%02d:%02d:%02d.%03d', ($start/3600),($start/60%60), $start%60,$dec);
				if($i==count($result)-1){
					//the video duration is not set
					if ($respons[0]["duration"]==1)
						$stop=86400;
					else
						$stop=$respons[0]["duration"];
				}
				else{
					$stop=$result[$i+1]['time'];
				}
				$dec=($stop-intval($stop))*1000;
				$stop=sprintf('%02d:%02d:%02d.%03d', ($stop/3600),($stop/60%60), $stop%60,$dec);
				$output.=" $start --> $stop\n";
				$title=$result[$i]['title'];
				$output.=" $title\n\n";
			}
			file_put_contents($dstFullpath,$output);
		}
	}
	
	/**
	 * Test if speaker's id exists or not 
	 *
	 * @param int $id
	 * 		the speaker's id
	 * @return bool
	 * 		true if speaker exists otherwise false
	 */
	function speakerExists($id){
		global $db;
		$result = $db->count(tbl('speaker'),"id"," id='".$id."'");
		return ($result>0);
	}
	
	
	/**
	 * Function used to get speaker details using it's id 
	 *
	 * @param int $id 
	 * 		the speaker's id
	 * @return array|bool 
	 * 		a dictionary containig each fields for a speaker, false if no speaker found
	 */
	function getChapterDetails($id=NULL)	{
		global $db;
		$fields = tbl_fields(array('speaker' => array('*')));
		$query = "SELECT $fields FROM ".cb_sql_table('speaker');
		$query .= " WHERE speaker.id = '$id'";
		$result = select($query);
		Assign('speaker', $result);
		
		if ($result) {
			$details = $result[0];
			$fields = tbl_fields(array('speakerfunction' => array('id','description')));
			$query = "SELECT $fields FROM ".cb_sql_table('speakerfunction');
			$query .= " WHERE speakerfunction.speaker_id = '$id'";
			$result = select($query);
			for ($i=0; $i<count($result); $i++){
				$arr=$result[$i];
				$keys=array_keys($arr);
				for ($j=0; $j<count($keys); $j++){
					if ($keys[$j]=='id') $keys[$j]='role_id';
					$details[$keys[$j]]=[];
				}
			}
			for ($i=0; $i<count($result); $i++){
				$arr=$result[$i];
				$keys=array_keys($arr);
				$values=array_values($arr);
				for ($j=0; $j<count($keys); $j++){
					if ($keys[$j]=='id') $keys[$j]='role_id';
					$details[$keys[$j]][]=$values[$j];
				}
			}
				
			return $details;
		}
		return false;
	}
	


/**
 	 * Create initial array for speaker fields 
 	 *
	 * this will tell
	 * array(
	 *       title [text that will represents the field]
	 *       type [One of the following values : textfield, password,texarea, checkbox,radiobutton, dropbox]
	 *       name [name of the fields, input NAME attribute]
	 *       id [id of the fields, input ID attribute]
	 *       value [value of the fields, input VALUE attribute]
	 *       size
	 *       class [CSS class of the field]
	 *       label
	 *       extra_tags [Extra tags added as is to the field]
	 *       hint_1 [hint before field]
	 *       hint_2 [hint after field]
	 *       anchor_before [anchor before field]
	 *       anchor_after [anchor after field]
	 *      )
	 *
 	 * @param array $input 
 	 * 		a dictionary with speaker's informations (if null $_POST is used)
	 * @param bool $strict
	 * 		if true then field is requiered in the data form
 	 *	@return array 
 	 *		Fields for the administration page of the plugin
 	 */
	function loadChapterFields($input=NULL,$strict=true) {
		global $LANG,$Cbucket;
		$default = array();
		if(isset($input))
			$default = $input;
		if(empty($default))
			$default = $_POST;
	
		$time = (isset($default['time'])) ? $default['time'] : "";
		$title = (isset($default['title'])) ? $default['title'] : "";

		$my_fields = array (
				'time' => array(
						'title'=> lang('time'),
						'type'=> "textfield",
						'name'=> "chtime",
						'id'=> "chtime",
						'value'=> $time,
						'db_field'=>'time',
						'required'=>($strict) ? 'yes' : 'no',
						'class' => 'col-md-2',
						//'hint_1'=> lang('user_allowed_format'),
						//'hint_2'=> lang('user_allowed_format'),
						// 'syntax_type'=> 'username',
						//'validate_function'=> 'username_check',
						//'function_error_msg' => lang('user_contains_disallow_err'),
						//'db_value_check_func'=> 'user_exists',
						//'db_value_exists'=>false,
						//'db_value_err'=>lang('usr_uname_err2'),
						//'min_length'	=> config('min_username'),
						//'max_length' => config('max_username'),
				),
				'title' => array(
						'title'=> lang('title'),
						'type'=> "textfield",
						'name'=> "chtitle",
						'id'=> "chtitle",
						'value'=> $title,
						'db_field'=>'title',
						'required'=>($strict) ? 'yes' : 'no',
						'class' => 'col-md-9',
				),
		);
		return $my_fields;
	}


	/**
	 * Validate speaker's administrion form fields (Add and Edit forms) 
	 *
 	 * @param  array $input 
 	 * 		a dictionary with speaker's informations (if null $_POST is used)
	 *	@param bool $strict
	 *		if true then field is requiered in the data form
	 * @return 
	 * 		true if the form is valid, otherwise false
	 */
	function validateFormFields($array=NULL,$strict=true) {
		$fields= $this->loadChapterFields($array,$strict);
		$ok=false;
		foreach($extrafields as $field) {
			if(is_array($array[$field['name']])) 
				$ok=true;
		}
		if ($ok)
			$fields= array_merge($this->loadChapterFields($array,$strict));
		if($array==NULL)
			$array = $_POST;
		if(is_array($_FILES))
			$array = array_merge($array,$_FILES);
	
		validate_cb_form($fields,$array);
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
	var $reqTbls=array('video','users', 'speaker', 'speakerfunction', 'video_speaker');
	
	/**
	 * Array that contains all requiered table and fields fo a sql join
	 * 
	 * each value of this table is an array like :
	 * array('table1'=>'table1_name'.'field1' => 'field1_name', 'table2'=>'table2_name'.'field2' => 'field2_name')
	 *
	 * This variable can be extended extrernally
	 */
	var $reqTblsJoin=array(array('table1'=>'users', 'field1'=>'userid','table2'=>'video','field2'=>'userid'),
			array('table1'=>'speaker', 'field1'=>'id','table2'=>'speakerfunction','field2'=>'speaker_id'),
			array('table1'=>'speakerfunction', 'field1'=>'id','table2'=>'video_speaker','field2'=>'speakerfunction_id'),
			array('table1'=>'video_speaker', 'field1'=>'video_id','table2'=>'video','field2'=>'videoid')
		);

	/**
	 * String used to declare all necessary fields the search request should return.
	 * This string have to contain fields in which we are searching data
	 * in order to make a post treatment for requests that contains single quotes
	 *
	 */
	var $reqFields="video.*,speaker.firstname,speaker.lastname,speaker.slug,users.userid,users.username";
	
	/**
	 * This method initilize the search engine for this class
	 */
	function init_search(){
		
		//parent::init_search();
		$search=new ExtendSearch();
		$this->cloneValues($this->search,$search);
		$this->search=$search;
		$this->search->results_per_page = config('videos_items_search_page');
		$this->search->display_template = LAYOUT.'/blocks/video.html';
		$this->search->template_var = 'video';
		$this->search->sorting	= array(
				'date_added'=> " date_added DESC",
				'datecreated'=> " datecreated DESC",
				'views'		=> " views DESC",
				'comments'  => " comments_count DESC ",
				'rating' 	=> " rating DESC",
				'favorites'	=> " favorites DeSC"
		);
		$this->search->sort_by = 'datecreated';
		$this->search->search_type['speaker'] = array('title'=>lang('speakers'));
		//set tables for this plugin in extended search plugin
		$this->search->reqTbls=$this->reqTbls;
		//set tables associations for this plugin in extended search plugin
		$this->search->reqTblsJoin =$this->reqTblsJoin;
		//set return fields  for this plugin in extended search plugin
		$this->search->searchFields=$this->reqFields;
		//set search fields for this plugin in extended search plugin
		$this->search->columns =array(
			array('table'=>'speaker', 'field'=>'firstname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
			array('table'=>'speaker', 'field'=>'lastname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
			array('table'=>'speaker', 'field'=>'slug','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
			array('field'=>'broadcast','type'=>'!=','var'=>'unlisted','op'=>'AND','value'=>'static'),
			array('field'=>'status','type'=>'=','var'=>'Successful','op'=>'AND','value'=>'static')
		);
	}
	
}

?>