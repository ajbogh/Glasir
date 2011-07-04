<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.lyrics3.php - part of getID3()                  //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function getLyrics3Filepointer(&$mp3info, $fd, $offset, $version, $length) {
	// http://www.volweb.cz/str/tags.htm

	fseek($fd, $offset, SEEK_END);
	$rawdata = fread($fd, $length);
	if (substr($rawdata, 0, 11) == 'LYRICSBEGIN') {
		if ($version == 1) {
			if (substr($rawdata, strlen($rawdata) - 9, 9) == 'LYRICSEND') {
				$mp3info['lyrics3']['raw']['lyrics3version'] = $version;
				$mp3info['lyrics3']['raw']['lyrics3tagsize'] = $length;
				$mp3info['lyrics3']['raw']['LYR'] = trim(substr($rawdata, 11, strlen($rawdata) - 11 - 9));
				Lyrics3LyricsTimestampParse($mp3info);
			} else {
				$mp3info['error'] .= "\n".'"LYRICSEND" expected at '.(ftell($fd) - 11 + $length - 9).' but found "'.substr($rawdata, strlen($rawdata) - 9, 9).'" instead';
			}
		} else if ($version == 2) {
			if (substr($rawdata, strlen($rawdata) - 9, 9) == 'LYRICS200') {
				$mp3info['lyrics3']['raw']['lyrics3version'] = $version;
				$mp3info['lyrics3']['raw']['lyrics3tagsize'] = $length;
				$mp3info['lyrics3']['raw']['unparsed'] = substr($rawdata, 11, strlen($rawdata) - 11 - 9 - 6); // LYRICSBEGIN + LYRICS200 + LSZ
				$rawdata = $mp3info['lyrics3']['raw']['unparsed'];
				while (strlen($rawdata) > 0) {
					$fieldname = substr($rawdata, 0, 3);
					$fieldsize = (int) substr($rawdata, 3, 5);
					$mp3info['lyrics3']['raw']["$fieldname"] = substr($rawdata, 8, $fieldsize);
					$rawdata = substr($rawdata, 3 + 5 + $fieldsize);
				}

				if (isset($mp3info['lyrics3']['raw']['IND'])) {
					$flagnames = array('lyrics', 'timestamps', 'inhibitrandom');
					for ($i=0;$i<count($flagnames);$i++) {
						if (strlen($mp3info['lyrics3']['raw']['IND']) > $i) {
							$mp3info['lyrics3']['flags'][$flagnames["$i"]] = IntString2Bool(substr($mp3info['lyrics3']['raw']['IND'], $i, 1));
						}
					}
				}
				$fieldnametranslation = array('ETT'=>'title', 'EAR'=>'artist', 'EAL'=>'album', 'INF'=>'comment', 'AUT'=>'author');
				foreach ($fieldnametranslation as $key => $value) {
					if (isset($mp3info['lyrics3']['raw']["$key"])) {
						$mp3info['lyrics3']["$value"] = $mp3info['lyrics3']['raw']["$key"];
					}
				}
				if (isset($mp3info['lyrics3']['raw']['IMG'])) {
					$imagestrings = explode(chr(0x0D).chr(0x0A), $mp3info['lyrics3']['raw']['IMG']);
					foreach ($imagestrings as $key => $imagestring) {
						if (strpos($imagestring, '||') !== FALSE) {
							$imagearray = explode('||', $imagestring);
							$mp3info['lyrics3']['images']["$key"]['filename']     = $imagearray[0];
							$mp3info['lyrics3']['images']["$key"]['description']  = $imagearray[1];
							$mp3info['lyrics3']['images']["$key"]['timestamp']    = Lyrics3Timestamp2Seconds($imagearray[2]);
						}
					}
				}
				if (isset($mp3info['lyrics3']['raw']['LYR'])) {
					Lyrics3LyricsTimestampParse($mp3info);
				}
			} else {
				$mp3info['error'] .= "\n".'"LYRICS200" expected at '.(ftell($fd) - 11 + $length - 9).' but found "'.substr($rawdata, strlen($rawdata) - 9, 9).'" instead';
			}
		} else {
			$mp3info['error'] .= "\n".'Cannot process Lyrics3 version '.$version.' (only v1 and v2)';
		}
	} else {
		$mp3info['error'] .= "\n".'"LYRICSBEGIN" expected at '.(ftell($fd) - 11).' but found "'.substr($rawdata, 0, 11).'" instead';
	}
}

function Lyrics3Timestamp2Seconds($rawtimestamp) {
	if (ereg("(\[[0-9]{2}:[0-9]{2}\])", $rawtimestamp)) {
		return ((int) substr($rawtimestamp, 1, 2) * 60) + (int) substr($rawtimestamp, 4, 2);
	} else {
		return NULL;
	}
}

function Lyrics3LyricsTimestampParse(&$mp3info) {
	$lyricsarray = explode(chr(0x0D).chr(0x0A), $mp3info['lyrics3']['raw']['LYR']);
	foreach ($lyricsarray as $key => $lyricline) {
		$regs = array();
		unset($thislinetimestamps);
		while (ereg("(\[[0-9]{2}:[0-9]{2}\])", $lyricline, $regs)) {
			$thislinetimestamps[] = Lyrics3Timestamp2Seconds($regs[0]);
			$lyricline = str_replace($regs[0], '', $lyricline);
		}
		$notimestamplyricsarray["$key"] = $lyricline;
		if (isset($thislinetimestamps) && is_array($thislinetimestamps)) {
			sort($thislinetimestamps);
			foreach ($thislinetimestamps as $timestampkey => $timestamp) {
				if (isset($mp3info['lyrics3']['synchedlyrics']["$timestamp"])) {
					// timestamps only have a 1-second resolution, it's possible that multiple lines
					// could have the same timestamp, if so, append
					$mp3info['lyrics3']['synchedlyrics']["$timestamp"] .= chr(0x0D).chr(0x0A).$lyricline;
				} else {
					$mp3info['lyrics3']['synchedlyrics']["$timestamp"] = $lyricline;
				}
			}
		}
	}
	$mp3info['lyrics3']['unsynchedlyrics'] = implode(chr(0x0D).chr(0x0A), $notimestamplyricsarray);
	if (isset($mp3info['lyrics3']['synchedlyrics']) && is_array($mp3info['lyrics3']['synchedlyrics'])) {
		ksort($mp3info['lyrics3']['synchedlyrics']);
	}
	return $mp3info;
}

?>