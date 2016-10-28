<?php
require_once PLUG_DIR.'/common_library/common_library.php';
require_once('../includes/common.php');

/**
 * Install locales for this plugin
 */
global $cbplugin;
if ($cbplugin->is_installed('common_library.php')){
	require_once PLUG_DIR.'/common_library/common_library.php';
	$folder= PLUG_DIR.'/'.basename(dirname(__FILE__))."/lang";
	importLangagePack($folder,'en');
	importLangagePack($folder,'fr');
	installPluginAdminPermissions("speaker", "Video Speaker administration", "Allow video speaker management");
}

/**
 * Create Table for video speakers if not exists 
 */
function installSpeaker() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("speaker").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`firstname` varchar(100) NOT NULL ,
	  		`lastname` varchar(100) NOT NULL ,
			`slug` varchar(100) NOT NULL ,
	  		`photo` varchar(200) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
}


/**
 * Create Table for video speaker Role if not exists 
 */
function installSpeakerfunction() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("speakerfunction").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`description` longtext,
			`speaker_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
	$db->Execute(
		'ALTER TABLE '.tbl("speakerfunction").'
  			ADD CONSTRAINT `speakerfunc_speaker_id_fk_speaker_id` FOREIGN KEY (`speaker_id`) REFERENCES '.tbl("speaker").' (`id`);
		'
	);
}


/**
 * Create a join table for linking speaker Roles to videos 
 */
function installVideospeaker() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("video_speaker").' (
			`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
			`video_id` bigint(20) NOT NULL,
			`speakerfunction_id` int(11) NOT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
	$db->Execute(
		'ALTER TABLE '.tbl("video_speaker").'
  			ADD UNIQUE KEY `video_id` (`video_id`,`speakerfunction_id`);
		'
	);
	$db->Execute(
		'ALTER TABLE '.tbl("video_speaker").'
			ADD CONSTRAINT `speakerfunction_id_fk_speakerfunction_id` FOREIGN KEY (`speakerfunction_id`) REFERENCES '.tbl("speakerfunction").' (`id`);
			'
	);
}

installSpeaker();
installSpeakerfunction();
installVideospeaker();
?>