<?php

function uninstallWaveform() {
	global $db;
	$db->Execute(
	    'DELETE FROM `'.tbl("expand_video_manager").'` WHERE `expand_video_manager`.`evm_plugin_url` = \''.BASEDIR.'/plugins/waveform/admin/mk_and_show_waveform.php\';'
	);
}

uninstallWaveform();