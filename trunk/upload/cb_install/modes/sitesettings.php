<?php

//Lets just save admin settings so we can move forward

$uid = 1;
$pass = pass_code(post('password'));

$db->update(tbl("users"),array
('username','password','email','doj','num_visits','ip','signup_ip','background_color','total_groups','banned_users'),array
(post('username'),$pass,post('email'),now(),1,$_SERVER['REMOTE_ADDR'],$_SERVER['REMOTE_ADDR'],'',0,''),"userid='$uid'");

//Login user
$userquery->login_user(post('username'),post('password'))

?><?//=msg_arr(array('msg'=>'Admin details have been updated'))?>


</div>

<div class="nav_des clearfix">
    <div class="cb_container">
    <h4 style="color:#fff">Website basic configurations</h4>
    <p style="color:#fff; font-size:13px;">here you can set basic configuration of your website, you can change them later by going to Admin area &gt; Website Configurations
    </p>

    </div><!--cb_container-->
    </div><!--nav_des-->
<!--<h2>Website basic configurations</h2>
here you can set basic configuration of your website, you can change them later by going to Admin area &gt; Website Configurations

<p>-->
<div id="sub_container" class="br5px">

 <div class="db_image"><img src="<?php echo installer_path(); ?>images/site_setting.png" style="margin-top: 28px;margin-left: 545px;"  width="280" height="280"/></div>
<div class="site_fields"  style="margin-top:-290px;">
<form name="installation" method="post" id="installation">
    
    <div class="field" >
    <label for="title">Website title</label>
    <input name="title" type="text" id="title" class="form-control" value="ClipBucket v<?php echo VERSION.' '.STATE?>">
     <p class="grey-text font-size" style="margin-top:0px;">Its your website title and you can change it from admin area.</p>
    </div>
    
    <div class="field">
    <label for="slogan">Website Slogan</label>
    <input name="slogan" type="text" id="slogan" class="form-control"value="A way to broadcast yourself">
     <p class="grey-text font-size" style="margin-top:0px;">Its a slogan of your website and you can change it from admin area.</p>
    </div>
    
    <div class="field">
    <label for="baseurl">Website URL</label>
    <input name="baseurl" type="text" id="baseurl" class="form-control" value="<?=BASEURL?>">
	  <p class="grey-text font-size" style="margin-top:0px;">without trailing slash '/'</p>
    </div>
    
    
  <input type="hidden" name="mode" value="register" />
     <p><br>
    
   <?=button('Save and Continue',' onclick="$(\'#installation\').submit()" ');?>
</form>

</div>