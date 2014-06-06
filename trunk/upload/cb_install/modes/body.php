<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ClipBucket v<?=VERSION?> <?=STATE?> Installer</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Cabin:regular,bold' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
<script type="text/javascript" src="functions.js"></script>

<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" type="text/css" />
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
</head>
<body>
    

     <div class="top clearfix">
    <div class="top_tabs">
        <ul>
            <li><a href="http://clip-bucket.com/V2.7-beta-overview/">V2.7 Overview </a><span>|</span></li>
                <li><a href="http://forums.clip-bucket.com/">Forums </a><span>|</span></li>
                 <li><a href="http://clip-bucket.com/Bug_Reporter/">Bug Reporter </a><span>|</span></li>
            <li><a href="http://clip-bucket.com/contact">Support </a></li>
        </ul>
    </div><!--top_tabs-->
   <p></p> <span id="logo"><span style="color:#09c">ClipBucket</span> Installer v<?php echo VERSION?> <?php echo STATE ?></span><p></p>
</div><!--top-->


<?php if(!$upgrade):?>
<div   class="top_nav clearfix br5px" style="height:35px;">
  <div class="cb_container">
    <div class="cb_navbar">
        <ul class="headstatus" >
           <li <?=selected('agreement')?>>Agreement<span></span></li>
            <li <?=selected('precheck')?>>Pre Check<span></span></li>
            <li <?=selected('permission')?>>Permissions<span></span></li>
            <li <?=selected('database')?>>Database<span></span></li>
            <li <?=selected('dataimport')?>>Data import<span></span></li>
            <li <?=selected('adminsettings')?>>Admin Settings<span></span></li>
            <li <?=selected('sitesettings')?>>Site Settings<span></span></li>
            <li <?=selected('register')?>>Register<span></span></li>
            <li <?=selected('finish')?>>Finish<span></span></li>
        </ul>
    </div><!--top_tabs-->
</div><!--cb_container-->
</div><!--top_nav-->


   <?php else: ?>
<div  id="header" class="top_nav clearfix br5px">
  <div class="cb_container">
    <div class="cb_navbar">
        <ul class="headstatus" >
           <li  <?=selected('upgrade')?>>Upgrade</li>
            <li  <?=selected('permission')?>>permission<span></span></li>
            <li  <?=selected('dataimport')?>>dataimport<span></span></li>
            <li  <?=selected('finish_upgrade')?>>finish_upgrade<span></span></li>
          
        </ul>
    </div><!--top_tabs-->
</div><!--cb_container-->
</div><!--top_nav-->



<?php endif; ?>

    <div id="container" class="br5px">
        <?php include($mode.'.php'); ?>
    </div>
    

    
    
</body>
</html>