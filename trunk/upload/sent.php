<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/

require_once('includes/config.inc.php');
$userquery->logincheck();
$pages->page_redir();

$user	= $_SESSION['username'];

//Delete Message
if(isset($_GET['delete_msg'])){
		$msgid = $_GET['delete_msg'];
		if(empty($msgid)){
		$msg = $LANG['usr_no_msg_del_err'];
		}else{
		$msg = $myquery->DeleteMessage($msgid,$user,'outbox');
		}
	}
	
	//Deleting Multiple
	$sql 		= "SELECT * FROM messages WHERE outbox_user = '".$user."' ORDER BY date_added DESC";
	$rs 		= $db->Execute($sql);
	$totalmsg	= $rs->recordcount() + 0;
	if(isset($_POST['delete_selected_message'])){
				for($id=0;$id<=$totalmsg;$id++){
					$msgid = $_POST['check_msg'][$id];
					if(!empty($msgid))
					$msg = $myquery->DeleteMessage($msgid,$user,'outbox');
				}
			$msg = $LANG['usr_sel_msg_del_msg'];
			}
	
//Getting User Messages
$sql 		= "SELECT * FROM messages WHERE outbox_user = '".$user."' ORDER BY date_added DESC";
$rs 		= $db->Execute($sql);
$totalmsg	= $rs->recordcount() + 0;
$msgs		= $rs->getrows();
Assign('msgs',$msgs);
Assign('totalmsg',$totalmsg);

//Getting Message
	@$msgid= mysql_clean($_GET['msgid']);
	if(!empty($msgid)){
	$msgdata = $myquery->GetMsgSent($msgid,$_COOKIE['username']);
		if(empty($msgdata['subject'])){
			$msg = "Sorry Message Doesn't Exists";
		}else{
		mysql_query("UPDATE messages SET status='1' WHERE message_id='".$msgid."'");
		$msgdata = $myquery->GetMsgSent($msgid,$_COOKIE['username']);
		Assign('show_msg','yes');
			if(!empty($msgdata['attachment'])){
			$videos = $myquery->GetVideDetails($msgdata['attachment']);
					if(!empty($videos['videokey'])){
					$videos['thumb1'] 		= GetName($videos['flv']).'-1.jpg';
					$videos['thumb1'] 		= GetName($videos['flv']).'-2.jpg';
					$videos['url'] 			= VideoLink($videos['videokey'],$videos['title']);
					Assign('attachment',$videos);
					}else{
					$msgdata['attachment'] = '';
					}
				}
		Assign('msgdata',$msgdata);
		}
	}

Assign('subtitle',$user.$LANG['title_sent']);
@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('sent.html');
Template('footer.html');
?>