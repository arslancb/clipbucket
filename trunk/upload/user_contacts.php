<?php
/* 
 ****************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author : ArslanHassan										
 | @ Software : ClipBucket , © PHPBucket.com					
 ****************************************************************
*/

define("THIS_PAGE",'user_contacts');
define("PARENT_PAGE",'channels');
require 'includes/config.inc.php';
$pages->page_redir();


$u = $_GET['user'];
$u = $u ? $u : $_GET['userid'];
$u = $u ? $u : $_GET['username'];
$u = $u ? $u : $_GET['uid'];
$u = $u ? $u : $_GET['u'];

$udetails = $userquery->get_user_details($u);
$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,CLISTPP);

if($udetails)
{
	assign("u",$udetails);
	$mode = $_GET['mode'];
	
	assign("u",$udetails);
	assign('p',$userquery->get_user_profile($udetails['userid']));
	assign('friends',$userquery->get_contacts($udetails['userid'],0,"yes"));

}else{
	e(lang("usr_exist_err"));
	$Cbucket->show_page = false;
}


subtitle(sprintf(lang("users_contacts"),$udetails['username']));
if($Cbucket->show_page)
Template('user_contacts.html');
else
display_it();
?>