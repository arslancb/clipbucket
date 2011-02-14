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

?><h2>Registeration (Optional)</h2>
As you have installed ClipBucket Succesffuly, we highly recommend you to register your website on our Clipbucket. its really simple, just click on Register and continue and your website will be register on Clipbucket website<br />
<br />
<h3>Why we suggest registeration?</h3>
<ul>
  <li>Get imediate security updates</li>
  <li>Help us count how many websites are using Clipbucket</li>
  <li>Grow our community</li>
  <li>Easy interaction between Clipbucket and Webmasters</li>
</ul>

<ul>
  <li>We only save your email and website url</li>
  <li>We will not share your email to anyone</li>
  <li>We will integerate bug tracker pretty soon and you will be able to report bugs easily if you are registered</li>
</ul>
<p>Like us on facebook</p>
<p><iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FClipBucket%2F189702491050380&amp;width=292&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=true&amp;height=62" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe></p>


<form name="installation" method="post" id="installation">
    <input type="hidden" name="mode" value="finish" />
     <?=button('Skip & Finish',' onclick="$(\'#installation\').submit()" ',true);?>
    <?=button('Register & Finish',' onclick="register(\''.$adminemail.'\',\''.urlencode(BASEURL).'\')" ');?>
    <span id="loadingReg"></span>
  
</form>

