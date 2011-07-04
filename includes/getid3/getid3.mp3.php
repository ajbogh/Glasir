<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.mp3.php - part of getID3()                      //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function getID3($filename) {
	$fd = fopen($filename, 'rb');
	getID3Filepointer($fd);
}

function decodeMPEGaudioHeader($fd, $offset, &$MP3fileInfo, $recursivesearch=TRUE) {
	if ($offset >= $MP3fileInfo['filesize']) {
		$MP3fileInfo['error'] .= "\n".'end of file encounter looking for MPEG synch';
		return FALSE;
	}
	fseek($fd, $offset, SEEK_SET);
	$headerstring = fread($fd, FREAD_BUFFER_SIZE);

	// MP3 audio frame structure:
	// $aa $aa $aa $aa [$bb $bb] $cc...
	// where $aa..$aa is the four-byte mpeg-audio header (below)
	// $bb $bb is the optional 2-byte CRC
	// and $cc... is the audio data

	// AAAAAAAA AAABBCCD EEEEFFGH IIJJKLMM
	// 01234567 01234567 01234567 01234567
	// A - Frame sync (all bits set)
	// B - MPEG Audio version ID
	// C - Layer description
	// D - Protection bit
	// E - Bitrate index
	// F - Sampling rate frequency index
	// G - Padding bit
	// H - Private bit
	// I - Channel Mode
	// J - Mode extension (Only if Joint stereo)
	// K - Copyright
	// L - Original
	// M - Emphasis

	$byte1 = BigEndian2Bin(substr($headerstring, 0, 1));
	$byte2 = BigEndian2Bin(substr($headerstring, 1, 1));
	$byte3 = BigEndian2Bin(substr($headerstring, 2, 1));
	$byte4 = BigEndian2Bin(substr($headerstring, 3, 1));
	if (substr(BigEndian2Bin(substr($headerstring, 0, 2)), 0, 11) == '11111111111') {
		// synch detected (11 set bits in a row)
	} else {
		$MP3fileInfo['error'] .= "\n".'MPEG-audio synch not found where expected (at offset '.$offset.')';
		return FALSE;
	}
	$MP3fileInfo['mpeg']['audio']['raw']['version']       = bindec(substr($byte2, 3, 2));
	$MP3fileInfo['mpeg']['audio']['raw']['layer']         = bindec(substr($byte2, 5, 2));
	$MP3fileInfo['mpeg']['audio']['raw']['protection']    = bindec(substr($byte2, 7, 1));
	$MP3fileInfo['mpeg']['audio']['raw']['bitrate']       = bindec(substr($byte3, 0, 4));
	$MP3fileInfo['mpeg']['audio']['raw']['frequency']     = bindec(substr($byte3, 4, 2));
	$MP3fileInfo['mpeg']['audio']['raw']['padding']       = bindec(substr($byte3, 6, 1));
	$MP3fileInfo['mpeg']['audio']['raw']['private']       = bindec(substr($byte3, 7, 1));
	$MP3fileInfo['mpeg']['audio']['raw']['channelmode']   = bindec(substr($byte4, 0, 2));
	$MP3fileInfo['mpeg']['audio']['raw']['modeextension'] = bindec(substr($byte4, 2, 2));
	$MP3fileInfo['mpeg']['audio']['raw']['copyright']     = bindec(substr($byte4, 4, 1));
	$MP3fileInfo['mpeg']['audio']['raw']['original']      = bindec(substr($byte4, 5, 1));
	$MP3fileInfo['mpeg']['audio']['raw']['emphasis']      = bindec(substr($byte4, 6, 2));
	
	if (!MPEGaudioHeaderValid($MP3fileInfo['mpeg']['audio']['raw'])) {
		$MP3fileInfo['error'] .= "\n".'invalid MPEG audio header at offset '.$offset;
		return FALSE;
	}

	$MP3fileInfo['mpeg']['audio']['version']              = MPEGaudioVersionLookup($MP3fileInfo['mpeg']['audio']['raw']['version']);
	$MP3fileInfo['mpeg']['audio']['layer']                = MPEGaudioLayerLookup($MP3fileInfo['mpeg']['audio']['raw']['layer']);
	$MP3fileInfo['mpeg']['audio']['protection']           = MPEGaudioCRCLookup($MP3fileInfo['mpeg']['audio']['raw']['protection']);
	$MP3fileInfo['mpeg']['audio']['bitrate']              = MPEGaudioBitrateLookup($MP3fileInfo['mpeg']['audio']['version'], $MP3fileInfo['mpeg']['audio']['layer'], $MP3fileInfo['mpeg']['audio']['raw']['bitrate']);
	$MP3fileInfo['mpeg']['audio']['frequency']            = MPEGaudioFrequencyLookup($MP3fileInfo['mpeg']['audio']['version'], $MP3fileInfo['mpeg']['audio']['raw']['frequency']);
	$MP3fileInfo['mpeg']['audio']['padding']              = (bool) $MP3fileInfo['mpeg']['audio']['raw']['padding'];
	$MP3fileInfo['mpeg']['audio']['private']              = (bool) $MP3fileInfo['mpeg']['audio']['raw']['private'];
	$MP3fileInfo['mpeg']['audio']['channelmode']          = MPEGaudioChannelModeLookup($MP3fileInfo['mpeg']['audio']['raw']['channelmode']);
	$MP3fileInfo['mpeg']['audio']['channels']             = (($MP3fileInfo['mpeg']['audio']['channelmode'] == 'mono') ? 1 : 2);
	$MP3fileInfo['mpeg']['audio']['modeextension']        = MPEGaudioModeExtensionLookup($MP3fileInfo['mpeg']['audio']['layer'], $MP3fileInfo['mpeg']['audio']['raw']['modeextension']);
	$MP3fileInfo['mpeg']['audio']['copyright']            = (bool) $MP3fileInfo['mpeg']['audio']['raw']['copyright'];
	$MP3fileInfo['mpeg']['audio']['original']             = (bool) $MP3fileInfo['mpeg']['audio']['raw']['original'];
	$MP3fileInfo['mpeg']['audio']['emphasis']             = MPEGaudioEmphasisLookup($MP3fileInfo['mpeg']['audio']['raw']['emphasis']);

	if ($MP3fileInfo['mpeg']['audio']['protection']) {
		$MP3fileInfo['mpeg']['audio']['crc'] = BigEndian2Int(substr($headerstring, 4, 2));
	}
	
	if ($MP3fileInfo['mpeg']['audio']['bitrate'] != 'free') {
		if ($MP3fileInfo['mpeg']['audio']['version'] == '1') {
			if ($MP3fileInfo['mpeg']['audio']['layer'] == 'I') {
				$FrameLengthCoefficient = 48;
				$FrameLengthPadding     = ($MP3fileInfo['mpeg']['audio']['padding'] ? 4 : 0); // "For Layer I slot is 32 bits long, for Layer II and Layer III slot is 8 bits long."
			} else { // Layer II / III
				$FrameLengthCoefficient = 144;
				$FrameLengthPadding     = ($MP3fileInfo['mpeg']['audio']['padding'] ? 1 : 0); // "For Layer I slot is 32 bits long, for Layer II and Layer III slot is 8 bits long."
			}
		} else { // MPEG-2 / MPEG-2.5
			if ($MP3fileInfo['mpeg']['audio']['layer'] == 'I') {
				$FrameLengthCoefficient = 24;
				$FrameLengthPadding     = ($MP3fileInfo['mpeg']['audio']['padding'] ? 4 : 0); // "For Layer I slot is 32 bits long, for Layer II and Layer III slot is 8 bits long."
			} else { // Layer II / III
				$FrameLengthCoefficient = 72;
				$FrameLengthPadding     = ($MP3fileInfo['mpeg']['audio']['padding'] ? 1 : 0); // "For Layer I slot is 32 bits long, for Layer II and Layer III slot is 8 bits long."
			}
		}
		// FrameLengthInBytes = ((Coefficient * BitRate) / SampleRate) + Padding
		// http://66.96.216.160/cgi-bin/YaBB.pl?board=c&action=display&num=1018474068
		// -> "Finding the next frame synch" on www.r3mix.net forums if the above link goes dead
		$MP3fileInfo['mpeg']['audio']['framelength'] = (int) floor(($FrameLengthCoefficient * 1000 * $MP3fileInfo['mpeg']['audio']['bitrate']) / $MP3fileInfo['mpeg']['audio']['frequency']) + $FrameLengthPadding;
	}
	$MP3fileInfo['bitrate'] = 1000 * $MP3fileInfo['mpeg']['audio']['bitrate'];
	
	$nextframetestarray  = array('error'=>'', 'filesize'=>$MP3fileInfo['filesize']);
	if (isset($MP3fileInfo['mpeg']['audio']['framelength'])) {
		$nextframetestoffset = $offset + $MP3fileInfo['mpeg']['audio']['framelength'];
	} else {
		$nextframetestoffset = $MP3fileInfo['filesize'];
	}

	if ($recursivesearch && isset($MP3fileInfo['mpeg']['audio']['framelength']) && $MP3fileInfo['mpeg']['audio']['framelength']) {
		for ($i=0;$i<5;$i++) {
			// check next 5 frames for validity, to make sure we haven't run across a false synch
			if ($nextframetestoffset >= $MP3fileInfo['filesize']) {
				// end of file
				break;
			}
			if (decodeMPEGaudioHeader($fd, $nextframetestoffset, $nextframetestarray, FALSE)) {
				// next frame is OK, get ready to check the one after that
				$nextframetestoffset += $nextframetestarray['mpeg']['audio']['framelength'];
			} else {
				// next frame is not valid, note the error and fail, so scanning can contiue for a valid frame sequence
				$MP3fileInfo['error'] .= "\n".'Frame at offset('.$offset.') is valid, but the next one at ('.$nextframetestoffset.') is not.';
				return FALSE;
			}
		}
	}

	// For Layer II there are some combinations of bitrate and mode which are not allowed.
	if ($MP3fileInfo['mpeg']['audio']['layer'] == 'II') {
		switch ($MP3fileInfo['mpeg']['audio']['channelmode']) {
			case 'mono':
				if (($MP3fileInfo['mpeg']['audio']['bitrate'] == 'free') || ($MP3fileInfo['mpeg']['audio']['bitrate'] <= 192)) {
					// these are ok
				} else {
					$MP3fileInfo['error'] .= "\n".$MP3fileInfo['mpeg']['audio']['bitrate'].'kbps not allowed in Layer II, '.$MP3fileInfo['mpeg']['audio']['channelmode'].'.';
				}
				break;
			case 'stereo':
			case 'joint stereo':
			case 'dual channel':
				if (($MP3fileInfo['mpeg']['audio']['bitrate'] == 'free') || ($MP3fileInfo['mpeg']['audio']['bitrate'] == 64) || ($MP3fileInfo['mpeg']['audio']['bitrate'] >= 96)) {
					// these are ok
				} else {
					$MP3fileInfo['error'] .= "\n".$MP3fileInfo['mpeg']['audio']['bitrate'].'kbps not allowed in Layer II, '.$MP3fileInfo['mpeg']['audio']['channelmode'].'.';
				}
				break;
		}
	}

////////////////////////////////////////////////////////////////////////////////////
	// Variable-bitrate headers

	if ($MP3fileInfo['mpeg']['audio']['version'] == '1') {
		if ($MP3fileInfo['mpeg']['audio']['channelmode'] == 'mono') {
			$VBRidOffset = (17 + 4); // 21 bytes
		} else {
			$VBRidOffset = (32 + 4); // 36 bytes
		}
	} else { // 2 or 2.5
		if ($MP3fileInfo['mpeg']['audio']['channelmode'] == 'mono') {
			$VBRidOffset = (9 + 4);  // 13 bytes
		} else {
			$VBRidOffset = (17 + 4); // 21 bytes
		}
	}

	$VBRid = substr($headerstring, $VBRidOffset, 4);
	if ($VBRid == 'Xing') {
		$MP3fileInfo['mpeg']['audio']['bitratemode']   = 'VBR';
		$MP3fileInfo['mpeg']['audio']['VBR_method'] = 'Xing';
	} else if ($VBRid == 'VBRI') {
		$MP3fileInfo['mpeg']['audio']['bitratemode']   = 'VBR';
		$MP3fileInfo['mpeg']['audio']['VBR_method'] = 'Fraunhofer';
	} else {
		$MP3fileInfo['mpeg']['audio']['bitratemode'] = 'CBR';
	}
	if ($MP3fileInfo['mpeg']['audio']['bitratemode'] == 'VBR') {
		if ($MP3fileInfo['mpeg']['audio']['VBR_method'] == 'Xing') {
			$XingVBROffset = $VBRidOffset + 4;
			$XingHeader_Flags = substr($headerstring, $XingVBROffset, 4);
			$XingVBROffset += 4;
			$XingHeader_byte4 = BigEndian2Bin(substr($XingHeader_Flags, 3, 1));
			$XingHeader_flags['frames']    = substr($XingHeader_byte4, 4, 1);
			$XingHeader_flags['bytes']     = substr($XingHeader_byte4, 5, 1);
			$XingHeader_flags['toc']       = substr($XingHeader_byte4, 6, 1);
			$XingHeader_flags['vbr_scale'] = substr($XingHeader_byte4, 7, 1);
			if ($XingHeader_flags['frames'] == '1') {
				$XingHeader_Frames = substr($headerstring, $XingVBROffset, 4);
				$XingVBROffset += 4;
				$MP3fileInfo['mpeg']['audio']['VBR_frames'] = BigEndian2Int($XingHeader_Frames);
			}
			if ($XingHeader_flags['bytes'] == '1') {
				$XingHeader_Bytes = substr($headerstring, $XingVBROffset, 4);
				$XingVBROffset += 4;
				$MP3fileInfo['mpeg']['audio']['VBR_bytes'] = BigEndian2Int($XingHeader_Bytes);
			}
		} else if ($MP3fileInfo['mpeg']['audio']['VBR_method'] == 'Fraunhofer') {
			// specs taken from http://minnie.tuhs.org/pipermail/mp3encoder/2001-January/001800.html
			$FraunhoferVBROffset = $VBRidOffset + 4;
			$Fraunhofer_version = substr($headerstring, $FraunhoferVBROffset, 4);
			$FraunhoferVBROffset += 4;
	
			$Fraunhofer_quality = substr($headerstring, $FraunhoferVBROffset, 2);
			$FraunhoferVBROffset += 2;
			$MP3fileInfo['mpeg']['audio']['VBR_quality'] = BigEndian2Int($Fraunhofer_quality);
	
			$Fraunhofer_Bytes = substr($headerstring, $FraunhoferVBROffset, 4);
			$FraunhoferVBROffset += 4;
			$MP3fileInfo['mpeg']['audio']['VBR_bytes'] = BigEndian2Int($Fraunhofer_Bytes);
	
			$Fraunhofer_Frames = substr($headerstring, $FraunhoferVBROffset, 4);
			$FraunhoferVBROffset += 4;
			$MP3fileInfo['mpeg']['audio']['VBR_frames'] = BigEndian2Int($Fraunhofer_Frames);
		}
		if(isset($MP3fileInfo['mpeg']['audio']['VBR_frames'])) $MP3fileInfo['mpeg']['audio']['VBR_frames']--; // don't count the Xing / VBRI frame
		if (($MP3fileInfo['mpeg']['audio']['version'] == '1') && ($MP3fileInfo['mpeg']['audio']['layer'] == 'I')) {
			$MP3fileInfo['mpeg']['audio']['VBR_bitrate'] = ((($MP3fileInfo['mpeg']['audio']['VBR_bytes'] / $MP3fileInfo['mpeg']['audio']['VBR_frames']) * 8) * ($MP3fileInfo['mpeg']['audio']['frequency'] / 384)) / 1000;
		} else if ((($MP3fileInfo['mpeg']['audio']['version'] == '2') || ($MP3fileInfo['mpeg']['audio']['version'] == '2.5')) && ($MP3fileInfo['mpeg']['audio']['layer'] == 'III')) {
			$MP3fileInfo['mpeg']['audio']['VBR_bitrate'] = ((($MP3fileInfo['mpeg']['audio']['VBR_bytes'] / $MP3fileInfo['mpeg']['audio']['VBR_frames']) * 8) * ($MP3fileInfo['mpeg']['audio']['frequency'] / 576)) / 1000;
		} else {
			if(isset($MP3fileInfo['mpeg']['audio']['VBR_frames'])){
				$MP3fileInfo['mpeg']['audio']['VBR_bitrate'] = ((($MP3fileInfo['mpeg']['audio']['VBR_bytes'] / $MP3fileInfo['mpeg']['audio']['VBR_frames']) * 8) * ($MP3fileInfo['mpeg']['audio']['frequency'] / 1152)) / 1000;
			}else{
				$MP3fileInfo['mpeg']['audio']['VBR_bitrate'] = 0; //fallback condition
			}
		}
		if ($MP3fileInfo['mpeg']['audio']['VBR_bitrate'] > 0) {
			$MP3fileInfo['bitrate'] = 1000 * $MP3fileInfo['mpeg']['audio']['VBR_bitrate'];
			unset($MP3fileInfo['mpeg']['audio']['bitrate']); // to avoid confusion
		}
	}

	return TRUE;
}

function getOnlyMPEGaudioInfo($fd, &$MP3fileInfo, $audiodataoffset) {
	// looks for synch, decodes MPEG audio header
	// you may call this function directly if you don't need any ID3 info
	fseek($fd, $audiodataoffset);
	$header = '';
	$SynchSeekOffset = 0;
	while (!isset($MP3fileInfo['fileformat']) || ($MP3fileInfo['fileformat'] == '') || ($MP3fileInfo['fileformat'] == 'id3')) {
		if (($SynchSeekOffset > (strlen($header) - 8192)) && !feof($fd)) {
			if ($SynchSeekOffset > (FREAD_BUFFER_SIZE * 4)) {
				// if a synch's not found within the first 64k bytes, then give up
				$MP3fileInfo['error'] .= "\n".'could not find valid MPEG synch within the first '.(FREAD_BUFFER_SIZE * 4).' bytes';
				if (isset($MP3fileInfo['bitrate'])) {
					unset($MP3fileInfo['bitrate']);
				}
				if (isset($MP3fileInfo['mpeg']['audio'])) {
					unset($MP3fileInfo['mpeg']['audio']);
				}
				if (isset($MP3fileInfo['mpeg']) && (!is_array($MP3fileInfo['mpeg']) || (count($MP3fileInfo['mpeg']) == 0))) {
					unset($MP3fileInfo['mpeg']);
				}
				return FALSE;

			} else if ($header .= fread($fd, FREAD_BUFFER_SIZE)) {
				// great
			} else {
				$MP3fileInfo['error'] .= "\n".'could not find valid MPEG synch before end of file';
				if (isset($MP3fileInfo['bitrate'])) {
					unset($MP3fileInfo['bitrate']);
				}
				if (isset($MP3fileInfo['mpeg']['audio'])) {
					unset($MP3fileInfo['mpeg']['audio']);
				}
				if (isset($MP3fileInfo['mpeg']) && (!is_array($MP3fileInfo['mpeg']) || (count($MP3fileInfo['mpeg']) == 0))) {
					unset($MP3fileInfo['mpeg']);
				}
				return FALSE;
			}
		}
		if ((ord($header{$SynchSeekOffset}) == 0xFF) && substr(BigEndian2Bin(substr($header, $SynchSeekOffset, 2)), 0, 11) == '11111111111') { // synch detected
			if (decodeMPEGaudioHeader($fd, $audiodataoffset + $SynchSeekOffset, $MP3fileInfo, TRUE)) {
				$MP3fileInfo['audiodataoffset'] = $audiodataoffset + $SynchSeekOffset;
				$MP3fileInfo['fileformat'] = 'mp3';
				break; // exit for() and while()
			}
		}
		if (!isset($MP3fileInfo['fileformat']) || ($MP3fileInfo['fileformat'] == '') || ($MP3fileInfo['fileformat'] == 'id3')) {
			$SynchSeekOffset++;
			if (($audiodataoffset + $SynchSeekOffset) >= $MP3fileInfo['filesize']) {
				// end of file
				$MP3fileInfo['error'] .= "\n".'could not find valid MPEG synch before end of file';
				if (isset($MP3fileInfo['bitrate'])) {
					unset($MP3fileInfo['bitrate']);
				}
				if (isset($MP3fileInfo['mpeg']['audio'])) {
					unset($MP3fileInfo['mpeg']['audio']);
				}
				if (isset($MP3fileInfo['mpeg']) && (!is_array($MP3fileInfo['mpeg']) || (count($MP3fileInfo['mpeg']) == 0))) {
					unset($MP3fileInfo['mpeg']);
				}
				return FALSE;
			}
		}
	}
	return TRUE;
}

function getMP3header($filename, &$MP3fileInfo) {
	$fd = fopen($filename, 'rb');
	return getMP3headerFilepointer($fd, $MP3fileInfo);
}

function getMP3headerFilepointer(&$fd, &$MP3fileInfo) {
	// get all information about an MP3 file - ID3v1, ID3v2, Lyrics3, MPEG-audio
	$MP3fileInfo['fileformat'] = '';
	if (!$fd) {
		$MP3fileInfo['error'] .= "\n".'Could not open file';
		return FALSE;
	} else {
		fseek($fd, -128 - 9 - 6, SEEK_END);
		$lyrics3_id3v1 = fread($fd, 128 + 9 + 6);
		$lyrics3lsz = substr($lyrics3_id3v1,  0,   6);
		$lyrics3end = substr($lyrics3_id3v1,  6,   9); // LYRICSEND or LYRICS200
		$id3v1tag   = substr($lyrics3_id3v1, 15, 128);
		if ($lyrics3end == 'LYRICSEND') {
			// Lyrics3 v1 and ID3v1
			$lyrics3size = 5100;
			include_once(GETID3_INCLUDEPATH.'getid3.lyrics3.php');
			getLyrics3Filepointer($MP3fileInfo, $fd, -128 - $lyrics3size, 1, $lyrics3size);
		} else if ($lyrics3end == 'LYRICS200') {
			// Lyrics3 v2 and ID3v1
			$lyrics3size = $lyrics3lsz + 6 + strlen('LYRICS200'); // LSZ = lyrics + 'LYRICSBEGIN'; add 6-byte size field; add 'LYRICS200'
			include_once(GETID3_INCLUDEPATH.'getid3.lyrics3.php');
			getLyrics3Filepointer($MP3fileInfo, $fd, -128 - $lyrics3size, 2, $lyrics3size);
		} else if (substr($lyrics3_id3v1, strlen($lyrics3_id3v1) - 1 - 9, 9) == 'LYRICSEND') {
			// Lyrics3 v1, no ID3v1 (I think according to Lyrics3 specs there MUST be ID3v1, but just in case :)
			$lyrics3size = 5100;
			include_once(GETID3_INCLUDEPATH.'getid3.lyrics3.php');
			getLyrics3Filepointer($MP3fileInfo, $fd, 0 - $lyrics3size, 1, $lyrics3size);
		} else if (substr($lyrics3_id3v1, strlen($lyrics3_id3v1) - 1 - 9, 9) == 'LYRICS200') {
			// Lyrics3 v2, no ID3v1 (I think according to Lyrics3 specs there MUST be ID3v1, but just in case :)
			$lyrics3size = $lyrics3lsz + 6 + strlen('LYRICS200'); // LSZ = lyrics + 'LYRICSBEGIN'; add 6-byte size field; add 'LYRICS200'
			include_once(GETID3_INCLUDEPATH.'getid3.lyrics3.php');
			getLyrics3Filepointer($MP3fileInfo, $fd, 0 - $lyrics3size, 2, $lyrics3size);
		}
		if (substr($id3v1tag, 0, 3) == 'TAG') {
			include_once(GETID3_INCLUDEPATH.'getid3.id3v1.php');
			$MP3fileInfo['id3']['id3v1'] = getID3v1Filepointer($fd);
			$MP3fileInfo['fileformat'] = 'id3';
		}
		include_once(GETID3_INCLUDEPATH.'getid3.id3v2.php');
		getID3v2Filepointer($fd, $MP3fileInfo);
		if (isset($MP3fileInfo['id3']['id3v2']['header'])) {
			$MP3fileInfo['fileformat'] = 'id3';
			$audiodataoffset = $MP3fileInfo['id3']['id3v2']['headerlength'];
			if (isset($MP3fileInfo['id3']['id3v2']['footer'])) {
				$audiodataoffset += 10;
			}
		} else { // no ID3v2 header
			if (isset($MP3fileInfo['id3']['id3v2'])) {
				unset($MP3fileInfo['id3']['id3v2']);
			}
			$audiodataoffset = 0;
		}
		if ($audiodataoffset < $MP3fileInfo['filesize']) {
			getOnlyMPEGaudioInfo($fd, $MP3fileInfo, $audiodataoffset);
		}
		if (isset($MP3fileInfo['audiodataoffset']) &&
			((isset($MP3fileInfo['id3']['id3v2']) && ($MP3fileInfo['audiodataoffset'] > $MP3fileInfo['id3']['id3v2']['headerlength'])) ||
			(!isset($MP3fileInfo['id3']['id3v2']) && ($MP3fileInfo['audiodataoffset'] > 0)))
			) {
			$MP3fileInfo['error'] .= "\n".'Unknown data before synch ';
			if (isset($MP3fileInfo['id3']['id3v2']['headerlength'])) {
				$MP3fileInfo['error'] .= '(ID3v2 header ends at '.$MP3fileInfo['id3']['id3v2']['headerlength'].', ';
			} else {
				$MP3fileInfo['error'] .= '(should be at beginning of file, ';
			}
			$MP3fileInfo['error'] .= 'synch detected at '.$MP3fileInfo['audiodataoffset'].')';
		}
		if (!$MP3fileInfo['fileformat']) {
			$MP3fileInfo['error'] .= "\n".'Synch not found';
			unset($MP3fileInfo['audiodataoffset']);
			unset($MP3fileInfo['fileformat']);
		}
	} // if ($fd)
	if (isset($MP3fileInfo['id3']) && !isset($MP3fileInfo['id3']['id3v2']) && !isset($MP3fileInfo['id3']['id3v1'])) {
		unset($MP3fileInfo['id3']);
	}

	return TRUE;
}

function MPEGaudioVersionLookup($rawversion) {
	$MPEGaudioVersionLookup = array('2.5', FALSE, '2', '1');
	return (isset($MPEGaudioVersionLookup["$rawversion"]) ? $MPEGaudioVersionLookup["$rawversion"] : FALSE);
}

function MPEGaudioLayerLookup($rawlayer) {
	$MPEGaudioLayerLookup = array(FALSE, 'III', 'II', 'I');
	return (isset($MPEGaudioLayerLookup["$rawlayer"]) ? $MPEGaudioLayerLookup["$rawlayer"] : FALSE);
}

function MPEGaudioBitrateLookup($version, $layer, $rawbitrate) {
	$MPEGaudioBitrateLookup['1']['I']     = array('free', 32, 64, 96, 128, 160, 192, 224, 256, 288, 320, 352, 384, 416, 448);
	$MPEGaudioBitrateLookup['1']['II']    = array('free', 32, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320, 384);
	$MPEGaudioBitrateLookup['1']['III']   = array('free', 32, 40, 48, 56, 64, 80, 96, 112, 128, 160, 192, 224, 256, 320);
	$MPEGaudioBitrateLookup['2']['I']     = array('free', 32, 48, 56, 64, 80, 96, 112, 128, 144, 160, 176, 192, 224, 256);
	$MPEGaudioBitrateLookup['2.5']['I']   = $MPEGaudioBitrateLookup['2']['I'];
	$MPEGaudioBitrateLookup['2']['II']    = array('free', 8, 16, 24, 32, 40, 48, 56, 64, 80, 96, 112, 128, 144, 160);
	$MPEGaudioBitrateLookup['2']['III']   = $MPEGaudioBitrateLookup['2']['II'];
	$MPEGaudioBitrateLookup['2.5']['II']  = $MPEGaudioBitrateLookup['2']['II'];
	$MPEGaudioBitrateLookup['2.5']['III'] = $MPEGaudioBitrateLookup['2']['II'];

	return (isset($MPEGaudioBitrateLookup["$version"]["$layer"]["$rawbitrate"]) ? $MPEGaudioBitrateLookup["$version"]["$layer"]["$rawbitrate"] : FALSE);
}

function MPEGaudioFrequencyLookup($version, $rawfrequency) {
	$MPEGaudioFrequencyLookup['1']   = array(44100, 48000, 32000);
	$MPEGaudioFrequencyLookup['2']   = array(22050, 24000, 16000);
	$MPEGaudioFrequencyLookup['2.5'] = array(11025, 12000,  8000);
	return (isset($MPEGaudioFrequencyLookup["$version"]["$rawfrequency"]) ? $MPEGaudioFrequencyLookup["$version"]["$rawfrequency"] : FALSE);
}

function MPEGaudioChannelModeLookup($rawchannelmode) {
	$MPEGaudioChannelModeLookup = array('stereo', 'joint stereo', 'dual channel', 'mono');
	return (isset($MPEGaudioChannelModeLookup["$rawchannelmode"]) ? $MPEGaudioChannelModeLookup["$rawchannelmode"] : FALSE);
}

function MPEGaudioModeExtensionLookup($layer, $rawmodeextension) {
	$MPEGaudioModeExtensionLookup['I']   = array('4-31', '8-31', '12-31', '16-31');
	$MPEGaudioModeExtensionLookup['II']  = array('4-31', '8-31', '12-31', '16-31');
	$MPEGaudioModeExtensionLookup['III'] = array('', 'IS', 'MS', 'IS+MS');
	return (isset($MPEGaudioModeExtensionLookup["$layer"]["$rawmodeextension"]) ? $MPEGaudioModeExtensionLookup["$layer"]["$rawmodeextension"] : FALSE);
}

function MPEGaudioEmphasisLookup($rawemphasis) {
	$MPEGaudioEmphasisLookup = array('none', '50/15ms', FALSE, 'CCIT J.17');
	return (isset($MPEGaudioEmphasisLookup["$rawemphasis"]) ? $MPEGaudioEmphasisLookup["$rawemphasis"] : FALSE);
}

function MPEGaudioCRCLookup($CRCbit) {
	// inverse boolean cast :)
	if ($CRCbit == '0') {
		return TRUE;
	} else {
		return FALSE;
	}
}

function MPEGaudioHeaderValid($rawarray) {
	$decodedVersion = MPEGaudioVersionLookup($rawarray['version']);
	$decodedLayer   = MPEGaudioLayerLookup($rawarray['layer']);
	if ($decodedVersion === FALSE) {
		return FALSE;
	}
	if ($decodedLayer === FALSE) {
		return FALSE;
	}
	if (MPEGaudioBitrateLookup($decodedVersion, $decodedLayer, $rawarray['bitrate']) === FALSE) {
		return FALSE;
	}
	if (MPEGaudioFrequencyLookup($decodedVersion, $rawarray['frequency']) === FALSE) {
		return FALSE;
	}
	if (MPEGaudioChannelModeLookup($rawarray['channelmode']) === FALSE) {
		return FALSE;
	}
	if (MPEGaudioModeExtensionLookup($decodedLayer, $rawarray['modeextension']) === FALSE) {
		return FALSE;
	}
	if (MPEGaudioEmphasisLookup($rawarray['emphasis']) === FALSE) {
		return FALSE;
	}
	// These are just either set or not set, you can't mess that up :)
	// $rawarray['protection'];
	// $rawarray['padding'];
	// $rawarray['private'];
	// $rawarray['copyright'];
	// $rawarray['original'];
	
	return TRUE;
}
?>