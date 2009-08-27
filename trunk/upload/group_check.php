<?php
		if($details['group_type'] == 2){
			if(!$groups->is_userinvite($_SESSION['username'],$details['username'],$details['group_id']) && !$groups->is_Joined($_SESSION['username'],$group)){
			$msg = $LANG['grp_prvt_err1'];
			$show_join = 'No';
			$show_group = 'No';
			}
		}
	
		if($details['active'] == no){
		$msg = $LANG['grp_inact_error'];
		$show_group = 'No';
		}
		
		if($details['video_type'] == 2 && $manage_vids==TRUE && @$_SESSION['username'] != $details['username']){
		$msg = $LANG['grp_owner_err'];
		$show_group = 'No';
		}
		if(@$_SESSION['username'] == $details['username']){
			Assign('owner','yes');
		}
		//Chceking Logged in user is group user or not
		if(!$groups->is_joined(@$_SESSION['username'],$group)){
			Assign('join','yes');
		}else{
			Assign('join','no');
		}
		if(@$MustJoin != 'No'){
			if(!$groups->is_Joined($_SESSION['username'],$group) && $_SESSION['username'] != $details['username']){
			$msg = $LANG['grp_join_error1'];
			$show_group = 'No';
			}
		}
?>