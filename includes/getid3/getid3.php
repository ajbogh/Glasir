<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// Requires: PHP 4.0.2 (or higher)                        //
//           PHP 4.0.7RC1 for zip functions               //
//           GD  <1.6 for GIF and JPEG functions          //
//           GD >=1.6 for PNG and JPEG functions          //
//                                                        //
// Please see getid3.readme.txt for more information      //
//                                                        //
////////////////////////////////////////////////////////////

define('GETID3VERSION', '1.4.1b5');
define('FREAD_BUFFER_SIZE', 16384); // number of bytes to read in at once

$includedfilepaths = get_included_files();
foreach ($includedfilepaths as $key => $val) {
	if (basename($val) == 'getid3.php') {
		define('GETID3_INCLUDEPATH', dirname($val).'/');
	}
}
if (!defined('GETID3_INCLUDEPATH')) {
	define('GETID3_INCLUDEPATH', '');
}


function GetAllMP3info($filename, $assumedFormat='', $allowedFormats=array('zip','ogg','riff','sdss','mpeg','midi','image','mp3')) {
	include_once(GETID3_INCLUDEPATH.'getid3.lookup.php');    // Lookup tables
	include_once(GETID3_INCLUDEPATH.'getid3.functions.php'); // Function library
	include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');

	if (!file_exists($filename)) {
		// this code segment is needed for the file browser demonstrated in check.php
		// but may interfere with finding a filename that actually does contain apparently
		// escaped characters (like "file\'name.mp3") and/or
		// %xx-format characters (like "file%20name.mp3")
		$filename = stripslashes($filename);
		if (!file_exists($filename)) {
			$filename = rawurldecode($filename);
		}
	}
	$fp = @fopen($filename, 'rb');
	$MP3fileInfo['getID3version'] = GETID3VERSION;
	$MP3fileInfo['exist']         = (bool) $fp;
	$MP3fileInfo['filename']      = basename($filename);
	$MP3fileInfo['fileformat']    = ''; // filled in later
	$MP3fileInfo['error']         = ''; // filled in later, unset if not used

	if ($MP3fileInfo['exist']) {
		if ((strpos($filename, 'http://') !== FALSE) || (strpos($filename, 'ftp://') !== FALSE)) {
			// remote file - copy locally first and work from there
			$localfilepointer = tmpfile();
			while ($buffer = fread($fp, FREAD_BUFFER_SIZE)) {
				$MP3fileInfo['filesize'] += fwrite($localfilepointer, $buffer);
			}
			fclose($fp);
		} else {
			clearstatcache();
			$MP3fileInfo['filesize'] = filesize($filename);
			$localfilepointer = $fp;
		}
		rewind($localfilepointer);
		$formattest = fread($localfilepointer, FREAD_BUFFER_SIZE);
		
		
		
		if (ParseAsThisFormat('zip', $assumedFormat, $allowedFormats, $formattest)) {
			$MP3fileInfo['fileformat'] = 'zip';
			include_once(GETID3_INCLUDEPATH.'getid3.zip.php');
			getZipHeaderFilepointer($filename, $MP3fileInfo);
		} else if (ParseAsThisFormat('ogg', $assumedFormat, $allowedFormats, $formattest)) {
			$MP3fileInfo['fileformat'] = 'ogg';
			include_once(GETID3_INCLUDEPATH.'getid3.ogg.php');
			getOggHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('riff', $assumedFormat, $allowedFormats, $formattest) || ParseAsThisFormat('sdss', $assumedFormat, $allowedFormats, $formattest)) {
			$MP3fileInfo['fileformat'] = 'riff';
			include_once(GETID3_INCLUDEPATH.'getid3.riff.php');
			getRIFFHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('mpeg', $assumedFormat, $allowedFormats, $formattest)) {
			$MP3fileInfo['fileformat'] = 'mpg';
			include_once(GETID3_INCLUDEPATH.'getid3.mpeg.php');
			getMPEGHeaderFilepointer($localfilepointer, $MP3fileInfo);
		} else if (ParseAsThisFormat('midi', $assumedFormat, $allowedFormats, $formattest)) {
			$MP3fileInfo['fileformat'] = 'midi';
			include_once(GETID3_INCLUDEPATH.'getid3.midi.php');
			if ($assumedFormat === FALSE) {
				// do not parse all MIDI tracks - much faster
				getMIDIHeaderFilepointer($localfilepointer, $MP3fileInfo, FALSE);
			} else {
				getMIDIHeaderFilepointer($localfilepointer, $MP3fileInfo);
			}
		} else if (in_array('image', $allowedFormats) && (($assumedFormat == 'image') || (($imagechunkcheck = GetDataImageSize($formattest)) && ($imagechunkcheck[2] >= 1) && ($imagechunkcheck[2] <= 3)))) {
			if ($assumedFormat == 'image') {
				$imagechunkcheck = GetDataImageSize($formattest);
			}
			$imagetypes = array(1=>'gif', 2=>'jpeg', 3=>'png');
			if (isset($imagechunkcheck[2]) && ($imagechunkcheck[2] >= 1) && ($imagechunkcheck[2] <= 3)) {
				$MP3fileInfo['fileformat'] = $imagetypes["{$imagechunkcheck[2]}"];
				$MP3fileInfo["{$MP3fileInfo['fileformat']}"]['width']  = $imagechunkcheck[0];
				$MP3fileInfo["{$MP3fileInfo['fileformat']}"]['height'] = $imagechunkcheck[1];
			} else {
				unset($MP3fileInfo['fileformat']);
				$MP3fileInfo['error'] = "\n".'Not a supported image format';
			}
		} else if (in_array('mp3', $allowedFormats) && ($allowedFormats !== FALSE) && (($assumedFormat == 'mp3') || (($assumedFormat == '') && ((substr($formattest, 0, 3) == 'ID3') || (substr(BigEndian2Bin(substr($formattest, 0, 2)), 0, 11) == '11111111111'))))) {
			// assume MP3 format
			include_once(GETID3_INCLUDEPATH.'getid3.mp3.php');
			getMP3headerFilepointer($localfilepointer, $MP3fileInfo);

			if (!isset($MP3fileInfo['audiodataoffset'])) {
				$MP3fileInfo['audiobytes'] = 0;
			} else {
				$MP3fileInfo['audiobytes'] = $MP3fileInfo['filesize'] - $MP3fileInfo['audiodataoffset'];
			}
			if (isset($MP3fileInfo['id3']['id3v1'])) {
				$MP3fileInfo['audiobytes'] -= 128;
			}
			if (isset($mp3info['lyrics3']['raw']['lyrics3tagsize'])) {
				$MP3fileInfo['audiobytes'] -= $mp3info['lyrics3']['raw']['lyrics3tagsize'];
			}
			if ($MP3fileInfo['audiobytes'] < 0) {
				unset($MP3fileInfo['audiobytes']);
			}
			if (isset($MP3fileInfo['audiobytes']) && isset($MP3fileInfo['bitrate']) && ($MP3fileInfo['bitrate'] > 0)) {
				$MP3fileInfo['playtime_seconds'] = ($MP3fileInfo['audiobytes'] * 8) / $MP3fileInfo['bitrate'];
			}
		}
	}
	if (isset($MP3fileInfo['playtime_seconds']) && ($MP3fileInfo['playtime_seconds'] > 0) && !isset($MP3fileInfo['playtime_string'])) {
		$contentseconds = round((($MP3fileInfo['playtime_seconds'] / 60) - floor($MP3fileInfo['playtime_seconds'] / 60)) * 60);
		$contentminutes = floor($MP3fileInfo['playtime_seconds'] / 60);
		$MP3fileInfo['playtime_string']  = number_format($contentminutes).':'.str_pad($contentseconds, 2, 0, STR_PAD_LEFT);
	}
	if (isset($MP3fileInfo['error']) && !$MP3fileInfo['error']) {
		unset($MP3fileInfo['error']);
	}
	if (isset($MP3fileInfo['fileformat']) && !$MP3fileInfo['fileformat']) {
		unset($MP3fileInfo['fileformat']);
	}
	
	unset($SourceArrayKey);
	if (isset($MP3fileInfo['id3']['id3v2'])) {
		$SourceArrayKey = $MP3fileInfo['id3']['id3v2'];
	} else if (isset($MP3fileInfo['id3']['id3v1'])) {
		$SourceArrayKey = $MP3fileInfo['id3']['id3v1'];
	} else if (isset($MP3fileInfo['ogg'])) {
		$SourceArrayKey = $MP3fileInfo['ogg'];
	} else if (isset($MP3fileInfo['RIFF'])) {
		$SourceArrayKey = $MP3fileInfo['RIFF'];
	}
	if (isset($SourceArrayKey)) {
		$handyaccesskeystocopy = array('title', 'artist', 'album', 'year', 'genre', 'comment', 'track');
		foreach ($handyaccesskeystocopy as $keytocopy) {
			if (isset($SourceArrayKey["$keytocopy"])) {
				$MP3fileInfo["$keytocopy"] = $SourceArrayKey["$keytocopy"];
			}
		}
	}

	if (isset($fp) && is_resource($fp) && (get_resource_type($fp) == 'file')) {
		fclose($fp);
	}
	if (isset($localfilepointer) && is_resource($localfilepointer) && (get_resource_type($localfilepointer) == 'file')) {
		fclose($localfilepointer);
	}
	if (isset($fp)) {
		unset($fp);
	}
	if (isset($localfilepointer)) {
		unset($localfilepointer);
	}
 	return $MP3fileInfo;
}

function ParseAsThisFormat($format, $assumedFormat, $allowedFormats, $formattest) {
	$FormatTestStrings['zip']  = 'PK';
	$FormatTestStrings['ogg']  = 'OggS';
	$FormatTestStrings['riff'] = 'RIFF';
	$FormatTestStrings['sdss'] = 'SDSS'; // simply a renamed RIFF-WAVE format, identical except for the 1st 4 chars, used by SmartSound QuickTracks (www.smartsound.com)
	$FormatTestStrings['mpeg'] = chr(0x00).chr(0x00).chr(0x01).chr(0xBA);
	$FormatTestStrings['midi'] = 'MThd';
	
	if (in_array($format, $allowedFormats) && (($assumedFormat == $format) || ((substr($formattest, 0, strlen($FormatTestStrings["$format"])) == $FormatTestStrings["$format"]) && ($assumedFormat == '')))) {
		return TRUE;
	}
	return FALSE;
}
?>