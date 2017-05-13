<?php
/*
Plugin Name: IFrame Category 
Description: Provides an iframe url displaying the latest videos of a specific category. 
Author: Stéphane Poinsart, Sylvain Tognola
Author Website: http://corbac.fr
ClipBucket Version: 2
Version: 1.0
Plugin Type: global

*/

//please define your own URL_SEED in config.inc.php
if(!defined('URL_SEED')) 
	define("URL_SEED","pLEAsE");

if(!function_exists('iframe_category'))
{
  
	function iframe_category($icat)
	{
	  $cat = strval($icat);
	  $selfurl = "/plugins/iframecategory/display.php";
    $url = $selfurl."?cat=".$cat."&amp;catcode=".sha1("".$cat.URL_SEED);
    return $url;
	}
	
	function iframe_category_echo($icat){ echo iframe_category($icat); }
  register_anchor_function("iframe_category_echo","iframe_category"); //{ANCHOR place='iframe_category' data="category_id"}
}


?>
