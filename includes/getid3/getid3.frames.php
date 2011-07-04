<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.frames.php - part of getID3()                   //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function ID3v2FrameProcessing($frame_name, $frame_flags, &$MP3fileInfo) {

	// define $frame_arrayindex once here (used for many frames), override or ignore as neccesary
	$frame_arrayindex = count($MP3fileInfo['id3']['id3v2']["$frame_name"]); // 'data', 'datalength'
	if (isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'])) {
		$frame_arrayindex--;
	}
	if (isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'])) {
		$frame_arrayindex--;
	}
	if (isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'])) {
		$frame_arrayindex--;
	}
	if (isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'])) {
		$frame_arrayindex--;
	}
	if (isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['timestampformat'])) {
		$frame_arrayindex--;
	}

	if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) { // frame flags are not part of the ID3v2.2 standard
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 3) {
			//	Frame Header Flags
			//	%abc00000 %ijk00000
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['TagAlterPreservation']  = (bool) substr($frame_flags,  0, 1); // a - Tag alter preservation
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['FileAlterPreservation'] = (bool) substr($frame_flags,  1, 1); // b - File alter preservation
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['ReadOnly']              = (bool) substr($frame_flags,  2, 1); // c - Read only
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['compression']           = (bool) substr($frame_flags,  8, 1); // i - Compression
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['Encryption']            = (bool) substr($frame_flags,  9, 1); // j - Encryption
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['GroupingIdentity']      = (bool) substr($frame_flags, 10, 1); // k - Grouping identity
		} else if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 4) {
			//	Frame Header Flags
			//	%0abc0000 %0h00kmnp
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['TagAlterPreservation']  = (bool) substr($frame_flags,  1, 1); // a - Tag alter preservation
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['FileAlterPreservation'] = (bool) substr($frame_flags,  2, 1); // b - File alter preservation
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['ReadOnly']              = (bool) substr($frame_flags,  3, 1); // c - Read only
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['GroupingIdentity']      = (bool) substr($frame_flags,  9, 1); // h - Grouping identity
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['compression']           = (bool) substr($frame_flags, 12, 1); // k - Compression
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['Encryption']            = (bool) substr($frame_flags, 13, 1); // m - Encryption
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['Unsynchronisation']     = (bool) substr($frame_flags, 14, 1); // n - Unsynchronisation
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['DataLengthIndicator']   = (bool) substr($frame_flags, 15, 1); // p - Data length indicator
		}

		//	Frame-level de-unsynchronization - ID3v2.4
		if (isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['Unsynchronisation'])) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = DeUnSynchronise($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		}

		//	Frame-level de-compression
		if (isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['compression'])) {
			// it's on the wishlist :)
		}

	}

	if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'UFID')) || // 4.1   UFID Unique file identifier
		(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'UFI'))) {  // 4.1   UFI  Unique file identifier
		//   There may be more than one 'UFID' frame in a tag,
		//   but only one with the same 'Owner identifier'.
		// <Header for 'Unique file identifier', ID: 'UFID'>
		// Owner identifier        <text string> $00
		// Identifier              <up to 64 bytes binary data>

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0));
		$frame_idstring = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 0, $frame_terminatorpos);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['ownerid'] = $frame_idstring;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(chr(0)));
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'TXXX')) || // 4.2.2 TXXX User defined text information frame
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'TXX'))) {     // 4.2.2 TXX  User defined text information frame
		//   There may be more than one 'TXXX' frame in each tag,
		//   but only one with the same description.
		// <Header for 'User defined text information frame', ID: 'TXXX'>
		// Text encoding     $xx
		// Description       <text string according to encoding> $00 (00)
		// Value             <text string according to encoding>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']  = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']    = TextEncodingLookup('encoding', $frame_textencoding);
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']   = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description'] = $frame_description;
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidescription'] = RoughTranslateUnicodeToASCII($frame_description, $frame_textencoding);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)));
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidata'] = RoughTranslateUnicodeToASCII($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data'], $frame_textencoding);
		}
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ($frame_name{0} == 'T') { // 4.2. T??[?] Text information frame
		//   There may only be one text information frame of its kind in an tag.
		// <Header for 'Text information frame', ID: 'T000' - 'TZZZ',
		// excluding 'TXXX' described in 4.2.6.>
		// Text encoding                $xx
		// Information                  <text string(s) according to encoding>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));

		// $MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		// this one-line method should work, but as a safeguard against null-padded data, do it the safe way
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		if ($frame_terminatorpos) {
			// there are null bytes after the data - this is not according to spec
			// only use data up to first null byte
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		} else {
			// no null bytes following data, just use all data
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		}

		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['compression']) || !$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['compression']) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['asciidata'] = RoughTranslateUnicodeToASCII($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_textencoding);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['encodingid']    = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['encoding']      = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'WXXX')) || // 4.3.2 WXXX User defined URL link frame
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'WXX'))) {     // 4.3.2 WXX  User defined URL link frame
		//   There may be more than one 'WXXX' frame in each tag,
		//   but only one with the same description
		// <Header for 'User defined URL link frame', ID: 'WXXX'>
		// Text encoding     $xx
		// Description       <text string according to encoding> $00 (00)
		// URL               <text string>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);

		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding));
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		if ($frame_terminatorpos) {
			// there are null bytes after the data - this is not according to spec
			// only use data up to first null byte
			$frame_urldata = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 0, $frame_terminatorpos);
		} else {
			// no null bytes following data, just use all data
			$frame_urldata = $MP3fileInfo['id3']['id3v2']["$frame_name"]['data'];
		}

		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']   = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']  = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']    = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['url']         = $frame_urldata;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description'] = $frame_description;
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidescription'] = RoughTranslateUnicodeToASCII($frame_description, $frame_textencoding);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ($frame_name{0} == 'W') { // 4.3. W??? URL link frames
		//   There may only be one URL link frame of its kind in a tag,
		//   except when stated otherwise in the frame description
		// <Header for 'URL link frame', ID: 'W000' - 'WZZZ', excluding 'WXXX'
		// described in 4.3.2.>
		// URL              <text string>

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['url'] = trim($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] == 3) && ($frame_name == 'IPLS')) || // 4.4  IPLS Involved people list (ID3v2.3 only)
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'IPL'))) {     // 4.4  IPL  Involved people list (ID3v2.2 only)
		//   There may only be one 'IPL' frame in each tag
		// <Header for 'User defined URL link frame', ID: 'IPL'>
		// Text encoding     $xx
		// People list strings    <textstrings>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['encodingid']    = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['encoding']      = TextEncodingLookup('encoding', $MP3fileInfo['id3']['id3v2']["$frame_name"]['encodingid']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['data']          = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['asciidata']     = RoughTranslateUnicodeToASCII($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'MCDI')) || // 4.4   MCDI Music CD identifier
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'MCI'))) {     // 4.5   MCI  Music CD identifier
		//   There may only be one 'MCDI' frame in each tag
		// <Header for 'Music CD identifier', ID: 'MCDI'>
		// CD TOC                <binary data>

		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		// no other special processing needed


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'ETCO')) || // 4.5   ETCO Event timing codes
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'ETC'))) {     // 4.6   ETC  Event timing codes
		//   There may only be one 'ETCO' frame in each tag
		// <Header for 'Event timing codes', ID: 'ETCO'>
		// Time stamp format    $xx
		//   Where time stamp format is:
		// $01  (32-bit value) MPEG frames from beginning of file
		// $02  (32-bit value) milliseconds from beginning of file
		//   Followed by a list of key events in the following format:
		// Type of event   $xx
		// Time stamp      $xx (xx ...)
		//   The 'Time stamp' is set to zero if directly at the beginning of the sound
		//   or after the previous event. All events MUST be sorted in chronological order.

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['timestampformat'] = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));

		while ($frame_offset < strlen($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'])) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['typeid']    = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1);
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['type']      = ETCOEventLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['typeid']);
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['timestamp'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4));
			$frame_offset += 4;
		}
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'MLLT')) || // 4.6   MLLT MPEG location lookup table
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'MLL'))) {     // 4.7   MLL MPEG location lookup table
		//   There may only be one 'MLLT' frame in each tag
		// <Header for 'Location lookup table', ID: 'MLLT'>
		// MPEG frames between reference  $xx xx
		// Bytes between reference        $xx xx xx
		// Milliseconds between reference $xx xx xx
		// Bits for bytes deviation       $xx
		// Bits for milliseconds dev.     $xx
		//   Then for every reference the following data is included;
		// Deviation in bytes         %xxx....
		// Deviation in milliseconds  %xxx....

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framesbetweenreferences'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 0, 2));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['bytesbetweenreferences']  = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 2, 3));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['msbetweenreferences']     = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 5, 3));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsforbytesdeviation']   = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 8, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsformsdeviation']      = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 9, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 10);
		while ($frame_offset < strlen($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'])) {
			$deviationbitstream .= BigEndian2Bin(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		}
		while (strlen($deviationbitstream)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['bytedeviation'] = bindec(substr($deviationbitstream, 0, $MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsforbytesdeviation']));
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['msdeviation']   = bindec(substr($deviationbitstream, $MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsforbytesdeviation'], $MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsformsdeviation']));
			$deviationbitstream = substr($deviationbitstream, $MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsforbytesdeviation'] + $MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsformsdeviation']);
			$frame_arrayindex++;
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'SYTC')) || // 4.7   SYTC Synchronised tempo codes
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'STC'))) {     // 4.8   STC  Synchronised tempo codes
		//   There may only be one 'SYTC' frame in each tag
		// <Header for 'Synchronised tempo codes', ID: 'SYTC'>
		// Time stamp format   $xx
		// Tempo data          <binary data>
		//   Where time stamp format is:
		// $01  (32-bit value) MPEG frames from beginning of file
		// $02  (32-bit value) milliseconds from beginning of file

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['timestampformat'] = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		while ($frame_offset < strlen($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'])) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['tempo'] = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
			if ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['tempo'] == 255) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['tempo'] += ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
			}
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['timestamp'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4));
			$frame_offset += 4;
			$frame_arrayindex++;
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'USLT')) || // 4.8   USLT Unsynchronised lyric/text transcription
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'ULT'))) {     // 4.9   ULT  Unsynchronised lyric/text transcription
		//   There may be more than one 'Unsynchronised lyrics/text transcription' frame
		//   in each tag, but only one with the same language and content descriptor.
		// <Header for 'Unsynchronised lyrics/text transcription', ID: 'USLT'>
		// Text encoding        $xx
		// Language             $xx xx xx
		// Content descriptor   <text string according to encoding> $00 (00)
		// Lyrics/text          <full text string according to encoding>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_language = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 3);
		$frame_offset += 3;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']   = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']     = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']         = $MP3fileInfo['id3']['id3v2']["$frame_name"]['data'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['language']     = $frame_language;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['languagename'] = LanguageLookup($frame_language, FALSE);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description']  = $frame_description;
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidescription'] = RoughTranslateUnicodeToASCII($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description'], $frame_textencoding);
		}
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']    = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidata'] = RoughTranslateUnicodeToASCII($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data'], $frame_textencoding);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'SYLT')) || // 4.9   SYLT Synchronised lyric/text
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'SLT'))) {     // 4.10  SLT  Synchronised lyric/text
		//   There may be more than one 'SYLT' frame in each tag,
		//   but only one with the same language and content descriptor.
		// <Header for 'Synchronised lyrics/text', ID: 'SYLT'>
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

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_language = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 3);
		$frame_offset += 3;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['timestampformat'] = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['contenttypeid']   = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['contenttype']     = SYTLContentTypeLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['contenttypeid']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']      = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']        = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['language']        = $frame_language;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['languagename']    = LanguageLookup($frame_language, FALSE);
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']       = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}

		$timestampindex = 0;
		$frame_remainingdata = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		while (strlen($frame_remainingdata)) {
			$frame_offset = 0;
			$frame_terminatorpos = strpos($frame_remainingdata, TextEncodingLookup('terminator', $frame_textencoding));
			if ($frame_terminatorpos === FALSE) {
				$frame_remainingdata = '';
			} else {
				if (ord(substr($frame_remainingdata, $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
					$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
				}
				$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']["$timestampindex"]['data'] = substr($frame_remainingdata, $frame_offset, $frame_terminatorpos - $frame_offset);
				if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
					$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']["$timestampindex"]['asciidata'] = RoughTranslateUnicodeToASCII($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']["$timestampindex"]['data'], $frame_textencoding);
				}

				$frame_remainingdata = substr($frame_remainingdata, $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)));
				if (($timestampindex == 0) && (ord($frame_remainingdata{0}) != 0)) {
					// timestamp probably omitted for first data item
				} else {
					$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']["$timestampindex"]['timestamp'] = BigEndian2Int(substr($frame_remainingdata, 0, 4));
					$frame_remainingdata = substr($frame_remainingdata, 4);
				}
				$timestampindex++;
			}
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'COMM')) || // 4.10  COMM Comments
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'COM'))) {     // 4.11  COM  Comments
		//   There may be more than one comment frame in each tag,
		//   but only one with the same language and content descriptor.
		// <Header for 'Comment', ID: 'COMM'>
		// Text encoding          $xx
		// Language               $xx xx xx
		// Short content descrip. <text string according to encoding> $00 (00)
		// The actual text        <full text string according to encoding>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_language = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 3);
		$frame_offset += 3;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		$frame_text = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']   = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']     = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['language']     = $frame_language;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['languagename'] = LanguageLookup($frame_language, FALSE);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description']  = $frame_description;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']         = $frame_text;
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']    = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidescription'] = RoughTranslateUnicodeToASCII($frame_description, $frame_textencoding);
		}
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidata']        = RoughTranslateUnicodeToASCII($frame_text, $frame_textencoding);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 4) && ($frame_name == 'RVA2')) { // 4.11  RVA2 Relative volume adjustment (2) (ID3v2.4+ only)
		//   There may be more than one 'RVA2' frame in each tag,
		//   but only one with the same identification string
		// <Header for 'Relative volume adjustment (2)', ID: 'RVA2'>
		// Identification          <text string> $00
		//   The 'identification' string is used to identify the situation and/or
		//   device where this adjustment should apply. The following is then
		//   repeated for every channel:
		// Type of channel         $xx
		// Volume adjustment       $xx xx
		// Bits representing peak  $xx
		// Peak volume             $xx (xx ...)

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0));
		$frame_idstring = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], 0, $frame_terminatorpos);
		if (ord($frame_idstring) === 0) {
			$frame_idstring = '';
		}
		$frame_remainingdata = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(chr(0)));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description'] = $frame_idstring;
		while (strlen($frame_remainingdata)) {
			$frame_offset = 0;
			$frame_channeltypeid = substr($frame_remainingdata, $frame_offset++, 1);
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]["$frame_channeltypeid"]['channeltypeid']  = $frame_channeltypeid;
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]["$frame_channeltypeid"]['channeltype']    = RVA2ChannelTypeLookup($frame_channeltypeid);
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]["$frame_channeltypeid"]['volumeadjust']   = BigEndian2Int(substr($frame_remainingdata, $frame_offset, 2)) - 0x7FFF; // 16-bit signed
			$frame_offset += 2;
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]["$frame_channeltypeid"]['bitspeakvolume'] = ord(substr($frame_remainingdata, $frame_offset++, 1));
			$frame_bytespeakvolume = ceil($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_channeltypeid"]['bitspeakvolume'] / 8);
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]["$frame_channeltypeid"]['peakvolume']     = BigEndian2Int(substr($frame_remainingdata, $frame_offset, $frame_bytespeakvolume));
			$frame_remainingdata = substr($frame_remainingdata, $frame_offset + $frame_bytespeakvolume);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]["$frame_channeltypeid"]['flags'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] == 3) && ($frame_name == 'RVAD')) || // 4.12  RVAD Relative volume adjustment (ID3v2.3 only)
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'RVA'))) {     // 4.12  RVA  Relative volume adjustment (ID3v2.2 only)
		//   There may only be one 'RVA' frame in each tag
		// <Header for 'Relative volume adjustment', ID: 'RVA'>
		// ID3v2.2 => Increment/decrement     %000000ba
		// ID3v2.3 => Increment/decrement     %00fedcba
		// Bits used for volume descr.        $xx
		// Relative volume change, right      $xx xx (xx ...) // a
		// Relative volume change, left       $xx xx (xx ...) // b
		// Peak volume right                  $xx xx (xx ...)
		// Peak volume left                   $xx xx (xx ...)
		//   ID3v2.3 only, optional (not present in ID3v2.2):
		// Relative volume change, right back $xx xx (xx ...) // c
		// Relative volume change, left back  $xx xx (xx ...) // d
		// Peak volume right back             $xx xx (xx ...)
		// Peak volume left back              $xx xx (xx ...)
		//   ID3v2.3 only, optional (not present in ID3v2.2):
		// Relative volume change, center     $xx xx (xx ...) // e
		// Peak volume center                 $xx xx (xx ...)
		//   ID3v2.3 only, optional (not present in ID3v2.2):
		// Relative volume change, bass       $xx xx (xx ...) // f
		// Peak volume bass                   $xx xx (xx ...)

		$frame_offset = 0;
		$frame_incrdecrflags = BigEndian2Bin(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['right'] = (bool) substr($frame_incrdecrflags, 6, 1);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['left']  = (bool) substr($frame_incrdecrflags, 7, 1);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsvolume'] = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_bytesvolume = ceil($MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsvolume'] / 8);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['right'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
		if ($MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['right'] === FALSE) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['right'] *= -1;
		}
		$frame_offset += $frame_bytesvolume;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['left'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
		if ($MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['left'] === FALSE) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['left'] *= -1;
		}
		$frame_offset += $frame_bytesvolume;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['peakvolume']['right'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
		$frame_offset += $frame_bytesvolume;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['peakvolume']['left']  = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
		$frame_offset += $frame_bytesvolume;
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
			if (strlen($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']) > 0) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['rightrear'] = (bool) substr($frame_incrdecrflags, 4, 1);
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['leftrear']  = (bool) substr($frame_incrdecrflags, 5, 1);
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['rightrear'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				if ($MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['rightrear'] === FALSE) {
					$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['rightrear'] *= -1;
				}
				$frame_offset += $frame_bytesvolume;
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['leftrear'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				if ($MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['leftrear'] === FALSE) {
					$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['leftrear'] *= -1;
				}
				$frame_offset += $frame_bytesvolume;
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['peakvolume']['rightrear'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				$frame_offset += $frame_bytesvolume;
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['peakvolume']['leftrear']  = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				$frame_offset += $frame_bytesvolume;
			}
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
			if (strlen($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']) > 0) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['center'] = (bool) substr($frame_incrdecrflags, 3, 1);
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['center'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				if ($MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['center'] === FALSE) {
					$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['center'] *= -1;
				}
				$frame_offset += $frame_bytesvolume;
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['peakvolume']['center'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				$frame_offset += $frame_bytesvolume;
			}
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
			if (strlen($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']) > 0) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['bass'] = (bool) substr($frame_incrdecrflags, 2, 1);
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['bass'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				if ($MP3fileInfo['id3']['id3v2']["$frame_name"]['incdec']['bass'] === FALSE) {
					$MP3fileInfo['id3']['id3v2']["$frame_name"]['volumechange']['bass'] *= -1;
				}
				$frame_offset += $frame_bytesvolume;
				$MP3fileInfo['id3']['id3v2']["$frame_name"]['peakvolume']['bass'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesvolume));
				$frame_offset += $frame_bytesvolume;
			}
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 4) && ($frame_name == 'EQU2')) { // 4.12  EQU2 Equalisation (2) (ID3v2.4+ only)
		//   There may be more than one 'EQU2' frame in each tag,
		//   but only one with the same identification string
		// <Header of 'Equalisation (2)', ID: 'EQU2'>
		// Interpolation method  $xx
		//   $00  Band
		//   $01  Linear
		// Identification        <text string> $00
		//   The following is then repeated for every adjustment point
		// Frequency          $xx xx
		// Volume adjustment  $xx xx

		$frame_offset = 0;
		$frame_interpolationmethod = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_idstring = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_idstring) === 0) {
			$frame_idstring = '';
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description'] = $frame_idstring;
		$frame_remainingdata = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(chr(0)));
		while (strlen($frame_remainingdata)) {
			$frame_frequency = BigEndian2Int(substr($frame_remainingdata, 0, 2)) / 2;
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']["$frame_frequency"] = BigEndian2Int(substr($frame_remainingdata, 2, 2));
			$frame_remainingdata = substr($frame_remainingdata, 4);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['interpolationmethod'] = $frame_interpolationmethod;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] == 3) && ($frame_name == 'EQUA')) || // 4.12  EQUA Equalisation (ID3v2.3 only)
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'EQU'))) {     // 4.13  EQU  Equalisation (ID3v2.2 only)
		//   There may only be one 'EQUA' frame in each tag
		// <Header for 'Relative volume adjustment', ID: 'EQU'>
		// Adjustment bits    $xx
		//   This is followed by 2 bytes + ('adjustment bits' rounded up to the
		//   nearest byte) for every equalisation band in the following format,
		//   giving a frequency range of 0 - 32767Hz:
		// Increment/decrement   %x (MSB of the Frequency)
		// Frequency             (lower 15 bits)
		// Adjustment            $xx (xx ...)

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['adjustmentbits'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1);
		$frame_adjustmentbytes = ceil($MP3fileInfo['id3']['id3v2']["$frame_name"]['adjustmentbits'] / 8);

		$frame_remainingdata = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		while (strlen($frame_remainingdata)) {
			$frame_frequencystr = BigEndian2Bin(substr($frame_remainingdata, 0, 2));
			$frame_incdec    = (bool) substr($frame_frequencystr, 0, 1);
			$frame_frequency = bindec(substr($frame_frequencystr, 1, 15));
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_frequency"]['incdec'] = $frame_incdec;
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_frequency"]['adjustment'] = BigEndian2Int(substr($frame_remainingdata, 2, $frame_adjustmentbytes));
			if ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_frequency"]['incdec'] === FALSE) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_frequency"]['adjustment'] *= -1;
			}
			$frame_remainingdata = substr($frame_remainingdata, 2 + $frame_adjustmentbytes);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'RVRB')) || // 4.13  RVRB Reverb
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'REV'))) {     // 4.14  REV  Reverb
		//   There may only be one 'RVRB' frame in each tag.
		// <Header for 'Reverb', ID: 'RVRB'>
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

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['left']  = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 2));
		$frame_offset += 2;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['right'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 2));
		$frame_offset += 2;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['bouncesL']      = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['bouncesR']      = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['feedbackLL']    = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['feedbackLR']    = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['feedbackRR']    = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['feedbackRL']    = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['premixLR']      = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['premixRL']      = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'APIC')) || // 4.14  APIC Attached picture
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'PIC'))) {     // 4.15  PIC  Attached picture
		//   There may be several pictures attached to one file,
		//   each in their individual 'APIC' frame, but only one
		//   with the same content descriptor
		// <Header for 'Attached picture', ID: 'APIC'>
		// Text encoding      $xx
		// ID3v2.3+ => MIME type          <text string> $00
		// ID3v2.2  => Image format       $xx xx xx
		// Picture type       $xx
		// Description        <text string according to encoding> $00 (00)
		// Picture data       <binary data>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));

		if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) {
			$frame_imagetype = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 3);
			if (strtolower($frame_imagetype) == 'ima') {
				// complete hack for mp3Rage (www.chaoticsoftware.com) that puts ID3v2.3-formatted
				// MIME type instead of 3-char ID3v2.2-format image type  (thanks xbhoff@pacbell.net)
				$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
				$frame_mimetype = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
				if (ord($frame_mimetype) === 0) {
					$frame_mimetype = '';
				}
				$frame_imagetype = strtoupper(str_replace('image/', '', strtolower($frame_mimetype)));
				if ($frame_imagetype == 'JPEG') {
					$frame_imagetype = 'JPG';
				}
				$frame_offset = $frame_terminatorpos + strlen(chr(0));
			} else {
				$frame_offset += 3;
			}
		}
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] > 2) {
			$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
			$frame_mimetype = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
			if (ord($frame_mimetype) === 0) {
				$frame_mimetype = '';
			}
			$frame_offset = $frame_terminatorpos + strlen(chr(0));
		}

		$frame_picturetype = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']        = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']       = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']         = TextEncodingLookup('encoding', $frame_textencoding);
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['imagetype']    = $frame_imagetype;
		} else {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['mime']         = $frame_mimetype;
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['picturetypeid']    = $frame_picturetype;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['picturetype']      = APICPictureTypeLookup($frame_picturetype);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description']      = $frame_description;
		if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidescription'] = RoughTranslateUnicodeToASCII($frame_description, $frame_textencoding);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']             = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)));
		
		include_once(GETID3_INCLUDEPATH.'getid3.getimagesize.php');
		$imagechunkcheck = GetDataImageSize($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']);
		if (($imagechunkcheck[2] >= 1) && ($imagechunkcheck[2] <= 3)) {
			$imagetypes = array(1=>'image/gif', 2=>'image/jpeg', 3=>'image/png');
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['image_mime']   = $imagetypes["{$imagechunkcheck[2]}"];
			if ($imagechunkcheck[0]) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['image_width']  = $imagechunkcheck[0];
			}
			if ($imagechunkcheck[1]) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['image_height'] = $imagechunkcheck[1];
			}
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['image_bytes']   = strlen($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']);
			//$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['image_offset']  = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'] + $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)) + ID3v2HeaderLength($MP3fileInfo['id3']['id3v2']['majorversion']);
		}
		
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong']    = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'GEOB')) || // 4.15  GEOB General encapsulated object
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'GEO'))) {     // 4.16  GEO  General encapsulated object
		//   There may be more than one 'GEOB' frame in each tag,
		//   but only one with the same content descriptor
		// <Header for 'General encapsulated object', ID: 'GEOB'>
		// Text encoding          $xx
		// MIME type              <text string> $00
		// Filename               <text string according to encoding> $00 (00)
		// Content description    <text string according to encoding> $00 (00)
		// Encapsulated object    <binary data>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_mimetype = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_mimetype) === 0) {
			$frame_mimetype = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_filename = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_filename) === 0) {
			$frame_filename = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['objectdata']       = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']       = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']         = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['mime']             = $frame_mimetype;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['filename']         = $frame_filename;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description']      = $frame_description;
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']        = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			if (!isset($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression']) || ($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']['compression'] === FALSE)) {
				$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidescription'] = RoughTranslateUnicodeToASCII($frame_description, $frame_textencoding);
			}
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'PCNT')) || // 4.16  PCNT Play counter
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'CNT'))) {     // 4.17  CNT  Play counter
		//   There may only be one 'PCNT' frame in each tag.
		//   When the counter reaches all one's, one byte is inserted in
		//   front of the counter thus making the counter eight bits bigger
		// <Header for 'Play counter', ID: 'PCNT'>
		// Counter        $xx xx xx xx (xx ...)

		$MP3fileInfo['id3']['id3v2']["$frame_name"]['data']          = BigEndian2Int($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'POPM')) || // 4.17  POPM Popularimeter
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'POP'))) {     // 4.18  POP  Popularimeter
		//   There may be more than one 'POPM' frame in each tag,
		//   but only one with the same email address
		// <Header for 'Popularimeter', ID: 'POPM'>
		// Email to user   <text string> $00
		// Rating          $xx
		// Counter         $xx xx xx xx (xx ...)

		$frame_offset = 0;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_emailaddress = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_emailaddress) === 0) {
			$frame_emailaddress = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));
		$frame_rating = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['data'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['email']  = $frame_emailaddress;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['rating'] = $frame_rating;
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'RBUF')) || // 4.18  RBUF Recommended buffer size
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'BUF'))) {     // 4.19  BUF  Recommended buffer size
		//   There may only be one 'RBUF' frame in each tag
		// <Header for 'Recommended buffer size', ID: 'RBUF'>
		// Buffer size               $xx xx xx
		// Embedded info flag        %0000000x
		// Offset to next tag        $xx xx xx xx

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['buffersize'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 3));
		$frame_offset += 3;

		$frame_embeddedinfoflags = BigEndian2Bin(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']['embededinfo'] = (bool) substr($frame_embeddedinfoflags, 7, 1);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['nexttagoffset'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'CRM')) { // 4.20  Encrypted meta frame (ID3v2.2 only)
		//   There may be more than one 'CRM' frame in a tag,
		//   but only one with the same 'owner identifier'
		// <Header for 'Encrypted meta frame', ID: 'CRM'>
		// Owner identifier      <textstring> $00 (00)
		// Content/explanation   <textstring> $00 (00)
		// Encrypted datablock   <binary data>

		$frame_offset = 0;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_ownerid = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_ownerid) === 0) {
			$frame_ownerid = count($MP3fileInfo['id3']['id3v2']["$frame_name"]) - 1;
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['ownerid']       = $frame_ownerid;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']          = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description']   = $frame_description;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'AENC')) || // 4.19  AENC Audio encryption
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'CRA'))) {     // 4.21  CRA  Audio encryption
		//   There may be more than one 'AENC' frames in a tag,
		//   but only one with the same 'Owner identifier'
		// <Header for 'Audio encryption', ID: 'AENC'>
		// Owner identifier   <text string> $00
		// Preview start      $xx xx
		// Preview length     $xx xx
		// Encryption info    <binary data>

		$frame_offset = 0;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_ownerid = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_ownerid) === 0) {
			$frame_ownerid == '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['ownerid'] = $frame_ownerid;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['previewstart'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 2));
		$frame_offset += 2;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['previewlength'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 2));
		$frame_offset += 2;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encryptioninfo'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_ownerid"]['flags'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if ((($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'LINK')) || // 4.20  LINK Linked information
			(($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) && ($frame_name == 'LNK'))) {     // 4.22  LNK  Linked information
		//   There may be more than one 'LINK' frame in a tag,
		//   but only one with the same contents
		// <Header for 'Linked information', ID: 'LINK'>
		// ID3v2.3+ => Frame identifier   $xx xx xx xx
		// ID3v2.2  => Frame identifier   $xx xx xx
		// URL                            <text string> $00
		// ID and additional data         <text string(s)>

		$frame_offset = 0;
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] == 2) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['frameid'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 3);
			$frame_offset += 3;
		} else {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['frameid'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4);
			$frame_offset += 4;
		}

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_url = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_url) === 0) {
			$frame_url = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['url'] = $frame_url;

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['additionaldata'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		if ($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
			unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'POSS')) { // 4.21  POSS Position synchronisation frame (ID3v2.3+ only)
		//   There may only be one 'POSS' frame in each tag
		// <Head for 'Position synchronisation', ID: 'POSS'>
		// Time stamp format         $xx
		// Position                  $xx (xx ...)

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['timestampformat'] = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['position']        = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong']   = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'USER')) { // 4.22  USER Terms of use (ID3v2.3+ only)
		//   There may be more than one 'Terms of use' frame in a tag,
		//   but only one with the same 'Language'
		// <Header for 'Terms of use frame', ID: 'USER'>
		// Text encoding        $xx
		// Language             $xx xx xx
		// The actual text      <text string according to encoding>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_language = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 3);
		$frame_offset += 3;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['language']      = $frame_language;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['languagename']  = LanguageLookup($frame_language, FALSE);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['encodingid']    = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['encoding']      = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['data']          = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		if (!$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['flags']['compression']) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['asciidata'] = RoughTranslateUnicodeToASCII($MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['data'], $frame_textencoding);
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['flags']         = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_language"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'OWNE')) { // 4.23  OWNE Ownership frame (ID3v2.3+ only)
		//   There may only be one 'OWNE' frame in a tag
		// <Header for 'Ownership frame', ID: 'OWNE'>
		// Text encoding     $xx
		// Price paid        <text string> $00
		// Date of purch.    <text string>
		// Seller            <text string according to encoding>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['encodingid'] = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['encoding']   = TextEncodingLookup('encoding', $frame_textencoding);

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_pricepaid = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]['pricepaid']['currencyid'] = substr($frame_pricepaid, 0, 3);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['pricepaid']['currency']   = LookupCurrency($MP3fileInfo['id3']['id3v2']["$frame_name"]['pricepaid']['currencyid'], 'units');
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['pricepaid']['value']      = substr($frame_pricepaid, 3);

		$MP3fileInfo['id3']['id3v2']["$frame_name"]['purchasedate'] = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 8);
		if (!IsValidDateStampString($MP3fileInfo['id3']['id3v2']["$frame_name"]['purchasedate'])) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['purchasedateunix'] = mktime (0, 0, 0, substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['purchasedate'], 4, 2), substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['purchasedate'], 6, 2), substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['purchasedate'], 0, 4));
		}
		$frame_offset += 8;

		$MP3fileInfo['id3']['id3v2']["$frame_name"]['seller']        = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'COMR')) { // 4.24  COMR Commercial frame (ID3v2.3+ only)
		//   There may be more than one 'commercial frame' in a tag,
		//   but no two may be identical
		// <Header for 'Commercial frame', ID: 'COMR'>
		// Text encoding      $xx
		// Price string       <text string> $00
		// Valid until        <text string>
		// Contact URL        <text string> $00
		// Received as        $xx
		// Name of seller     <text string according to encoding> $00 (00)
		// Description        <text string according to encoding> $00 (00)
		// Picture MIME type  <string> $00
		// Seller logo        <binary data>

		$frame_offset = 0;
		$frame_textencoding = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_pricestring = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		$frame_offset = $frame_terminatorpos + strlen(chr(0));
		$frame_rawpricearray = explode('/', $frame_pricestring);
		foreach ($frame_rawpricearray as $key => $val) {
			$frame_currencyid = substr($val, 0, 3);
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['price']["$frame_currencyid"]['currency'] = LookupCurrency($frame_currencyid, 'units');
			$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['price']["$frame_currencyid"]['value']    = substr($val, 3);
		}

		$frame_datestring = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 8);
		$frame_offset += 8;

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_contacturl = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$frame_receivedasid = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_sellername = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_sellername) === 0) {
			$frame_sellername = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], TextEncodingLookup('terminator', $frame_textencoding), $frame_offset);
		if (ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding)), 1)) === 0) {
			$frame_terminatorpos++; // strpos() fooled because 2nd byte of Unicode chars are often 0x00
		}
		$frame_description = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_description) === 0) {
			$frame_description = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(TextEncodingLookup('terminator', $frame_textencoding));

		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_mimetype = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$frame_sellerlogo = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encodingid']        = $frame_textencoding;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['encoding']          = TextEncodingLookup('encoding', $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['pricevaliduntil']   = $frame_datestring;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['contacturl']        = $frame_contacturl;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['receivedasid']      = $frame_receivedasid;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['receivedas']        = COMRReceivedAsLookup($frame_receivedasid);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['sellername']        = $frame_sellername;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciisellername']   = RoughTranslateUnicodeToASCII($frame_sellername, $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['description']       = $frame_description;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['asciidescription']  = RoughTranslateUnicodeToASCII($frame_description, $frame_textencoding);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['mime']              = $frame_mimetype;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['logo']              = $frame_sellerlogo;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']             = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong']     = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'ENCR')) { // 4.25  ENCR Encryption method registration (ID3v2.3+ only)
		//   There may be several 'ENCR' frames in a tag,
		//   but only one containing the same symbol
		//   and only one containing the same owner identifier
		// <Header for 'Encryption method registration', ID: 'ENCR'>
		// Owner identifier    <text string> $00
		// Method symbol       $xx
		// Encryption data     <binary data>

		$frame_offset = 0;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_ownerid = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_ownerid) === 0) {
			$frame_ownerid = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['ownerid']       = $frame_ownerid;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['methodsymbol']  = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']          = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']         = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'GRID')) { // 4.26  GRID Group identification registration (ID3v2.3+ only)

		//   There may be several 'GRID' frames in a tag,
		//   but only one containing the same symbol
		//   and only one containing the same owner identifier
		// <Header for 'Group ID registration', ID: 'GRID'>
		// Owner identifier      <text string> $00
		// Group symbol          $xx
		// Group dependent data  <binary data>

		$frame_offset = 0;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_ownerid = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_ownerid) === 0) {
			$frame_ownerid = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['ownerid']       = $frame_ownerid;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['groupsymbol']   = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']          = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']         = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'PRIV')) { // 4.27  PRIV Private frame (ID3v2.3+ only)
		//   The tag may contain more than one 'PRIV' frame
		//   but only with different contents
		// <Header for 'Private frame', ID: 'PRIV'>
		// Owner identifier      <text string> $00
		// The private data      <binary data>

		$frame_offset = 0;
		$frame_terminatorpos = strpos($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], chr(0), $frame_offset);
		$frame_ownerid = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_terminatorpos - $frame_offset);
		if (ord($frame_ownerid) === 0) {
			$frame_ownerid = '';
		}
		$frame_offset = $frame_terminatorpos + strlen(chr(0));

		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['ownerid']       = $frame_ownerid;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']          = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']         = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 4) && ($frame_name == 'SIGN')) { // 4.28  SIGN Signature frame (ID3v2.4+ only)
		//   There may be more than one 'signature frame' in a tag,
		//   but no two may be identical
		// <Header for 'Signature frame', ID: 'SIGN'>
		// Group symbol      $xx
		// Signature         <binary data>

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['groupsymbol']   = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['data']          = substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['flags']         = $MP3fileInfo['id3']['id3v2']["$frame_name"]['flags'];
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['flags']);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['datalength'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['datalength']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]["$frame_arrayindex"]['dataoffset'] = $MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset'];
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['dataoffset']);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 4) && ($frame_name == 'SEEK')) { // 4.29  SEEK Seek frame (ID3v2.4+ only)
		//   There may only be one 'seek frame' in a tag
		// <Header for 'Seek frame', ID: 'SEEK'>
		// Minimum offset to next tag       $xx xx xx xx

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['data']          = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);


	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 4) && ($frame_name == 'ASPI')) { // 4.30  ASPI Audio seek point index (ID3v2.4+ only)
		//   There may only be one 'audio seek point index' frame in a tag
		// <Header for 'Seek Point Index', ID: 'ASPI'>
		// Indexed data start (S)         $xx xx xx xx
		// Indexed data length (L)        $xx xx xx xx
		// Number of index points (N)     $xx xx
		// Bits per index point (b)       $xx
		//   Then for every index point the following data is included:
		// Fraction at index (Fi)          $xx (xx)

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['datastart'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4));
		$frame_offset += 4;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['indexeddatalength'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4));
		$frame_offset += 4;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['indexpoints'] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 2));
		$frame_offset += 2;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsperpoint'] = ord(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset++, 1));
		$frame_bytesperpoint = ceil($MP3fileInfo['id3']['id3v2']["$frame_name"]['bitsperpoint'] / 8);
		for ($i=0;$i<$frame_indexpoints;$i++) {
			$MP3fileInfo['id3']['id3v2']["$frame_name"]['indexes']["$i"] = BigEndian2Int(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, $frame_bytesperpoint));
			$frame_offset += $frame_bytesperpoint;
		}
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);

	} else if (($MP3fileInfo['id3']['id3v2']['majorversion'] >= 3) && ($frame_name == 'RGAD')) { // Replay Gain Adjustment
		// http://privatewww.essex.ac.uk/~djmrob/replaygain/file_format_id3v2.html
		//   There may only be one 'RGAD' frame in a tag
		// <Header for 'Replay Gain Adjustment', ID: 'RGAD'>
		// Peak Amplitude                      $xx $xx $xx $xx
		// Radio Replay Gain Adjustment        %aaabbbcd %dddddddd
		// Audiophile Replay Gain Adjustment   %aaabbbcd %dddddddd
		//   a - name code
		//   b - originator code
		//   c - sign bit
		//   d - replay gain adjustment

		$frame_offset = 0;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['peakamplitude'] = BigEndian2Float(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 4));
		$frame_offset += 4;
		$radioadjustment = Dec2Bin(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 2));
		$frame_offset += 2;
		$audiophileadjustment = Dec2Bin(substr($MP3fileInfo['id3']['id3v2']["$frame_name"]['data'], $frame_offset, 2));
		$frame_offset += 2;
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['name']            = Bin2Dec(substr($radioadjustment, 0, 3));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['originator']      = Bin2Dec(substr($radioadjustment, 3, 3));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['signbit']         = Bin2Dec(substr($radioadjustment, 6, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['adjustment']      = Bin2Dec(substr($radioadjustment, 7, 9));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['name']       = Bin2Dec(substr($audiophileadjustment, 0, 3));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['originator'] = Bin2Dec(substr($audiophileadjustment, 3, 3));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['signbit']    = Bin2Dec(substr($audiophileadjustment, 6, 1));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['adjustment'] = Bin2Dec(substr($audiophileadjustment, 7, 9));
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['radio']['name']       = RGADnameLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['name']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['radio']['originator'] = RGADoriginatorLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['originator']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['radio']['adjustment'] = RGADadjustmentLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['adjustment'], $MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['radio']['signbit']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['audiophile']['name']       = RGADnameLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['name']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['audiophile']['originator'] = RGADoriginatorLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['originator']);
		$MP3fileInfo['id3']['id3v2']["$frame_name"]['audiophile']['adjustment'] = RGADadjustmentLookup($MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['adjustment'], $MP3fileInfo['id3']['id3v2']["$frame_name"]['raw']['audiophile']['signbit']);

		$MP3fileInfo['id3']['id3v2']["$frame_name"]['framenamelong'] = FrameNameLongLookup($frame_name);
		unset($MP3fileInfo['id3']['id3v2']["$frame_name"]['data']);

	}

	return TRUE;
}
?>