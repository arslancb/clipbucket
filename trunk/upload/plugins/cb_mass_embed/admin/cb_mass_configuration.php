<?php
/* 
 ***************************************************************
 | Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.
 | @ Author 	: ArslanHassan									
 | @ Software 	: ClipBucket , © PHPBucket.com					
 ***************************************************************
*/


define("THIS_PAGE","cb_mass_embed");

$cb_mass_embed = new cb_mass_embed();
$Smarty->assign_by_ref('cb_mass_embed',$cb_mass_embed);

$cats = $cbvid->get_categories();
//pr($cb_mass_embed->get_cat_keywords());
if(isset($_POST['update']))
{
	//Setting Category Keywords
	$cat_keys = array();
	foreach($cats as $cat)
	{
		$keys = $_POST['cat_'.$cat['category_id']];
		$keys = preg_replace(array("/, /","/ ,/"),",",$keys);
		$keys = preg_replace("/\|/","",$keys);
		$cat_keys[] = $cat['category_id'].":".$keys;
	}
	$cat_keys = implode('|',$cat_keys);

	$cb_mass_embed->set_config('category_keywords',$cat_keys);
	
	//Setting Api
	$apis = $_POST['apis'];
	$apis = implode(',',$apis);
	$cb_mass_embed->set_config('apis',$apis);
	
	//Other Apis
	$configs = array(
	'results',
	'keywords',
	'sort_type',
	'time',
	'license_key',
	'import_stats',
	'import_comments',
	'cb_wp_secret_key',
	'enable_wp_integeration',
	'wp_blog_url',
	'result_type',
	'categorization',
	'mass_category',
	'max_tries',
	);
	
	foreach($configs as $config)
	{
		$cb_mass_embed->set_config($config,$_POST[$config],false);
		$cb_mass_embed->get_configs();
	}
	
	$eh->flush();
	
	/**
	 * checking if wordpress integeration is enabled or not
	 * if it is enabled then check weather credentials or valid or not
	 */
	if($_POST['enable_wp_integeration']=='yes')
	{
		$cb_mass_embed->validate_wp_input(true);
	}
	
	
	$cb_mass_embed = "";
	$cb_mass_embed = new cb_mass_embed();
	
	e("Settings have been updated","m");
}



if(is_installed("ebay_epn"))
{
	assign("show_epn","yes");
}

assign('categories',$cats);
//template_files('cb_mass_embed.html');
template_files('cb_mass_configuration.html',PLUG_DIR.'/cb_mass_embed/admin');
#template_files('test.html',PLUG_DIR.'/cb_mass_embed/admin');
?>