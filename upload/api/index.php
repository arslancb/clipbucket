<?php
/**
 * @Author Mohammad Shoaib
 * 
 * Rest full Api for ClipBucket to let other application access data
 */
//header("Content-Typeapplication/json");
if(isset($_REQUEST['method']))
{
   $method = strtolower($_REQUEST['method']);
   if($method=="get")
   require_once("get.php");
   else if($method=="post")
   require_once("post.php"); 
   else if($method=="delete")
   require_once("delete.php");
   else if($method=="put")
   require_once("put.php");
   else
   echo '{"status":"Failure","msg":"Bad Request", "code":"404"}'; 
}
else
echo '{"status":"Failure","msg":"Bad Request", "code":"404"}';  

?>