<?php

/**
 * file used to redirect links to outer website
 */

 include("includes/config.inc.php");
 $link = urldecode($_GET['l']);
 
 if(!$link)
 	redirect_to(BASEURL);
 if(!strstr($link,'http'))
 	$link = "http://".$link;
	

 redirect_to($link);
 
 
 exit();

?>