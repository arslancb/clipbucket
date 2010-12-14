<?php


if(isset($_POST['update']))
{
	$license = $_POST['pak_license'];
	$db->update(tbl("config"),array("value"),array($license)," name='pak_license'");
	e("Player license has been updated","m");
}
assign("configs",$Cbucket->get_configs());

template_files('pakplayer.html',PAKPLAYER_PLUG_DIR.'/admin/');

?>