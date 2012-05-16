<?php 
/*************************
  Coppermine Photo Gallery
  ************************
  Copyright (c) 2003-2012 Coppermine Dev Team
  v1.0 originally written by Gregory Demar

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License version 3
  as published by the Free Software Foundation.
  
  ********************************************
  Coppermine version: 1.5.18
  $HeadURL: https://coppermine.svn.sourceforge.net/svnroot/coppermine/trunk/cpg1.5.x/include/makers/olympus.php $
  $Revision: 8304 $
**********************************************/
/*
	Exifer
	Extracts EXIF information from digital photos.
	
	Copyright © 2003 Jake Olefsky
	http://www.offsky.com/software/exif/index.php
	jake@olefsky.com
	
	Please see exif.php for the complete information about this software.
	
	------------
	
	This program is free software; you can redistribute it and/or modify it under the terms of 
	the GNU General Public License as published by the Free Software Foundation; either version 2 
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
	without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
	See the GNU General Public License for more details. http://www.gnu.org/copyleft/gpl.html
*/
//================================================================================================
//================================================================================================
//================================================================================================



//=================
// Looks up the name of the tag for the MakerNote (Depends on Manufacturer)
//====================================================================
function lookup_Olympus_tag($tag) {
	switch($tag) {
		case "0200": $tag = "SpecialMode";break;
		case "0201": $tag = "JpegQual";break;
		case "0202": $tag = "Macro";break;
		case "0203": $tag = "Unknown1";break;
		case "0204": $tag = "DigiZoom";break;	
		case "0205": $tag = "Unknown2";break;	
		case "0206": $tag = "Unknown3";break;	
		case "0207": $tag = "SoftwareRelease";break;	
		case "0208": $tag = "PictInfo";break;	
		case "0209": $tag = "CameraID";break;	
		case "0f00": $tag = "DataDump";break;	
		
		default: $tag = "unknown:".$tag;break;
	}
	
	return $tag;
}

//=================
// Formats Data for the data type
//====================================================================
function formatOlympusData($type,$tag,$intel,$data) {
	if($type=="ASCII") {
		
	} else if($type=="URATIONAL" || $type=="SRATIONAL") {
		$data = bin2hex($data);
		if($intel==1) $data = intel2Moto($data);
		$top = hexdec(substr($data,8,8));
		$bottom = hexdec(substr($data,0,8));
		if($bottom!=0) $data=$top/$bottom;
		else if($top==0) $data = 0;
		else $data=$top."/".$bottom;
	
		if($tag=="0204") { //DigitalZoom
			$data=$data."x";
		} 
		if($tag=="0205") { //Unknown2
			$data=$top."/".$bottom;
		} 
	} else if($type=="USHORT" || $type=="SSHORT" || $type=="ULONG" || $type=="SLONG" || $type=="FLOAT" || $type=="DOUBLE") {
		$data = bin2hex($data);
		if($intel==1) $data = intel2Moto($data);
		$data=hexdec($data);
		
		if($tag=="0201") { //JPEGQuality
			if($data == 1) $data = "SQ";
			else if($data == 2) $data = "HQ";
			else if($data == 3) $data = "SHQ";
			else $data = "Unknown: ".$data;
		}
		if($tag=="0202") { //Macro
			if($data == 0) $data = "Normal";
			else if($data == 1) $data = "Macro";
			else $data = "Unknown: ".$data;
		}
	} else if($type=="UNDEFINED") {
		
	} else {
		$data = bin2hex($data);
		if($intel==1) $data = intel2Moto($data);
	}
	
	return $data;
}



//==============================================================================
// Olympus Special data section
// - Updated by Zenphoto for new header tag in E-410/E-510/E-3 cameras. 2/24/2008
//==============================================================================
function parseOlympus($block, &$result, $seek, $globalOffset) {	
		
	if($result['Endien']=="Intel") $intel = 1;
	else $intel = 0;
	
	$model = $result['IFD0']['Model'];
	
	// New header for new DSLRs - Check for it because the
	// number of bytes that count the IFD fields differ in each case.
	// Fixed by Zenphoto 2/24/08
	$new = false;
	if (substr($block, 0, 8) == "OLYMPUS\x00") {
		$new = true;
	} else if (substr($block, 0, 7) == "OLYMP\x00\x01"
		|| substr($block, 0, 7) == "OLYMP\x00\x02") {
		$new = false;
	} else {
		// Header does not match known Olympus headers.
		// This is not a valid OLYMPUS Makernote.
		return false;
	}
	
	// Offset of IFD entry after Olympus header.
	$place = 8;
	$offset = 8;
	
	// Get number of tags (1 or 2 bytes, depending on New or Old makernote)
	$countfieldbits = $new ? 1 : 2;
	// New makernote repeats 1-byte value twice, so increment $place by 2 in either case.
	$num = bin2hex(substr($block, $place, $countfieldbits)); $place += 2;
	if ($intel == 1) $num = intel2Moto($num);
	$ntags = hexdec($num);
	$result['SubIFD']['MakerNote']['MakerNoteNumTags'] = $ntags;
	
	//loop thru all tags  Each field is 12 bytes
	for($i=0; $i < $ntags; $i++) {
		//2 byte tag
		$tag = bin2hex(substr($block, $place,2));
		$place += 2;
		if ($intel == 1) $tag = intel2Moto($tag);
		$tag_name = lookup_Olympus_tag($tag);
		
		//2 byte type
		$type = bin2hex(substr($block, $place,2));
		$place += 2;
		if ($intel == 1) $type = intel2Moto($type);
		lookup_type($type,$size);
		
		//4 byte count of number of data units
		$count = bin2hex(substr($block, $place,4));
		$place+=4;
		if ($intel == 1) $count = intel2Moto($count);
		$bytesofdata = $size * hexdec($count);
		
		//4 byte value of data or pointer to data
		$value = substr($block, $place,4);
		$place += 4;

		
		if ($bytesofdata <= 4) {
			$data = $value;
		} else {
			$value = bin2hex($value);
			if($intel==1) $value = intel2Moto($value);
			$v = fseek($seek,$globalOffset+hexdec($value));  //offsets are from TIFF header which is 12 bytes from the start of the file
			if($v == 0 && $bytesofdata < $GLOBALS['exiferFileSize']) {
				$data = fread($seek, $bytesofdata);
			} else {
				$result['Errors'] = $result['Errors']++;
			}
		}
		$formated_data = formatOlympusData($type,$tag,$intel,$data);
		
		if($result['VerboseOutput']==1) {
			$result['SubIFD']['MakerNote'][$tag_name] = $formated_data;
			if($type=="URATIONAL" || $type=="SRATIONAL" || $type=="USHORT" || $type=="SSHORT" || $type=="ULONG" || $type=="SLONG" || $type=="FLOAT" || $type=="DOUBLE") {
				$data = bin2hex($data);
				if($intel==1) $data = intel2Moto($data);
			}
			$result['SubIFD']['MakerNote'][$tag_name."_Verbose"]['RawData'] = $data;
			$result['SubIFD']['MakerNote'][$tag_name."_Verbose"]['Type'] = $type;
			$result['SubIFD']['MakerNote'][$tag_name."_Verbose"]['Bytes'] = $bytesofdata;
		} else {
			$result['SubIFD']['MakerNote'][$tag_name] = $formated_data;
		}
	}
}


?>