<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// putid3.php - part of getID3()                          //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function GenerateID3v2TagFlags($majorversion=4, $Unsynchronisation=FALSE, $Compression=FALSE, $ExtendedHeader=FALSE, $Experimental=FALSE, $Footer=FALSE) {
	if ($majorversion == 4) {
		// %abcd0000
		$flag .= Bool2IntString($Unsynchronisation); // a - Unsynchronisation
		$flag .= Bool2IntString($ExtendedHeader);    // b - Extended header
		$flag .= Bool2IntString($Experimental);      // c - Experimental indicator
		$flag .= Bool2IntString($Footer);            // d - Footer present
		$flag .= '0000';
	} else if ($majorversion == 3) {
		// %abc00000
		$flag .= Bool2IntString($Unsynchronisation); // a - Unsynchronisation
		$flag .= Bool2IntString($ExtendedHeader);    // b - Extended header
		$flag .= Bool2IntString($Experimental);      // c - Experimental indicator
		$flag .= '00000';
	} else if ($majorversion == 2) {
		// %ab000000
		$flag .= Bool2IntString($Unsynchronisation); // a - Unsynchronisation
		$flag .= Bool2IntString($Compression);       // b - Compression
		$flag .= '000000';
	} else {
		return FALSE;
	}
	return chr(bindec($flag));
}


function GenerateID3v2FrameFlags($majorversion=4, $TagAlter=FALSE, $FileAlter=FALSE, $ReadOnly=FALSE, $Compression=FALSE, $Encryption=FALSE, $GroupingIdentity=FALSE, $Unsynchronisation=FALSE, $DataLengthIndicator=FALSE) {
	if ($majorversion == 4) {
		// %0abc0000 %0h00kmnp

		$flag1 .= '0';
		$flag1 .= Bool2IntString($TagAlter);  // a - Tag alter preservation (TRUE == discard)
		$flag1 .= Bool2IntString($FileAlter); // b - File alter preservation (TRUE == discard)
		$flag1 .= Bool2IntString($ReadOnly);  // c - Read only (TRUE == read only)
		$flag1 .= '0000';

		$flag2 .= '0';
		$flag2 .= Bool2IntString($GroupingIdentity);    // h - Grouping identity (TRUE == contains group information)
		$flag2 .= '00';
		$flag2 .= Bool2IntString($Compression);         // k - Compression (TRUE == compressed)
		$flag2 .= Bool2IntString($Encryption);          // m - Encryption (TRUE == encrypted)
		$flag2 .= Bool2IntString($Unsynchronisation);   // n - Unsynchronisation (TRUE == unsynchronised)
		$flag2 .= Bool2IntString($DataLengthIndicator); // p - Data length indicator (TRUE == data length indicator added)

	} else if ($majorversion == 3) {
		// %abc00000 %ijk00000

		$flag1 .= Bool2IntString($TagAlter);  // a - Tag alter preservation (TRUE == discard)
		$flag1 .= Bool2IntString($FileAlter); // b - File alter preservation (TRUE == discard)
		$flag1 .= Bool2IntString($ReadOnly);  // c - Read only (TRUE == read only)
		$flag1 .= '00000';

		$flag2 .= Bool2IntString($Compression);      // i - Compression (TRUE == compressed)
		$flag2 .= Bool2IntString($Encryption);       // j - Encryption (TRUE == encrypted)
		$flag2 .= Bool2IntString($GroupingIdentity); // k - Grouping identity (TRUE == contains group information)
		$flag2 .= '00000';

	} else {
		return FALSE;
	}
	return chr(bindec($flag1)).chr(bindec($flag2));
}

function GenerateID3v2FrameData($frame_name, $frame_data, $majorversion=4, $showerrors=FALSE) {
	if (!IsValidID3v2FrameName($frame_name, $majorversion)) {
		return FALSE;
	}
	if ($majorversion == 2) {
		ksort($frame_data);
		reset($frame_data);
		switch ($frame_name) {
			case 'TXX':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'WXX':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'IPL':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'MCI':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'ETC':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'MLL':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'STC':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'ULT':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'SLT':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'COM':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'RVA':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'EQU':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'REV':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'PIC':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'GEO':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'CNT':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'POP':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'BUF':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'CRM':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'CRA':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			case 'LNK':
				$error .= $frame_name.' not yet supported in putid3.php<BR>';
				break;
			default:
				if ($frame_name{0} == 'T') {
					// T??
					$error .= $frame_name.' not yet supported in putid3.php<BR>';
				} else if ($frame_name{0} == 'W') {
					// W??
					$error .= $frame_name.' not yet supported in putid3.php<BR>';
				} else {
					$error .= $frame_name.' not yet supported in putid3.php<BR>';
					return FALSE;
				}
		}
	} else { // $majorversion > 2
		switch ($frame_name) {
			case 'UFID':
				// 4.1   UFID Unique file identifier
				// Owner identifier        <text string> $00
				// Identifier              <up to 64 bytes binary data>
				if (strlen($frame_data['data']) > 64) {
					$error .= 'Identifier not allowed to be longer than 64 bytes in '.$frame_name.' (supplied data was '.strlen($frame_data['data']).' bytes long)<BR>';
				} else {
					$framedata .= str_replace(chr(0), '', $frame_data['ownerid']).chr(0);
					$framedata .= substr($frame_data['data'], 0, 64); // max 64 bytes - truncate anything longer
				}
				break;
			case 'TXXX':
				// 4.2.2 TXXX User defined text information frame
				// Text encoding     $xx
				// Description       <text string according to encoding> $00 (00)
				// Value             <text string according to encoding>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'WXXX':
				// 4.3.2 WXXX User defined URL link frame
				// Text encoding     $xx
				// Description       <text string according to encoding> $00 (00)
				// URL               <text string>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (!IsValidURL($frame_data['url'], FALSE, FALSE)) {
					$error .= 'Invalid URL in '.$frame_name.' ('.$frame_data['url'].')<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['url'];
				}
				break;
			case 'IPLS':
				// 4.4  IPLS Involved people list (ID3v2.3 only)
				// Text encoding     $xx
				// People list strings    <textstrings>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'MCDI':
				// 4.4   MCDI Music CD identifier
				// CD TOC                <binary data>
				$framedata .= $frame_data['data'];
				break;
			case 'ETCO':
				// 4.5   ETCO Event timing codes
				// Time stamp format    $xx
				//   Where time stamp format is:
				// $01  (32-bit value) MPEG frames from beginning of file
				// $02  (32-bit value) milliseconds from beginning of file
				//   Followed by a list of key events in the following format:
				// Type of event   $xx
				// Time stamp      $xx (xx ...)
				//   The 'Time stamp' is set to zero if directly at the beginning of the sound
				//   or after the previous event. All events MUST be sorted in chronological order.
				if (($frame_data['timestampformat'] > 2) || ($frame_data['timestampformat'] < 1)) {
					$error .= 'Invalid Time Stamp Format byte in '.$frame_name.' ('.$frame_data['timestampformat'].')<BR>';
				} else {
					$framedata .= chr($frame_data['timestampformat']);
					foreach ($frame_data as $key => $val) {
						if (!IsValidETCOevent($val['typeid'], $majorversion)) {
							$error .= 'Invalid Event Type byte in '.$frame_name.' ('.$val['typeid'].')<BR>';
						} else if (($key != 'timestampformat') && ($key != 'flags')) {
							if (($val['timestamp'] > 0) && ($previousETCOtimestamp >= $val['timestamp'])) {
								//   The 'Time stamp' is set to zero if directly at the beginning of the sound
								//   or after the previous event. All events MUST be sorted in chronological order.
								$error .= 'Out-of-order timestamp in '.$frame_name.' ('.$val['timestamp'].') for Event Type ('.$val['typeid'].')<BR>';
							} else {
								$framedata .= chr($val['typeid']);
								$framedata .= BigEndian2String($val['timestamp'], 4, FALSE);
							}
						}
					}
				}
				break;
			case 'MLLT':
				// 4.6   MLLT MPEG location lookup table
				// MPEG frames between reference  $xx xx
				// Bytes between reference        $xx xx xx
				// Milliseconds between reference $xx xx xx
				// Bits for bytes deviation       $xx
				// Bits for milliseconds dev.     $xx
				//   Then for every reference the following data is included;
				// Deviation in bytes         %xxx....
				// Deviation in milliseconds  %xxx....
				if (($frame_data['framesbetweenreferences'] > 0) && ($frame_data['framesbetweenreferences'] <= 65535)) {
					$framedata .= BigEndian2String($frame_data['framesbetweenreferences'], 2, FALSE);
				} else {
					$error .= 'Invalid MPEG Frames Between References in '.$frame_name.' ('.$frame_data['framesbetweenreferences'].')<BR>';
				}
				if (($frame_data['bytesbetweenreferences'] > 0) && ($frame_data['bytesbetweenreferences'] <= 16777215)) {
					$framedata .= BigEndian2String($frame_data['bytesbetweenreferences'], 3, FALSE);
				} else {
					$error .= 'Invalid bytes Between References in '.$frame_name.' ('.$frame_data['bytesbetweenreferences'].')<BR>';
				}
				if (($frame_data['msbetweenreferences'] > 0) && ($frame_data['msbetweenreferences'] <= 16777215)) {
					$framedata .= BigEndian2String($frame_data['msbetweenreferences'], 3, FALSE);
				} else {
					$error .= 'Invalid Milliseconds Between References in '.$frame_name.' ('.$frame_data['msbetweenreferences'].')<BR>';
				}
				if (!IsWithinBitRange($frame_data['bitsforbytesdeviation'], 8, FALSE)) {
					if (($frame_data['bitsforbytesdeviation'] % 4) == 0) {
						$framedata .= chr($frame_data['bitsforbytesdeviation']);
					} else {
						$error .= 'Bits For Bytes Deviation in '.$frame_name.' ('.$frame_data['bitsforbytesdeviation'].') must be a multiple of 4.<BR>';
					}
				} else {
					$error .= 'Invalid Bits For Bytes Deviation in '.$frame_name.' ('.$frame_data['bitsforbytesdeviation'].')<BR>';
				}
				if (!IsWithinBitRange($frame_data['bitsformsdeviation'], 8, FALSE)) {
					if (($frame_data['bitsformsdeviation'] % 4) == 0) {
						$framedata .= chr($frame_data['bitsformsdeviation']);
					} else {
						$error .= 'Bits For Milliseconds Deviation in '.$frame_name.' ('.$frame_data['bitsforbytesdeviation'].') must be a multiple of 4.<BR>';
					}
				} else {
					$error .= 'Invalid Bits For Milliseconds Deviation in '.$frame_name.' ('.$frame_data['bitsformsdeviation'].')<BR>';
				}
				foreach ($frame_data as $key => $val) {
					if (($key != 'framesbetweenreferences') && ($key != 'bytesbetweenreferences') && ($key != 'msbetweenreferences') && ($key != 'bitsforbytesdeviation') && ($key != 'bitsformsdeviation') && ($key != 'flags')) {
						$unwrittenbitstream .= str_pad(Dec2Bin($val['bytedeviation']), $frame_data['bitsforbytesdeviation'], '0', STR_PAD_LEFT);
						$unwrittenbitstream .= str_pad(Dec2Bin($val['msdeviation']),   $frame_data['bitsformsdeviation'],    '0', STR_PAD_LEFT);
					}
				}
				for ($i=0;$i<strlen($unwrittenbitstream);$i+=8) {
					$highnibble = bindec(substr($unwrittenbitstream, $i, 4)) << 4;
					$lownibble  = bindec(substr($unwrittenbitstream, $i + 4, 4));
					$framedata .= chr($highnibble & $lownibble);
				}
				break;
			case 'SYTC':
				// 4.7   SYTC Synchronised tempo codes
				// Time stamp format   $xx
				// Tempo data          <binary data>
				//   Where time stamp format is:
				// $01  (32-bit value) MPEG frames from beginning of file
				// $02  (32-bit value) milliseconds from beginning of file
				if (($frame_data['timestampformat'] > 2) || ($frame_data['timestampformat'] < 1)) {
					$error .= 'Invalid Time Stamp Format byte in '.$frame_name.' ('.$frame_data['timestampformat'].')<BR>';
				} else {
					$framedata .= chr($frame_data['timestampformat']);
					foreach ($frame_data as $key => $val) {
						if (!IsValidETCOevent($val['typeid'], $majorversion)) {
							$error .= 'Invalid Event Type byte in '.$frame_name.' ('.$val['typeid'].')<BR>';
						} else if (($key != 'timestampformat') && ($key != 'flags')) {
							if (($val['tempo'] < 0) || ($val['tempo'] > 510)) {
								$error .= 'Invalid Tempo (max = 510) in '.$frame_name.' ('.$val['tempo'].') at timestamp ('.$val['timestamp'].')<BR>';
							} else {
								if ($val['tempo'] > 255) {
									$framedata .= chr(255);
									$val['tempo'] -= 255;
								}
								$framedata .= chr($val['tempo']);
								$framedata .= BigEndian2String($val['timestamp'], 4, FALSE);
							}
						}
					}
				}
				break;
			case 'USLT':
				// 4.8   USLT Unsynchronised lyric/text transcription
				// Text encoding        $xx
				// Language             $xx xx xx
				// Content descriptor   <text string according to encoding> $00 (00)
				// Lyrics/text          <full text string according to encoding>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (LanguageLookup($frame_data['language'], TRUE) == '') {
					$error .= 'Invalid Language in '.$frame_name.' ('.$frame_data['language'].')<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= strtolower($frame_data['language']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'SYLT':
				// 4.9   SYLT Synchronised lyric/text
				// Text encoding        $xx
				// Language             $xx xx xx
				// Time stamp format    $xx
				//   $01  (32-bit value) MPEG frames from beginning of file
				//   $02  (32-bit value) milliseconds from beginning of file
				// Content type         $xx
				// Content descriptor   <text string according to encoding> $00 (00)
				//   Terminated text to be synced (typically a syllable)
				//   Sync identifier (terminator to above string)   $00 (00)
				//   Time stamp                                     $xx (xx ...)
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (LanguageLookup($frame_data['language'], TRUE) == '') {
					$error .= 'Invalid Language in '.$frame_name.' ('.$frame_data['language'].')<BR>';
				} else if (($frame_data['timestampformat'] > 2) || ($frame_data['timestampformat'] < 1)) {
					$error .= 'Invalid Time Stamp Format byte in '.$frame_name.' ('.$frame_data['timestampformat'].')<BR>';
				} else if (!IsValidSYLTtype($frame_data['contenttypeid'], $majorversion)) {
					$error .= 'Invalid Content Type byte in '.$frame_name.' ('.$frame_data['contenttypeid'].')<BR>';
				} else if (!is_array($frame_data['data'])) {
					$error .= 'Invalid Lyric/Timestamp data in '.$frame_name.' (must be an array)<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= strtolower($frame_data['language']);
					$framedata .= chr($frame_data['timestampformat']);
					$framedata .= chr($frame_data['contenttypeid']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					ksort($frame_data['data']);
					foreach ($frame_data['data'] as $key => $val) {
						$framedata .= $val['data'].TextEncodingLookup('terminator', $frame_data['encodingid']);
						$framedata .= BigEndian2String($val['timestamp'], 4, FALSE);
					}
				}
				break;
			case 'COMM':
				// 4.10  COMM Comments
				// Text encoding          $xx
				// Language               $xx xx xx
				// Short content descrip. <text string according to encoding> $00 (00)
				// The actual text        <full text string according to encoding>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (LanguageLookup($frame_data['language'], TRUE) == '') {
					$error .= 'Invalid Language in '.$frame_name.' ('.$frame_data['language'].')<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= strtolower($frame_data['language']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'RVA2':
				// 4.11  RVA2 Relative volume adjustment (2) (ID3v2.4+ only)
				// Identification          <text string> $00
				//   The 'identification' string is used to identify the situation and/or
				//   device where this adjustment should apply. The following is then
				//   repeated for every channel:
				// Type of channel         $xx
				// Volume adjustment       $xx xx
				// Bits representing peak  $xx
				// Peak volume             $xx (xx ...)
				$framedata .= str_replace(chr(0), '', $frame_data['description']).chr(0);
				foreach ($frame_data as $key => $val) {
					if ($key != 'description') {
						$framedata .= chr($val['channeltypeid']);
						$framedata .= substr(str_pad(dechex($val['volumeadjust']), 8, '0', STR_PAD_LEFT), 4, 4); // signed 16-bit
						if (!IsWithinBitRange($frame_data['bitspeakvolume'], 8, FALSE)) {
							$framedata .= chr($val['bitspeakvolume']);
							if ($val['bitspeakvolume'] > 0) {
								$framedata .= BigEndian2String($val['peakvolume'], ceil($val['bitspeakvolume'] / 8), FALSE);
							}
						} else {
							$error .= 'Invalid Bits Representing Peak Volume in '.$frame_name.' ('.$val['bitspeakvolume'].') (range = 0 to 255)<BR>';
						}
					}
				}
				break;
			case 'RVAD':
				// 4.12  RVAD Relative volume adjustment (ID3v2.3 only)
				// Increment/decrement     %00fedcba
				// Bits used for volume descr.        $xx
				// Relative volume change, right      $xx xx (xx ...) // a
				// Relative volume change, left       $xx xx (xx ...) // b
				// Peak volume right                  $xx xx (xx ...)
				// Peak volume left                   $xx xx (xx ...)
				// Relative volume change, right back $xx xx (xx ...) // c
				// Relative volume change, left back  $xx xx (xx ...) // d
				// Peak volume right back             $xx xx (xx ...)
				// Peak volume left back              $xx xx (xx ...)
				// Relative volume change, center     $xx xx (xx ...) // e
				// Peak volume center                 $xx xx (xx ...)
				// Relative volume change, bass       $xx xx (xx ...) // f
				// Peak volume bass                   $xx xx (xx ...)
				if (!IsWithinBitRange($frame_data['bitsvolume'], 8, FALSE)) {
					$error .= 'Invalid Bits For Volume Description byte in '.$frame_name.' ('.$frame_data['bitsvolume'].') (range = 1 to 255)<BR>';
				} else {
					$incdecflag .= '00';
					$incdecflag .= Bool2IntString($frame_data['incdec']['right']);     // a - Relative volume change, right
					$incdecflag .= Bool2IntString($frame_data['incdec']['left']);      // b - Relative volume change, left
					$incdecflag .= Bool2IntString($frame_data['incdec']['rightrear']); // c - Relative volume change, right back
					$incdecflag .= Bool2IntString($frame_data['incdec']['leftrear']);  // d - Relative volume change, left back
					$incdecflag .= Bool2IntString($frame_data['incdec']['center']);    // e - Relative volume change, center
					$incdecflag .= Bool2IntString($frame_data['incdec']['bass']);      // f - Relative volume change, bass
					$framedata .= chr(bindec($incdecflag));
					$framedata .= chr($frame_data['bitsvolume']);
					$framedata .= BigEndian2String($frame_data['volumechange']['right'], ceil($frame_data['bitsvolume'] / 8), FALSE);
					$framedata .= BigEndian2String($frame_data['volumechange']['left'],  ceil($frame_data['bitsvolume'] / 8), FALSE);
					$framedata .= BigEndian2String($frame_data['peakvolume']['right'], ceil($frame_data['bitsvolume'] / 8), FALSE);
					$framedata .= BigEndian2String($frame_data['peakvolume']['left'],  ceil($frame_data['bitsvolume'] / 8), FALSE);
					if ($frame_data['volumechange']['rightrear'] || $frame_data['volumechange']['leftrear'] ||
						$frame_data['peakvolume']['rightrear'] || $frame_data['peakvolume']['leftrear'] ||
						$frame_data['volumechange']['center'] || $frame_data['peakvolume']['center'] ||
						$frame_data['volumechange']['bass'] || $frame_data['peakvolume']['bass']) {
							$framedata .= BigEndian2String($frame_data['volumechange']['rightrear'], ceil($frame_data['bitsvolume']/8), FALSE);
							$framedata .= BigEndian2String($frame_data['volumechange']['leftrear'],  ceil($frame_data['bitsvolume']/8), FALSE);
							$framedata .= BigEndian2String($frame_data['peakvolume']['rightrear'], ceil($frame_data['bitsvolume']/8), FALSE);
							$framedata .= BigEndian2String($frame_data['peakvolume']['leftrear'],  ceil($frame_data['bitsvolume']/8), FALSE);
					}
					if ($frame_data['volumechange']['center'] || $frame_data['peakvolume']['center'] ||
						$frame_data['volumechange']['bass'] || $frame_data['peakvolume']['bass']) {
							$framedata .= BigEndian2String($frame_data['volumechange']['center'], ceil($frame_data['bitsvolume']/8), FALSE);
							$framedata .= BigEndian2String($frame_data['peakvolume']['center'], ceil($frame_data['bitsvolume']/8), FALSE);
					}
					if ($frame_data['volumechange']['bass'] || $frame_data['peakvolume']['bass']) {
							$framedata .= BigEndian2String($frame_data['volumechange']['bass'], ceil($frame_data['bitsvolume']/8), FALSE);
							$framedata .= BigEndian2String($frame_data['peakvolume']['bass'], ceil($frame_data['bitsvolume']/8), FALSE);
					}
				}
				break;
			case 'EQU2':
				// 4.12  EQU2 Equalisation (2) (ID3v2.4+ only)
				// Interpolation method  $xx
				//   $00  Band
				//   $01  Linear
				// Identification        <text string> $00
				//   The following is then repeated for every adjustment point
				// Frequency          $xx xx
				// Volume adjustment  $xx xx
				if (($frame_data['interpolationmethod'] < 0) || ($frame_data['interpolationmethod'] > 1)) {
					$error .= 'Invalid Interpolation Method byte in '.$frame_name.' ('.$frame_data['interpolationmethod'].') (valid = 0 or 1)<BR>';
				} else {
					$framedata .= chr($frame_data['interpolationmethod']);
					$framedata .= str_replace(chr(0), '', $frame_data['description']).chr(0);
					foreach ($frame_data['data'] as $key => $val) {
						$framedata .= BigEndian2String(round($key * 2), 2, FALSE);
						$framedata .= substr(str_pad(dechex($val), 4, '0', STR_PAD_LEFT), 4, 4); // signed 16-bit
					}
				}
				break;
			case 'EQUA':
				// 4.12  EQUA Equalisation (ID3v2.3 only)
				// Adjustment bits    $xx
				//   This is followed by 2 bytes + ('adjustment bits' rounded up to the
				//   nearest byte) for every equalisation band in the following format,
				//   giving a frequency range of 0 - 32767Hz:
				// Increment/decrement   %x (MSB of the Frequency)
				// Frequency             (lower 15 bits)
				// Adjustment            $xx (xx ...)
				if (!IsWithinBitRange($frame_data['bitsvolume'], 8, FALSE)) {
					$error .= 'Invalid Adjustment Bits byte in '.$frame_name.' ('.$frame_data['bitsvolume'].') (range = 1 to 255)<BR>';
				} else {
					$framedata .= chr($frame_data['adjustmentbits']);
					foreach ($frame_data as $key => $val) {
						if ($key != 'bitsvolume') {
							if (($key > 32767) || ($key < 0)) {
								$error .= 'Invalid Frequency in '.$frame_name.' ('.$key.') (range = 0 to 32767)<BR>';
							} else {
								if ($val >= 0) {
									// put MSB of frequency to 1 if increment, 0 if decrement
									$key |= 0x8000;
								}
								$framedata .= BigEndian2String($key, 2, FALSE);
								$framedata .= BigEndian2String($val, ceil($frame_data['adjustmentbits'] / 8), FALSE);
							}
						}
					}
				}
				break;
			case 'RVRB':
				// 4.13  RVRB Reverb
				// Reverb left (ms)                 $xx xx
				// Reverb right (ms)                $xx xx
				// Reverb bounces, left             $xx
				// Reverb bounces, right            $xx
				// Reverb feedback, left to left    $xx
				// Reverb feedback, left to right   $xx
				// Reverb feedback, right to right  $xx
				// Reverb feedback, right to left   $xx
				// Premix left to right             $xx
				// Premix right to left             $xx
				if (!IsWithinBitRange($frame_data['left'], 16, FALSE)) {
					$error .= 'Invalid Reverb Left in '.$frame_name.' ('.$frame_data['left'].') (range = 0 to 65535)<BR>';
				} else if (!IsWithinBitRange($frame_data['right'], 16, FALSE)) {
					$error .= 'Invalid Reverb Left in '.$frame_name.' ('.$frame_data['right'].') (range = 0 to 65535)<BR>';
				} else if (!IsWithinBitRange($frame_data['bouncesL'], 8, FALSE)) {
					$error .= 'Invalid Reverb Bounces, Left in '.$frame_name.' ('.$frame_data['bouncesL'].') (range = 0 to 255)<BR>';
				} else if (!IsWithinBitRange($frame_data['bouncesR'], 8, FALSE)) {
					$error .= 'Invalid Reverb Bounces, Right in '.$frame_name.' ('.$frame_data['bouncesR'].') (range = 0 to 255)<BR>';
				} else if (!IsWithinBitRange($frame_data['feedbackLL'], 8, FALSE)) {
					$error .= 'Invalid Reverb Feedback, Left-To-Left in '.$frame_name.' ('.$frame_data['feedbackLL'].') (range = 0 to 255)<BR>';
				} else if (!IsWithinBitRange($frame_data['feedbackLR'], 8, FALSE)) {
					$error .= 'Invalid Reverb Feedback, Left-To-Right in '.$frame_name.' ('.$frame_data['feedbackLR'].') (range = 0 to 255)<BR>';
				} else if (!IsWithinBitRange($frame_data['feedbackRR'], 8, FALSE)) {
					$error .= 'Invalid Reverb Feedback, Right-To-Right in '.$frame_name.' ('.$frame_data['feedbackRR'].') (range = 0 to 255)<BR>';
				} else if (!IsWithinBitRange($frame_data['feedbackRL'], 8, FALSE)) {
					$error .= 'Invalid Reverb Feedback, Right-To-Left in '.$frame_name.' ('.$frame_data['feedbackRL'].') (range = 0 to 255)<BR>';
				} else if (!IsWithinBitRange($frame_data['premixLR'], 8, FALSE)) {
					$error .= 'Invalid Premix, Left-To-Right in '.$frame_name.' ('.$frame_data['premixLR'].') (range = 0 to 255)<BR>';
				} else if (!IsWithinBitRange($frame_data['premixRL'], 8, FALSE)) {
					$error .= 'Invalid Premix, Right-To-Left in '.$frame_name.' ('.$frame_data['premixRL'].') (range = 0 to 255)<BR>';
				} else {
					$framedata .= BigEndian2String($frame_data['left'], 2, FALSE);
					$framedata .= BigEndian2String($frame_data['right'], 2, FALSE);
					$framedata .= chr($frame_data['bouncesL']);
					$framedata .= chr($frame_data['bouncesR']);
					$framedata .= chr($frame_data['feedbackLL']);
					$framedata .= chr($frame_data['feedbackLR']);
					$framedata .= chr($frame_data['feedbackRR']);
					$framedata .= chr($frame_data['feedbackRL']);
					$framedata .= chr($frame_data['premixLR']);
					$framedata .= chr($frame_data['premixRL']);
				}
				break;
			case 'APIC':
				// 4.14  APIC Attached picture
				// Text encoding      $xx
				// MIME type          <text string> $00
				// Picture type       $xx
				// Description        <text string according to encoding> $00 (00)
				// Picture data       <binary data>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (!IsValidAPICpicturetype($frame_data['picturetypeid'], $majorversion)) {
					$error .= 'Invalid Picture Type byte in '.$frame_name.' ('.$frame_data['picturetypeid'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (($majorversion >= 3) && (!IsValidAPICimageformat($frame_data['mime'], $majorversion))) {
					$error .= 'Invalid MIME Type in '.$frame_name.' ('.$frame_data['mime'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (($frame_data['mime'] == '-->') && (!IsValidURL($frame_data['data'], FALSE, FALSE))) {
					$error .= 'Invalid URL in '.$frame_name.' ('.$frame_data['data'].')<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= str_replace(chr(0), '', $frame_data['mime']).chr(0);
					$framedata .= chr($frame_data['picturetypeid']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'GEOB':
				// 4.15  GEOB General encapsulated object
				// Text encoding          $xx
				// MIME type              <text string> $00
				// Filename               <text string according to encoding> $00 (00)
				// Content description    <text string according to encoding> $00 (00)
				// Encapsulated object    <binary data>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
				} else if (!IsValidMIMEstring($frame_data['mime'])) {
					$error .= 'Invalid MIME Type in '.$frame_name.' ('.$frame_data['mime'].')<BR>';
				} else if (!$frame_data['description']) {
					$error .= 'Missing Description in '.$frame_name.'<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= str_replace(chr(0), '', $frame_data['mime']).chr(0);
					$framedata .= $frame_data['filename'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'PCNT':
				// 4.16  PCNT Play counter
				//   When the counter reaches all one's, one byte is inserted in
				//   front of the counter thus making the counter eight bits bigger
				// Counter        $xx xx xx xx (xx ...)
				$framedata .= BigEndian2String($frame_data['data'], 4, FALSE);
				break;
			case 'POPM':
				// 4.17  POPM Popularimeter
				//   When the counter reaches all one's, one byte is inserted in
				//   front of the counter thus making the counter eight bits bigger
				// Email to user   <text string> $00
				// Rating          $xx
				// Counter         $xx xx xx xx (xx ...)
				if (!IsWithinBitRange($frame_data['rating'], 8, FALSE)) {
					$error .= 'Invalid Rating byte in '.$frame_name.' ('.$frame_data['rating'].') (range = 0 to 255)<BR>';
				} else if (!IsValidEmail($frame_data['email'])) {
					$error .= 'Invalid Email in '.$frame_name.' ('.$frame_data['email'].')<BR>';
				} else {
					$framedata .= str_replace(chr(0), '', $frame_data['email']).chr(0);
					$framedata .= chr($frame_data['rating']);
					$framedata .= BigEndian2String($frame_data['data'], 4, FALSE);
				}
				break;
			case 'RBUF':
				// 4.18  RBUF Recommended buffer size
				// Buffer size               $xx xx xx
				// Embedded info flag        %0000000x
				// Offset to next tag        $xx xx xx xx
				if (!IsWithinBitRange($frame_data['buffersize'], 24, FALSE)) {
					$error .= 'Invalid Buffer Size in '.$frame_name.'<BR>';
				} else if (!IsWithinBitRange($frame_data['nexttagoffset'], 32, FALSE)) {
					$error .= 'Invalid Offset To Next Tag in '.$frame_name.'<BR>';
				} else {
					$framedata .= BigEndian2String($frame_data['buffersize'], 3, FALSE);
					$flag .= '0000000';
					$flag .= Bool2IntString($frame_data['flags']['embededinfo']);
					$framedata .= chr(bindec($flag));
					$framedata .= BigEndian2String($frame_data['nexttagoffset'], 4, FALSE);
				}
				break;
			case 'AENC':
				// 4.19  AENC Audio encryption
				// Owner identifier   <text string> $00
				// Preview start      $xx xx
				// Preview length     $xx xx
				// Encryption info    <binary data>
				if (!IsWithinBitRange($frame_data['previewstart'], 16, FALSE)) {
					$error .= 'Invalid Preview Start in '.$frame_name.' ('.$frame_data['previewstart'].')<BR>';
				} else if (!IsWithinBitRange($frame_data['previewlength'], 16, FALSE)) {
					$error .= 'Invalid Preview Length in '.$frame_name.' ('.$frame_data['previewlength'].')<BR>';
				} else {
					$framedata .= str_replace(chr(0), '', $frame_data['ownerid']).chr(0);
					$framedata .= BigEndian2String($frame_data['previewstart'], 2, FALSE);
					$framedata .= BigEndian2String($frame_data['previewlength'], 2, FALSE);
					$framedata .= $frame_data['encryptioninfo'];
				}
				break;
			case 'LINK':
				// 4.20  LINK Linked information
				// Frame identifier               $xx xx xx xx
				// URL                            <text string> $00
				// ID and additional data         <text string(s)>
				if (!IsValidID3v2FrameName($frame_data['frameid'], $majorversion)) {
					$error .= 'Invalid Frame Identifier in '.$frame_name.' ('.$frame_data['frameid'].')<BR>';
				} else if (!IsValidURL($frame_data['url'], TRUE, FALSE)) {
					$error .= 'Invalid URL in '.$frame_name.' ('.$frame_data['url'].')<BR>';
				} else if ((($frame_data['frameid'] == 'AENC') || ($frame_data['frameid'] == 'APIC') || ($frame_data['frameid'] == 'GEOB') || ($frame_data['frameid'] == 'TXXX')) && ($frame_data['additionaldata'] == '')) {
					$error .= 'Content Descriptor must be specified as additional data for Frame Identifier of '.$frame_data['frameid'].' in '.$frame_name.'<BR>';
				} else if (($frame_data['frameid'] == 'USER') && (LanguageLookup($frame_data['additionaldata'], TRUE) == '')) {
					$error .= 'Language must be specified as additional data for Frame Identifier of '.$frame_data['frameid'].' in '.$frame_name.'<BR>';
				} else if (($frame_data['frameid'] == 'PRIV') && ($frame_data['additionaldata'] == '')) {
					$error .= 'Owner Identifier must be specified as additional data for Frame Identifier of '.$frame_data['frameid'].' in '.$frame_name.'<BR>';
				} else if ((($frame_data['frameid'] == 'COMM') || ($frame_data['frameid'] == 'SYLT') || ($frame_data['frameid'] == 'USLT')) && ((LanguageLookup(substr($frame_data['additionaldata'], 0, 3), TRUE) == '') || (substr($frame_data['additionaldata'], 3) == ''))) {
					$error .= 'Language followed by Content Descriptor must be specified as additional data for Frame Identifier of '.$frame_data['frameid'].' in '.$frame_name.'<BR>';
				} else {
					$framedata .= $frame_data['frameid'];
					$framedata .= str_replace(chr(0), '', $frame_data['url']).chr(0);
					switch ($frame_data['frameid']) {
						case 'COMM':
						case 'SYLT':
						case 'USLT':
						case 'PRIV':
						case 'USER':
						case 'AENC':
						case 'APIC':
						case 'GEOB':
						case 'TXXX':
							$framedata .= $frame_data['additionaldata'];
							break;
						case 'ASPI':
						case 'ETCO':
						case 'EQU2':
						case 'MCID':
						case 'MLLT':
						case 'OWNE':
						case 'RVA2':
						case 'RVRB':
						case 'SYTC':
						case 'IPLS':
						case 'RVAD':
						case 'EQUA':
							// no additional data required
							break;
						case 'RBUF':
							if ($majorversion == 3) {
								// no additional data required
							} else {
								$error .= $frame_data['frameid'].' is not a valid Frame Identifier in '.$frame_name.' (in ID3v2.'.$majorversion.')<BR>';
							}

						default:
							if ((substr($frame_data['frameid'], 0, 1) == 'T') || (substr($frame_data['frameid'], 0, 1) == 'W')) {
								// no additional data required
							} else {
								$error .= $frame_data['frameid'].' is not a valid Frame Identifier in '.$frame_name.' (in ID3v2.'.$majorversion.')<BR>';
							}
							break;
					}
				}
				break;
			case 'POSS':
				// 4.21  POSS Position synchronisation frame (ID3v2.3+ only)
				// Time stamp format         $xx
				// Position                  $xx (xx ...)
				if (($frame_data['timestampformat'] < 1) || ($frame_data['timestampformat'] > 2)) {
					$error .= 'Invalid Time Stamp Format in '.$frame_name.' ('.$frame_data['timestampformat'].') (valid = 1 or 2)<BR>';
				} else if (!IsWithinBitRange($frame_data['position'], 32, FALSE)) {
					$error .= 'Invalid Position in '.$frame_name.' ('.$frame_data['position'].') (range = 0 to 4294967295)<BR>';
				} else {
					$framedata .= chr($frame_data['timestampformat']);
					$framedata .= BigEndian2String($frame_data['position'], 4, FALSE);
				}
				break;
			case 'USER':
				// 4.22  USER Terms of use (ID3v2.3+ only)
				// Text encoding        $xx
				// Language             $xx xx xx
				// The actual text      <text string according to encoding>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].')<BR>';
				} else if (LanguageLookup($frame_data['language'], TRUE) == '') {
					$error .= 'Invalid Language in '.$frame_name.' ('.$frame_data['language'].')<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= strtolower($frame_data['language']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'OWNE':
				// 4.23  OWNE Ownership frame (ID3v2.3+ only)
				// Text encoding     $xx
				// Price paid        <text string> $00
				// Date of purch.    <text string>
				// Seller            <text string according to encoding>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].')<BR>';
				} else if (!IsANumber($frame_data['pricepaid']['value'], FALSE)) {
					$error .= 'Invalid Price Paid in '.$frame_name.' ('.$frame_data['pricepaid']['value'].')<BR>';
				} else if (!IsValidDateStampString($frame_data['purchasedate'])) {
					$error .= 'Invalid Date Of Purchase in '.$frame_name.' ('.$frame_data['purchasedate'].') (format = YYYYMMDD)<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					$framedata .= str_replace(chr(0), '', $frame_data['pricepaid']['value']).chr(0);
					$framedata .= $frame_data['purchasedate'];
					$framedata .= $frame_data['seller'];
				}
				break;
			case 'COMR':
				// 4.24  COMR Commercial frame (ID3v2.3+ only)
				// Text encoding      $xx
				// Price string       <text string> $00
				// Valid until        <text string>
				// Contact URL        <text string> $00
				// Received as        $xx
				// Name of seller     <text string according to encoding> $00 (00)
				// Description        <text string according to encoding> $00 (00)
				// Picture MIME type  <string> $00
				// Seller logo        <binary data>
				if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
					$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].')<BR>';
				} else if (!IsValidDateStampString($frame_data['pricevaliduntil'])) {
					$error .= 'Invalid Valid Until date in '.$frame_name.' ('.$frame_data['pricevaliduntil'].') (format = YYYYMMDD)<BR>';
				} else if (!IsValidURL($frame_data['contacturl'], FALSE, TRUE)) {
					$error .= 'Invalid Contact URL in '.$frame_name.' ('.$frame_data['contacturl'].') (allowed schemes: http, https, ftp, mailto)<BR>';
				} else if (!IsValidCOMRreceivedas($frame_data['receivedasid'], $majorversion)) {
					$error .= 'Invalid Received As byte in '.$frame_name.' ('.$frame_data['contacturl'].') (range = 0 to 8)<BR>';
				} else if (!IsValidMIMEstring($frame_data['mime'])) {
					$error .= 'Invalid MIME Type in '.$frame_name.' ('.$frame_data['mime'].')<BR>';
				} else {
					$framedata .= chr($frame_data['encodingid']);
					unset($pricestring);
					foreach ($frame_data['price'] as $key => $val) {
						if (IsValidPriceString($key.$val['value'])) {
							$pricestrings[] = $key.$val['value'];
						} else {
							$error .= 'Invalid Price String in '.$frame_name.' ('.$key.$val['value'].')<BR>';
						}
					}
					$framedata .= implode('/', $pricestrings);
					$framedata .= $frame_data['pricevaliduntil'];
					$framedata .= str_replace(chr(0), '', $frame_data['contacturl']).chr(0);
					$framedata .= chr($frame_data['receivedasid']);
					$framedata .= $frame_data['sellername'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['description'].TextEncodingLookup('terminator', $frame_data['encodingid']);
					$framedata .= $frame_data['mime'].chr(0);
					$framedata .= $frame_data['logo'];
				}
				break;
			case 'ENCR':
				// 4.25  ENCR Encryption method registration (ID3v2.3+ only)
				// Owner identifier    <text string> $00
				// Method symbol       $xx
				// Encryption data     <binary data>
				if (!IsWithinBitRange($frame_data['methodsymbol'], 8, FALSE)) {
					$error .= 'Invalid Group Symbol in '.$frame_name.' ('.$frame_data['methodsymbol'].') (range = 0 to 255)<BR>';
				} else {
					$framedata .= str_replace(chr(0), '', $frame_data['ownerid']).chr(0);
					$framedata .= ord($frame_data['methodsymbol']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'GRID':
				// 4.26  GRID Group identification registration (ID3v2.3+ only)
				// Owner identifier      <text string> $00
				// Group symbol          $xx
				// Group dependent data  <binary data>
				if (!IsWithinBitRange($frame_data['groupsymbol'], 8, FALSE)) {
					$error .= 'Invalid Group Symbol in '.$frame_name.' ('.$frame_data['groupsymbol'].') (range = 0 to 255)<BR>';
				} else {
					$framedata .= str_replace(chr(0), '', $frame_data['ownerid']).chr(0);
					$framedata .= ord($frame_data['groupsymbol']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'PRIV':
				// 4.27  PRIV Private frame (ID3v2.3+ only)
				// Owner identifier      <text string> $00
				// The private data      <binary data>
				$framedata .= str_replace(chr(0), '', $frame_data['ownerid']).chr(0);
				$framedata .= $frame_data['data'];
				break;
			case 'SIGN':
				// 4.28  SIGN Signature frame (ID3v2.4+ only)
				// Group symbol      $xx
				// Signature         <binary data>
				if (!IsWithinBitRange($frame_data['groupsymbol'], 8, FALSE)) {
					$error .= 'Invalid Group Symbol in '.$frame_name.' ('.$frame_data['groupsymbol'].') (range = 0 to 255)<BR>';
				} else {
					$framedata .= ord($frame_data['groupsymbol']);
					$framedata .= $frame_data['data'];
				}
				break;
			case 'SEEK':
				// 4.29  SEEK Seek frame (ID3v2.4+ only)
				// Minimum offset to next tag       $xx xx xx xx
				if (!IsWithinBitRange($frame_data['data'], 32, FALSE)) {
					$error .= 'Invalid Minimum Offset in '.$frame_name.' ('.$frame_data['data'].') (range = 0 to 4294967295)<BR>';
				} else {
					$framedata .= BigEndian2String($frame_data['data'], 4, FALSE);
				}
				break;
			case 'ASPI':
				// 4.30  ASPI Audio seek point index (ID3v2.4+ only)
				// Indexed data start (S)         $xx xx xx xx
				// Indexed data length (L)        $xx xx xx xx
				// Number of index points (N)     $xx xx
				// Bits per index point (b)       $xx
				//   Then for every index point the following data is included:
				// Fraction at index (Fi)          $xx (xx)
				if (!IsWithinBitRange($frame_data['datastart'], 32, FALSE)) {
					$error .= 'Invalid Indexed Data Start in '.$frame_name.' ('.$frame_data['datastart'].') (range = 0 to 4294967295)<BR>';
				} else if (!IsWithinBitRange($frame_data['datalength'], 32, FALSE)) {
					$error .= 'Invalid Indexed Data Length in '.$frame_name.' ('.$frame_data['datalength'].') (range = 0 to 4294967295)<BR>';
				} else if (!IsWithinBitRange($frame_data['indexpoints'], 16, FALSE)) {
					$error .= 'Invalid Number Of Index Points in '.$frame_name.' ('.$frame_data['indexpoints'].') (range = 0 to 65535)<BR>';
				} else if (!IsWithinBitRange($frame_data['bitsperpoint'], 8, FALSE)) {
					$error .= 'Invalid Bits Per Index Point in '.$frame_name.' ('.$frame_data['bitsperpoint'].') (range = 0 to 255)<BR>';
				} else if ($frame_data['indexpoints'] != count($frame_data['indexes'])) {
					$error .= 'Number Of Index Points does not match actual supplied data in '.$frame_name.'<BR>';
				} else {
					$framedata .= BigEndian2String($frame_data['datastart'], 4, FALSE);
					$framedata .= BigEndian2String($frame_data['datalength'], 4, FALSE);
					$framedata .= BigEndian2String($frame_data['indexpoints'], 2, FALSE);
					$framedata .= BigEndian2String($frame_data['bitsperpoint'], 1, FALSE);
					foreach ($frame_data['indexes'] as $key => $val) {
						$framedata .= BigEndian2String($val, ceil($frame_data['bitsperpoint'] / 8), FALSE);
					}
				}
				break;
			case 'RGAD':
				//   RGAD Replay Gain Adjustment
				//   http://privatewww.essex.ac.uk/~djmrob/replaygain/
				// Peak Amplitude                     $xx $xx $xx $xx
				// Radio Replay Gain Adjustment        %aaabbbcd %dddddddd
				// Audiophile Replay Gain Adjustment   %aaabbbcd %dddddddd
				//   a - name code
				//   b - originator code
				//   c - sign bit
				//   d - replay gain adjustment

				if (($frame_data['radio_adjustment'] > 51) || ($frame_data['radio_adjustment'] < -51)) {
					$error .= 'Invalid Radio Adjustment in '.$frame_name.' ('.$frame_data['radio_adjustment'].') (range = -51.0 to +51.0)<BR>';
				} else if (($frame_data['audiophile_adjustment'] > 51) || ($frame_data['audiophile_adjustment'] < -51)) {
					$error .= 'Invalid Audiophile Adjustment in '.$frame_name.' ('.$frame_data['audiophile_adjustment'].') (range = -51.0 to +51.0)<BR>';
				} else if (!IsValidRGADname($frame_data['raw']['radio_name'], $majorversion)) {
					$error .= 'Invalid Radio Name Code in '.$frame_name.' ('.$frame_data['raw']['radio_name'].') (range = 0 to 2)<BR>';
				} else if (!IsValidRGADname($frame_data['raw']['audiophile_name'], $majorversion)) {
					$error .= 'Invalid Audiophile Name Code in '.$frame_name.' ('.$frame_data['raw']['audiophile_name'].') (range = 0 to 2)<BR>';
				} else if (!IsValidRGADoriginator($frame_data['raw']['radio_originator'], $majorversion)) {
					$error .= 'Invalid Radio Originator Code in '.$frame_name.' ('.$frame_data['raw']['radio_originator'].') (range = 0 to 3)<BR>';
				} else if (!IsValidRGADoriginator($frame_data['raw']['audiophile_originator'], $majorversion)) {
					$error .= 'Invalid Audiophile Originator Code in '.$frame_name.' ('.$frame_data['raw']['audiophile_originator'].') (range = 0 to 3)<BR>';
				} else {
					$framedata .= Float2String($frame_data['peakamplitude'], 32);
					$framedata .= RGADgainString($frame_data['raw']['radio_name'], $frame_data['raw']['radio_originator'], $frame_data['radio_adjustment']);
					$framedata .= RGADgainString($frame_data['raw']['audiophile_name'], $frame_data['raw']['audiophile_originator'], $frame_data['audiophile_adjustment']);
				}
				break;
			default:
				if ($frame_name{0} == 'T') {
					// 4.2. T???  Text information frames
					// Text encoding                $xx
					// Information                  <text string(s) according to encoding>
					if (!IsValidTextEncoding($frame_data['encodingid'], $majorversion)) {
						$error .= 'Invalid Text Encoding in '.$frame_name.' ('.$frame_data['encodingid'].') for ID3v2.'.$majorversion.'<BR>';
					} else {
						$framedata .= chr($frame_data['encodingid']);
						$framedata .= $frame_data['data'];
					}
				} else if ($frame_name{0} == 'W') {
					// 4.3. W???  URL link frames
					// URL              <text string>
					if (!IsValidURL($frame_data['url'], FALSE, FALSE)) {
						$error .= 'Invalid URL in '.$frame_name.' ('.$frame_data['url'].')<BR>';
					} else {
						$framedata .= $frame_data['url'];
					}
				} else {
					$error .= $frame_name.' not yet supported in putid3.php<BR>';
				}
				break;
		}
	}
	if ($error) {
		if ($showerrors) {
			echo $error;
		}
		return FALSE;
	} else {
		return $framedata;
	}
}

function ID3v2FrameIsAllowed($frame_name, $frame_data, $majorversion, $showerrors=FALSE) {
	static $PreviousFrames = array();
	
	if ($frame_name === NULL) {
		// if the writing functions are called multiple times, the static array needs to be
		// cleared - this can be done by calling ID3v2FrameIsAllowed(NULL, '', '')
		$PreviousFrames = array();
		return TRUE;
	}

	if ($majorversion == 4) {
		switch ($frame_name) {
			case 'UFID':
			case 'AENC':
			case 'ENCR':
			case 'GRID':
				if (in_array($frame_name.$frame_data['ownerid'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same OwnerID ('.$frame_data['ownerid'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['ownerid'];
				}
				break;
			case 'TXXX':
			case 'WXXX':
			case 'RVA2':
			case 'EQU2':
			case 'APIC':
			case 'GEOB':
				if (in_array($frame_name.$frame_data['description'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Description ('.$frame_data['description'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['description'];
				}
				break;
			case 'USER':
				if (in_array($frame_name.$frame_data['language'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Language ('.$frame_data['language'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['language'];
				}
				break;
			case 'USLT':
			case 'SYLT':
			case 'COMM':
				if (in_array($frame_name.$frame_data['language'].$frame_data['description'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Language + Description ('.$frame_data['language'].' + '.$frame_data['description'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['language'].$frame_data['description'];
				}
				break;
			case 'POPM':
				if (in_array($frame_name.$frame_data['email'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Email ('.$frame_data['email'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['email'];
				}
				break;
			case 'IPLS':
			case 'MCDI':
			case 'ETCO':
			case 'MLLT':
			case 'SYTC':
			case 'RVRB':
			case 'PCNT':
			case 'RBUF':
			case 'POSS':
			case 'OWNE':
			case 'SEEK':
			case 'ASPI':
			case 'RGAD':
				if (in_array($frame_name, $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed<BR>';
				} else {
					$PreviousFrames[] = $frame_name;
				}
				break;
			case 'LINK':
				// this isn't implemented quite right (yet) - it should check the target frame data for compliance
				// but right now it just allows one linked frame of each type, to be safe.
				if (in_array($frame_name.$frame_data['frameid'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same FrameID ('.$frame_data['frameid'].')<BR>';
				} else if (in_array($frame_data['frameid'], $PreviousFrames)) {
					// no links to singleton tags
					$error .= 'Cannot specify a '.$frame_name.' tag to a singleton tag that already exists ('.$frame_data['frameid'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['frameid']; // only one linked tag of this type
					$PreviousFrames[] = $frame_data['frameid'];             // no non-linked singleton tags of this type
				}
				break;
			case 'COMR':
				//   There may be more than one 'commercial frame' in a tag, but no two may be identical
				// Checking isn't implemented at all (yet) - just assumes that it's OK.
				break;
			case 'PRIV':
			case 'SIGN':
				if (in_array($frame_name.$frame_data['ownerid'].$frame_data['data'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same OwnerID + Data ('.$frame_data['ownerid'].' + '.$frame_data['data'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['ownerid'].$frame_data['data'];
				}
				break;
			default:
				if (($frame_name{0} != 'T') && ($frame_name{0} != 'W')) {
					$error .= 'Frame not allowed in ID3v2.'.$majorversion.': '.$frame_name.'<BR>';
				}
				break;
		}
	} else if ($majorversion == 3) {
		switch ($frame_name) {
			case 'UFID':
			case 'AENC':
			case 'ENCR':
			case 'GRID':
				if (in_array($frame_name.$frame_data['ownerid'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same OwnerID ('.$frame_data['ownerid'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['ownerid'];
				}
				break;
			case 'TXXX':
			case 'WXXX':
			case 'APIC':
			case 'GEOB':
				if (in_array($frame_name.$frame_data['description'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Description ('.$frame_data['description'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['description'];
				}
				break;
			case 'USER':
				if (in_array($frame_name.$frame_data['language'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Language ('.$frame_data['language'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['language'];
				}
				break;
			case 'USLT':
			case 'SYLT':
			case 'COMM':
				if (in_array($frame_name.$frame_data['language'].$frame_data['description'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Language + Description ('.$frame_data['language'].' + '.$frame_data['description'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['language'].$frame_data['description'];
				}
				break;
			case 'POPM':
				if (in_array($frame_name.$frame_data['email'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Email ('.$frame_data['email'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['email'];
				}
				break;
			case 'IPLS':
			case 'MCDI':
			case 'ETCO':
			case 'MLLT':
			case 'SYTC':
			case 'RVAD':
			case 'EQUA':
			case 'RVRB':
			case 'PCNT':
			case 'RBUF':
			case 'POSS':
			case 'OWNE':
			case 'RGAD':
				if (in_array($frame_name, $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed<BR>';
				} else {
					$PreviousFrames[] = $frame_name;
				}
				break;
			case 'LINK':
				// this isn't implemented quite right (yet) - it should check the target frame data for compliance
				// but right now it just allows one linked frame of each type, to be safe.
				if (in_array($frame_name.$frame_data['frameid'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same FrameID ('.$frame_data['frameid'].')<BR>';
				} else if (in_array($frame_data['frameid'], $PreviousFrames)) {
					// no links to singleton tags
					$error .= 'Cannot specify a '.$frame_name.' tag to a singleton tag that already exists ('.$frame_data['frameid'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['frameid']; // only one linked tag of this type
					$PreviousFrames[] = $frame_data['frameid'];             // no non-linked singleton tags of this type
				}
				break;
			case 'COMR':
				//   There may be more than one 'commercial frame' in a tag, but no two may be identical
				// Checking isn't implemented at all (yet) - just assumes that it's OK.
				break;
			case 'PRIV':
				if (in_array($frame_name.$frame_data['ownerid'].$frame_data['data'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same OwnerID + Data ('.$frame_data['ownerid'].' + '.$frame_data['data'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['ownerid'].$frame_data['data'];
				}
				break;
			default:
				if (($frame_name{0} != 'T') && ($frame_name{0} != 'W')) {
					$error .= 'Frame not allowed in ID3v2.'.$majorversion.': '.$frame_name.'<BR>';
				}
				break;
		}
	} else if ($majorversion == 2) {
		switch ($frame_name) {
			case 'UFI':
			case 'CRM':
			case 'CRA':
				if (in_array($frame_name.$frame_data['ownerid'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same OwnerID ('.$frame_data['ownerid'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['ownerid'];
				}
				break;
			case 'TXX':
			case 'WXX':
			case 'PIC':
			case 'GEO':
				if (in_array($frame_name.$frame_data['description'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Description ('.$frame_data['description'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['description'];
				}
				break;
			case 'ULT':
			case 'SLT':
			case 'COM':
				if (in_array($frame_name.$frame_data['language'].$frame_data['description'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Language + Description ('.$frame_data['language'].' + '.$frame_data['description'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['language'].$frame_data['description'];
				}
				break;
			case 'POP':
				if (in_array($frame_name.$frame_data['email'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same Email ('.$frame_data['email'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['email'];
				}
				break;
			case 'IPL':
			case 'MCI':
			case 'ETC':
			case 'MLL':
			case 'STC':
			case 'RVA':
			case 'EQU':
			case 'REV':
			case 'CNT':
			case 'BUF':
				if (in_array($frame_name, $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed<BR>';
				} else {
					$PreviousFrames[] = $frame_name;
				}
				break;
			case 'LNK':
				// this isn't implemented quite right (yet) - it should check the target frame data for compliance
				// but right now it just allows one linked frame of each type, to be safe.
				if (in_array($frame_name.$frame_data['frameid'], $PreviousFrames)) {
					$error .= 'Only one '.$frame_name.' tag allowed with the same FrameID ('.$frame_data['frameid'].')<BR>';
				} else if (in_array($frame_data['frameid'], $PreviousFrames)) {
					// no links to singleton tags
					$error .= 'Cannot specify a '.$frame_name.' tag to a singleton tag that already exists ('.$frame_data['frameid'].')<BR>';
				} else {
					$PreviousFrames[] = $frame_name.$frame_data['frameid']; // only one linked tag of this type
					$PreviousFrames[] = $frame_data['frameid'];             // no non-linked singleton tags of this type
				}
				break;
			default:
				if (($frame_name{0} != 'T') && ($frame_name{0} != 'W')) {
					$error .= 'Frame not allowed in ID3v2.'.$majorversion.': '.$frame_name.'<BR>';
				}
				break;
		}
	}

	if ($error) {
		if ($showerrors) {
			echo $error;
		}
		return FALSE;
	} else {
		return TRUE;
	}
}

function GenerateID3v2Tag($data, $majorversion=4, $minorversion=0, $paddedlength=0, $extendedheader='', $footer=FALSE, $showerrors=TRUE, $noerrorsonly=TRUE) {
	ID3v2FrameIsAllowed(NULL, '', ''); // clear static array in case this isn't the first call to GenerateID3v2Tag()

	if (is_array($data)) {
		if (is_array($extendedheader)) {
			// not supported yet
		}
		foreach ($data as $frame_name => $frame_rawinputdata) {
			if (!is_array($frame_rawinputdata[0])) {
				// force everything to be arrayed so only one processing loop
				$frame_rawinputdata = array($frame_rawinputdata);
			}
			foreach ($frame_rawinputdata as $irrelevantindex => $frame_inputdata) {
				if (IsValidID3v2FrameName($frame_name, $majorversion)) {
					unset($frame_length);
					unset($frame_flags);
					$frame_data = FALSE;
					if (ID3v2FrameIsAllowed($frame_name, $frame_inputdata, $majorversion, $showerrors)) {
						if ($frame_data = GenerateID3v2FrameData($frame_name, $frame_inputdata, $majorversion, $showerrors)) {
							if ($majorversion >= 4) {
								// frame-level unsynchronization
								$FrameUnsynchronisation = FALSE;
								$unsynchdata = Unsynchronise($frame_data);
								if (strlen($unsynchdata) != strlen($frame_data)) {
									// unsynchronization needed
									$FrameUnsynchronisation = TRUE;
									$frame_data = $unsynchdata;
									if (isset($TagUnsynchronisation) && $TagUnsynchronisation === FALSE) {
										// only set to true if ALL frames are unsynchronised
									} else {
										$TagUnsynchronisation = TRUE;
									}
								} else {
									if (isset($TagUnsynchronisation)) {
										$TagUnsynchronisation = FALSE;
									}
								}
								unset($unsynchdata);

								$frame_length = BigEndian2String(strlen($frame_data), 4, TRUE);
							} else {
								$frame_length = BigEndian2String(strlen($frame_data), 4, FALSE);
							}
							$frame_flags  = GenerateID3v2FrameFlags($majorversion, ID3v2FrameFlagsLookupTagAlter($frame_name, $majorversion), ID3v2FrameFlagsLookupFileAlter($frame_name, $majorversion), FALSE, FALSE, FALSE, FALSE, $FrameUnsynchronisation, FALSE);
						}
					} else {
						if ($showerrors) {
							echo 'Frame "'.$frame_name.'" is NOT allowed<BR>';
						}
					}
					if ($frame_data === FALSE) {
						if ($showerrors) {
							echo 'GenerateID3v2FrameData() failed for "'.$frame_name.'"<BR>';
							echo 'Error generated in getID3() v'.GETID3VERSION.'<BR>';
						}
						if ($noerrorsonly) {
							return FALSE;
						} else {
							unset($frame_name);
						}
					}
				} else {
					// ignore any invalid frame names, including 'title', 'header', etc
					unset($frame_name);
					unset($frame_length);
					unset($frame_flags);
					unset($frame_data);
				}
				$tagstring .= $frame_name.$frame_length.$frame_flags.$frame_data;
			}
		}
		if ($footer) {
			if ($showerrors) {
				echo 'Footer not supported (yet)<BR>';
			}
			return FALSE;
		}
//echo number_format(strlen($tagstring));
		if ($majorversion <= 3) {
			// tag-level unsynchronization
			$unsynchdata = Unsynchronise($tagstring);
			if (strlen($unsynchdata) != strlen($tagstring)) {
				// unsynchronization needed
				$TagUnsynchronisation = TRUE;
				$tagstring = $unsynchdata;
			}
		}
//echo ' - '.number_format(strlen($tagstring)).'<BR>';
		if (!$footer && ($paddedlength > (strlen($tagstring) + ID3v2HeaderLength($id3info['majorversion'])))) {
			// pad up to $paddedlength bytes if unpadded tag is shorter than $paddedlength
			// "Furthermore it MUST NOT have any padding when a tag footer is added to the tag."
			$tagstring .= @str_repeat(chr(0), $paddedlength - strlen($tagstring));
		}
		if (substr($tagstring, strlen($tagstring) - 1, 1) == chr(255)) {
			// special unsynchronization case:
			// if last byte == $FF then appended a $00
			$TagUnsynchronisation = TRUE;
			$tagstring .= chr(0);
		}

		$tagheader  = 'ID3';
		$tagheader .= chr($majorversion);
		$tagheader .= chr($minorversion);
		$tagheader .= GenerateID3v2TagFlags($majorversion, $TagUnsynchronisation, FALSE, (bool) $extendedheader, FALSE, $footer);
		$tagheader .= BigEndian2String(strlen($tagstring), 4, TRUE);

		return $tagheader.$tagstring;
	} else {
		return FALSE;
	}
}

function WriteID3v2($filename, $data, $majorversion=4, $minorversion=0, $overwrite=FALSE, $paddedlength=0, $showerrors=FALSE) {
	// File MUST be writeable - CHMOD(646) at least. It's best if the
	// directory is also writeable, because that method is both faster and less susceptible to errors.
	if (is_writeable($filename) || (!file_exists($filename) && is_writeable(dirname($filename)))) {
		$OldMP3fileInfo = GetAllMP3info($filename);
		if ($overwrite) {
			// ignore previous data
		} else {
			// merge with existing data
			$data = array_join_merge($OldMP3fileInfo, $data);
			$paddedlength = max($OldMP3fileInfo['id3']['id3v2']['headerlength'], $paddedlength);
		}
		if ($NewID3v2Tag = GenerateID3v2Tag($data['id3']['id3v2'], $majorversion, $minorversion, $paddedlength, '', FALSE, $showerrors, TRUE)) {
			if ((!file_exists($filename) && is_writeable(dirname($filename))) || (is_writeable($filename) && ($OldMP3fileInfo['id3']['id3v2']['headerlength'] == strlen($NewID3v2Tag)))) {
				// best and fastest method - insert-overwrite existing tag (padded to length of old tag if neccesary)
				if (file_exists($filename)) {
					if ($fp = @fopen($filename, 'r+b')) {
						rewind($fp);
						fwrite($fp, $NewID3v2Tag, strlen($NewID3v2Tag));
						fclose($fp);
					} else {
						$error .= 'Could not open '.$filename.' mode "r+b"<BR>';
					}
				} else {
					if ($fp = @fopen($filename, 'wb')) {
						rewind($fp);
						fwrite($fp, $NewID3v2Tag, strlen($NewID3v2Tag));
						fclose($fp);
					} else {
						$error .= 'Could not open '.$filename.' mode "wb"<BR>';
					}
				}
			} else {
				// new tag is longer than old tag - must rewrite entire file
				if (is_writeable(dirname($filename))) {
					// preferred alternate method - only one copying operation, minimal chance of corrupting
					// original file if script is interrupted, but required directory to be writeable
					if ($OldMP3fileInfo['audiobytes'] > 0) {
						if ($fp_source = @fopen($filename, 'rb')) {
							rewind($fp_source);
							if ($OldMP3fileInfo['audiodataoffset'] !== FALSE) {
								fseek($fp_source, $OldMP3fileInfo['audiodataoffset'], SEEK_SET);
							}
							if ($fp_temp = @fopen($filename.'getid3tmp', 'wb')) {
								fwrite($fp_temp, $NewID3v2Tag, strlen($NewID3v2Tag));
								// while (($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) !== FALSE) {
								while ($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) {
									fwrite($fp_temp, $buffer, strlen($buffer));
								}
								fclose($fp_temp);
							} else {
								$error .= 'Could not open '.$filename.'getid3tmp mode "wb"<BR>';
							}
							fclose($fp_source);
						} else { // no previous audiodata
							if ($fp_temp = @fopen($filename.'getid3tmp', 'wb')) {
								fwrite($fp_temp, $NewID3v2Tag, strlen($NewID3v2Tag));
								fclose($fp_temp);
							}
						}
					} else {
						$error .= 'Could not open '.$filename.' mode "rb"<BR>';
					}
					if (file_exists($filename)) {
						unlink($filename);
					}
					rename($filename.'getid3tmp', $filename);
				} else {
					// less desirable alternate method - double-copies the file, overwrites original file
					// and could corrupt source file if the script is interrupted or an error occurs.
					if ($fp_source = @fopen($filename, 'rb')) {
						rewind($fp_source);
						if ($OldMP3fileInfo['audiodataoffset'] !== FALSE) {
							fseek($fp_source, $OldMP3fileInfo['audiodataoffset'], SEEK_SET);
						}
						if ($fp_temp = tmpfile()) {
							fwrite($fp_temp, $NewID3v2Tag, strlen($NewID3v2Tag));
							// while (($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) !== FALSE) {
							while ($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) {
								fwrite($fp_temp, $buffer, strlen($buffer));
							}
							fclose($fp_source);
							if ($fp_source = @fopen($filename, 'wb')) {
								rewind($fp_temp);
								// while (($buffer = fread($fp_temp, FREAD_BUFFER_SIZE)) !== FALSE) {
								while ($buffer = fread($fp_temp, FREAD_BUFFER_SIZE)) {
									fwrite($fp_source, $buffer, strlen($buffer));
								}
								fseek($fp_temp, -128, SEEK_END);
								fclose($fp_source);
							} else {
								$error .= 'Could not open '.$filename.' mode "wb"<BR>';
							}
							fclose($fp_temp);
						} else {
							$error .= 'Could not create tmpfile()<BR>';
						}
					} else {
						$error .= 'Could not open '.$filename.' mode "rb"<BR>';
					}
				}
			}
		} else {
			$error .= 'GenerateID3v2Tag() failed<BR>';
		}

		if ($error) {
			if ($showerrors) {
				echo $error;
			}
			return FALSE;
		} else {
			return TRUE;
		}
	} else {
		return FALSE;
	}
}

function RemoveID3v2($filename, $showerrors=FALSE) {
	// File MUST be writeable - CHMOD(646) at least. It's best if the
	// directory is also writeable, because that method is both faster and less susceptible to errors.
	if (is_writeable(dirname($filename))) {
		// preferred method - only one copying operation, minimal chance of corrupting
		// original file if script is interrupted, but required directory to be writeable
		if ($fp_source = @fopen($filename, 'rb')) {
			$OldMP3fileInfo = GetAllMP3info($filename);
			rewind($fp_source);
			if ($OldMP3fileInfo['audiodataoffset'] !== FALSE) {
				fseek($fp_source, $OldMP3fileInfo['audiodataoffset'], SEEK_SET);
			}
			if ($fp_temp = @fopen($filename.'getid3tmp', 'w+b')) {
				// while (($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) !== FALSE) {
				while ($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) {
					fwrite($fp_temp, $buffer, strlen($buffer));
				}
				fclose($fp_temp);
			} else {
				$error .= 'Could not open '.$filename.'getid3tmp mode "w+b"<BR>';
			}
			fclose($fp_source);
		} else {
			$error .= 'Could not open '.$filename.' mode "rb"<BR>';
		}
		if (file_exists($filename)) {
			unlink($filename);
		}
		rename($filename.'getid3tmp', $filename);
	} else if (is_writable($filename)) {
		// less desirable alternate method - double-copies the file, overwrites original file
		// and could corrupt source file if the script is interrupted or an error occurs.
		if ($fp_source = @fopen($filename, 'rb')) {
			$OldMP3fileInfo = GetAllMP3info($filename);
			rewind($fp_source);
			if ($OldMP3fileInfo['audiodataoffset'] !== FALSE) {
				fseek($fp_source, $OldMP3fileInfo['audiodataoffset'], SEEK_SET);
			}
			if ($fp_temp = tmpfile()) {
				// while (($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) !== FALSE) {
				while ($buffer = fread($fp_source, FREAD_BUFFER_SIZE)) {
					fwrite($fp_temp, $buffer, strlen($buffer));
				}
				fclose($fp_source);
				if ($fp_source = @fopen($filename, 'wb')) {
					rewind($fp_temp);
					// while (($buffer = fread($fp_temp, FREAD_BUFFER_SIZE)) !== FALSE) {
					while ($buffer = fread($fp_temp, FREAD_BUFFER_SIZE)) {
						fwrite($fp_source, $buffer, strlen($buffer));
					}
					fseek($fp_temp, -128, SEEK_END);
					fclose($fp_source);
				} else {
					$error .= 'Could not open '.$filename.' mode "wb"<BR>';
				}
				fclose($fp_temp);
			} else {
				$error .= 'Could not create tmpfile()<BR>';
			}
		} else {
			$error .= 'Could not open '.$filename.' mode "rb"<BR>';
		}
	} else {
		$error .= 'Directory and file both not writeable<BR>';
	}
	if ($error) {
		if ($showerrors) {
			echo $error;
		}
		return FALSE;
	} else {
		return TRUE;
	}
}


function GenerateID3v1Tag($title, $artist, $album, $year, $genre, $comment, $track) {
	$ID3v1Tag .= 'TAG';
	$ID3v1Tag .= str_pad(substr($title,  0, 30), 30, chr(0), STR_PAD_RIGHT);
	$ID3v1Tag .= str_pad(substr($artist, 0, 30), 30, chr(0), STR_PAD_RIGHT);
	$ID3v1Tag .= str_pad(substr($album,  0, 30), 30, chr(0), STR_PAD_RIGHT);
	$ID3v1Tag .= str_pad(substr($year,   0,  4),  4,    ' ', STR_PAD_LEFT);
	if (isset($track) && ($track > 0) && ($track <= 255)) {
		$ID3v1Tag .= str_pad(substr($comment, 0, 28), 28, chr(0), STR_PAD_RIGHT);
		$ID3v1Tag .= chr(0);
		if (gettype($track) == 'string') {
			$track = (int) $track;
		}
		$ID3v1Tag .= chr($track);
	} else {
		$ID3v1Tag .= str_pad(substr($comment, 0, 30), 30, chr(0), STR_PAD_RIGHT);
	}
	if (($genre < 0) || ($genre > 147)) {
		$genre = 255; // 'unknown' genre
	}
	if (gettype($genre) == 'string') {
		$genrenumber = (int) $genre;
		$ID3v1Tag .= chr($genrenumber);
	} else if (gettype($genre) == 'integer') {
		$ID3v1Tag .= chr($genre);
	} else {
		$ID3v1Tag .= chr(255); // 'unknown' genre
	}

	return $ID3v1Tag;
}

function WriteID3v1($filename, $title='', $artist='', $album='', $year='', $comment='', $genre=255, $track='', $showerrors=FALSE) {
	// File MUST be writeable - CHMOD(646) at least
	if (is_writeable($filename)) {
		if ($fp_source = @fopen($filename, 'r+b')) {
			fseek($fp_source, -128, SEEK_END);
			if (fread($fp_source, 3) == 'TAG') {
				fseek($fp_source, -128, SEEK_END); // overwrite existing ID3v1 tag
			} else {
				fseek($fp_source, 0, SEEK_END);    // append new ID3v1 tag
			}
			fwrite($fp_source, GenerateID3v1Tag($title, $artist, $album, $year, $genre, $comment, $track), 128);
			fclose($fp_source);
		} else {
			$error .= 'Could not open '.$filename.' mode "r+b"<BR>';
		}
		if ($error) {
			if ($showerrors) {
				echo $error;
			}
			return FALSE;
		} else {
			return TRUE;
		}
	} else {
		return FALSE;
	}
}

function RemoveID3v1($filename, $showerrors=FALSE) {
	// File MUST be writeable - CHMOD(646) at least
	if (is_writeable($filename)) {
		if ($fp_source = @fopen($filename, 'r+b')) {
			fseek($fp_source, -128, SEEK_END);
			if (fread($fp_source, 3) == 'TAG') {
				ftruncate($fp_source, filesize($filename) - 128);
			} else {
				// no ID3v1 tag to begin with - do nothing
			}
			fclose($fp_source);
		} else {
			$error .= 'Could not open '.$filename.' mode "r+b"<BR>';
		}
		if ($error) {
			if ($showerrors) {
				echo $error;
			}
			return FALSE;
		} else {
			return TRUE;
		}
	} else {
		return FALSE;
	}
}

?>