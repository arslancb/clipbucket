<?php
	
	/**
	* File: Watch Video Ajax
	* Description: File handles watch video ajax requests
	* @since: 4th April, 2016, ClipBucket 2.8.1 
	* @author: Saqib Razzaq
	* @modified: 8th April, 2016
	*/

	require '../includes/config.inc.php';
	if (isset($_POST['mode'])) {
		$mode = $_POST['mode'];

		switch ($mode) {
			case 'playlistMore':
				global $db, $cbvid, $Smarty;
				$load_hit = $_POST['loadHit'];
				$load_limit = $_POST['loadLimit'];
				$playlist = $_POST['playlist'];
				$start = $load_limit * $load_hit - $load_limit;
				$sql_limit = "$start, $load_limit";
				$total_items = $cbvid->action->count_playlist_items($playlist);
				$items = $cbvid->get_playlist_items( $playlist, 'playlist_items.date_added DESC', $sql_limit );
				if ($start >= $total_items) {
					echo "none";
					return false;
				}
				foreach ($items as $key => $video) {
					assign("video", $video);
					assign("control","onWatch");
					assign("pid", $video['playlist_id']);
					echo trim(Fetch('/blocks/manage/account_video.html'));
				}
				break;
			
			default:
				# code...
				break;
		}
	}

?>