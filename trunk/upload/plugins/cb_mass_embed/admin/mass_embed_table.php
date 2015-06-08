<?php

define("THIS_PAGE","cb_mass_embed");

if(isset($_GET['delete']))
{
	$db->Execute("DELETE FROM ".tbl("mass_embed")." WHERE mass_embed_id='".mysql_clean($_GET['delete'])."' ");
	e("Record deleted","m");
}
if(isset($_POST['delete_btn'])){

	if(isset($_POST['delete']) && !empty($_POST['delete']))
	{
		$db->Execute("DELETE FROM ".tbl("mass_embed")." WHERE mass_embed_id='".mysql_clean($_POST['delete'])."' ");
		e("Video id ".$_POST['delete']." has been deleted","m");
	}
	elseif(isset($_POST['website']) && !empty($_POST['website']))
	{
		$db->Execute("DELETE FROM ".tbl("mass_embed")." WHERE mass_embed_website ='".mysql_clean($_POST['website'])."' ");
		e("All video's of ".$_POST['website']." has been deleted","m");
	}
	else {
		e("please enter video id or website","e");
	}
}
$cond=NULL;
if(isset($_POST['search_btn'])){

	if(isset($_POST['search_id']) && !empty($_POST['search_id']))
	{
		$cond= " mass_embed_id='".mysql_clean($_POST['search_id'])." ' ";
		e("You are searching for video id ".$_POST['search_id'],"m");
	}
	elseif(isset($_POST['search_website']) && !empty($_POST['search_website']))
	{
		$cond= " mass_embed_website='".mysql_clean($_POST['search_website'])." ' ";
		e("You are searching for website ".$_POST['search_website'],"m");
	}
	else {
		$cond=NULL;
		e("please enter video id or website","e");
	}
}

$page = mysql_clean($_GET['page']);
$get_limit = create_query_limit($page,RESULTS);

$query_count = $db->select(tbl("mass_embed"),"*",$cond,NULL," mass_embed_id  DESC limit ".$get_limit);

$query = $db->select(tbl("mass_embed"),"*",$cond,NULL," mass_embed_id  DESC ");


assign("_link_",BASEURL."/admin_area/plugin.php?folder=cb_mass_embed/admin&file=mass_embed_table.php");
$qs = $_SERVER['QUERY_STRING'];
$qs = preg_replace('/\&delete\=([0-9]+)/','',$qs);
assign('the_link',$_SERVER['PHP_SELF'].'?'.$qs);
//Collecting Data for Pagination


$total_rows  = count($query);
$total_pages = count_pages($total_rows,RESULTS);
$pages->paginate($total_pages,$page);

assign('records',$query);
template_files('mass_embed_table.html',PLUG_DIR.'/cb_mass_embed/admin');

?>