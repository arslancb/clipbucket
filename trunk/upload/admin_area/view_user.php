<?php
/**
 * View User Details
 * @author:Arslan
 * @Since : Oct 16 09
 */
require'../includes/admin_config.php';
$userquery->admin_login_check();
$pages->page_redir();
$userquery->login_check('member_moderation');


/* Assigning page and subpage */
if(!defined('MAIN_PAGE')){
    define('MAIN_PAGE', 'Members');
}
if(!defined('SUB_PAGE')){
    define('SUB_PAGE', 'Edit Members');
}



$uid = $_GET['uid'];
$udetails = $userquery->get_user_details($uid);

if($udetails)
{	
	//Deactivating User
	if(isset($_GET['deactivate']))
	{
		$userquery->action('deactivate',$uid);
		$udetails = $userquery->get_user_details($uid);
	}
	//Activating User
	if(isset($_GET['activate']))
	{
		$userquery->action('activate',$uid);
		$udetails = $userquery->get_user_details($uid);
	}
	//Banning User
	if(isset($_GET['ban']))
	{
		$userquery->action('ban',$uid);
		$udetails = $userquery->get_user_details($uid);
	}
	//Unbanning User
	if(isset($_GET['unban']))
	{
		$userquery->action('unban',$uid);
		$udetails = $userquery->get_user_details($uid);
	}
	
	//Deleting User
	if(isset($_GET['delete']))
		$userquery->delete_user($uid);
	//Deleting User Videos
	if(isset($_GET['delete_vids']))
		$userquery->delete_user_vids($uid);
	//Deleting User Contacts
	if(isset($_GET['delete_contacts']))
		$userquery->remove_contacts($uid);
	//Deleting User Pms
	if(isset($_GET['delete_pms']))
		$userquery->remove_user_pms($uid);
	
	
	//Deleting Comment
	$cid = mysql_clean($_GET['delete_comment']);
	if(!empty($cid))
	{
		$myquery->delete_comment($cid);
	}
	
	if(isset($_POST['update_user']))
	{
		$userquery->update_user($_POST);
		if(!error())
		$udetails = $userquery->get_user_details($uid);
	}
	
	$profile = $userquery->get_user_profile($udetails['userid']);
	$user_profile = array_merge($udetails,$profile);
	assign('u',$udetails);
	assign('p',$user_profile);


    /*$type = "u";
    $comment_cond = array();
    $comment_cond['type'] = $type;
    $comment_cond['order'] = " comment_id DESC";
    $comment_cond['type_id'] = 'userid';
    $comment_cond['sectionTable'] = 'users';
    $comment_cond['count_only'] = false;
    $comments = getComments($comment_cond);

    $comments = $myquery->get_comments(array(
        'type_id' 		=> 'userid',
        'type' 			=> 'u',
        'count_only' 	=> false,
        'get_type' 		=> 'user',
        'parent_id' 	=> '',
        'cache'			=> $useCache
    );)

    pr($comments,true);
    assign("comments",$comments);

    */





}else{
	e("No User Found");
	$CBucket->show_page = false;
}

subtitle("View User");
template_files("view_user.html");
display_it();
?>