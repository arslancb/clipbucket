<?php
//require BASEDIR.'/includes/classes/video.class.php';

class extend_video extends CBvideo {
	
	function init() {
		global $Cbucket;
		parent::init();
		$Cbucket->search_types['videos'] = "cbvidext";
	}
	function init_search(){
		parent::init_search();
		$this->search->columns[]=array('field'=>'description','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR');
		
	}
	
}
?>