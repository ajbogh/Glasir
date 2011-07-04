<?php
////////////////////////////////////////////////////////////
/// getID3() by James Heinrich <getid3@silisoftware.com>  //
//       available at http://www.silisoftware.com        ///
////////////////////////////////////////////////////////////
//                                                        //
// getid3.midi.php - part of getID3()                     //
// See getid3.readme.txt for more details                 //
//                                                        //
////////////////////////////////////////////////////////////

function getMIDIHeaderFilepointer(&$fd, &$MP3fileInfo, $scanwholefile=TRUE) {
	if (!$fd) {
		$MP3fileInfo['error'] .= "\n".'Could not open file';
		return FALSE;
	} else {
		rewind($fd);
		$MIDIdata = fread($fd, FREAD_BUFFER_SIZE);
		$offset = 0;
		$MIDIheaderID                                = substr($MIDIdata, $offset, 4); // 'MThd'
		$offset += 4;
		$MP3fileInfo['midi']['raw']['headersize']    = BigEndian2Int(substr($MIDIdata, $offset, 4));
		$offset += 4;
		$MP3fileInfo['midi']['raw']['fileformat']    = BigEndian2Int(substr($MIDIdata, $offset, 2));
		$offset += 2;
		$MP3fileInfo['midi']['raw']['tracks']        = BigEndian2Int(substr($MIDIdata, $offset, 2));
		$offset += 2;
		$MP3fileInfo['midi']['raw']['ticksperqnote'] = BigEndian2Int(substr($MIDIdata, $offset, 2));
		$offset += 2;
		
		for ($i = 0; $i < $MP3fileInfo['midi']['raw']['tracks']; $i++) {
			if ((strlen($MIDIdata) - $offset) < 8) {
				$MIDIdata .= fread($fd, FREAD_BUFFER_SIZE);
			}
			$trackID = substr($MIDIdata, $offset, 4);
			$offset += 4;
			if ($trackID == 'MTrk') {
				$tracksize = BigEndian2Int(substr($MIDIdata, $offset, 4));
				$offset += 4;
				// $MP3fileInfo['midi']['tracks']["$i"]['size'] = $tracksize;
				$trackdataarray["$i"] = substr($MIDIdata, $offset, $tracksize);
				$offset += $tracksize;
			} else {
				$MP3fileInfo['error'] .= "\n".'Expecting "MTrk" at '.$offset.', found '.$trackID.' instead';
				return FALSE;
			}
		}
		
		if (!isset($trackdataarray) || !is_array($trackdataarray)) {
			$MP3fileInfo['error'] .= "\n".'Cannot find MIDI track information';
			unset($MP3fileInfo['midi']);
			unset($MP3fileInfo['fileformat']);
			return FALSE;
		}
		
		if ($scanwholefile) { // this can take quite a long time, so have the option to bypass it if speed is very important
			$MP3fileInfo['midi']['totalticks'] = 0;
			$MP3fileInfo['playtime_seconds']   = 0;
			$CurrentMicroSecondsPerBeat = 500000; // 120 beats per minute;  60,000,000 microseconds per minute -> 500,000 microseconds per beat
			$CurrentBeatsPerMinute      = 120;    // 120 beats per minute;  60,000,000 microseconds per minute -> 500,000 microseconds per beat
			
			foreach ($trackdataarray as $tracknumber => $trackdata) {
	
				$eventsoffset               = 0;
				$LastIssuedMIDIcommand      = 0;
				$LastIssuedMIDIchannel      = 0;
				$CumulativeDeltaTime        = 0;
				$TicksAtCurrentBPM = 0;
				while ($eventsoffset < strlen($trackdata)) {
					$eventid = 0;
					if (isset($MIDIevents["$tracknumber"]) && is_array($MIDIevents["$tracknumber"])) {
						$eventid = count($MIDIevents["$tracknumber"]);
					}
					$deltatime = 0;
					for ($i=0;$i<4;$i++) {
						$deltatimebyte = ord(substr($trackdata, $eventsoffset++, 1));
						$deltatime = ($deltatime << 7) + ($deltatimebyte & 0x7F);
						if ($deltatimebyte & 0x80) {
							// another byte follows
						} else {
							break;
						}
					}
					$CumulativeDeltaTime += $deltatime;
					$TicksAtCurrentBPM   += $deltatime;
					$MIDIevents["$tracknumber"]["$eventid"]['deltatime'] = $deltatime;
					$MIDI_event_channel                                  = ord(substr($trackdata, $eventsoffset++, 1));
					if ($MIDI_event_channel & 0x80) {
						// OK, normal event - MIDI command has MSB set
						$LastIssuedMIDIcommand = $MIDI_event_channel >> 4;
						$LastIssuedMIDIchannel = $MIDI_event_channel & 0x0F;
					} else {
						// running event - assume last command
						$eventsoffset--;
					}
					$MIDIevents["$tracknumber"]["$eventid"]['eventid']   = $LastIssuedMIDIcommand;
					$MIDIevents["$tracknumber"]["$eventid"]['channel']   = $LastIssuedMIDIchannel;
					if ($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0x8) { // Note off (key is released)
	
						$notenumber = ord(substr($trackdata, $eventsoffset++, 1));
						$velocity   = ord(substr($trackdata, $eventsoffset++, 1));
	
					} else if ($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0x9) { // Note on (key is pressed)
	
						$notenumber = ord(substr($trackdata, $eventsoffset++, 1));
						$velocity   = ord(substr($trackdata, $eventsoffset++, 1));
	
					} else if ($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0xA) { // Key after-touch
	
						$notenumber = ord(substr($trackdata, $eventsoffset++, 1));
						$velocity   = ord(substr($trackdata, $eventsoffset++, 1));
	
					} else if ($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0xB) { // Control Change
	
						$controllernum = ord(substr($trackdata, $eventsoffset++, 1));
						$newvalue      = ord(substr($trackdata, $eventsoffset++, 1));
	
					} else if ($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0xC) { // Program (patch) change
	
						$newprogramnum = ord(substr($trackdata, $eventsoffset++, 1));
	
						$MP3fileInfo['midi']['raw']['track']["$tracknumber"]['instrumentid'] = $newprogramnum;
						if ($tracknumber == 10) {
							$MP3fileInfo['midi']['raw']['track']["$tracknumber"]['instrument'] = GeneralMIDIpercussionLookup($newprogramnum);
						} else {
							$MP3fileInfo['midi']['raw']['track']["$tracknumber"]['instrument'] = GeneralMIDIinstrumentLookup($newprogramnum);
						}
	
					} else if ($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0xD) { // Channel after-touch
	
						$channelnumber = ord(substr($trackdata, $eventsoffset++, 1));
	
					} else if ($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0xE) { // Pitch wheel change (2000H is normal or no change)
	
						$changeLSB = ord(substr($trackdata, $eventsoffset++, 1));
						$changeMSB = ord(substr($trackdata, $eventsoffset++, 1));
						$pitchwheelchange = (($changeMSB & 0x7F) << 7) & ($changeLSB & 0x7F);
	
					} else if (($MIDIevents["$tracknumber"]["$eventid"]['eventid'] == 0xF) && ($MIDIevents["$tracknumber"]["$eventid"]['channel'] == 0xF)) {
	
						$METAeventCommand = ord(substr($trackdata, $eventsoffset++, 1));
						$METAeventLength  = ord(substr($trackdata, $eventsoffset++, 1));
						$METAeventData    = substr($trackdata, $eventsoffset, $METAeventLength);
						$eventsoffset += $METAeventLength;
						switch ($METAeventCommand) {
							case 0x00: // Set track sequence number
								$track_sequence_number = BigEndian2Int(substr($METAeventData, 0, $METAeventLength));
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['seqno'] = $track_sequence_number;
								break;
							case 0x01: // Text: generic
								$text_generic = substr($METAeventData, 0, $METAeventLength);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['text'] = $text_generic;
								if (!isset($MP3fileInfo['midi']['comment'])) {
									$MP3fileInfo['midi']['comment'] = '';
								}
								$MP3fileInfo['midi']['comment'] .= $text_generic."\n";
								break;
							case 0x02: // Text: copyright
								$text_copyright = substr($METAeventData, 0, $METAeventLength);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['copyright'] = $text_copyright;
								if (!isset($MP3fileInfo['midi']['copyright'])) {
									$MP3fileInfo['midi']['copyright'] = '';
								}
								$MP3fileInfo['midi']['copyright'] = $text_copyright."\n";
								break;
							case 0x03: // Text: track name
								$text_trackname = substr($METAeventData, 0, $METAeventLength);
								$MP3fileInfo['midi']['raw']['track']["$tracknumber"]['name'] = $text_trackname;
								break;
							case 0x04: // Text: track instrument name
								$text_instrument = substr($METAeventData, 0, $METAeventLength);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['instrument'] = $text_instrument;
								break;
							case 0x05: // Text: lyric
								$text_lyric  = substr($METAeventData, 0, $METAeventLength);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['lyric'] = $text_lyric;
								if (!isset($MP3fileInfo['midi']['lyric'])) {
									$MP3fileInfo['midi']['lyric'] = '';
								}
								$MP3fileInfo['midi']['lyric'] .= $text_lyric."\n";
								break;
							case 0x06: // Text: marker
								$text_marker = substr($METAeventData, 0, $METAeventLength);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['marker'] = $text_marker;
								break;
							case 0x07: // Text: cue point
								$text_cuepoint = substr($METAeventData, 0, $METAeventLength);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['cuepoint'] = $text_cuepoint;
								break;
							case 0x2F: // End Of Track
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['EOT'] = $CumulativeDeltaTime;
								break;
							case 0x51: // Tempo: microseconds / quarter note
								$tempo_usperqnote = BigEndian2Int(substr($METAeventData, 0, $METAeventLength));
								// $MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['us_qnote'] = $tempo_usperqnote;
								$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$CumulativeDeltaTime"]['us_qnote'] = $tempo_usperqnote;
								//$MP3fileInfo['playtime_seconds'] += ($TicksAtCurrentBPM  / $MP3fileInfo['midi']['raw']['ticksperqnote']) * ($CurrentMicroSecondsPerBeat / 1000000);
								$CurrentMicroSecondsPerBeat = $tempo_usperqnote;
								$CurrentBeatsPerMinute      = (1000000 / $CurrentMicroSecondsPerBeat) * 60;
								$MicroSecondsPerQuarterNoteAfter["$CumulativeDeltaTime"] = $CurrentMicroSecondsPerBeat;
								$TicksAtCurrentBPM = 0;
								break;
							case 0x58: // Time signature
								$timesig_numerator   = BigEndian2Int(substr($METAeventData, 0, 1));
								$timesig_denominator = pow(2, BigEndian2Int(substr($METAeventData, 1, 1))); // $02 -> x/4, $03 -> x/8, etc
								$timesig_32inqnote   = BigEndian2Int(substr($METAeventData, 2, 1));         // number of 32nd notes to the quarter note
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['timesig_32inqnote']   = $timesig_32inqnote;
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['timesig_numerator']   = $timesig_numerator;
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['timesig_denominator'] = $timesig_denominator;
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['timesig_text']        = $timesig_numerator.'/'.$timesig_denominator;
								$MP3fileInfo['midi']['timesignature'][] = $timesig_numerator.'/'.$timesig_denominator;
								break;
							case 0x59: // Keysignature
								$keysig_sharpsflats = BigEndian2Int(substr($METAeventData, 0, 1)) & 0x7F; // (-7 -> 7 flats, 0 ->key of C, 7 -> 7 sharps)
								$keysig_sfsign      = BigEndian2Int(substr($METAeventData, 0, 1)) & 0x80;
								if ($keysig_sfsign == 1) {
									$keysig_sharpsflats = 0 - $keysig_sharpsflats;
								}
								$keysig_majorminor  = BigEndian2Int(substr($METAeventData, 1, 1)); // 0 -> major, 1 -> minor
								$keysigs = array(-7=>'Cb', -6=>'Gb', -5=>'Db', -4=>'Ab', -3=>'Eb', -2=>'Bb', -1=>'F', 0=>'C', 1=>'G', 2=>'D', 3=>'A', 4=>'E', 5=>'B', 6=>'F#', 7=>'C#');
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['keysig_sharps'] = (($keysig_sharpsflats > 0) ? abs($keysig_sharpsflats) : 0);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['keysig_flats']  = (($keysig_sharpsflats < 0) ? abs($keysig_sharpsflats) : 0);
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['keysig_minor']  = (bool) $keysig_majorminor;
								//$MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['keysig_text']   = $keysigs["$keysig_sharpsflats"].' '.($MP3fileInfo['midi']['raw']['events']["$tracknumber"]["$eventid"]['keysig_minor'] ? 'minor' : 'major');
								$MP3fileInfo['midi']['keysignature'][] = $keysigs["$keysig_sharpsflats"].' '.((bool) $keysig_majorminor ? 'minor' : 'major');
								break;
							case 0x7F: // Sequencer specific information
								$custom_data = substr($METAeventData, 0, $METAeventLength);
								break;
							default:
								break;
						}
					} else {
						// unknown MIDI event?
					}
				}
				if ($tracknumber > 0) {
					$MP3fileInfo['midi']['totalticks'] = max($MP3fileInfo['midi']['totalticks'], $CumulativeDeltaTime);
				}
			}
			$previoustickoffset = 0;
			foreach ($MicroSecondsPerQuarterNoteAfter as $tickoffset => $microsecondsperbeat) {
				if ($MP3fileInfo['midi']['totalticks'] > $tickoffset) {
					$MP3fileInfo['playtime_seconds'] += (($tickoffset - $previoustickoffset) / $MP3fileInfo['midi']['raw']['ticksperqnote']) * ($microsecondsperbeat / 1000000);
					$previoustickoffset = $tickoffset;
				}
			}
			if ($MP3fileInfo['midi']['totalticks'] > $previoustickoffset) {
				$MP3fileInfo['playtime_seconds'] += (($MP3fileInfo['midi']['totalticks'] - $previoustickoffset) / $MP3fileInfo['midi']['raw']['ticksperqnote']) * ($microsecondsperbeat / 1000000);
			}
		}
		return TRUE;
	}
}
?>