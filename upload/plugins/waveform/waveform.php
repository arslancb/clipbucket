<?php
/*
Plugin Name: Waveform
Description: /!\ Use Expand Video Manager. Add functionnality in the admin page in order to trace the waveform
Author: Adrien Ponchelet
Author Website: https://www.u-picardie.fr
ClipBucket Version: 2.8.1 rc1
Version: 0.1
*/

	/**
	 *	Test Expand Video Manager
	 */
	$structure = BASEDIR.'/files/waveform';
	
 	if (file_exists(BASEDIR.'/files/waveform')){

	}
	else{
 		if (!mkdir($structure, 0777, true)) {
 			die('Failed to create folders...');
 		}
	
	}
