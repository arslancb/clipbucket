<?php

/**
 * License : CBLA
 * Author : Stéphane Poinsart (working at the Université de Technologie de Compiègne) , Sylvain Tognola
 */

#define("THIS_PAGE","videos");

include("../../includes/config.inc.php");
include("iframecategory.php");

//please define your own URL_SEED in config.inc.php
if(!defined('URL_SEED')) 
	define("URL_SEED","pLEAsE");

$cat = $_GET['cat'];
$catcode = $_GET['catcode'];
if (!$cat || !$catcode || sha1("".$cat.URL_SEED)!=$catcode) {
	echo "Ah ah ah, you didn't say the magic word!</br> <b>Dennis Nedry</b>";
	die();
}

/* TODO verifier si l'usager est connecté
if (! user_id('force_check_CAS')) {
	redirect_to_cas();
	die();
}
*/

//TODO: get tree flat, n'existe que dans la version UTC et mysql_clean qui marche pas 
//$params['category'] = array_keys($cbvid->get_tree_flat(mysql_clean($cat)));
//TODO: mysq_clean
$params['category'] = $cat;

//$params['featured']='yes';
$params['limit']='16';
$params['order'] = ' date_added DESC ';
$params['show_hidden'] = true;

assign('iframe_vids',get_videos($params));

var_dump(iframe_vids);

if(file_exists(LAYOUT.'/iframecategory.html'))
  Template('iframecategory.html', true);
else
  Template(dirname(__FILE__).'/default.html', false);

?>
