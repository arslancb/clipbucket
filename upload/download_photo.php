<?php
/**
 * FILE : Download
 * Function : Download video
 * @author : Arslan Hassan
 * @Software : ClipBucket
 * @Since : 1 Feb, 2010
 *
 * @License : Attribution Assurance License -- http://www.opensource.org/licenses/attribution.php
 */
 
define("THIS_PAGE",'download_photo');
define("PARENT_PAGE",'photos');

require 'includes/config.inc.php';
//$userquery->perm_check('download_video',true);
$pages->page_redir();

$key = $cbphoto->decode_key($_GET['download']);

if(empty($key))
	header("location:".BASEURL);
else
{
	$cbphoto->download_photo($key);
}

display_it();
?>