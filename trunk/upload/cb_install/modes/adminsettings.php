<h2>Admin Settings</h2>
All major steps are done, now enter username and password for your admin, 
by default its username : <strong>admin</strong> | pass : <strong>admin</strong><br />
we now update installation details and insert language phrases
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

$arr['msg'] = "Installation details have been updated";
echo msg_arr($arr);

$lang_obj->updateFromPack('en');

$arr['msg'] = "Language phrases have been imported";
echo "<br>".msg_arr($arr);

?>


<form name="installation" method="post" id="installation">
    
    <div class="field">
    <label for="username">Admin username</label>
    <input name="username" type="text" id="username" class="br5px" value="admin">
    </div>
    <div class="field">
    <label for="password">Admin Password</label>
    <input name="password" type="password" id="password" class="br5px" value="admin">
    	<a href="javascript:void(0)" onclick="

        var pass = password(8,true);
        
        $('#genPass').html(pass);
        $('#password').val(pass);

        " >Generate</a> | <strong>Current</strong> : <span id="genPass" style="color:#09c"><strong>admin</strong></span>
    </div>
    <div class="field">
    <label for="email">Admin Email</label>
    <input name="email" type="text" id="email" class="br5px" value="admin@thiswebsite.com">
    </div>
    
    <input type="hidden" name="mode" value="sitesettings" />
     
   <?=button('Save and Continue',' onclick="$(\'#installation\').submit()" ');?>
</form>
</p>