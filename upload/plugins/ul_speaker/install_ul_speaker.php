<?php
require_once('../includes/common.php');

//Creating Table for video speakers if not exists
function install_ul_speaker() {
	global $db;
	$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("speaker").' (
	  		`id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  		`firstname` varchar(100) NOT NULL UNIQUE KEY ,
	  		`lastname` varchar(100) NOT NULL UNIQUE KEY ,
			`slug` varchar(100) NOT NULL UNIQUE KEY ,
	  		`photo` varchar(200) DEFAULT NULL
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;;'
	);
}


//Creating Table for video speaker Role if not exists
function install_ul_speakerfunction() {
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

//Creating Table for video speaker Role if not exists
function install_ul_video_speaker() {
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
	/* Foreign Key not available on "video" table because the table is store in MyISAM and not in INNODB
	$db->Execute(
		'ALTER TABLE '.tbl("video_speaker").'
			ADD CONSTRAINT `speakerfunction_id_fk_speakerfunction_id` FOREIGN KEY (`speakerfunction_id`) REFERENCES '.tbl("speakerfunction").' (`id`),
			ADD CONSTRAINT `video_speaker_video_id_fk_videoid` FOREIGN KEY (`video_id`) REFERENCES '.tbl("video").' (`videoid`);
			'
	);
	*/
}


//This will first check if plugin is installed or not, if not this function will install the plugin details
install_ul_speaker();
install_ul_speakerfunction();
install_ul_video_speaker();

?>