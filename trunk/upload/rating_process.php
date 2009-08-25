<?php
include("includes/config.inc.php");
header("Cache-Control: no-cache");
header("Pragma: nocache");

$expire = time() + 99999999;
$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost

if($_POST){

	//setcookie('has_voted_'.$id,$id,$expire,'/',$domain,false);
	$id = (int) $_POST['id'];
	$rating = (int) $_POST['rating'];
	if($rating > 5){
		$rating = 5;
	}
	if($rating < 0){
		$rating = 0;
	}
	$rating = $rating*2;
	$msg = $myquery->RateVideo($id,$_SESSION['userid'],$rating);
	echo $msg;

}

// IF JAVASCRIPT IS DISABLED

if($_GET){
	//	setcookie('has_voted_'.$id,$id,$expire,'/',$domain,false);
	$id = (int) $_GET['id'];
	$rating = (int) $_GET['rating'];
	if($rating > 5){
		$rating = 5;
	}
	if($rating < 0){
		$rating = 0;
	}
	$rating = $rating*2;
	$msg = $myquery->RateVideo($id,$_SESSION['userid'],$rating);
	echo $msg;
}

?>