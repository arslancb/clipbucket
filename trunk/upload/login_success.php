<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';

$userquery->logincheck();
header('location:'.BASEURL.'/index.php');
?>
<script language="javascript">

pause_time = 2000;
function transfer()
{
   setTimeout('location.href="<?php 
   
   if(!empty($_COOKIE['pageredir'])){
   echo $_COOKIE['pageredir'];
   Assign('link',$_COOKIE['pageredir']);
   }else{
   echo BASEURL.'/index.php';
   Assign('link',BASEURL.'/index.php');
   }
    ?>";',pause_time);
}
</script>
<?php

subtitle('login_succes');

@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('login_success.html');
Template('footer.html');

?>