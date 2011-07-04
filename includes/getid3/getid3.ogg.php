<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.ogg.php - part of getID3()                      //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function ParseOggPageHeader(&$fd) {
	// http://xiph.org/ogg/vorbis/doc/framing.html
	$basefileoffset = ftell($fd); // where we started from in the file
	
	$filedata = fread($fd, FREAD_BUFFER_SIZE);
	$filedataoffset = 0;
	while ((substr($filedata, $filedataoffset++, 4) != 'OggS')) {
		if ((ftell($fd) - $basefileoffset) >= 10000) {
			// should be found before here
			return FALSE;
		}
		if (strlen($filedata) < 1024) {
			if (feof($fd) || (($filedata .= fread($fd, FREAD_BUFFER_SIZE)) === FALSE)) {
				// get some more data, unless eof, in which case fail
				return FALSE;
			}
		}
	}
	$filedataoffset += strlen('OggS') - 1; // page, delimited by 'OggS'

	$stream_structure_version = ord(substr($filedata, $filedataoffset++, 1));
	$header_type_flag = BigEndian2Bin(substr($filedata, $filedataoffset++, 1));
	$header_type_flag_fresh = IntString2Bool($header_type_flag{5}); // fresh packet
	$header_type_flag_bos   = IntString2Bool($header_type_flag{6}); // first page of logical bitstream (bos)
	$header_type_flag_eos   = IntString2Bool($header_type_flag{7}); // last page of logical bitstream (eos)
	$pcm_absolute_position  = LittleEndian2Int(substr($filedata, $filedataoffset, 8));
	$filedataoffset += 8;
	$stream_serial_number   = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
	$filedataoffset += 4;
	$page_seq_no            = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
	$filedataoffset += 4;
	$page_checksum          = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
	$filedataoffset += 4;
	$page_segments          = LittleEndian2Int(substr($filedata, $filedataoffset, 1));
	$filedataoffset += 1;
	for ($i=0;$i<$page_segments;$i++) {
		$segment_table["$i"] = LittleEndian2Int(substr($filedata, $filedataoffset++, 1));
	}
	$packettype = ord(substr($filedata, $filedataoffset, 1));
	$filedataoffset += 1;
	$streamtype = substr($filedata, $filedataoffset, 6); // hard-coded to 'vorbis'
	$filedataoffset += 6;
	fseek($fd, $filedataoffset + $basefileoffset, SEEK_SET);
	
	$oggheader['packet_type']      = $packettype;
	$oggheader['stream_type']      = $streamtype;
	$oggheader['stream_structver'] = $stream_structure_version;
	$oggheader['flag']['fresh']    = $header_type_flag_fresh;
	$oggheader['flag']['bos']      = $header_type_flag_fresh;
	$oggheader['flag']['eos']      = $header_type_flag_fresh;
	$oggheader['pcm_abs_position'] = $pcm_absolute_position;
	$oggheader['stream_serialno']  = $stream_serial_number;
	$oggheader['page_seqno']       = $page_seq_no;
	$oggheader['page_checksum']    = $page_checksum;
	$oggheader['page_segments']    = $page_segments;
	$oggheader['segment_table']    = $segment_table;
	
	return $oggheader;
}

function getOggHeaderFilepointer(&$fd, &$MP3fileInfo) {
	if (!$fd) {
		$MP3fileInfo['error'] = "\n".'Could not open file';
		return FALSE;
	} else {
		// Page 1 - Stream Header
		
		rewind($fd);
		$MP3fileInfo['ogg']['pageheader'][0] = ParseOggPageHeader($fd);
		if (ftell($fd) >= 10000) {
			$MP3fileInfo['error'] = "\n".'Could not find start of Ogg page in the first 10,000 bytes (this might not be an Ogg-Vorbis file?)';
			unset($MP3fileInfo['fileformat']);
			unset($MP3fileInfo['ogg']);
			return FALSE;
		}

		$filedata = fread($fd, 23);
		$filedataoffset = 0;
		
		$MP3fileInfo['ogg']['bitstreamversion'] = LittleEndian2Int(substr($filedata, 0, 4));
		$MP3fileInfo['ogg']['numberofchannels'] = LittleEndian2Int(substr($filedata, 4, 1));
		$MP3fileInfo['ogg']['samplerate']       = LittleEndian2Int(substr($filedata, 5, 4));
		$MP3fileInfo['ogg']['samples']          = 0; // filled in later
		$MP3fileInfo['ogg']['bitrate_average']  = 0; // filled in later
		if (substr($filedata,  9, 4) !== chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF)) {
			$MP3fileInfo['ogg']['bitrate_max']  = LittleEndian2Int(substr($filedata,  9, 4));
		}
		if (substr($filedata, 13, 4) !== chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF)) {
			$MP3fileInfo['ogg']['bitrate_nominal'] = LittleEndian2Int(substr($filedata, 13, 4));
		}
		if (substr($filedata, 17, 4) !== chr(0xFF).chr(0xFF).chr(0xFF).chr(0xFF)) {
			$MP3fileInfo['ogg']['bitrate_min']  = LittleEndian2Int(substr($filedata, 17, 4));
		}
		$MP3fileInfo['ogg']['blocksize_small']  = pow(2,  LittleEndian2Int(substr($filedata, 21, 1)) & 0x0F);
		$MP3fileInfo['ogg']['blocksize_large']  = pow(2, (LittleEndian2Int(substr($filedata, 21, 1)) & 0xF0) >> 4);
		$MP3fileInfo['ogg']['stop_bit']         = ord(substr($filedata, 22, 1)); // must be 1, marks end of packet
		

		// Page 2 - Comment Header

		$MP3fileInfo['ogg']['pageheader'][1] = ParseOggPageHeader($fd);
		$filedata = fread($fd, FREAD_BUFFER_SIZE);
		$filedataoffset = 0;

		$vendorsize = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
		$filedataoffset += 4;
		$MP3fileInfo['ogg']['vendor'] = substr($filedata, $filedataoffset, $vendorsize);
		$filedataoffset += $vendorsize;
		$basicfields = array('TITLE', 'ARTIST', 'ALBUM', 'TRACKNUMBER', 'GENRE', 'DATE', 'DESCRIPTION', 'COMMENT');
		$totalcomments = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
		$filedataoffset += 4;
		for ($i = 0; $i < $totalcomments; $i++) {
		    $commentsize = LittleEndian2Int(substr($filedata, $filedataoffset, 4));
			$filedataoffset += 4;
		    $commentstring = substr($filedata, $filedataoffset, $commentsize);
		    $filedataoffset += $commentsize;
		    $commentexploded = explode('=', $commentstring, 2);
		    $MP3fileInfo['ogg']['comments']["$i"]['key']   = strtoupper($commentexploded[0]);
		    $MP3fileInfo['ogg']['comments']["$i"]['value'] = ($commentexploded[1] ? $commentexploded[1] : '');
		    if (in_array($MP3fileInfo['ogg']['comments']["$i"]['key'], $basicfields)) {
		    	$MP3fileInfo['ogg'][strtolower($MP3fileInfo['ogg']['comments']["$i"]['key'])] = $MP3fileInfo['ogg']['comments']["$i"]['value'];
		    }
		}
		$MP3fileInfo['ogg']['comments_offset_end'] = $filedataoffset;
		
		
		// Last Page - Number of Samples
		
		fseek($fd, max($MP3fileInfo['filesize'] - FREAD_BUFFER_SIZE, 0), SEEK_SET);
		$LastChunkOfOgg = strrev(fread($fd, FREAD_BUFFER_SIZE));
		if ($LastOggSpostion = strpos($LastChunkOfOgg, 'SggO')) {
			fseek($fd, 0 - ($LastOggSpostion + strlen('SggO')), SEEK_END);
			$MP3fileInfo['ogg']['pageheader']['eos'] = ParseOggPageHeader($fd);
			$MP3fileInfo['ogg']['samples']   = $MP3fileInfo['ogg']['pageheader']['eos']['pcm_abs_position'];
			$MP3fileInfo['ogg']['bitrate_average'] = ($MP3fileInfo['filesize'] * 8) / ($MP3fileInfo['ogg']['samples'] / $MP3fileInfo['ogg']['samplerate']);
		}

		if (isset($MP3fileInfo['ogg']['bitrate_average']) && ($MP3fileInfo['ogg']['bitrate_average'] > 0)) {
			$MP3fileInfo['bitrate'] = $MP3fileInfo['ogg']['bitrate_average'];
		} else if (isset($MP3fileInfo['ogg']['bitrate_nominal']) && ($MP3fileInfo['ogg']['bitrate_nominal'] > 0)) {
			$MP3fileInfo['bitrate'] = $MP3fileInfo['ogg']['bitrate_nominal'];
		} else if (isset($MP3fileInfo['ogg']['bitrate_min']) && isset($MP3fileInfo['ogg']['bitrate_max'])) {
			$MP3fileInfo['bitrate'] = ($MP3fileInfo['ogg']['bitrate_min'] + $MP3fileInfo['ogg']['bitrate_max']) / 2;
		}
		if (isset($MP3fileInfo['bitrate']) && !isset($MP3fileInfo['playtime_seconds'])) {
			$MP3fileInfo['playtime_seconds'] = (float) (($MP3fileInfo['filesize'] * 8) / $MP3fileInfo['bitrate']);
		}

	}
	return TRUE;
}
?>