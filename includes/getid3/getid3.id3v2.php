<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.id3v2.php - part of getID3()                    //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function getID3v2Filepointer($fd, &$MP3fileInfo) {
	//	Overall tag structure:
	//		+-----------------------------+
	//		|      Header (10 bytes)      |
	//		+-----------------------------+
	//		|       Extended Header       |
	//		| (variable length, OPTIONAL) |
	//		+-----------------------------+
	//		|   Frames (variable length)  |
	//		+-----------------------------+
	//		|           Padding           |
	//		| (variable length, OPTIONAL) |
	//		+-----------------------------+
	//		| Footer (10 bytes, OPTIONAL) |
	//		+-----------------------------+
	
	//	Header
	//		ID3v2/file identifier      "ID3"
	//		ID3v2 version              $04 00
	//		ID3v2 flags                (%ab000000 in v2.2, %abc00000 in v2.3, %abcd0000 in v2.4.x)
	//		ID3v2 size             4 * %0xxxxxxx

	rewind($fd);
	$header = fread ($fd, 10);
	if (substr($header, 0, 3) == 'ID3') {
		$MP3fileInfo['id3']['id3v2']['header'] = TRUE;
		$MP3fileInfo['id3']['id3v2']['majorversion'] = ord($header{3});
		$MP3fileInfo['id3']['id3v2']['minorversion'] = ord($header{4});
	}

	if (isset($MP3fileInfo['id3']['id3v2']['header']) && ($MP3fileInfo['id3']['id3v2']['majorversion'] <= 4)) { // this script probably won't correctly parse ID3v2.5.x and above.

		$id3_flags = BigEndian2Bin($header{5});
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) {
			// %ab000000 in v2.2
			$MP3fileInfo['id3']['id3v2']['flags']['unsynch']     = $id3_flags{0}; // a - Unsynchronisation
			$MP3fileInfo['id3']['id3v2']['flags']['compression'] = $id3_flags{1}; // b - Compression
		} else if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 3) {
			// %abc00000 in v2.3
			$MP3fileInfo['id3']['id3v2']['flags']['unsynch']     = $id3_flags{0}; // a - Unsynchronisation
			$MP3fileInfo['id3']['id3v2']['flags']['exthead']     = $id3_flags{1}; // b - Extended header
			$MP3fileInfo['id3']['id3v2']['flags']['experim']     = $id3_flags{2}; // c - Experimental indicator
		} else if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 4) {
			// %abcd0000 in v2.4
			$MP3fileInfo['id3']['id3v2']['flags']['unsynch']     = $id3_flags{0}; // a - Unsynchronisation
			$MP3fileInfo['id3']['id3v2']['flags']['exthead']     = $id3_flags{1}; // b - Extended header
			$MP3fileInfo['id3']['id3v2']['flags']['experim']     = $id3_flags{2}; // c - Experimental indicator
			$MP3fileInfo['id3']['id3v2']['flags']['isfooter']    = $id3_flags{3}; // d - Footer present
		}

		$MP3fileInfo['id3']['id3v2']['headerlength'] = BigEndian2Int(substr($header, 6, 4), 1) + ID3v2HeaderLength($MP3fileInfo['id3']['id3v2']['majorversion']);

//	Extended Header
		if (isset($MP3fileInfo['id3']['id3v2']['flags']['exthead']) && $MP3fileInfo['id3']['id3v2']['flags']['exthead']) {
//			Extended header size   4 * %0xxxxxxx
//			Number of flag bytes       $01
//			Extended Flags             $xx
//			Where the 'Extended header size' is the size of the whole extended header, stored as a 32 bit synchsafe integer.
			$extheader = fread ($fd, 4);
			$MP3fileInfo['id3']['id3v2']['extheaderlength'] = BigEndian2Int($extheader, 1);

//			The extended flags field, with its size described by 'number of flag  bytes', is defined as:
//				%0bcd0000
//			b - Tag is an update
//				Flag data length       $00
//			c - CRC data present
//				Flag data length       $05
//				Total frame CRC    5 * %0xxxxxxx
//			d - Tag restrictions
//				Flag data length       $01
			$extheaderflagbytes = fread ($fd, 1);
			$extheaderflags     = fread ($fd, $extheaderflagbytes);
			$id3_exthead_flags = BigEndian2Bin(substr($header, 5, 1));
			$MP3fileInfo['id3']['id3v2']['exthead_flags']['update']       = substr($id3_exthead_flags, 1, 1);
			$MP3fileInfo['id3']['id3v2']['exthead_flags']['CRC']          = substr($id3_exthead_flags, 2, 1);
			if ($MP3fileInfo['id3']['id3v2']['exthead_flags']['CRC']) {
				$extheaderrawCRC = fread ($fd, 5);
				$MP3fileInfo['id3']['id3v2']['exthead_flags']['CRC'] = BigEndian2Int($extheaderrawCRC, 1);
			}
			$MP3fileInfo['id3']['id3v2']['exthead_flags']['restrictions'] = substr($id3_exthead_flags, 3, 1);
			if ($MP3fileInfo['id3']['id3v2']['exthead_flags']['restrictions']) {
				// Restrictions           %ppqrrstt
				$extheaderrawrestrictions = fread ($fd, 1);
				$MP3fileInfo['id3']['id3v2']['exthead_flags']['restrictions_tagsize']  = (bindec('11000000') & ord($extheaderrawrestrictions)) >> 6; // p - Tag size restrictions
				$MP3fileInfo['id3']['id3v2']['exthead_flags']['restrictions_textenc']  = (bindec('00100000') & ord($extheaderrawrestrictions)) >> 5; // q - Text encoding restrictions
				$MP3fileInfo['id3']['id3v2']['exthead_flags']['restrictions_textsize'] = (bindec('00011000') & ord($extheaderrawrestrictions)) >> 3; // r - Text fields size restrictions
				$MP3fileInfo['id3']['id3v2']['exthead_flags']['restrictions_imgenc']   = (bindec('00000100') & ord($extheaderrawrestrictions)) >> 2; // s - Image encoding restrictions
				$MP3fileInfo['id3']['id3v2']['exthead_flags']['restrictions_imgsize']  = (bindec('00000011') & ord($extheaderrawrestrictions)) >> 0; // t - Image size restrictions
			}
		} // end extended header

//	Frames

//		All ID3v2 frames consists of one frame header followed by one or more
//		fields containing the actual information. The header is always 10
//		bytes and laid out as follows:
//
//		Frame ID      $xx xx xx xx  (four characters)
//		Size      4 * %0xxxxxxx
//		Flags         $xx xx

		$sizeofframes = $MP3fileInfo['id3']['id3v2']['headerlength'] - ID3v2HeaderLength($MP3fileInfo['id3']['id3v2']['majorversion']);
		if (isset($MP3fileInfo['id3']['id3v2']['extheaderlength'])) {
			$sizeofframes -= $MP3fileInfo['id3']['id3v2']['extheaderlength'];
		}
		if (isset($MP3fileInfo['id3']['id3v2']['flags']['isfooter']) && $MP3fileInfo['id3']['id3v2']['flags']['isfooter']) {
			$sizeofframes -= 10; // footer takes last 10 bytes of ID3v2 header, after frame data, before audio
		}
		if ($sizeofframes > 0) {
			$framedata = fread($fd, $sizeofframes); // read all frames from file into $framedata variable

			//	if entire frame data is unsynched, de-unsynch it now (ID3v2.3.x)
			if (isset($MP3fileInfo['id3']['id3v2']['flags']['unsynch']) && $MP3fileInfo['id3']['id3v2']['flags']['unsynch'] && ($MP3fileInfo['id3']['id3v2']['majorversion'] <= 3)) {
				$framedata = DeUnSynchronise($framedata);
			}
			//		[in ID3v2.4.0] Unsynchronisation [S:6.1] is done on frame level, instead
			//		of on tag level, making it easier to skip frames, increasing the streamability
			//		of the tag. The unsynchronisation flag in the header [S:3.1] indicates that
			//		there exists an unsynchronised frame, while the new unsynchronisation flag in
			//		the frame header [S:4.1.2] indicates unsynchronisation.

			include_once(GETID3_INCLUDEPATH.'getid3.frames.php'); // ID3v2FrameProcessing()

			$framedataoffset = 10; // how many bytes into the stream - start from after the 10-byte header
			while (isset($framedata) && (strlen($framedata) > 0)) { // cycle through until no more frame data is left to parse
				if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) {
					// Frame ID  $xx xx xx (three characters)
					// Size      $xx xx xx (24-bit integer)
					// Flags     $xx xx
	
					$frame_header = substr($framedata, 0, 6); // take next 6 bytes for header
					$framedata    = substr($framedata, 6);    // and leave the rest in $framedata
					$frame_name   = substr($frame_header, 0, 3);
					$frame_size   = BigEndian2Int(substr($frame_header, 3, 3), 0);
					$frame_flags  = ''; // not used for anything, just to avoid E_NOTICEs
	
				} else if ($MP3fileInfo['id3']['id3v2']['majorversion'] > 2) {
	
					// Frame ID  $xx xx xx xx (four characters)
					// Size      $xx xx xx xx (32-bit integer in v2.3, 28-bit synchsafe in v2.4+)
					// Flags     $xx xx
	
					$frame_header = substr($framedata, 0, 10); // take next 10 bytes for header
					$framedata    = substr($framedata, 10);    // and leave the rest in $framedata
	
					$frame_name = substr($frame_header, 0, 4);
					if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 3) {
						$frame_size = BigEndian2Int(substr($frame_header, 4, 4), 0); // 32-bit integer
					} else { // ID3v2.4+
						$frame_size = BigEndian2Int(substr($frame_header, 4, 4), 1); // 32-bit synchsafe integer (28-bit value)
					}

					if ($frame_size < (strlen($framedata) + 4)) {
						$nextFrameID = substr($framedata, $frame_size, 4);
						if (IsValidID3v2FrameName($nextFrameID, $MP3fileInfo['id3']['id3v2']['majorversion'])) {
							// next frame is OK
						} else if (($frame_name == chr(0).'MP3') || ($frame_name == ' MP3') || ($frame_name == 'MP3e')) {
							// MP3ext known broken frames - "ok" for the purposes of this test
						} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] == 4) && (IsValidID3v2FrameName(substr($framedata, BigEndian2Int(substr($frame_header, 4, 4), 0), 4), 3))) {
							$MP3fileInfo['error'] .= "\n".'ID3v2 tag written as ID3v2.4, but with non-synchsafe integers (ID3v2.3 style). Older versions of Helium2 (www.helium2.com) is a known culprit of this. Tag has been parsed as ID3v2.3';
							$MP3fileInfo['id3']['id3v2']['majorversion'] = 3;
							$frame_size = BigEndian2Int(substr($frame_header, 4, 4), 0); // 32-bit integer
						}
					}


					$frame_flags = BigEndian2Bin(substr($frame_header, 8, 2));
				}

				if ($frame_name == chr(0).chr(0).chr(0).chr(0)) { // padding encountered
					// $MP3fileInfo['id3']['id3v2']['padding']['start']  = $MP3fileInfo['id3']['id3v2']['headerlength'] - strlen($framedata);
					$MP3fileInfo['id3']['id3v2']['padding']['start']  = $framedataoffset;
					$MP3fileInfo['id3']['id3v2']['padding']['length'] = strlen($framedata);
					$MP3fileInfo['id3']['id3v2']['padding']['valid']  = TRUE;
					for ($i=0;$i<$MP3fileInfo['id3']['id3v2']['padding']['length'];$i++) {
						if (substr($framedata, $i, 1) != chr(0)) {
							$MP3fileInfo['id3']['id3v2']['padding']['valid'] = FALSE;
							$MP3fileInfo['id3']['id3v2']['padding']['errorpos'] = $MP3fileInfo['id3']['id3v2']['padding']['start'] + $i;
							break;
						}
					}
					break; // skip rest of ID3v2 header
				}
	
				if (($frame_size <= strlen($framedata)) && (IsValidID3v2FrameName($frame_name, $MP3fileInfo['id3']['id3v2']['majorversion']))) {
	
					$MP3fileInfo['id3']['id3v2']["$frame_name"]['data']       = substr($framedata, 0, $frame_size);
					$MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'] = CastAsInt($frame_size);
					$MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'] = $framedataoffset;
					$framedata = substr($framedata, $frame_size);
	
					// in getid3.frames.php - this function does all the FrameID-level parsing
					ID3v2FrameProcessing($frame_name, $frame_flags, $MP3fileInfo);
					$framedataoffset += ($frame_size + ID3v2HeaderLength($MP3fileInfo['id3']['id3v2']['majorversion']));

				} else { // invalid frame length or FrameID
	
					$MP3fileInfo['error'] .= "\n".'error parsing "'.$frame_name.'" ('.$framedataoffset.' bytes into the ID3v2.'.$MP3fileInfo['id3']['id3v2']['majorversion'].' tag).';
					if ($frame_size > strlen($framedata)){
						$MP3fileInfo['error'] .= ' (ERROR: $frame_size ('.$frame_size.') > strlen($framedata) ('.strlen($framedata).')).';
					}
					if (!IsValidID3v2FrameName($frame_name, $MP3fileInfo['id3']['id3v2']['majorversion'])) {
						$MP3fileInfo['error'] .= ' (ERROR: !IsValidID3v2FrameName("'.str_replace(chr(0), ' ', $frame_name).'", '.$MP3fileInfo['id3']['id3v2']['majorversion'].'))).';
						if (($frame_name == chr(0).'MP3') || ($frame_name == ' MP3') || ($frame_name == 'MP3e')) {
							$MP3fileInfo['error'] .= ' [Note: this particular error has been known to happen with tags edited by "MP3ext V3.3.17(unicode)"]';
						} else if ($frame_name == 'COM ') {
							$MP3fileInfo['error'] .= ' [Note: this particular error has been known to happen with tags edited by "iTunes X v2.0.3"]';
						}
					}
					if (($frame_size <= strlen($framedata)) && (IsValidID3v2FrameName(substr($framedata, $frame_size, 4), $MP3fileInfo['id3']['id3v2']['majorversion']))) {
						// next frame is valid, just skip the current frame
						$framedata = substr($framedata, $frame_size);
					} else {
						// next frame is invalid too, abort processing
						unset($framedata);
					}
				}
			}
		}

//	Footer

	//	The footer is a copy of the header, but with a different identifier.
	//		ID3v2 identifier           "3DI"
	//		ID3v2 version              $04 00
	//		ID3v2 flags                %abcd0000
	//		ID3v2 size             4 * %0xxxxxxx

		if (isset($MP3fileInfo['id3']['id3v2']['flags']['isfooter']) && $MP3fileInfo['id3']['id3v2']['flags']['isfooter']) {
			$footer = fread ($fd, 10);
			if (substr($footer, 0, 3) == '3DI') {
				$MP3fileInfo['id3']['id3v2']['footer'] = true;
				$MP3fileInfo['id3']['id3v2']['majorversion_footer'] = ord(substr($footer, 3, 1));
				$MP3fileInfo['id3']['id3v2']['minorversion_footer'] = ord(substr($footer, 4, 1));
			}
			if ($MP3fileInfo['id3']['id3v2']['majorversion_footer'] <= 4) {
				$id3_flags = BigEndian2Bin(substr($footer, 5, 1));
				$MP3fileInfo['id3']['id3v2']['flags']['unsynch_footer']  = substr($id3_flags, 0, 1);
				$MP3fileInfo['id3']['id3v2']['flags']['extfoot_footer']  = substr($id3_flags, 1, 1);
				$MP3fileInfo['id3']['id3v2']['flags']['experim_footer']  = substr($id3_flags, 2, 1);
				$MP3fileInfo['id3']['id3v2']['flags']['isfooter_footer'] = substr($id3_flags, 3, 1);

				$MP3fileInfo['id3']['id3v2']['footerlength'] = BigEndian2Int(substr($footer, 6, 4), 1);
			}
		} // end footer


		// Translate most common ID3v2 FrameIDs to easier-to-understand names
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) {
			if (isset($MP3fileInfo['id3']['id3v2']['TT2'])) { $MP3fileInfo['id3']['id3v2']['title']   = $MP3fileInfo['id3']['id3v2']['TT2']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TP1'])) { $MP3fileInfo['id3']['id3v2']['artist']  = $MP3fileInfo['id3']['id3v2']['TP1']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TAL'])) { $MP3fileInfo['id3']['id3v2']['album']   = $MP3fileInfo['id3']['id3v2']['TAL']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TYE'])) { $MP3fileInfo['id3']['id3v2']['year']    = $MP3fileInfo['id3']['id3v2']['TYE']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TRK'])) { $MP3fileInfo['id3']['id3v2']['track']   = $MP3fileInfo['id3']['id3v2']['TRK']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TCO'])) { $MP3fileInfo['id3']['id3v2']['genre']   = $MP3fileInfo['id3']['id3v2']['TCO']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['COM'][0]['asciidata'])) { $MP3fileInfo['id3']['id3v2']['comment'] = $MP3fileInfo['id3']['id3v2']['COM'][0]['asciidata']; }
		} else { // $MP3fileInfo['id3']['id3v2']['majorversion'] > 2
			if (isset($MP3fileInfo['id3']['id3v2']['TIT2'])) { $MP3fileInfo['id3']['id3v2']['title']  = $MP3fileInfo['id3']['id3v2']['TIT2']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TPE1'])) { $MP3fileInfo['id3']['id3v2']['artist'] = $MP3fileInfo['id3']['id3v2']['TPE1']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TALB'])) { $MP3fileInfo['id3']['id3v2']['album']  = $MP3fileInfo['id3']['id3v2']['TALB']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TYER'])) { $MP3fileInfo['id3']['id3v2']['year']   = $MP3fileInfo['id3']['id3v2']['TYER']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TRCK'])) { $MP3fileInfo['id3']['id3v2']['track']  = $MP3fileInfo['id3']['id3v2']['TRCK']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['TCON'])) { $MP3fileInfo['id3']['id3v2']['genre']  = $MP3fileInfo['id3']['id3v2']['TCON']['asciidata']; }
			if (isset($MP3fileInfo['id3']['id3v2']['COMM'][0]['asciidata'])) { $MP3fileInfo['id3']['id3v2']['comment'] = $MP3fileInfo['id3']['id3v2']['COMM'][0]['asciidata']; }
		}
		if (isset($MP3fileInfo['id3']['id3v2']['genre'])) {
			$MP3fileInfo['id3']['id3v2']['genrelist'] = ParseID3v2GenreString($MP3fileInfo['id3']['id3v2']['genre']);
			if ($MP3fileInfo['id3']['id3v2']['genrelist']['genreid'][0] !== '') {
				$MP3fileInfo['id3']['id3v2']['genreid'] = $MP3fileInfo['id3']['id3v2']['genrelist']['genreid'][0];
			}
			$MP3fileInfo['id3']['id3v2']['genre'] = $MP3fileInfo['id3']['id3v2']['genrelist']['genre'][0];
		}
		if (isset($MP3fileInfo['id3']['id3v2']['track']) && strpos($MP3fileInfo['id3']['id3v2']['track'], '/') !== FALSE) {
			$tracktotaltracks = explode('/', $MP3fileInfo['id3']['id3v2']['track']);
			$MP3fileInfo['id3']['id3v2']['track'] = $tracktotaltracks[0];
			$MP3fileInfo['id3']['id3v2']['totaltracks'] = $tracktotaltracks[1];
		}

	} else { // MajorVersion is > 4, or no ID3v2 header present

		if (isset($MP3fileInfo['id3']['id3v2']['header'])) { // MajorVersion is > 4
			$MP3fileInfo['error'] .= "\n".'this script only parses up to ID3v2.4.x - this tag is ID3v2.'.$MP3fileInfo['id3']['id3v2']['majorversion'].'.'.$MP3fileInfo['id3']['id3v2']['minorversion'];
		} else {
			// no ID3v2 header present - this is fine, just don't process anything.
		}
	}

	return TRUE;
}


function ParseID3v2GenreString($genrestring) {
	// Parse genres into arrays of genreName and genreID
	// ID3v2.2.x, ID3v2.3.x: '(21)' or '(4)Eurodisco' or '(51)(39)' or '(55)((I think...)'
	// ID3v2.4.x: '21' $00 'Eurodisco' $00
	$returnarray = NULL;
	if (strpos($genrestring, chr(0)) !== FALSE) {
		$unprocessed = trim($genrestring); // trailing nulls will cause an infinite loop.
		$genrestring = '';
		while (strpos($unprocessed, chr(0)) !== FALSE) {
			// convert null-seperated v2.4-format into v2.3 ()-seperated format
			$endpos = strpos($unprocessed, chr(0));
			$genrestring .= '('.substr($unprocessed, 0, $endpos).')';
			$unprocessed = substr($unprocessed, $endpos + 1);
		}
		unset($unprocessed);
	}
	while (strpos($genrestring, '(') !== FALSE) {
		$startpos = strpos($genrestring, '(');
		$endpos   = strpos($genrestring, ')');
		if (substr($genrestring, $startpos + 1, 1) == '(') {
			$genrestring = substr($genrestring, 0, $startpos).substr($genrestring, $startpos + 1);
			$endpos--;
		}
		$element     = substr($genrestring, $startpos + 1, $endpos - ($startpos + 1));
		$genrestring = substr($genrestring, 0, $startpos).substr($genrestring, $endpos + 1);
		if (LookupGenre($element) !== '') { // $element is a valid genre id/abbreviation
			if (!is_array($returnarray['genre']) || !in_array(LookupGenre($element), $returnarray['genre'])) { // avoid duplicate entires
				if (($element == 'CR') && ($element == 'RX')) {
					$returnarray['genreid'][] = $element;
				} else {
					$returnarray['genreid'][] = (int) $element;
				}
				$returnarray['genre'][]   = LookupGenre($element);
			}
		} else {
			if (!is_array($returnarray['genre']) || !in_array($element, $returnarray['genre'])) { // avoid duplicate entires
				$returnarray['genreid'][] = '';
				$returnarray['genre'][]   = $element;
			}
		}
	}
	if ($genrestring) {
		if (!is_array($returnarray['genre']) || !in_array($genrestring, $returnarray['genre'])) { // avoid duplicate entires
			$returnarray['genreid'][] = '';
			$returnarray['genre'][]   = $genrestring;
		}
	}

	return $returnarray;
}

?>