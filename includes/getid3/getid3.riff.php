<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.riff.php - part of getID3()                     //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function ParseRIFF(&$fd, &$offset, $maxoffset) {
	fseek($fd, $offset, SEEK_SET);
	// while (($chunkname = fread($fd, 4)) !== FALSE) {
	while ($chunkname = fread($fd, 4)) {
		if ($chunkname{0} === chr(0)) {
			// a hack I don't understand - some frames, including (but maybe not limited to)
			//  strn, IART, IENG, IGNR, IKEY, IMED, ISRC, ITCH, ISBJ, ISRF
			// are specified as being one byte shorter than is acutally the case to the next
			// chunk, and that extra space is padded with a null. This hack simply detects that
			// the previous chunk had been shorted, and reads the next byte in and discards the null.
			$chunkname = substr($chunkname, 1, 3).fread($fd, 1);
		}
		$chunksize = LittleEndian2Int(fread($fd, 4));
		if ($chunksize <= 0) {
			// just in case something goes wrong :)
			break;
		}
		switch ($chunkname) {
			case 'RIFF':
			case 'SDSS': // simply a renamed RIFF-WAVE format, identical except for the 1st 4 chars, used by SmartSound QuickTracks (www.smartsound.com)
			case 'LIST':
				$listname = fread($fd, 4);
				$offset = ftell($fd);
				if ($offset >= $maxoffset) {
					$RIFFchunk = array_merge_recursive($RIFFchunk, ParseRIFF($fd, $offset, $offset + $chunksize));
				} else {
					$RIFFchunk["$listname"] = ParseRIFF($fd, $offset, $offset + $chunksize);
				}
				$offset = ftell($fd) + $chunksize;
				fseek($fd, $offset, SEEK_CUR);
				break;
			default:
				// skip over
				if (isset($RIFFchunk["$chunkname"]) && is_array($RIFFchunk["$chunkname"])) {
					$thisindex = count($RIFFchunk["$chunkname"]);
				} else {
					$thisindex = 0;
				}
				$RIFFchunk["$chunkname"]["$thisindex"]['size'] = $chunksize;
				if ($chunksize <= 128) {
					$RIFFchunk["$chunkname"]["$thisindex"]['data'] = fread($fd, $chunksize);
				} else {
					fseek($fd, $chunksize, SEEK_CUR);
				}
				$offset = ftell($fd);
				break;
		}
	}
	if (isset($RIFFchunk)) {
		return $RIFFchunk;
	} else {
		return FALSE;
	}
}

function getRIFFHeaderFilepointer(&$fd, &$MP3fileInfo) {
	$offset = 0;
	rewind($fd);
	$MP3fileInfo['RIFF'] = ParseRIFF($fd, $offset, $MP3fileInfo['filesize']);

	$streamindex = 0;
	if (!is_array($MP3fileInfo['RIFF'])) {
		$MP3fileInfo['error'] .= "\n".'Cannot parse RIFF (this is maybe not a RIFF / WAV / AVI file?)';
		unset($MP3fileInfo['RIFF']);
		unset($MP3fileInfo['fileformat']);
		return FALSE;
	}
	$arraykeys = array_keys($MP3fileInfo['RIFF']);
	switch ($arraykeys[0]) {
		case 'WAVE':
			$MP3fileInfo['fileformat'] = 'wav';
			if (isset($MP3fileInfo['RIFF']['WAVE']['fmt '][0]['data'])) {
				$fmtData = $MP3fileInfo['RIFF']['WAVE']['fmt '][0]['data'];
				$MP3fileInfo['RIFF']['raw']['fmt ']['wFormatTag']      = LittleEndian2Int(substr($fmtData,  0, 2));
				$MP3fileInfo['RIFF']['raw']['fmt ']['nChannels']       = LittleEndian2Int(substr($fmtData,  2, 2));
				$MP3fileInfo['RIFF']['raw']['fmt ']['nSamplesPerSec']  = LittleEndian2Int(substr($fmtData,  4, 4));
				$MP3fileInfo['RIFF']['raw']['fmt ']['nAvgBytesPerSec'] = LittleEndian2Int(substr($fmtData,  8, 4));
				$MP3fileInfo['RIFF']['raw']['fmt ']['nBlockAlign']     = LittleEndian2Int(substr($fmtData, 12, 2));
				$MP3fileInfo['RIFF']['raw']['fmt ']['nBitsPerSample']  = LittleEndian2Int(substr($fmtData, 14, 2));

				$MP3fileInfo['RIFF']['audio']["$streamindex"]['format']        = RIFFwFormatTagLookup($MP3fileInfo['RIFF']['raw']['fmt ']['wFormatTag']);
				$MP3fileInfo['RIFF']['audio']["$streamindex"]['channels']      = $MP3fileInfo['RIFF']['raw']['fmt ']['nChannels'];
				$MP3fileInfo['RIFF']['audio']["$streamindex"]['channelmode']   = (($MP3fileInfo['RIFF']['audio']["$streamindex"]['channels'] == 1) ? 'mono' : 'stereo');
				$MP3fileInfo['RIFF']['audio']["$streamindex"]['frequency']     = $MP3fileInfo['RIFF']['raw']['fmt ']['nSamplesPerSec'];
				$MP3fileInfo['RIFF']['audio']["$streamindex"]['bitrate']       = $MP3fileInfo['RIFF']['raw']['fmt ']['nAvgBytesPerSec'] * 8;
				$MP3fileInfo['RIFF']['audio']["$streamindex"]['bitspersample'] = $MP3fileInfo['RIFF']['raw']['fmt ']['nBitsPerSample'];
			}
			if (isset($MP3fileInfo['RIFF']['WAVE']['rgad'][0]['data'])) {
				$rgadData = $MP3fileInfo['RIFF']['WAVE']['rgad'][0]['data'];
				$MP3fileInfo['RIFF']['raw']['rgad']['fPeakAmplitude']      = LittleEndian2Float(substr($rgadData, 0, 4));
				$MP3fileInfo['RIFF']['raw']['rgad']['nRadioRgAdjust']      = LittleEndian2Int(substr($rgadData, 4, 2));
				$MP3fileInfo['RIFF']['raw']['rgad']['nAudiophileRgAdjust'] = LittleEndian2Int(substr($rgadData, 6, 2));
				$nRadioRgAdjustBitstring      = str_pad(Dec2Bin($MP3fileInfo['RIFF']['raw']['rgad']['nRadioRgAdjust']), 16, '0', STR_PAD_LEFT);
				$nAudiophileRgAdjustBitstring = str_pad(Dec2Bin($MP3fileInfo['RIFF']['raw']['rgad']['nAudiophileRgAdjust']), 16, '0', STR_PAD_LEFT);
				$MP3fileInfo['RIFF']['raw']['rgad']['radio']['name']       = Bin2Dec(substr($nRadioRgAdjustBitstring, 0, 3));
				$MP3fileInfo['RIFF']['raw']['rgad']['radio']['originator'] = Bin2Dec(substr($nRadioRgAdjustBitstring, 3, 3));
				$MP3fileInfo['RIFF']['raw']['rgad']['radio']['signbit']    = Bin2Dec(substr($nRadioRgAdjustBitstring, 6, 1));
				$MP3fileInfo['RIFF']['raw']['rgad']['radio']['adjustment'] = Bin2Dec(substr($nRadioRgAdjustBitstring, 7, 9));
				$MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['name']       = Bin2Dec(substr($nAudiophileRgAdjustBitstring, 0, 3));
				$MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['originator'] = Bin2Dec(substr($nAudiophileRgAdjustBitstring, 3, 3));
				$MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['signbit']    = Bin2Dec(substr($nAudiophileRgAdjustBitstring, 6, 1));
				$MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['adjustment'] = Bin2Dec(substr($nAudiophileRgAdjustBitstring, 7, 9));

				$MP3fileInfo['RIFF']['rgad']['peakamplitude'] = $MP3fileInfo['RIFF']['raw']['rgad']['fPeakAmplitude'];
				if (($MP3fileInfo['RIFF']['raw']['rgad']['radio']['name'] != 0) && ($MP3fileInfo['RIFF']['raw']['rgad']['radio']['originator'] != 0)) {
					$MP3fileInfo['RIFF']['rgad']['radio']['name']            = RGADnameLookup($MP3fileInfo['RIFF']['raw']['rgad']['radio']['name']);
					$MP3fileInfo['RIFF']['rgad']['radio']['originator']      = RGADoriginatorLookup($MP3fileInfo['RIFF']['raw']['rgad']['radio']['originator']);
					$MP3fileInfo['RIFF']['rgad']['radio']['adjustment']      = RGADadjustmentLookup($MP3fileInfo['RIFF']['raw']['rgad']['radio']['adjustment'], $MP3fileInfo['RIFF']['raw']['rgad']['radio']['signbit']);
				}
				if (($MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['name'] != 0) && ($MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['originator'] != 0)) {
					$MP3fileInfo['RIFF']['rgad']['audiophile']['name']       = RGADnameLookup($MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['name']);
					$MP3fileInfo['RIFF']['rgad']['audiophile']['originator'] = RGADoriginatorLookup($MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['originator']);
					$MP3fileInfo['RIFF']['rgad']['audiophile']['adjustment'] = RGADadjustmentLookup($MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['adjustment'], $MP3fileInfo['RIFF']['raw']['rgad']['audiophile']['signbit']);
				}
			}
			if (isset($MP3fileInfo['RIFF']['WAVE']['fact'][0]['data'])) {
				$MP3fileInfo['RIFF']['raw']['fact']['NumberOfSamples'] = LittleEndian2Int(substr($MP3fileInfo['RIFF']['WAVE']['fact'][0]['data'], 0, 4));
				if (isset($MP3fileInfo['RIFF']['raw']['fmt ']['nSamplesPerSec']) && $MP3fileInfo['RIFF']['raw']['fmt ']['nSamplesPerSec']) {
					$MP3fileInfo['playtime_seconds'] = (float) $MP3fileInfo['RIFF']['raw']['fact']['NumberOfSamples'] / $MP3fileInfo['RIFF']['raw']['fmt ']['nSamplesPerSec'];
				}
				if (isset($MP3fileInfo['RIFF']['raw']['fmt ']['nAvgBytesPerSec']) && $MP3fileInfo['RIFF']['raw']['fmt ']['nAvgBytesPerSec']) {
					$MP3fileInfo['audiobytes'] = CastAsInt(round($MP3fileInfo['playtime_seconds'] * $MP3fileInfo['RIFF']['raw']['fmt ']['nAvgBytesPerSec']));
					$MP3fileInfo['bitrate']    = CastAsInt($MP3fileInfo['RIFF']['raw']['fmt ']['nAvgBytesPerSec'] * 8);
				}
			}
			if (!isset($MP3fileInfo['audiobytes']) && isset($MP3fileInfo['RIFF']['WAVE']['data'][0]['size'])) {
				$MP3fileInfo['audiobytes'] = $MP3fileInfo['RIFF']['WAVE']['data'][0]['size'];
			}
			if (!isset($MP3fileInfo['bitrate']) && isset($MP3fileInfo['RIFF']['audio']["$streamindex"]['bitrate']) && isset($MP3fileInfo['audiobytes'])) {
				$MP3fileInfo['bitrate'] = $MP3fileInfo['RIFF']['audio']["$streamindex"]['bitrate'];
				$MP3fileInfo['playtime_seconds'] = (float) (($MP3fileInfo['audiobytes'] * 8) / $MP3fileInfo['bitrate']);
			}
			break;
		case 'AVI ':
			$MP3fileInfo['fileformat'] = 'avi';
			if (isset($MP3fileInfo['RIFF']['AVI ']['hdrl']['avih']["$streamindex"]['data'])) {
				$avihData = $MP3fileInfo['RIFF']['AVI ']['hdrl']['avih']["$streamindex"]['data'];
				$MP3fileInfo['RIFF']['raw']['avih']['dwMicroSecPerFrame']    = LittleEndian2Int(substr($avihData,  0, 4)); // frame display rate (or 0L)
				$MP3fileInfo['RIFF']['raw']['avih']['dwMaxBytesPerSec']      = LittleEndian2Int(substr($avihData,  4, 4)); // max. transfer rate
				$MP3fileInfo['RIFF']['raw']['avih']['dwPaddingGranularity']  = LittleEndian2Int(substr($avihData,  8, 4)); // pad to multiples of this size; normally 2K.
				$MP3fileInfo['RIFF']['raw']['avih']['dwFlags']               = LittleEndian2Int(substr($avihData, 12, 4)); // the ever-present flags
				$MP3fileInfo['RIFF']['raw']['avih']['dwTotalFrames']         = LittleEndian2Int(substr($avihData, 16, 4)); // # frames in file
				$MP3fileInfo['RIFF']['raw']['avih']['dwInitialFrames']       = LittleEndian2Int(substr($avihData, 20, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwStreams']             = LittleEndian2Int(substr($avihData, 24, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwSuggestedBufferSize'] = LittleEndian2Int(substr($avihData, 28, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwWidth']               = LittleEndian2Int(substr($avihData, 32, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwHeight']              = LittleEndian2Int(substr($avihData, 36, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwScale']               = LittleEndian2Int(substr($avihData, 40, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwRate']                = LittleEndian2Int(substr($avihData, 44, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwStart']               = LittleEndian2Int(substr($avihData, 48, 4));
				$MP3fileInfo['RIFF']['raw']['avih']['dwLength']              = LittleEndian2Int(substr($avihData, 52, 4));

				$MP3fileInfo['RIFF']['raw']['avih']['flags']['hasindex']     = (bool) ($MP3fileInfo['RIFF']['raw']['avih']['dwFlags'] & 0x00000010);
				$MP3fileInfo['RIFF']['raw']['avih']['flags']['mustuseindex'] = (bool) ($MP3fileInfo['RIFF']['raw']['avih']['dwFlags'] & 0x00000020);
				$MP3fileInfo['RIFF']['raw']['avih']['flags']['interleaved']  = (bool) ($MP3fileInfo['RIFF']['raw']['avih']['dwFlags'] & 0x00000100);
				$MP3fileInfo['RIFF']['raw']['avih']['flags']['trustcktype']  = (bool) ($MP3fileInfo['RIFF']['raw']['avih']['dwFlags'] & 0x00000800);
				$MP3fileInfo['RIFF']['raw']['avih']['flags']['capturedfile'] = (bool) ($MP3fileInfo['RIFF']['raw']['avih']['dwFlags'] & 0x00010000);
				$MP3fileInfo['RIFF']['raw']['avih']['flags']['copyrighted']  = (bool) ($MP3fileInfo['RIFF']['raw']['avih']['dwFlags'] & 0x00020010);


				$MP3fileInfo['RIFF']['video']["$streamindex"]['frame_width']  = $MP3fileInfo['RIFF']['raw']['avih']['dwWidth'];
				$MP3fileInfo['RIFF']['video']["$streamindex"]['frame_height'] = $MP3fileInfo['RIFF']['raw']['avih']['dwHeight'];
				$MP3fileInfo['RIFF']['video']["$streamindex"]['frame_rate']   = round(1000000 / $MP3fileInfo['RIFF']['raw']['avih']['dwMicroSecPerFrame'], 3);

				$MP3fileInfo['playtime_seconds'] = $MP3fileInfo['RIFF']['raw']['avih']['dwTotalFrames'] * ($MP3fileInfo['RIFF']['raw']['avih']['dwMicroSecPerFrame'] / 1000000);
				$MP3fileInfo['bitrate']          = ($MP3fileInfo['filesize'] / $MP3fileInfo['playtime_seconds']) * 8;
			}
			if (isset($MP3fileInfo['RIFF']['AVI ']['hdrl']['strl']['strh'][0]['data'])) {
				if (is_array($MP3fileInfo['RIFF']['AVI ']['hdrl']['strl']['strh'])) {
					for ($i=0;$i<count($MP3fileInfo['RIFF']['AVI ']['hdrl']['strl']['strh']);$i++) {
						if (isset($MP3fileInfo['RIFF']['AVI ']['hdrl']['strl']['strh']["$i"]['data'])) {
							$strhData = $MP3fileInfo['RIFF']['AVI ']['hdrl']['strl']['strh']["$i"]['data'];
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['fccType']               = substr($strhData,  0, 4);
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['fccHandler']            = substr($strhData,  4, 4);
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwFlags']               = LittleEndian2Int(substr($strhData,  8, 4)); // Contains AVITF_* flags
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['wPriority']             = LittleEndian2Int(substr($strhData, 12, 2));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['wLanguage']             = LittleEndian2Int(substr($strhData, 14, 2));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwInitialFrames']       = LittleEndian2Int(substr($strhData, 16, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwScale']               = LittleEndian2Int(substr($strhData, 20, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwRate']                = LittleEndian2Int(substr($strhData, 24, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwStart']               = LittleEndian2Int(substr($strhData, 28, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwLength']              = LittleEndian2Int(substr($strhData, 32, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwSuggestedBufferSize'] = LittleEndian2Int(substr($strhData, 36, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwQuality']             = LittleEndian2Int(substr($strhData, 40, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['dwSampleSize']          = LittleEndian2Int(substr($strhData, 44, 4));
							$MP3fileInfo['RIFF']['raw']['strh']["$i"]['rcFrame']               = LittleEndian2Int(substr($strhData, 48, 4));

							if (isset($MP3fileInfo['RIFF']['AVI ']['hdrl']['strl']['strf']["$i"]['data'])) {
								$strfData = $MP3fileInfo['RIFF']['AVI ']['hdrl']['strl']['strf']["$i"]['data'];
								switch ($MP3fileInfo['RIFF']['raw']['strh']["$i"]['fccType']) {
									case 'auds':
										if (isset($MP3fileInfo['RIFF']['audio']) && is_array($MP3fileInfo['RIFF']['audio'])) {
											$streamindex = count($MP3fileInfo['RIFF']['audio']);
										}
										$MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['wFormatTag']      = LittleEndian2Int(substr($strfData,  0, 2));
										$MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nChannels']       = LittleEndian2Int(substr($strfData,  2, 2));
										$MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nSamplesPerSec']  = LittleEndian2Int(substr($strfData,  4, 4));
										$MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nAvgBytesPerSec'] = LittleEndian2Int(substr($strfData,  8, 4));
										$MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nBlockAlign']     = LittleEndian2Int(substr($strfData, 12, 2));
										$MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nBitsPerSample']  = LittleEndian2Int(substr($strfData, 14, 2));
						
										$MP3fileInfo['RIFF']['audio']["$streamindex"]['format']        = RIFFwFormatTagLookup($MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['wFormatTag']);
										$MP3fileInfo['RIFF']['audio']["$streamindex"]['channels']      = $MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nChannels'];
										$MP3fileInfo['RIFF']['audio']["$streamindex"]['channelmode']   = (($MP3fileInfo['RIFF']['audio']["$streamindex"]['channels'] == 1) ? 'mono' : 'stereo');
										$MP3fileInfo['RIFF']['audio']["$streamindex"]['frequency']     = $MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nSamplesPerSec'];
										$MP3fileInfo['RIFF']['audio']["$streamindex"]['bitrate']       = $MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nAvgBytesPerSec'] * 8;
										$MP3fileInfo['RIFF']['audio']["$streamindex"]['bitspersample'] = $MP3fileInfo['RIFF']['raw']['strf']['auds']["$streamindex"]['nBitsPerSample'];
										break;
									case 'vids':
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biSize']          = LittleEndian2Int(substr($strfData,  0, 4)); // number of bytes required by the BITMAPINFOHEADER structure
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biWidth']         = LittleEndian2Int(substr($strfData,  4, 4)); // width of the bitmap in pixels
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biHeight']        = LittleEndian2Int(substr($strfData,  8, 4)); // height of the bitmap in pixels. If biHeight is positive, the bitmap is a "bottom-up" DIB and its origin is the lower left corner. If biHeight is negative, the bitmap is a "top-down" DIB and its origin is the upper left corner
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biPlanes']        = LittleEndian2Int(substr($strfData, 12, 2)); // number of color planes on the target device. In most cases this value must be set to 1
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biBitCount']      = LittleEndian2Int(substr($strfData, 14, 2)); // Specifies the number of bits per pixels
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['fourcc']          = substr($strfData, 16, 4);                   // 
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biSizeImage']     = LittleEndian2Int(substr($strfData, 20, 4)); // size of the bitmap data section of the image (the actual pixel data, excluding BITMAPINFOHEADER and RGBQUAD structures)
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biXPelsPerMeter'] = LittleEndian2Int(substr($strfData, 24, 4)); // horizontal resolution, in pixels per metre, of the target device
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biYPelsPerMeter'] = LittleEndian2Int(substr($strfData, 28, 4)); // vertical resolution, in pixels per metre, of the target device
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biClrUsed']       = LittleEndian2Int(substr($strfData, 32, 4)); // actual number of color indices in the color table used by the bitmap. If this value is zero, the bitmap uses the maximum number of colors corresponding to the value of the biBitCount member for the compression mode specified by biCompression
										$MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['biClrImportant']  = LittleEndian2Int(substr($strfData, 36, 4)); // number of color indices that are considered important for displaying the bitmap. If this value is zero, all colors are important
		
										$MP3fileInfo['RIFF']['video']["$streamindex"]['codec'] = RIFFfourccLookup($MP3fileInfo['RIFF']['raw']['strh']["$i"]['fccHandler']);
										if (!$MP3fileInfo['RIFF']['video']["$streamindex"]['codec'] && RIFFfourccLookup($MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['fourcc'])) {
											RIFFfourccLookup($MP3fileInfo['RIFF']['raw']['strf']['vids']["$streamindex"]['fourcc']);
										}
										break;
								}
							}
						}
					}
				}
			}
			break;
		default:
			unset($MP3fileInfo['fileformat']);
			break;
	}

	if (isset($MP3fileInfo['RIFF']['WAVE']['INFO']) && is_array($MP3fileInfo['RIFF']['WAVE']['INFO'])) {
		$MP3fileInfo['RIFF']['title']              = trim(substr($MP3fileInfo['RIFF']['WAVE']['INFO']['DISP'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['DISP']) - 1]['data'], 4));
		$MP3fileInfo['RIFF']['artist']             = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['IART'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['IART']) - 1]['data']);
		$MP3fileInfo['RIFF']['genre']              = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['IGNR'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['IGNR']) - 1]['data']);
		$MP3fileInfo['RIFF']['comment']            = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['ICMT'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['ICMT']) - 1]['data']);

		$MP3fileInfo['RIFF']['copyright']          = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['ICOP'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['ICOP']) - 1]['data']);
		$MP3fileInfo['RIFF']['engineers']          = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['IENG'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['IENG']) - 1]['data']);
		$MP3fileInfo['RIFF']['keywords']           = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['IKEY'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['IKEY']) - 1]['data']);
		$MP3fileInfo['RIFF']['originalmedium']     = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['IMED'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['IMED']) - 1]['data']);
		$MP3fileInfo['RIFF']['name']               = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['INAM'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['INAM']) - 1]['data']);
		$MP3fileInfo['RIFF']['sourcesupplier']     = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['ISRC'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['ISRC']) - 1]['data']);
		$MP3fileInfo['RIFF']['digitizer']          = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['ITCH'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['ITCH']) - 1]['data']);
		$MP3fileInfo['RIFF']['subject']            = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['ISBJ'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['ISBJ']) - 1]['data']);
		$MP3fileInfo['RIFF']['digitizationsource'] = trim($MP3fileInfo['RIFF']['WAVE']['INFO']['ISRF'][count($MP3fileInfo['RIFF']['WAVE']['INFO']['ISRF']) - 1]['data']);
	}
	foreach ($MP3fileInfo['RIFF'] as $key => $value) {
		if (!is_array($value) && !$value) {
			unset($MP3fileInfo['RIFF']["$key"]);
		}
	}

	return TRUE;
}
?>