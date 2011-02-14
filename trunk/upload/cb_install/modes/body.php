<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ClipBucket v2.1 Beta Installer</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Cabin:regular,bold' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript" src="functions.js"></script>
</head>
<body>
	<div id="head">
    	<span id="logo"><span style="color:#09c">ClipBucket</span> Installer v2.1 -&beta;eta</span>
    </div>
	<div id="header" class="br5px">
    	<ul class="headstatus">
        	<li <?=selected('agreement')?>>Agreement</li>
        	<li <?=selected('precheck')?>>Pre Check</li>
        	<li <?=selected('permission')?>>Permissions</li>
        	<li <?=selected('database')?>>Database</li>
        	<li <?=selected('dataimport')?>>Data import</li>
        	<li <?=selected('adminsettings')?>>Admin Settings</li>
        	<li <?=selected('sitesettings')?>>Site Settings</li>
        	<li <?=selected('register')?>>Regiser</li>
        	<li <?=selected('finish')?>>Finish</li>
        </ul>
    </div>
	<div id="container" class="br5px">
    	<?php include($mode.'.php'); ?>
    </div>
    
    <div align="center" style="padding-top:10px; color:#999">
    	Clipbucket 2.1 is an effort of Arslan Hassan, Fawaz Tahir and some great supporters.<br />
&copy; Clipbucket2007 - <?=date("Y",time())?> | Code written by Clipbucket &amp; Team
    </div>
    
    
</body>
</html>