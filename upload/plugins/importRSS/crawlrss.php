<?php

	require_once(dirname(__FILE__)."/../../includes/config.inc.php");

	function video_keygen()
	{
		
		$char_list = "ABDGHKMNORSUXWY";
		$char_list .= "123456789";

		$vkey = '';
		srand((double)microtime()*1000000);
		for($i = 0; $i < 12; $i++)
		{
		$vkey .= substr($char_list,(rand()%(strlen($char_list))), 1);
		}
			
		return $vkey;
	}

	if ($_POST['id']){
		$d = getRssDetails($_POST['id']);
	}
	else{
		exit();
	}


$month = array(
"Jan"=>"01", "Feb"=>"02", "Mar"=>"03", "Apr"=>"04", 
"Mai"=>"05", "Jun"=>"06", "Jul"=>"07", "Aug"=>"08", 
"Sep"=>"09", "Oct"=>"10", "Nov"=>"11", "Dec"=>"12"
);

// Get the file
$xmlstr = file_get_contents($d['url_rss']);

// Small conversion
$xmlstr = str_replace("media:thumbnail", "poster", $xmlstr);
$xmlstr = str_replace("media:category", "tag", $xmlstr);

// XML Object
$movies = new SimpleXMLElement($xmlstr);


	/**
	*	Generate file name
	*/
// 	echo 'key : '.video_keygen().'<br>';
// 	echo 'filename : '.time().RandomString(5).'<br>';

	if ($d['last_crawl'] == '0000-00-00 00:00:00'){
		$newlim = 0;
	
	}
	else{
		$lim = explode(" ", str_replace(":", " ", str_replace("-", " ", $d['last_crawl'])));
		$newlim = mktime(($lim[3]-$d['crawl_frequence']), $lim[4], $lim[5], $lim[1], $lim[2], $lim[0]);
	}


$cpt = 0;
/* **
 *	Loop
 */
foreach ($movies->channel->item as $video) {

	// Date        
	$date = $video->pubDate;
	// Date conversion
	$date = explode(" ", str_replace(":", " ", str_replace(",", "", $date)));
	
	$islimit = mktime($date[4], $date[5], $date[6], $month[$date[2]], $date[1], $date[3]);
	

	if ($islimit > $newlim){
		
		// Lien
		$url_cb = $video->link;
		// Title
		$title = $video->title;
		// Description
		$description = trim(str_replace("\n", "",str_replace("\t", "",strip_tags(nl2br($video->description)))));
		// Category
		$category = $video->category;
		// Poster
		$poster = $video->poster->attributes();
		// Last slash position
		$derslash = strrpos($poster, "/");
		// End of line
		$fin = substr($poster, ($derslash+1), strlen($poster));
		// Begin is the filename
		$filename = substr($fin, 0, strpos($fin, "-"));
		// List of tag
		$tag = $video->tag;
		// Date        
		$date = $date[3].'-'.$month[$date[2]].'-'.$date[1].' '.$date[4].':'.$date[5].':'.$date[6];
		
		// Insert into table import rss video queued
		$query = "INSERT INTO ".tbl("import_rss_video_queued")." (`id`, `url_cb`, `title`, `description`, `category`, `url_thumnail`, `filename`, `tags`, `date_uploaded`, `id_rss_config`) VALUES ('', '".$url_cb."', '".$title."', '".$description."', '".$category."', '".$poster."', '".$filename."', '".$tag."', '".$date."', '".$_POST['id']."');";

		$db->execute($query);
		
		$cpt++;
	}
}


	// Update value
	$last_crawl = date("Y-m-d H:i:s");
	$db->update(tbl('import_rss_config'), array('last_crawl', 'nb_new_vid_from_last_crawl'), array($last_crawl, $cpt), "id=".$_POST['id']);

	
	
	/**
	 * Count of queud video from rss for menu badge
	 */
	$nbqueued = $db->_select('SELECT COUNT(id) AS rssvid FROM '.tbl("import_rss_video_queued"));
	
//	print_r($nbqueued);
	
	if ($nbqueued[0]['rssvid'] > 0){
		$rss_badge = $nbqueued[0]['rssvid'];
	}


	echo '{"last_crawl":"'.$last_crawl.'", "nbvid":"'.$cpt.'", "totvid":"'.$rss_badge.'"}';
	
	
	/*
		DELETE FROM `import_rss_video_queued`;
		UPDATE `import_rss_config` SET `nb_new_vid_from_last_crawl` = '0', last_crawl = '0000-00-00 00:00:00';
	*/

?>