<?php

//Lets just save admin settings so we can move forward

$uid = 1;
$pass = pass_code(post('password'));

$db->update(tbl("users"),array
('username','password','email','doj','num_visits','ip','signup_ip','background_color','total_groups','banned_users'),array
(post('username'),$pass,post('email'),now(),1,$_SERVER['REMOTE_ADDR'],$_SERVER['REMOTE_ADDR'],'',0,''),"userid='$uid'");

//Login user
$userquery->login_user(post('username'),post('password'))

?><?=msg_arr(array('msg'=>'Admin details have been updated'))?>
<h2>Website basic configurations</h2>
here you can set basic configuration of your website, you can change them later by going to Admin area &gt; Website Configurations

<p>


<form name="installation" method="post" id="installation">
    
    <div class="field">
    <label for="title">Website title</label>
    <input name="title" type="text" id="title" class="br5px" value="Clipbucket v2">
    </div>
    
    <div class="field">
    <label for="slogan">Website Slogan</label>
    <input name="slogan" type="text" id="slogan" class="br5px" value="A way to broadcast yourself">
    </div>
    
    <div class="field">
    <label for="baseurl">Website URL</label>
    <input name="baseurl" type="text" id="baseurl" class="br5px" value="<?=BASEURL?>"><br />
	without trailing slash '/'
    </div>
    
    
  <input type="hidden" name="mode" value="register" />
     <p>
     Please read <a href="http://docs.clip-bucket.com/clipbucket-v2/how-to-setup-clipbucket-admin-area" target="_blank"><strong>this documentation</strong></a> for complete website configurations guide </p>
   <?=button('Save and Continue',' onclick="$(\'#installation\').submit()" ');?>
</form>

</p>