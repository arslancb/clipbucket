<?php

	global $db;


	function installWaveform() {
		global $db;
		
		$sql = 'INSERT INTO '.tbl("expand_video_manager").' (`evm_id`, `evm_plugin_url`, `evm_zone`, `evm_is_new_tab`, `evm_tab_title`) VALUES (\'\', \'/var/www/html/cb_uhdf/upload/plugins/waveform/admin/mk_and_show_waveform.php\', \'expand_video_manager_right_panel\', 1, \'Waveform\');';
		
 		//e($sql, "m");
		
		$db->Execute($sql);
	}

	installWaveform();
