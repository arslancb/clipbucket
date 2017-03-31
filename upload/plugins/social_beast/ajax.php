<?php
	require '../../includes/config.inc.php';
	if (isset($_POST)) {
		global $db;
		$data = $_POST;
		$flds = array();
		$vals = array();

		foreach ($data as $network => $link) {
			if (!empty($network)) {
				$flds[] = $network;
			}

			if (!empty($link)) {
				$vals[] = $link;
			}
		}

		$db->update(tbl("social_beast_links"),$flds,$vals," id!=''");

	}
?>