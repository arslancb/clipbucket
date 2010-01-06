<?php

/* 
****************************************************************
| Copyright (c) 2007-2010 Clip-Bucket.com. All rights reserved.	
| @ Author 	: ArslanHassan										
| @ Software 	: ClipBucket , © PHPBucket.com					
****************************************************************
****************************************************************
Copyright (c) 2007-2008 Clip-Bucket.com. All rights reserved.
****************************************************************
**/


class mass_upload extends Upload
{
	
	
	/**
	 * FUNCTION USED TO GET FILES FROM DIRECTORY
	 */
	function glob_files($source_folder, $ext='*', $sec=0)
	{
		if( !is_dir( $source_folder ) ) {
			die ( "Invalid directory.\n\n" );
		}
	   
		$FILES = glob($source_folder."\*.".$ext);
		$set_limit    = 0;
	   
		foreach($FILES as $key => $file)
		{
			if( filemtime( $file ) > $sec ){
				$FILE_LIST[$key]['path']    = substr( $file, 0, ( strrpos( $file, "\\" ) +1 ) );
				$FILE_LIST[$key]['file']    = substr( $file, ( strrpos( $file, "\\" ) +1 ) ); 
				$FILE_LIST[$key]['name']    = getName($FILE_LIST[$key]['file']);
				$FILE_LIST[$key]['size']    = filesize( $file );
				$FILE_LIST[$key]['date']    = date('Y-m-d G:i:s', filemtime( $file ) );
			}
		}
		if(!empty($FILE_LIST)){
			return $FILE_LIST;
		} else {
			die( "No files found!\n\n" );
		}
	}

	
	/**
	 * Function used to get list of available files that can be processed
	 */
	function get_files()
	{
		$files = $this->glob_files(MASS_UPLOAD_DIR);
		return $files;
	}
	
	
	/**
	 * function used to get video files only3
	 */
	function get_video_files($with_path=false)
	{
		$exts = get_vid_extensions($with_path);
		
		$vid_files = array();
		$files = $this->get_files();
		foreach($files as $file)
		{
			$ext = getext($file['file']);
			if(in_array($ext,$exts))
				$vid_files[] = $file;
		}
		return $vid_files;
	}
	
}

?>