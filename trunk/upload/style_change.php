<?php
/*
 ****************************************************************************************************
 | Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.											|
 | @ Author : ArslanHassan																			|
 | @ Software : ClipBucket , © PHPBucket.com														|
 ****************************************************************************************************
*/
require 'includes/config.inc.php';

if(!empty($_REQUEST['returnto']))
{
   $return_to = $_REQUEST['returnto'];
   Assign('return_to',$return_to);
}
else
{
    $return_to = "/";
    Assign('return_to',$return_to);
}

if(ALLOW_STYLE_SELECT == 1)
{
subtitle('style_change');
Template('header.html');
Template('message.html');
Template('style_change.html');
Template('footer.html');

if(!empty($_REQUEST['style']))
{
   $style = $_REQUEST['style'];
    setcookie('sitestyle', $style, time()+315360000, '/');
    if(isset($_COOKIE['userid']))
    {
    mysql_query("UPDATE users SET site_style = '".$style."' WHERE userid = '".$_COOKIE['userid']."'");
    }
}
else
{
    redirect_to(BASEURL);
}
}
else
{
redirect_to($return_to);
}

?>