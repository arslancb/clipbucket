<?php
	

	function getBeastLinks($assign = false) {
		global $db;
		$rawData = $db->select(tbl('social_beast_links'),'*',"id!=''");
		$rawData = $rawData[0];
		if ($assign) {
			foreach ($rawData as $key => $link) {
				assign($key,$link);
			}
		} else {
			return $rawData;
		}
	}

	function beastHorns() {
		$links = getBeastLinks();
		$preFix = 'scl_beast_';
		unset($links[0]);
		foreach ($links as $name => $daLink) {
			if (empty($daLink)) {
				continue;
			}
			$toAssign = $preFix.$name;
			assign($toAssign, $daLink);
		}
		assign($preFix.'all', $links);
	}

	function beastAll($vertical = false) {
		$links = getBeastLinks();
		if ($vertical) {
			$class = "class='vertical-social'";
		} else {
			$class = '';
		}
		unset($links['id']);
		echo '<link rel="stylesheet" href="'.BEAST_CSS.'">';
		echo '<link rel="stylesheet" href="'.FONT_AWESOME.'">';
		echo "<ul id='social_beast_links' $class>";
		foreach ($links as $name => $daLink) {
			if (empty($daLink)) {
				continue;
			}
			echo "<li><a href=".$daLink."><i class='fa fa-".$name."'></i> ".ucfirst($name)."</a></li>";
		}
		echo "</ul>";
	}

	function beastAllVertical () {
		beastAll(true);
	}

	function getWild() {
		add_admin_menu('Social Beast','Social Links','links.php', SOCIAL_BEAST_BASE.'/admin');
		beastHorns();
		register_anchor_function('beastAll','beastAll');
		register_anchor_function('beastAllVertical','beastAllVertical');
	}

?>