<?php
$userquery->get_user_details();
$adminemail = $userquery->get_user_field_only(1,'email');

$title = mysql_clean(post('title'));
$slogan = mysql_clean(post('slogan'));
$baseurl = mysql_clean(post('baseurl'));

//First update website settings
$myquery->Set_Website_Details('site_title',$title);
$myquery->Set_Website_Details('site_slogan',$slogan);
$myquery->Set_Website_Details('baseurl',$baseurl);

?>
</div>
<div class="nav_des clearfix">
    <div class="cb_container">
    <h4 style="color:#fff">Registeration (Optional)</h4>
    <p style="color:#fff; font-size:13px;">As you have installed ClipBucket Succesffuly, we highly recommend you to register your website on our Clipbucket. its really simple, just click on Register and continue and your website will be register on Clipbucket website</p>



</div><!--cb_container-->
</div><!--nav_des-->
<!--<h2>Registeration (Optional)</h2>
As you have installed ClipBucket Succesffuly, we highly recommend you to register your website on our Clipbucket. its really simple, just click on Register and continue and your website will be register on Clipbucket website<br />
<br />-->



<div id="sub_container" class="br5px">
<h3>Why we suggest registeration?</h3>
<div class="register">
  <div class="db_image"><img src="<?php echo installer_path(); ?>images/reg_thumb.png" style="margin-top: 15px;margin-left: 740px;"  width="130" height="120"/></div>
<ul  class="grey-text" style="margin-top:-145px;">
  <br>
  <li>Get imediate security updates</li>
  <li>Help us count how many websites are using Clipbucket</li>
  <li>Grow our community</li>
  <li>Easy interaction between Clipbucket and Webmasters</li>
  <li>We only save your email and website url</li>
  <li>We will not share your email to anyone</li>
  <li>We will integerate bug tracker pretty soon and you will be able to report bugs easily if you are registered</li>
</ul>
</div>
<br>
<form name="installation" method="post" id="installation">
    <input type="hidden" name="mode" value="finish" />
     <?=button('Skip & Finish',' onclick="$(\'#installation\').submit()" ',true);?>
    <?=button('Register & Finish',' onclick="register(\''.$adminemail.'\',\''.urlencode(BASEURL).'\')" ');?>
    <span id="loadingReg"></span>
  
</form>

