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
  $HeadURL: https://coppermine.svn.sourceforge.net/svnroot/coppermine/trunk/cpg1.5.x/include/exif.php $
  $Revision: 8304 $


**********************************************/
/*
    Exifer 1.6
    Extracts EXIF information from digital photos.
    
    Originally created by:
    Copyright 2005 Jake Olefsky
    http:// www.offsky.com/software/exif/index.php
    jake@olefsky.com
    
    This program is free software; you can redistribute it and/or modify it under the terms of 
    the GNU General Public License as published by the Free Software Foundation; either version 2 
    of the License, or (at your option) any later version.

    This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; 
    without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
    See the GNU General Public License for more details. http:// www.gnu.org/copyleft/gpl.html
    
    SUMMARY:
                This script will correctly parse all of the EXIF data included in images taken
                with digital cameras.  It will read the IDF0, IDF1, SubIDF and InteroperabilityIFD
                fields as well as parsing some of the MakerNote fields that vary depending on
                camera make and model.  This script parses more tags than the internal PHP exif
                implementation and it will correctly identify and decode what all the values mean.
                
                This version will correctly parse the MakerNote field for Nikon, Olympus, and Canon 
                digital cameras.  Others will follow.
                
    TESTED WITH:
                Nikon CoolPix 700
                Nikon CoolPix E3200
                Nikon CoolPix 4500
                Nikon CoolPix 950
                Nikon Coolpix 5700
                Canon PowerShot S200
                Canon PowerShot S110
                Olympus C2040Z 
                Olympus C960
                Olumpus E-300
                Olympus E-410
                Olympus E-500
                Olympus E-510
                Olympus E-3
                Canon Ixus
                Canon EOS 300D
                Canon Digital Rebel
                Canon EOS 10D
                Canon PowerShot G2
                FujiFilm DX 10
                FujiFilm MX-1200
                FujiFilm FinePix2400
                FujiFilm FinePix2600
                FujiFilm FinePix S602
                FujiFilm FinePix40i
                Sony D700
                Sony Cybershot
                Kodak DC210
                Kodak DC240
                Kodak DC4800
                Kodak DX3215
                Ricoh RDC-5300 
                Sanyo VPC-G250
                Sanyo VPC-SX550
                Epson 3100z

    
    VERSION HISTORY:
    
    1.0   September 23, 2002   
    
        + First Public Release
    
    1.1    January 25, 2003
        
        + Gracefully handled the error case where you pass an empty string to this library
        + Fixed an inconsistency in the Olympus Camera parsing module
        + Added support for parsing the MakerNote of Canon images.
        + Modified how the imagefile is opened so it works for windows machines.
        + Correctly parses the FocalPlaneResolutionUnit and PhotometricInterpretation fields
        + Negative rational numbers are properly displayed
        + Strange old cameras that use Motorola endineness are now properly supported
        + Tested with several more cameras
        
        Potential Problem: Negative Shorts and Negative Longs may not be correctly displayed, but I
            have not yet found an example of negative shorts or longs being used.
    
    1.2    March 30, 2003
    
        + Fixed an error that was displayed if you edited your image with WinXP's image viewer
        + Fixed a bug that caused some images saved from 3rd party software to not parse correctly
        + Changed the ExposureTime tag to display in fractional seconds rather than decimal
        + Updated the ShutterSpeedValue tag to have the units of 'sec'
        + Added support for parsing the MakeNote of FujiFilm images
        + Added support for parsing the MakeNote of Sanyo images
        + Fixed a bug with parsing some Olympus MakerNote tags
        + Tested with several more cameras
            
    1.3    June 15, 2003
    
        + Fixed Canon MakerNote support for some models
            (Canon has very difficult and inconsistent MakerNote syntax)
        + Negative signed shorts and negative signed longs are properly displayed
        + Several more tags are defined
        + More information in my comments about what each tag is
        + Parses and Displays GPS information if available
        + Tested with several more cameras
            
    1.4    September 14, 2003
        
        + This software is now licensed under the GNU General Public License
        + Exposure time is now correctly displayed when the numerator is 10
        + Fixed the calculation and display of ShutterSpeedValue, ApertureValue and MaxApertureValue 
        + Fixed a bug with the GPS code
        + Tested with several more cameras
            
    1.5    February 18, 2005
    
        + It now gracefully deals with a passed in file that cannot be found.
        + Fixed a GPS bug for the parsing of Altitude and other signed rational numbers
        + Defined more values for Canon cameras.
        + Added 'bulb' detection for ShutterSpeed
        + Made script loading a little faster and less memory intensive.
        + Bug fixes
        + Better error reporting
        + Graceful failure for files with corrupt exif info.
        + QuickTime (including iPhoto) messes up the Makernote tag for certain photos (no workaround yet)
        + Now reads exif information when the jpeg markers are out of order
        + Gives raw data output for IPTC, COM and APP2 fields which are sometimes set by other applications
        + Improvements to Nikon Makernote parsing
            
    1.6    March 25th, 2007 [Zenphoto]
    
        + Adopted into the Zenphoto gallery project, at http://www.zenphoto.org
        + Fixed a bug where strings had trailing null bytes.
        + Formatted selected strings better.
        + Added calculation of 35mm-equivalent focal length when possible.
        + Cleaned up code for readability and efficiency.
        
    1.7    April 11th, 2008 [Zenphoto]
      
      + Fixed bug with newer Olympus cameras where number of fields was miscalculated leading to bad performance.
        + More logical fraction calculation for shutter speed.
        
*/



    


//================================================================================================
// Converts from Intel to Motorola endien.  Just reverses the bytes (assumes hex is passed in)
//================================================================================================

function intel2Moto($intel) {
    $len  = strlen($intel);
    $moto = '';
    for($i = 0; $i <= $len; $i += 2) {
        $moto .= substr($intel, $len-$i, 2);
    }
    return $moto;
}


//================================================================================================
// Looks up the name of the tag
//================================================================================================
function lookup_tag($tag) {
    switch($tag) {
        // used by IFD0 'Camera Tags'
        case '000b': $tag = 'ACDComment'; break;               // text string up to 999 bytes long
        case '00fe': $tag = 'ImageType'; break;                // integer -2147483648 to 2147483647
        case '0106': $tag = 'PhotometricInterpret'; break;     // ?? Please send sample image with this tag
        case '010e': $tag = 'ImageDescription'; break;         // text string up to 999 bytes long
        case '010f': $tag = 'Make'; break;                     // text string up to 999 bytes long
        case '0110': $tag = 'Model'; break;                    // text string up to 999 bytes long
        case '0112': $tag = 'Orientation'; break;              // integer values 1-9
        case '0115': $tag = 'SamplePerPixel'; break;           // integer 0-65535
        case '011a': $tag = 'xResolution'; break;              // positive rational number
        case '011b': $tag = 'yResolution'; break;              // positive rational number
        case '011c': $tag = 'PlanarConfig'; break;             // integer values 1-2
        case '0128': $tag = 'ResolutionUnit'; break;           // integer values 1-3
        case '0131': $tag = 'Software'; break;                 // text string up to 999 bytes long
        case '0132': $tag = 'DateTime'; break;                 // YYYY:MM:DD HH:MM:SS
        case '013b': $tag = 'Artist'; break;                   // text string up to 999 bytes long
        case '013c': $tag = 'HostComputer'; break;             // text string
        case '013e': $tag = 'WhitePoint'; break;               // two positive rational numbers
        case '013f': $tag = 'PrimaryChromaticities'; break;    // six positive rational numbers
        case '0211': $tag = 'YCbCrCoefficients'; break;        // three positive rational numbers
        case '0213': $tag = 'YCbCrPositioning'; break;         // integer values 1-2
        case '0214': $tag = 'ReferenceBlackWhite'; break;      // six positive rational numbers
        case '8298': $tag = 'Copyright'; break;                // text string up to 999 bytes long
        case '8649': $tag = 'PhotoshopSettings'; break;        // ??  
        case '8825': $tag = 'GPSInfoOffset'; break;            
        case '9286': $tag = 'UserCommentOld'; break;           // ??
        case '8769': $tag = 'ExifOffset'; break;               // positive integer
        // used by Exif SubIFD 'Image Tags'                    
        case '829a': $tag = 'ExposureTime'; break;             // seconds or fraction of seconds 1/x
        case '829d': $tag = 'FNumber'; break;                  // positive rational number
        case '8822': $tag = 'ExposureProgram'; break;          // integer value 1-9
        case '8824': $tag = 'SpectralSensitivity'; break;      // ??
        case '8827': $tag = 'ISOSpeedRatings'; break;          // integer 0-65535
        case '9000': $tag = 'ExifVersion'; break;              // ??
        case '9003': $tag = 'DateTimeOriginal'; break;         // YYYY:MM:DD HH:MM:SS
        case '9004': $tag = 'DateTimedigitized'; break;        // YYYY:MM:DD HH:MM:SS
        case '9101': $tag = 'ComponentsConfiguration'; break;  // ??
        case '9102': $tag = 'CompressedBitsPerPixel'; break;   // positive rational number
        case '9201': $tag = 'ShutterSpeedValue'; break;        // seconds or fraction of seconds 1/x
        case '9202': $tag = 'ApertureValue'; break;            // positive rational number
        case '9203': $tag = 'BrightnessValue'; break;          // positive rational number
        case '9204': $tag = 'ExposureBiasValue'; break;        // positive rational number (EV)
        case '9205': $tag = 'MaxApertureValue'; break;         // positive rational number
        case '9206': $tag = 'SubjectDistance'; break;          // positive rational number (meters)
        case '9207': $tag = 'MeteringMode'; break;             // integer 1-6 and 255
        case '9208': $tag = 'LightSource'; break;              // integer 1-255
        case '9209': $tag = 'Flash'; break;                    // integer 1-255
        case '920a': $tag = 'FocalLength'; break;              // positive rational number (mm)
        case '9213': $tag = 'ImageHistory'; break;             // text string up to 999 bytes long
        case '927c': $tag = 'MakerNote'; break;                // a bunch of data
        case '9286': $tag = 'UserComment'; break;              // text string
        case '9290': $tag = 'SubsecTime'; break;               // text string up to 999 bytes long
        case '9291': $tag = 'SubsecTimeOriginal'; break;       // text string up to 999 bytes long
        case '9292': $tag = 'SubsecTimeDigitized'; break;      // text string up to 999 bytes long
        case 'a000': $tag = 'FlashPixVersion'; break;          // ??
        case 'a001': $tag = 'ColorSpace'; break;               // values 1 or 65535
        case 'a002': $tag = 'ExifImageWidth'; break;           // ingeter 1-65535
        case 'a003': $tag = 'ExifImageHeight'; break;          // ingeter 1-65535
        case 'a004': $tag = 'RelatedSoundFile'; break;         // text string 12 bytes long
        case 'a005': $tag = 'ExifInteroperabilityOffset'; break;    // positive integer
        case 'a20c': $tag = 'SpacialFreqResponse'; break;      // ??
        case 'a20b': $tag = 'FlashEnergy'; break;              // positive rational number
        case 'a20e': $tag = 'FocalPlaneXResolution'; break;    // positive rational number
        case 'a20f': $tag = 'FocalPlaneYResolution'; break;    // positive rational number
        case 'a210': $tag = 'FocalPlaneResolutionUnit'; break; // values 1-3
        case 'a214': $tag = 'SubjectLocation'; break;          // two integers 0-65535
        case 'a215': $tag = 'ExposureIndex'; break;            // positive rational number
        case 'a217': $tag = 'SensingMethod'; break;            // values 1-8
        case 'a300': $tag = 'FileSource'; break;               // integer
        case 'a301': $tag = 'SceneType'; break;                // integer
        case 'a302': $tag = 'CFAPattern'; break;               // undefined data type
        case 'a401': $tag = 'CustomerRender'; break;           // values 0 or 1
        case 'a402': $tag = 'ExposureMode'; break;             // values 0-2
        case 'a403': $tag = 'WhiteBalance'; break;             // values 0 or 1
        case 'a404': $tag = 'DigitalZoomRatio'; break;         // positive rational number
        case 'a406': $tag = 'SceneCaptureMode'; break;         // values 0-3
        case 'a407': $tag = 'GainControl'; break;              // values 0-4
        case 'a408': $tag = 'Contrast'; break;                 // values 0-2
        case 'a409': $tag = 'Saturation'; break;               // values 0-2
        case 'a40a': $tag = 'Sharpness'; break;                // values 0-2

        // used by Interoperability IFD
        case '0001': $tag = 'InteroperabilityIndex'; break;    // text string 3 bytes long
        case '0002': $tag = 'InteroperabilityVersion'; break;  // datatype undefined
        case '1000': $tag = 'RelatedImageFileFormat'; break;   // text string up to 999 bytes long
        case '1001': $tag = 'RelatedImageWidth'; break;        // integer in range 0-65535
        case '1002': $tag = 'RelatedImageLength'; break;       // integer in range 0-65535
        
        // used by IFD1 'Thumbnail'
        case '0100': $tag = 'ImageWidth'; break;               // integer in range 0-65535
        case '0101': $tag = 'ImageLength'; break;              // integer in range 0-65535
        case '0102': $tag = 'BitsPerSample'; break;            // integers in range 0-65535
        case '0103': $tag = 'Compression'; break;              // values 1 or 6
        case '0106': $tag = 'PhotometricInterpretation'; break;// values 0-4
        case '010e': $tag = 'ThumbnailDescription'; break;     // text string up to 999 bytes long
        case '010f': $tag = 'ThumbnailMake'; break;            // text string up to 999 bytes long
        case '0110': $tag = 'ThumbnailModel'; break;           // text string up to 999 bytes long
        case '0111': $tag = 'StripOffsets'; break;             // ??
        case '0112': $tag = 'ThumbnailOrientation'; break;     // integer 1-9
        case '0115': $tag = 'SamplesPerPixel'; break;          // ??
        case '0116': $tag = 'RowsPerStrip'; break;             // ??
        case '0117': $tag = 'StripByteCounts'; break;          // ??
        case '011a': $tag = 'ThumbnailXResolution'; break;     // positive rational number
        case '011b': $tag = 'ThumbnailYResolution'; break;     // positive rational number
        case '011c': $tag = 'PlanarConfiguration'; break;      // values 1 or 2
        case '0128': $tag = 'ThumbnailResolutionUnit'; break;  // values 1-3    
        case '0201': $tag = 'JpegIFOffset'; break;        
        case '0202': $tag = 'JpegIFByteCount'; break;
        case '0212': $tag = 'YCbCrSubSampling'; break;
    
        // misc
        case '00ff': $tag = 'SubfileType'; break;
        case '012d': $tag = 'TransferFunction'; break;
        case '013d': $tag = 'Predictor'; break;
        case '0142': $tag = 'TileWidth'; break;
        case '0143': $tag = 'TileLength'; break;
        case '0144': $tag = 'TileOffsets'; break;
        case '0145': $tag = 'TileByteCounts'; break;
        case '014a': $tag = 'SubIFDs'; break;
        case '015b': $tag = 'JPEGTables'; break;
        case '828d': $tag = 'CFARepeatPatternDim'; break;
        case '828e': $tag = 'CFAPattern'; break;
        case '828f': $tag = 'BatteryLevel'; break;
        case '83bb': $tag = 'IPTC/NAA'; break;
        case '8773': $tag = 'InterColorProfile'; break;
        
        case '8828': $tag = 'OECF'; break;
        case '8829': $tag = 'Interlace'; break;
        case '882a': $tag = 'TimeZoneOffset'; break;
        case '882b': $tag = 'SelfTimerMode'; break;
        case '920b': $tag = 'FlashEnergy'; break;
        case '920c': $tag = 'SpatialFrequencyResponse'; break;
        case '920d': $tag = 'Noise'; break;
        case '9211': $tag = 'ImageNumber'; break;
        case '9212': $tag = 'SecurityClassification'; break;
        case '9214': $tag = 'SubjectLocation'; break;
        case '9215': $tag = 'ExposureIndex'; break;
        case '9216': $tag = 'TIFF/EPStandardID'; break;
        case 'a20b': $tag = 'FlashEnergy'; break;
        
        default: $tag = 'unknown:'.$tag; break;
    }
    return $tag;

}


//================================================================================================
// Looks up the datatype
//================================================================================================
function lookup_type(&$type,&$size) {
    switch($type) {
        case '0001': $type = 'UBYTE'; $size=1; break;
        case '0002': $type = 'ASCII'; $size=1; break;
        case '0003': $type = 'USHORT'; $size=2; break;
        case '0004': $type = 'ULONG'; $size=4; break;
        case '0005': $type = 'URATIONAL'; $size=8; break;
        case '0006': $type = 'SBYTE'; $size=1; break;
        case '0007': $type = 'UNDEFINED'; $size=1; break;
        case '0008': $type = 'SSHORT'; $size=2; break;
        case '0009': $type = 'SLONG'; $size=4; break;
        case '000a': $type = 'SRATIONAL'; $size=8; break;
        case '000b': $type = 'FLOAT'; $size=4; break;
        case '000c': $type = 'DOUBLE'; $size=8; break;
        default: $type = 'error:'.$type; $size=0; break;
    }
    return $type;
}

//================================================================================================
// Formats Data for the data type
//================================================================================================
function formatData($type,$tag,$intel,$data) {

    if ($type == 'ASCII') {
        // Search for a null byte and stop there.
        if (($pos = strpos($data, chr(0))) !== false) {
            $data = substr($data, 0, $pos);
        }
        // Format certain kinds of strings nicely (Camera make etc.)
        if ($tag == '010f') $data = ucwords(strtolower(trim($data)));

    } else if ($type == 'URATIONAL' || $type == 'SRATIONAL') {
        $data = bin2hex($data);
        if ($intel == 1) $data = intel2Moto($data);
        
        if ($intel == 1) $top = hexdec(substr($data,8,8));   // intel stores them bottom-top
        else  $top = hexdec(substr($data,0,8));        // motorola stores them top-bottom
        
        if ($intel == 1) $bottom = hexdec(substr($data,0,8));  // intel stores them bottom-top
        else  $bottom = hexdec(substr($data,8,8));      // motorola stores them top-bottom
        
        if ($type == 'SRATIONAL' && $top > 2147483647) $top = $top - 4294967296;    // this makes the number signed instead of unsigned
        if ($bottom != 0) $data=$top/$bottom;
        else if ($top == 0) $data = 0;
        else $data = $top.'/'.$bottom;
        
        if (($tag == '011a' || $tag == '011b') && $bottom == 1) { // XResolution YResolution
            $data = $top.' dots per ResolutionUnit';
        } else if ($tag == '829a') { // Exposure Time
            if (($bottom % $top) == 0) {
                $data = '1/'.round($bottom/$top, 0).' sec'; 
            }   else {
                if ($bottom == 1) {
                    $data = $top.' sec';
                } else {
                    $data = $top.'/'.$bottom.' sec';
                }
            }
        } else if ($tag == '829d') { // FNumber
            $data = 'f/'.$data;
        } else if ($tag == '9204') { // ExposureBiasValue
            $data = round($data, 2) . ' EV';
        } else if ($tag == '9205' || $tag == '9202') { // ApertureValue and MaxApertureValue
            // ApertureValue is given in the APEX Mode. Many thanks to Matthieu Froment for this code
            // The formula is : Aperture = 2*log2(FNumber) <=> FNumber = e((Aperture.ln(2))/2)
            $data = exp(($data*log(2))/2);
            $data = round($data, 1);// Focal is given with a precision of 1 digit.
            $data='f/'.$data; 
        } else if ($tag == '920a') { // FocalLength
            $data = $data.' mm';
        } else if ($tag == '9201') { // ShutterSpeedValue
            // The ShutterSpeedValue is given in the APEX mode. Many thanks to Matthieu Froment for this code
            // The formula is : Shutter = - log2(exposureTime) (Appendix C of EXIF spec.)
            // Where shutter is in APEX, log2(exposure) = ln(exposure)/ln(2)
            // So final formula is : exposure = exp(-ln(2).shutter)
            // The formula can be developed : exposure = 1/(exp(ln(2).shutter))
            $data = exp($data * log(2)); 
            if ($data > 1) $data = floor($data);  // Drop the decimal.
            if ($data > 0) {
                $data = 1/$data; // Final calculation. We now have a floating number. Transform it in a pretty number
                $n=0; $d=0;
                ConvertToFraction($data, $n, $d); 
                if ($n >= 1 && $d == 1) $data = $n.' sec'; // To avoid exposure times style 3/1 sec.
                else $data = $n.'/'.$d.' sec';
            } else {
                $data = 'Bulb';
            }
        } 
        
    } else if ($type == 'USHORT' || $type == 'SSHORT' || $type == 'ULONG' || $type == 'SLONG' || $type == 'FLOAT' || $type == 'DOUBLE') {
        $data = bin2hex($data);
        if ($intel == 1) $data = intel2Moto($data);
        if ($intel == 0 && ($type == 'USHORT' || $type == 'SSHORT')) $data = substr($data,0,4);
        $data = hexdec($data);
        
        if ($type == 'SSHORT' && $data > 32767)     $data = $data - 65536;  // this makes the number signed instead of unsigned
        if ($type == 'SLONG' && $data > 2147483647) $data = $data - 4294967296;  // this makes the number signed instead of unsigned
        
        if ($tag == '0112') { // Orientation
            // Example of how all of these tag formatters should be...
            switch ($data) {
                case 1  :   $data = '1: Normal (0 deg)';      break;
                case 2  :   $data = '2: Mirrored';            break;
                case 3  :   $data = '3: Upsidedown';          break;
                case 4  :   $data = '4: Upsidedown Mirrored'; break;
                case 5  :   $data = '5: 90 deg CW Mirrored';  break;
                case 6  :   $data = '6: 90 deg CCW';          break;
                case 7  :   $data = '7: 90 deg CCW Mirrored'; break;
                case 8  :   $data = '8: 90 deg CW';           break;
                default :   $data = 'Unknown: '.$data;
            }
            
        } else if ($tag == '0128' || $tag == 'a210' || $tag == '0128') {  // ResolutionUnit and FocalPlaneResolutionUnit and ThumbnailResolutionUnit
            if ($data == 1)         $data = 'No Unit';
            else if ($data == 2)    $data = 'Inch';
            else if ($data == 3)    $data = 'Centimeter';
            
        } else if ($tag == '0213') { // YCbCrPositioning
            if ($data == 1)         $data = 'Center of Pixel Array';
            else if ($data == 2)    $data = 'Datum Point';
            
        } else if ($tag == '8822') { // ExposureProgram
            if ($data == 1)         $data = 'Manual';
            else if ($data == 2)    $data = 'Program';
            else if ($data == 3)    $data = 'Aperture Priority';
            else if ($data == 4)    $data = 'Shutter Priority';
            else if ($data == 5)    $data = 'Program Creative';
            else if ($data == 6)    $data = 'Program Action';
            else if ($data == 7)    $data = 'Portrait';
            else if ($data == 8)    $data = 'Landscape';
			else if ($data == 9)    $data = 'Blub';
            else                    $data = 'Unknown: '.$data;
            
        } else if ($tag == '9207') { // MeteringMode
            if ($data == 0) $data = 'Unknown';
            else if ($data == 1)    $data = 'Average';
            else if ($data == 2)    $data = 'Center Weighted Average';
            else if ($data == 3)    $data = 'Spot';
            else if ($data == 4)    $data = 'Multi-Spot';
            else if ($data == 5)    $data = 'Multi-Segment';
            else if ($data == 6)    $data = 'Partial';
            else if ($data == 255)  $data = 'Other';
            else                    $data = 'Unknown: '.$data;
            
        } else if ($tag == '9208') { // LightSource
            if ($data == 0) $data = 'Unknown or Auto';
            else if ($data == 1)    $data = 'Daylight';
            else if ($data == 2)    $data = 'Flourescent';
            else if ($data == 3)    $data = 'Tungsten';
			else if ($data == 4)    $data = 'Flash';
			else if ($data == 9)    $data = 'Fine Weather';
            else if ($data == 10)   $data = 'Cloudy';
			else if ($data == 11)   $data = 'Shade';
            else if ($data == 17)   $data = 'Standard Light A';
            else if ($data == 18)   $data = 'Standard Light B';
            else if ($data == 19)   $data = 'Standard Light C';
            else if ($data == 20)   $data = 'D55';
            else if ($data == 21)   $data = 'D65';
            else if ($data == 22)   $data = 'D75';
			else if ($data == 23)   $data = 'D50';
			else if ($data == 24)   $data = 'ISO Studio Tungsten';
            else if ($data == 255)  $data = 'Other';
            else                    $data = 'Unknown: '.$data;
            
        } else if ($tag == '9209') { // Flash
            if ($data == 0) $data = 'No Flash';
            else if ($data == 1)    $data = 'Flash';
            else if ($data == 5)    $data = 'Flash, strobe return light not detected';
            else if ($data == 7)    $data = 'Flash, strob return light detected';
			else if ( $data == 8) 	$data ='On, Did not fire';
            else if ($data == 9)    $data = 'Compulsory Flash';
            else if ($data == 13)   $data = 'Compulsory Flash, Return light not detected';
            else if ($data == 15)   $data = 'Compulsory Flash, Return light detected';
            else if ($data == 16)   $data = 'Off, Did not fire';
			else if ( $data == 20 ) $data = 'Off, Did not fire, Return not detected';
            else if ($data == 24)   $data = 'Auto, Did not fire';
            else if ($data == 25)   $data = 'Flash, Auto-Mode';
            else if ($data == 29)   $data = 'Flash, Auto-Mode, Return light not detected';
            else if ($data == 31)   $data = 'Flash, Auto-Mode, Return light detected';
            else if ($data == 32)   $data = 'No flash function';
			else if ( $data == 48 ) $data = 'Off';
            else if ($data == 65)   $data = 'Red Eye';
            else if ($data == 69)   $data = 'Red Eye, Return light not detected';
            else if ($data == 71)   $data = 'Red Eye, Return light detected';
            else if ($data == 73)   $data = 'Red Eye, Compulsory Flash';
            else if ($data == 77)   $data = 'Red Eye, Compulsory Flash, Return light not detected';
            else if ($data == 79)   $data = 'Red Eye, Compulsory Flash, Return light detected';
			else if ( $data == 80 ) $data = 'Off, Red-eye reduction';
			else if ( $data == 88 ) $data = 'Auto, Did not fire, Red-eye reduction';
            else if ($data == 89)   $data = 'Red Eye, Auto-Mode';
            else if ($data == 93)   $data = 'Red Eye, Auto-Mode, Return light not detected';
            else if ($data == 95)   $data = 'Red Eye, Auto-Mode, Return light detected';
            else                    $data = 'Unknown: '.$data;
            
        } else if ($tag == 'a001') { // ColorSpace
            if ($data == 1)         $data = 'sRGB';
            else                    $data = 'Uncalibrated';
            
        } else if ($tag == 'a002' || $tag == 'a003') { // ExifImageWidth/Height
            $data = $data. ' pixels';
            
        } else if ($tag == '0103') { // Compression
            if ($data == 1)      $data = 'No Compression';
            else if ($data == 6) $data = 'Jpeg Compression';
            else                 $data = 'Unknown: '.$data;
            
        } else if ($tag == 'a217') { // SensingMethod
            if ($data == 1)      $data = 'Not defined';
            else if ($data == 2)      $data = 'One Chip Color Area Sensor';
            else if ($data == 3)      $data = 'Two Chip Color Area Sensor';
            else if ($data == 4)      $data = 'Three Chip Color Area Sensor';
            else if ($data == 5)      $data = 'Color Sequential Area Sensor';
            else if ($data == 7)      $data = 'Trilinear Sensor';
            else if ($data == 8)      $data = 'Color Sequential Linear Sensor';
            else                 $data = 'Unknown: '.$data;
            
        } else if ($tag == '0106') { // PhotometricInterpretation
            if ($data == 1)      $data = 'Monochrome';
            else if ($data == 2) $data = 'RGB';
            else if ($data == 6) $data = 'YCbCr';
            else                 $data = 'Unknown: '.$data;
        } else if ( $tag == 'a407' ) { // GainControl
			if ( $data == 0 ) $data = "None";
			if ( $data == 1 ) $data = "Low gain up";
			if ( $data == 2 ) $data = "High gain up";
			if ( $data == 3 ) $data = "Low gain down";
			if ( $data == 4 ) $data = "High gain down";
		} else if ( $tag == 'a408' || $tag == 'a409') { // Contrast & Saturation
			if ( $data == 0 ) $data = "Normal";
			if ( $data == 1 ) $data = "Low";
			if ( $data == 2 ) $data = "High";
		} else if ( $tag == 'a40a' )  { // Sharpness
			if ( $data == 0 ) $data = "Normal";
			if ( $data == 1 ) $data = "Soft";
			if ( $data == 2 ) $data = "Hard";
		} else if ( $tag == 'a402' ) { // ExposureMode
			if ( $data == 0 ) $data = "Auto";
			if ( $data == 1 ) $data = "Manual";
			if ( $data == 2 ) $data = "Auto bracket";
		} else if ( $tag == 'a401' ) { // CustomRendered
			if ( $data == 0 ) $data = "Normal";
			if ( $data == 1 ) $data = "Custom";
		} else if ( $tag == 'a403' ) { // WhiteBalance
			if ( $data == 0 ) $data = "Auto";
			if ( $data == 1 ) $data = "Manual";
		} else if ( $tag == 'a406' ) { // SceneCaptureType
			if ( $data == 0 ) $data = "Standard";
			if ( $data == 1 ) $data = "Landscape";
			if ( $data == 2 ) $data = "Portrait";
			if ( $data == 3 ) $data = "Night";
		}
    
    } else if ($type == 'UNDEFINED') {
        
        if ($tag == '9000' || $tag == 'a000' || $tag == '0002') { // ExifVersion,FlashPixVersion,InteroperabilityVersion
            $data='version '.$data/100;
        }
        if ($tag == 'a300') { // FileSource
            $data = bin2hex($data);
            $data = str_replace('00','',$data);
            $data = str_replace('03','Digital Still Camera',$data);
        }
        if ($tag == 'a301') { // SceneType
            $data = bin2hex($data);
            $data = str_replace('00','',$data);
            $data = str_replace('01','Directly Photographed',$data);
        }
        if ($tag == '9101') {  // ComponentsConfiguration
            $data = bin2hex($data);
            $data = str_replace('01','Y',$data);
            $data = str_replace('02','Cb',$data);
            $data = str_replace('03','Cr',$data);
            $data = str_replace('04','R',$data);
            $data = str_replace('05','G',$data);
            $data = str_replace('06','B',$data);
            $data = str_replace('00','',$data);
        }
    } else {
        $data = bin2hex($data);
        if ($intel == 1) $data = intel2Moto($data);
    }
    
    return $data;
}



//================================================================================================
// Reads one standard IFD entry
//================================================================================================
function read_entry(&$result,$in,$seek,$intel,$ifd_name,$globalOffset) {
    
    if (feof($in)) { // test to make sure we can still read.
        $result['Errors'] = $result['Errors']+1;
        return;
    }
    
    // 2 byte tag
    $tag = bin2hex(fread($in, 2));
    if ($intel == 1) $tag = intel2Moto($tag);
    $tag_name = lookup_tag($tag);
    
    // 2 byte datatype
    $type = bin2hex(fread($in, 2));
    if ($intel == 1) $type = intel2Moto($type);
    lookup_type($type, $size);
    
    // 4 byte number of elements
    $count = bin2hex(fread($in, 4));
    if ($intel == 1) $count = intel2Moto($count);
    $bytesofdata = $size*hexdec($count);
    
    // 4 byte value or pointer to value if larger than 4 bytes
    $value = fread( $in, 4 );
    
    if ($bytesofdata <= 4) {   // if datatype is 4 bytes or less, its the value
        $data = $value;
    } else if ($bytesofdata < 100000) {        // otherwise its a pointer to the value, so lets go get it
        $value = bin2hex($value);
        if ($intel == 1) $value = intel2Moto($value);
        $v = fseek($seek, $globalOffset+hexdec($value));  // offsets are from TIFF header which is 12 bytes from the start of the file
        if ($v == 0) {
            $data = fread($seek, $bytesofdata);
        } else if ($v == -1) {
            $result['Errors'] = $result['Errors']+1;
        }
    } else { // bytesofdata was too big, so the exif had an error
        $result['Errors'] = $result['Errors']+1;
        return;
    }
    if ($tag_name == 'MakerNote') { // if its a maker tag, we need to parse this specially
        $make = $result['IFD0']['Make'];
        
        if ($result['VerboseOutput'] == 1) {
            $result[$ifd_name]['MakerNote']['RawData'] = $data;
        }
        if (stripos($make, 'NIKON') !== FALSE) {
            require_once('makers/nikon.php');
            parseNikon($data,$result);
            $result[$ifd_name]['KnownMaker'] = 1;
        } else if (stripos($make, 'OLYMPUS') !== FALSE) {
            require_once('makers/olympus.php');
            parseOlympus($data,$result,$seek,$globalOffset);
            $result[$ifd_name]['KnownMaker'] = 1;
        } else if (stripos($make, 'CANON') !== FALSE) {
            require_once('makers/canon.php');
            parseCanon($data,$result,$seek,$globalOffset);
            $result[$ifd_name]['KnownMaker'] = 1;
        } else if (stripos($make, 'FUJIFILM') !== FALSE) {
            require_once('makers/fujifilm.php');
            parseFujifilm($data,$result);
            $result[$ifd_name]['KnownMaker'] = 1;
        } else if (stripos($make, 'SANYO') !== FALSE) {
            require_once('makers/sanyo.php');
            parseSanyo($data,$result,$seek,$globalOffset);
            $result[$ifd_name]['KnownMaker'] = 1;
    } else if (stripos($make, 'PANASONIC') !== FALSE) {
        require_once('makers/panasonic.php'); 
        parsePanasonic($data,$result,$seek,$globalOffset); 
        $result[$ifd_name]['KnownMaker'] = 1; 
        } else {
            $result[$ifd_name]['KnownMaker'] = 0;
        }
    } else if ($tag_name == 'GPSInfoOffset') {
        require_once('makers/gps.php');
        $formated_data = formatData($type,$tag,$intel,$data);
        $result[$ifd_name]['GPSInfo'] = $formated_data;
        parseGPS($data,$result,$formated_data,$seek,$globalOffset);
    } else {
        // Format the data depending on the type and tag
        $formated_data = formatData($type,$tag,$intel,$data);
        
        $result[$ifd_name][$tag_name] = $formated_data;
        
        if ($result['VerboseOutput'] == 1) {
            if ($type == 'URATIONAL' || $type == 'SRATIONAL' || $type == 'USHORT' || $type == 'SSHORT' || $type == 'ULONG' || $type == 'SLONG' || $type == 'FLOAT' || $type == 'DOUBLE') {
                $data = bin2hex($data);
                if ($intel == 1) $data = intel2Moto($data);
            }
            $result[$ifd_name][$tag_name.'_Verbose']['RawData'] = $data;
        $result[$ifd_name][$tag_name.'_Verbose']['Type'] = $type;
            $result[$ifd_name][$tag_name.'_Verbose']['Bytes'] = $bytesofdata;
        }
    }
}


//================================================================================================
// Pass in a file and this reads the EXIF data
//
// Usefull resources
// http:// www.ba.wakwak.com/~tsuruzoh/Computer/Digicams/exif-e.html
// http:// www.w3.org/Graphics/JPEG/jfif.txt
// http:// exif.org/
// http:// www.ozhiker.com/electronics/pjmt/library/list_contents.php4
// http:// www.ozhiker.com/electronics/pjmt/jpeg_info/makernotes.html
// http:// pel.sourceforge.net/
// http:// us2.php.net/manual/en/function.exif-read-data.php
//================================================================================================
function read_exif_data_raw($path,$verbose) {
    if ($path == '' || $path == 'none') return;
    
    $in = @fopen($path, 'rb'); // the b is for windows machines to open in binary mode
    $seek = @fopen($path, 'rb'); // There may be an elegant way to do this with one file handle.
    
    $globalOffset = 0;
    
    if (!isset($verbose)) $verbose=0;
    
    $result['VerboseOutput'] = $verbose;
    $result['Errors'] = 0;
    
    if (!$in || !$seek) {  // if the path was invalid, this error will catch it
        $result['Errors'] = 1;
        $result['Error'][$result['Errors']] = 'The file could not be found.';
        return $result;
    }
    
    // First 2 bytes of JPEG are 0xFFD8 
    $data = bin2hex(fread( $in, 2 ));
    if ($data == 'ffd8') {
        $result['ValidJpeg'] = 1;
    } else {
        $result['ValidJpeg'] = 0;
        fclose($in);
        fclose($seek);
        return $result;
    }  
    
    $result['ValidIPTCData'] = 0;
    $result['ValidJFIFData'] = 0;
    $result['ValidEXIFData'] = 0;
    $result['ValidAPP2Data'] = 0;
    $result['ValidCOMData'] = 0;
    
    // Next 2 bytes are MARKER tag (0xFFE#)
    $data = bin2hex(fread( $in, 2 ));
    $size = bin2hex(fread( $in, 2 ));
    
    // LOOP THROUGH MARKERS TILL YOU GET TO FFE1  (exif marker)
    while(!feof($in) && $data!='ffe1' && $data!='ffc0' && $data!='ffd9') {
        if ($data == 'ffe0') { // JFIF Marker
            $result['ValidJFIFData'] = 1;
            $result['JFIF']['Size'] = hexdec($size);
            
            if (hexdec($size)-2 > 0) {
                $data = fread( $in, hexdec($size)-2);
                $result['JFIF']['Data'] = $data;
            } 
            
            $result['JFIF']['Identifier'] = substr($data,0,5);
            $result['JFIF']['ExtensionCode'] =  bin2hex(substr($data,6,1));
            
            $globalOffset+=hexdec($size)+2;
            
        } else if ($data == 'ffed') {  // IPTC Marker
            $result['ValidIPTCData'] = 1;
            $result['IPTC']['Size'] = hexdec($size);
            
            if (hexdec($size)-2 > 0) {
                $data = fread( $in, hexdec($size)-2);
                $result['IPTC']['Data'] = $data ;
            } 
            $globalOffset+=hexdec($size)+2;
            
        } else if ($data == 'ffe2') {  // EXIF extension Marker
            $result['ValidAPP2Data'] = 1;
            $result['APP2']['Size'] = hexdec($size);
            
            if (hexdec($size)-2 > 0) {
                $data = fread( $in, hexdec($size)-2);
                $result['APP2']['Data'] = $data ;
            } 
            $globalOffset+=hexdec($size)+2;
            
        } else if ($data == 'fffe') {  // COM extension Marker
            $result['ValidCOMData'] = 1;
            $result['COM']['Size'] = hexdec($size);
            
            if (hexdec($size)-2 > 0) {
                $data = fread( $in, hexdec($size)-2);
                $result['COM']['Data'] = $data ;
            } 
            $globalOffset+=hexdec($size)+2;
            
        } else if ($data == 'ffe1') {
            $result['ValidEXIFData'] = 1;
        }
    
        $data = bin2hex(fread( $in, 2 ));
        $size = bin2hex(fread( $in, 2 ));
    }
    // END MARKER LOOP
    
    if ($data == 'ffe1') {
        $result['ValidEXIFData'] = 1;
    } else {
        fclose($in);
        fclose($seek);
        return $result;
    }
    
    // Size of APP1 
    $result['APP1Size'] = hexdec($size);
    
    // Start of APP1 block starts with 'Exif' header (6 bytes)
    $header = fread( $in, 6 );
    
    // Then theres a TIFF header with 2 bytes of endieness (II or MM) 
    $header = fread( $in, 2 );
    if ($header==='II') {
        $intel=1;
        $result['Endien'] = 'Intel';
    } else if ($header==='MM') {
        $intel=0;
        $result['Endien'] = 'Motorola';
    } else {
        $intel=1; // not sure what the default should be, but this seems reasonable
        $result['Endien'] = 'Unknown';
    }
    
    // 2 bytes of 0x002a
    $tag = bin2hex(fread( $in, 2 ));
    
    // Then 4 bytes of offset to IFD0 (usually 8 which includes all 8 bytes of TIFF header)
    $offset = bin2hex(fread( $in, 4 ));
    if ($intel == 1) $offset = intel2Moto($offset);
    
    // Check for extremely large values here
    if (hexdec($offset) > 100000) {
            $result['ValidEXIFData'] = 0;
        fclose($in);
        fclose($seek);
        return $result;
    }

    if (hexdec($offset)>8) $unknown = fread( $in, hexdec($offset)-8); // fixed this bug in 1.3
    
    // add 12 to the offset to account for TIFF header
    $globalOffset+=12;
    
    
    //===========================================================
    // Start of IFD0
    $num = bin2hex(fread( $in, 2 ));
    if ($intel == 1) $num = intel2Moto($num);
    $num = hexdec($num);
    $result['IFD0NumTags'] = $num;
    
    if ($num<1000) { // 1000 entries is too much and is probably an error.
        for($i=0; $i<$num; $i++) {
            read_entry($result,$in,$seek,$intel,'IFD0',$globalOffset);
        }
    } else {
        $result['Errors'] = $result['Errors']+1;
        $result['Error'][$result['Errors']] = 'Illegal size for IFD0';
    }
    
    // store offset to IFD1
    $offset = bin2hex(fread( $in, 4 ));
    if ($intel == 1) $offset = intel2Moto($offset);
    $result['IFD1Offset'] = hexdec($offset);
    
    // Check for SubIFD
    if (!isset($result['IFD0']['ExifOffset']) || $result['IFD0']['ExifOffset'] == 0) {
        fclose($in);
        fclose($seek);
        return $result;
    }
    
    // seek to SubIFD (Value of ExifOffset tag) above.
    $ExitOffset = $result['IFD0']['ExifOffset'];
    $v = fseek($in,$globalOffset+$ExitOffset);
    if ($v == -1) {
        $result['Errors'] = $result['Errors']+1;
        $result['Error'][$result['Errors']] = 'Couldnt Find SubIFD';
    }
    
    //===========================================================
    // Start of SubIFD
    $num = bin2hex(fread( $in, 2 ));
    if ($intel == 1) $num = intel2Moto($num);
    $num = hexdec($num);
    $result['SubIFDNumTags'] = $num;
    
    if ($num<1000) { // 1000 entries is too much and is probably an error.
        for($i=0; $i<$num; $i++) {
            read_entry($result,$in,$seek,$intel,'SubIFD',$globalOffset);
        }
    } else {
        $result['Errors'] = $result['Errors']+1;
        $result['Error'][$result['Errors']] = 'Illegal size for SubIFD';
    }
    
    // Add the 35mm equivalent focal length:
    $result['SubIFD']['FocalLength35mmEquiv'] = get35mmEquivFocalLength($result);
    
    // Check for IFD1
    if (!isset($result['IFD1Offset']) || $result['IFD1Offset'] == 0) {
        fclose($in);
        fclose($seek);
        return $result;
    }
    // seek to IFD1
    $v = fseek($in,$globalOffset+$result['IFD1Offset']);
    if ($v == -1) {
        $result['Errors'] = $result['Errors']+1;
        $result['Error'][$result['Errors']] = 'Couldnt Find IFD1';
    }
    
    //===========================================================
    // Start of IFD1
    $num = bin2hex(fread( $in, 2 ));
    if ($intel == 1) $num = intel2Moto($num);
    $num = hexdec($num);
    $result['IFD1NumTags'] = $num;
    
    if ($num<1000) { // 1000 entries is too much and is probably an error.
        for($i=0; $i<$num; $i++) {
            read_entry($result,$in,$seek,$intel,'IFD1',$globalOffset);
        }
    } else {
        $result['Errors'] = $result['Errors']+1;
        $result['Error'][$result['Errors']] = 'Illegal size for IFD1';
    }
    // If verbose output is on, include the thumbnail raw data...
    if ($result['VerboseOutput'] == 1 && $result['IFD1']['JpegIFOffset']>0 && $result['IFD1']['JpegIFByteCount']>0) {
            $v = fseek($seek,$globalOffset+$result['IFD1']['JpegIFOffset']);
            if ($v == 0) {
                $data = fread($seek, $result['IFD1']['JpegIFByteCount']);
            } else if ($v == -1) {
                $result['Errors'] = $result['Errors']+1;
            }
            $result['IFD1']['ThumbnailData'] = $data;
    } 
    
    
    // Check for Interoperability IFD
    if (!isset($result['SubIFD']['ExifInteroperabilityOffset']) || $result['SubIFD']['ExifInteroperabilityOffset'] == 0) {
        fclose($in);
        fclose($seek);
        return $result;
    }
    // Seek to InteroperabilityIFD
    $v = fseek($in,$globalOffset+$result['SubIFD']['ExifInteroperabilityOffset']);
    if ($v == -1) {
        $result['Errors'] = $result['Errors']+1;
        $result['Error'][$result['Errors']] = 'Couldnt Find InteroperabilityIFD';
    }
    
    //===========================================================
    // Start of InteroperabilityIFD
    $num = bin2hex(fread( $in, 2 ));
    if ($intel == 1) $num = intel2Moto($num);
    $num = hexdec($num);
    $result['InteroperabilityIFDNumTags'] = $num;
    
    if ($num<1000) { // 1000 entries is too much and is probably an error.
        for($i=0; $i<$num; $i++) {
            read_entry($result,$in,$seek,$intel,'InteroperabilityIFD',$globalOffset);
        }
    } else {
        $result['Errors'] = $result['Errors']+1;
        $result['Error'][$result['Errors']] = 'Illegal size for InteroperabilityIFD';
    }
    fclose($in);
    fclose($seek);
    return $result;
}

//================================================================================================
// Converts a floating point number into a fraction.  Many thanks to Matthieu Froment for this code
//================================================================================================
function ConvertToFraction($v, &$n, &$d)
{
    $MaxTerms = 15;         // Limit to prevent infinite loop
    $MinDivisor = 0.000001; // Limit to prevent divide by zero
    $MaxError = 0.00000001; // How close is enough

    $f = $v; // Initialize fraction being converted

    $n_un = 1; // Initialize fractions with 1/0, 0/1
    $d_un = 0;
    $n_deux = 0;
    $d_deux = 1;

    for ($i = 0; $i<$MaxTerms; $i++)
    {
        $a = floor($f); // Get next term
        $f = $f - $a; // Get new divisor
        $n = $n_un * $a + $n_deux; // Calculate new fraction
        $d = $d_un * $a + $d_deux;
        $n_deux = $n_un; // Save last two fractions
        $d_deux = $d_un;
        $n_un = $n;
        $d_un = $d;

        if ($f < $MinDivisor) // Quit if dividing by zero
            break;

        if (abs($v - $n / $d) < $MaxError)
            break;

        $f = 1 / $f; // Take reciprocal
    }
}  

//================================================================================================
// Calculates the 35mm-equivalent focal length from the reported sensor resolution, by Tristan Harward.
//================================================================================================
function get35mmEquivFocalLength(&$result) {
    $width = $result['SubIFD']['ExifImageWidth'];
    $units = $result['SubIFD']['FocalPlaneResolutionUnit'];
    $unitfactor = 1;
    switch ($units) {
        case 'Inch' :       $unitfactor = 25.4; break;
        case 'Centimeter' : $unitfactor = 10;   break;
        case 'No Unit' :    $unitfactor = 25.4; break;
        default :           $unitfactor = 25.4;
    }
    $xres = $result['SubIFD']['FocalPlaneXResolution'];
    $fl = $result['SubIFD']['FocalLength'];
    
    if (($width != 0) && !empty($units) && !empty($xres) && !empty($fl) && !empty($width)) {
        $ccdwidth = ($width * $unitfactor) / $xres;
        $equivfl = $fl / $ccdwidth*36+0.5;
        return $equivfl;
    }
    return null;
}

?>