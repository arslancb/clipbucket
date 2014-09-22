
</div>


<div class="nav_des clearfix">
    <div class="cb_container">
<h4 style="color:#fff">Admin Settings</h4>
<p style="color:#fff; font-size:13px;">All major steps are done, now enter username and password for your admin, 
by default its username : <strong>admin</strong> | pass : <strong>admin</strong><br />
we now update installation details and insert language phrases.</p>
<p>
<?php


function GetServerProtocol()
{
	if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on'){
	return 'https://';}else
	{
		$protocol = preg_replace('/^([a-z]+)\/.*$/', '\\1', strtolower($_SERVER['SERVER_PROTOCOL']));
		$protocol .= '://';
		return $protocol;
	}
}

function GetServerURL()
{
	return GetServerProtocol().$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
}

$released = $Cbucket->cbinfo;

$baseurl = dirname(GetServerURL());
if(substr($baseurl,strlen($baseurl)-1,1)=='/')
	$baseurl = substr($baseurl,0,strlen($baseurl)-1);
	
$db->update(tbl("config"),array("value"),array($baseurl)," name='baseurl'");
$db->update(tbl("config"),array("value"),array(BASEDIR)," name='basedir'");
$db->update(tbl("config"),array("value"),array($released['release_date'])," name='date_released'");
$db->update(tbl("config"),array("value"),array(now())," name='date_updated'");
$db->update(tbl("config"),array("value"),array(now())," name='date_installed'");
$db->update(tbl("config"),array("value"),array($released['version'])," name='version'");
$db->update(tbl("config"),array("value"),array($released['state'])," name='type'");

//$arr['msg'] = "Installation details have been updated";
//echo msg_arr($arr);
echo '<span class="glyphicon glyphicon-ok"  style="color:#fff"></span><span style="color:#fff"> Installation details have been updated</span>';

$lang_obj->updateFromPack('en');

//$arr['msg'] = "Language phrases have been imported";
//echo "<br>".msg_arr($arr);
echo '<br><span class="glyphicon glyphicon-ok"  style="color:#fff"></span><span style="color:#fff"> Language phrases have been imported</span>';
?>

</div><!--cb_container-->
</div><!--nav_des-->
<div id="sub_container" class="br5px">

    <div class="db_image"><img src="<?php echo installer_path(); ?>images/user_thumb.png" style="margin-top: 38px;margin-left: 495px;"  width="250" height="280"/></div>
<form name="installation" method="post" id="installation">
    
    <div class="field" style="margin-top:-310px;">
    <label class="grey-text" for="username">Admin username</label>
    <input name="username" type="text" id="username" class="form-control"  value="admin">
    <p class="grey-text font-size" style="margin-top:0px;">Username can have only alphanumeric characters, Underscores.</p>
    </div><br>
    <div class="field">
    <label class="grey-text" for="password">Admin Password</label>
    <input name="password" type="password" id="password" class="form-control"  value="admin">
    	<a href="javascript:void(0)" onclick="

        var pass = password(8,true);
        
        $('#genPass').html(pass);
        $('#password').val(pass);

        " >Generate</a> | <strong>Current</strong> : <span id="genPass" style="color:#09c"><strong>admin</strong></span>
    </div><br>
    <div class="field">
    <label class="grey-text" for="email">Admin Email</label>
    <input name="email" type="text" id="email" class="form-control"  value="admin@thiswebsite.com">
    <p class="grey-text font-size" style="margin-top:0px;">Double check your email address before continuing.</p>
    </div>
    
    <input type="hidden" name="mode" value="sitesettings" /><br>
     
   <?php echo button('Save and Continue',' onclick="$(\'#installation\').submit()" ');?>
</form>
</p>