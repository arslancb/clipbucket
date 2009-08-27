<?php
/* 
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';
?>
<script language="javascript">

pause_time = 2000;
function transfer()
{
   setTimeout('location.href="<?php 
   echo BASEURL;
   Assign('link',BASEURL);
    ?>";',pause_time);
}
</script>
<?php

subtitle('logout_succes');

@Assign('msg',$msg);
Template('header.html');
Template('message.html');
Template('logout_success.html');
Template('footer.html');

?>
