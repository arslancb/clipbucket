<?php
	require_once '../../includes/admin_config.php';
	require_once CHAPTER_DIR.'/chapter_class.php';
	
	$userquery->admin_login_check();
	$userquery->login_check('admin_access');
	$pages->page_redir();
	global $chapter;
	global $db;
	$video= $_POST['video'];
	
	if ($_POST['action']=='remove' && isset($_POST['video']) && isset($_POST['chid'])){
		if ($_POST['chid']===''){
			echo lang("chapter_deleted");
		}
		else {
			$query="DELETE FROM ".tbl('chapters')." WHERE `videoid` =".$_POST['video'].' AND `id`='.$_POST['chid'];
			if ($db->Execute($query))
				echo lang("chapter_deleted");
			$chapter->saveVTT($video);
		}
	}
	elseif ($_POST['action']=='update' && isset($_POST['data']) && isset($_POST['video']) ){
		$data=$_POST["data"];
		for ($i=0; $i< count($data); $i++){
			// new entry
			if ($data[$i]["chid"]==='') {
				$db->insert(tbl('chapters'), ["`videoid`","`time`", "`title`"], [$video,$data[$i]["chtime"],$data[$i]["chtitle"]]);
			}
			//update an existing entry
			else {
				$db->update(tbl('chapters'), ["`time`", "`title`"], [$data[$i]["chtime"],$data[$i]["chtitle"]], 
						" `videoid`=".$video." AND `id`=".$data[$i]["chid"]);
			}
		}
		$output=$chapter->generateHTMLChapters($video);
		$chapter->saveVTT($video);
		echo json_encode(array("message"=>lang("chapters_saved"),"output"=>$output));
		
	}
	else{
		console.log(lang("chapter_no_action"));
	}
