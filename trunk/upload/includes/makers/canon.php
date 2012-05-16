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
  $HeadURL: https://coppermine.svn.sourceforge.net/svnroot/coppermine/trunk/cpg1.5.x/include/makers/canon.php $
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
function lookup_Canon_tag($tag) {
	
	switch($tag) {
		case "0001": $tag = "Settings 1";break;
		case "0004": $tag = "Settings 4";break;
		case "0006": $tag = "ImageType";break;
		case "0007": $tag = "FirmwareVersion";break;
		case "0008": $tag = "ImageNumber";break;
		case "0009": $tag = "OwnerName";break;
		case "000c": $tag = "CameraSerialNumber";break;	
		case "000f": $tag = "CustomFunctions";break;	
		
		default: $tag = "unknown:".$tag;break;
	}
	
	return $tag;
}

//=================
// Formats Data for the data type
//====================================================================
function formatCanonData($type,$tag,$intel,$data,$exif,&$result) {
	$place = 0;
	
	
	if($type=="ASCII") {
		$result = $data = str_replace("\0", "", $data);
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
		
	} else if($type=="USHORT" || $type=="SSHORT" || $type=="ULONG" || $type=="SLONG" || $type=="FLOAT" || $type=="DOUBLE") {
		
		$data = bin2hex($data);
		$result['RAWDATA'] = $data;
	
		if($tag=="0001") { //first chunk
			$result['Bytes']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//0
			if ($result['Bytes'] != strlen($data) / 2) return $result; //Bad chunk
			$result['Macro']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//1
			switch($result['Macro']) {
				case 1: $result['Macro'] = "Macro"; break;
				case 2: $result['Macro'] = "Normal"; break;
				default: $result['Macro'] = "Unknown";
			}
			$result['SelfTimer']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//2
			switch($result['SelfTimer']) {
				case 0: $result['SelfTimer'] = "Off"; break;
				default: $result['SelfTimer'] .= "/10s";
			}
			$result['Quality']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//3
			switch($result['Quality']) {
				case 2: $result['Quality'] = "Normal"; break;
				case 3: $result['Quality'] = "Fine"; break;
				case 5: $result['Quality'] = "Superfine"; break;
				default: $result['Quality'] = "Unknown";
			}
			$result['Flash']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//4
			switch($result['Flash']) {
				case 0: $result['Flash'] = "Off"; break;
				case 1: $result['Flash'] = "Auto"; break;
				case 2: $result['Flash'] = "On"; break;
				case 3: $result['Flash'] = "Red Eye Reduction"; break;
				case 4: $result['Flash'] = "Slow Synchro"; break;
				case 5: $result['Flash'] = "Auto + Red Eye Reduction"; break;
				case 6: $result['Flash'] = "On + Red Eye Reduction"; break;
				case 16: $result['Flash'] = "External Flash"; break;
				default: $result['Flash'] = "Unknown";
			}
			$result['DriveMode']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//5
			switch($result['DriveMode']) {
				case 0: $result['DriveMode'] = "Single/Timer"; break;
				case 1: $result['DriveMode'] = "Continuous"; break;
				default: $result['DriveMode'] = "Unknown";
			}
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//6
			$result['FocusMode']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//7
			switch($result['FocusMode']) {
				case 0: $result['FocusMode'] = "One-Shot"; break;
				case 1: $result['FocusMode'] = "AI Servo"; break;
				case 2: $result['FocusMode'] = "AI Focus"; break;
				case 3: $result['FocusMode'] = "Manual Focus"; break;
				case 4: $result['FocusMode'] = "Single"; break;
				case 5: $result['FocusMode'] = "Continuous"; break;
				case 6: $result['FocusMode'] = "Manual Focus"; break;
				default: $result['FocusMode'] = "Unknown";
			}
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//8
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//9
			$result['ImageSize']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//10
			switch($result['ImageSize']) {
				case 0: $result['ImageSize'] = "Large"; break;
				case 1: $result['ImageSize'] = "Medium"; break;
				case 2: $result['ImageSize'] = "Small"; break;
				default: $result['ImageSize'] = "Unknown";
			}
			$result['EasyShooting']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//11
			switch($result['EasyShooting']) {
				case 0: $result['EasyShooting'] = "Full Auto"; break;
				case 1: $result['EasyShooting'] = "Manual"; break;
				case 2: $result['EasyShooting'] = "Landscape"; break;
				case 3: $result['EasyShooting'] = "Fast Shutter"; break;
				case 4: $result['EasyShooting'] = "Slow Shutter"; break;
				case 5: $result['EasyShooting'] = "Night"; break;
				case 6: $result['EasyShooting'] = "Black & White"; break;
				case 7: $result['EasyShooting'] = "Sepia"; break;
				case 8: $result['EasyShooting'] = "Portrait"; break;
				case 9: $result['EasyShooting'] = "Sport"; break;
				case 10: $result['EasyShooting'] = "Macro/Close-Up"; break;
				case 11: $result['EasyShooting'] = "Pan Focus"; break;
				default: $result['EasyShooting'] = "Unknown";
			}
			$result['DigitalZoom']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//12
			switch($result['DigitalZoom']) {
				case 0:
				case 65535: $result['DigitalZoom'] = "None"; break;
				case 1: $result['DigitalZoom'] = "2x"; break;
				case 2: $result['DigitalZoom'] = "4x"; break;
				default: $result['DigitalZoom'] = "Unknown";
			}
			$result['Contrast']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//13
			switch($result['Contrast']) {
				case 0: $result['Contrast'] = "Normal"; break;
				case 1: $result['Contrast'] = "High"; break;
				case 65535: $result['Contrast'] = "Low"; break;
				default: $result['Contrast'] = "Unknown";
			}
			$result['Saturation']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//14
			switch($result['Saturation']) {
				case 0: $result['Saturation'] = "Normal"; break;
				case 1: $result['Saturation'] = "High"; break;
				case 65535: $result['Saturation'] = "Low"; break;
				default: $result['Saturation'] = "Unknown";
			}
			$result['Sharpness']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//15
			switch($result['Sharpness']) {
				case 0: $result['Sharpness'] = "Normal"; break;
				case 1: $result['Sharpness'] = "High"; break;
				case 65535: $result['Sharpness'] = "Low"; break;
				default: $result['Sharpness'] = "Unknown";
			}
			$result['ISO']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//16
			switch($result['ISO']) {
				case 32767:
				case 0: $result['ISO'] = isset($exif['SubIFD']['ISOSpeedRatings'])
					? $exif['SubIFD']['ISOSpeedRatings'] : 'Unknown'; break;
				case 15: $result['ISO'] = "Auto"; break;
				case 16: $result['ISO'] = "50"; break;
				case 17: $result['ISO'] = "100"; break;
				case 18: $result['ISO'] = "200"; break;
				case 19: $result['ISO'] = "400"; break;
				default: $result['ISO'] = "Unknown";
			}
			$result['MeteringMode']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//17
			switch($result['MeteringMode']) {
				case 3: $result['MeteringMode'] = "Evaluative"; break;
				case 4: $result['MeteringMode'] = "Partial"; break;
				case 5: $result['MeteringMode'] = "Center-weighted"; break;
				default: $result['MeteringMode'] = "Unknown";
			}
			$result['FocusType']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//18
			switch($result['FocusType']) {
				case 0: $result['FocusType'] = "Manual"; break;
				case 1: $result['FocusType'] = "Auto"; break;
				case 3: $result['FocusType'] = "Close-up (Macro)"; break;
				case 8: $result['FocusType'] = "Locked (Pan Mode)"; break;
				default: $result['FocusType'] = "Unknown";
			}
			$result['AFPointSelected']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//19
			switch($result['AFPointSelected']) {
				case 12288: $result['AFPointSelected'] = "Manual Focus"; break;
				case 12289: $result['AFPointSelected'] = "Auto Selected"; break;
				case 12290: $result['AFPointSelected'] = "Right"; break;
				case 12291: $result['AFPointSelected'] = "Center"; break;
				case 12292: $result['AFPointSelected'] = "Left"; break;
				default: $result['AFPointSelected'] = "Unknown";
			}
			$result['ExposureMode']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//20
			switch($result['ExposureMode']) {
				case 0: $result['ExposureMode'] = "EasyShoot"; break;
				case 1: $result['ExposureMode'] = "Program"; break;
				case 2: $result['ExposureMode'] = "Tv"; break;
				case 3: $result['ExposureMode'] = "Av"; break;
				case 4: $result['ExposureMode'] = "Manual"; break;
				case 5: $result['ExposureMode'] = "Auto-DEP"; break;
				default: $result['ExposureMode'] = "Unknown";
			}
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//21
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//22
			$result['LongFocalLength']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//23
				$result['LongFocalLength'] .= " focal units";
			$result['ShortFocalLength']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//24
				$result['ShortFocalLength'] .= " focal units";
			$result['FocalUnits']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//25
				$result['FocalUnits'] .= " per mm";
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//26
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//27
			$result['FlashActivity']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//28
			switch($result['FlashActivity']) {
				case 0: $result['FlashActivity'] = "Flash Did Not Fire"; break;
				case 1: $result['FlashActivity'] = "Flash Fired"; break;
				default: $result['FlashActivity'] = "Unknown";
			}
			$result['FlashDetails']=str_pad(base_convert(intel2Moto(substr($data,$place,4)), 16, 2), 16, "0", STR_PAD_LEFT);$place+=4;//29
				$flashDetails = array();
				if (substr($result['FlashDetails'], 1, 1) == 1) { $flashDetails[] = 'External E-TTL'; }
				if (substr($result['FlashDetails'], 2, 1) == 1) { $flashDetails[] = 'Internal Flash'; }
				if (substr($result['FlashDetails'], 4, 1) == 1) { $flashDetails[] = 'FP sync used'; }
				if (substr($result['FlashDetails'], 8, 1) == 1) { $flashDetails[] = '2nd(rear)-curtain sync used'; }
				if (substr($result['FlashDetails'], 12, 1) == 1) { $flashDetails[] = '1st curtain sync'; }
				$result['FlashDetails']=implode(",", $flashDetails);
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//30
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//31
			$anotherFocusMode=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//32
			if(strpos(strtoupper($exif['IFD0']['Model']), "G1") !== false) {
				switch($anotherFocusMode) {
					case 0: $result['FocusMode'] = "Single"; break;
					case 1: $result['FocusMode'] = "Continuous"; break;
					default: $result['FocusMode'] = "Unknown";
				}
			}
			
		} else if($tag=="0004") { //second chunk
			$result['Bytes']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//0
			if ($result['Bytes'] != strlen($data) / 2) return $result; //Bad chunk
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//1
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//2
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//3
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//4
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//5
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//6
			$result['WhiteBalance']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//7
			switch($result['WhiteBalance']) {
				case 0: $result['WhiteBalance'] = "Auto"; break;
				case 1: $result['WhiteBalance'] = "Sunny"; break;
				case 2: $result['WhiteBalance'] = "Cloudy"; break;
				case 3: $result['WhiteBalance'] = "Tungsten"; break;
				case 4: $result['WhiteBalance'] = "Fluorescent"; break;
				case 5: $result['WhiteBalance'] = "Flash"; break;
				case 6: $result['WhiteBalance'] = "Custom"; break;
				default: $result['WhiteBalance'] = "Unknown";
			}
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//8
			$result['SequenceNumber']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//9
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//10
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//11
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//12
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//13
			$result['AFPointUsed']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//14
				$afPointUsed = array();
				if ($result['AFPointUsed'] & 0x0001) $afPointUsed[] = "Right"; //bit 0
				if ($result['AFPointUsed'] & 0x0002) $afPointUsed[] = "Center"; //bit 1
				if ($result['AFPointUsed'] & 0x0004) $afPointUsed[] = "Left"; //bit 2
				if ($result['AFPointUsed'] & 0x0800) $afPointUsed[] = "12"; //bit 12
				if ($result['AFPointUsed'] & 0x1000) $afPointUsed[] = "13"; //bit 13
				if ($result['AFPointUsed'] & 0x2000) $afPointUsed[] = "14"; //bit 14
				if ($result['AFPointUsed'] & 0x4000) $afPointUsed[] = "15"; //bit 15
				$result['AFPointUsed'] = implode(",", $afPointUsed);
			$result['FlashBias']=intel2Moto(substr($data,$place,4));$place+=4;//15
			switch($result['FlashBias']) {
				case 'ffc0': $result['FlashBias'] = "-2 EV"; break;
				case 'ffcc': $result['FlashBias'] = "-1.67 EV"; break;
				case 'ffd0': $result['FlashBias'] = "-1.5 EV"; break;
				case 'ffd4': $result['FlashBias'] = "-1.33 EV"; break;
				case 'ffe0': $result['FlashBias'] = "-1 EV"; break;
				case 'ffec': $result['FlashBias'] = "-0.67 EV"; break;
				case 'fff0': $result['FlashBias'] = "-0.5 EV"; break;
				case 'fff4': $result['FlashBias'] = "-0.33 EV"; break;
				case '0000': $result['FlashBias'] = "0 EV"; break;
				case '000c': $result['FlashBias'] = "0.33 EV"; break;
				case '0010': $result['FlashBias'] = "0.5 EV"; break;
				case '0014': $result['FlashBias'] = "0.67 EV"; break;
				case '0020': $result['FlashBias'] = "1 EV"; break;
				case '002c': $result['FlashBias'] = "1.33 EV"; break;
				case '0030': $result['FlashBias'] = "1.5 EV"; break;
				case '0034': $result['FlashBias'] = "1.67 EV"; break;
				case '0040': $result['FlashBias'] = "2 EV"; break;
				default: $result['FlashBias'] = "Unknown";
			}
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//16
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//17
			$result['Unknown']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//18
			$result['SubjectDistance']=hexdec(intel2Moto(substr($data,$place,4)));$place+=4;//19
				$result['SubjectDistance'] .= "/100 m";
			
		} else if($tag=="0008") { //image number
			if($intel==1) $data = intel2Moto($data);
			$data=hexdec($data);
			$result = round($data/10000)."-".$data%10000;
		} else if($tag=="000c") { //camera serial number
			if($intel==1) $data = intel2Moto($data);
			$data=hexdec($data);
			$result = "#".bin2hex(substr($data,0,16)).substr($data,16,16);
		}
		
	} else if($type=="UNDEFINED") {
		
	
		
	} else {
		$data = bin2hex($data);
		if($intel==1) $data = intel2Moto($data);
	}
	
	return $data;
}



//=================
// Cannon Special data section
// Useful:  http://www.burren.cx/david/canon.html
// http://www.burren.cx/david/canon.html
// http://www.ozhiker.com/electronics/pjmt/jpeg_info/canon_mn.html
//====================================================================
function parseCanon($block,&$result,$seek, $globalOffset) {	
	$place = 0; //current place
		
	if($result['Endien']=="Intel") $intel=1;
	else $intel=0;
	
	$model = $result['IFD0']['Model'];
	
		//Get number of tags (2 bytes)
	$num = bin2hex(substr($block,$place,2));$place+=2;
	if($intel==1) $num = intel2Moto($num);
	$result['SubIFD']['MakerNote']['MakerNoteNumTags'] = hexdec($num);
	
	//loop thru all tags  Each field is 12 bytes
	for($i=0;$i<hexdec($num);$i++) {
		
			//2 byte tag
		$tag = bin2hex(substr($block,$place,2));$place+=2;
		if($intel==1) $tag = intel2Moto($tag);
		$tag_name = lookup_Canon_tag($tag);
		
			//2 byte type
		$type = bin2hex(substr($block,$place,2));$place+=2;
		if($intel==1) $type = intel2Moto($type);
		lookup_type($type,$size);
		
			//4 byte count of number of data units
		$count = bin2hex(substr($block,$place,4));$place+=4;
		if($intel==1) $count = intel2Moto($count);
		$bytesofdata = $size*hexdec($count);
	
		if($bytesofdata<=0) {
			return; //if this value is 0 or less then we have read all the tags we can
		}
		
			//4 byte value of data or pointer to data
		$value = substr($block,$place,4);$place+=4;
		
		if($bytesofdata<=4) {
			$data = $value;
		} else {
			$value = bin2hex($value);
			if($intel==1) $value = intel2Moto($value);
			$v = fseek($seek,$globalOffset+hexdec($value));  //offsets are from TIFF header which is 12 bytes from the start of the file
			if($v==0 && $bytesofdata < $GLOBALS['exiferFileSize']) {
				$data = fread($seek, $bytesofdata);
			} else if($v==-1) {
				$result['Errors'] = $result['Errors']++;
			}
		}
		$formated_data = formatCanonData($type,$tag,$intel,$data,$result,$result['SubIFD']['MakerNote'][$tag_name]);
		
		if($result['VerboseOutput']==1) {
			//$result['SubIFD']['MakerNote'][$tag_name] = $formated_data;
			if($type=="URATIONAL" || $type=="SRATIONAL" || $type=="USHORT" || $type=="SSHORT" || $type=="ULONG" || $type=="SLONG" || $type=="FLOAT" || $type=="DOUBLE") {
				$data = bin2hex($data);
				if($intel==1) $data = intel2Moto($data);
			}
			$result['SubIFD']['MakerNote'][$tag_name."_Verbose"]['RawData'] = $data;
			$result['SubIFD']['MakerNote'][$tag_name."_Verbose"]['Type'] = $type;
			$result['SubIFD']['MakerNote'][$tag_name."_Verbose"]['Bytes'] = $bytesofdata;
		} else {
			//$result['SubIFD']['MakerNote'][$tag_name] = $formated_data;
		}
	}
}


?>