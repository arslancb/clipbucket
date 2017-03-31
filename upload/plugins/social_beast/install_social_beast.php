<?php
	require_once('../includes/common.php');

	function produce_beast() {
		global $db;
		$db->Execute(
		'CREATE TABLE IF NOT EXISTS '.tbl("social_beast_links").' (
		`id` int(20) NOT NULL AUTO_INCREMENT,
		`facebook` TEXT NOT NULL,
		`twitter` TEXT NOT NULL,
		`google` TEXT NOT NULL,
		`linkedin` TEXT NOT NULL,
		`pinterest` TEXT NOT NULL,
		`reddit` TEXT NOT NULL,
		`youtube` TEXT NOT NULL,
		`vine` TEXT NOT NULL,
		`rss` TEXT NOT NULL,
		`github` TEXT NOT NULL,
		`dropbox` TEXT NOT NULL,
		`stumbleupon` TEXT NOT NULL,
		 PRIMARY KEY (id)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;;'
		);

		$db->Execute("INSERT INTO  ".tbl('social_beast_links')." (`id`, `facebook`, `twitter`, `google`, `linkedin`, `pinterest`, `reddit`, `youtube`, `vine`, `rss`, `github`, `dropbox`, `stumbleupon`) VALUES (NULL, '', '', '', '', '', '', '', '', '', '', '', '');");
	}

	produce_beast()

?>