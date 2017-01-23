<?php
		global $Upload;

		// creates folders to hold files
		$file_directory = createDataFolders();
		$file_name = time().RandomString(5);
		$vidDetails = array
		(
			'title' => "video title here",
			'description' => "video description here",
			'tags' => genTags(str_replace(' ',', ',"text to generate tags from")),
			'category' => array($cbvid->get_default_cid()),
			'file_name' => $file_name,
			'file_directory' => $file_directory,
			'userid' => userid(),
			'video_version' => '2.7',
		);
		
		$vid = $Upload->submit_upload($vidDetails);

		if ($vid) {
			$original_file = 'path_to_orig_file';
			$new_dest = TEMP_DIR.'/'.$file_name.'.mp4';
			rename($original_file, $new_dest);
			if (file_exists($new_dest)) {
				exec(php_path()." -q ".BASEDIR."/actions/video_convert.php {$new_dest} {$file_name} {$file_directory} {$logFile} > /dev/null &");
			}
		}
?>